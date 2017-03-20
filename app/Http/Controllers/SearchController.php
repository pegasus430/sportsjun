<?php
namespace App\Http\Controllers;

use App\Http\Controllers\User\TournamentsController;
use App\Model\Album;
use App\Model\MatchSchedule;
use App\Model\Organization;
use App\Model\Team;
use App\Model\Tournaments;
use App\User;
use Barryvdh\Reflection\DocBlock\Type\Collection;


class SearchController extends Controller
{
    static $TYPES_STRING = [
        Tournaments::class => 'tournaments',
        Team::class => 'teams',
        User::class => 'users',
        Organization::class=>'organizations',
        MatchSchedule::class=>'matches'
    ];

    public function searchGuess()
    {
        $what = \Request::get('term');
       # $searches[] = Team::where('name', 'like', $what . '%')->select('name')->get();
        $searches[] = User::regular()->where('name', 'like', '%'.$what . '%')->select('name')->get();
        $searches[] = Tournaments::where('name', 'like', '%'.$what . '%')->select('name')->get();
   #     $searches[] = Organization::where('name','like',$what.'%')->select('name')->get();
        $search_data = collect();
        foreach ($searches as $items)
            foreach ($items as $item)
                $search_data->push(['id'=>$item->name,'value'=>$item->name]);
        return $search_data->unique('value');
    }

    public function search()
    {
        HomeController::shareResource();
        $what = \Request::get('what');

        $searches = [];
       # $searches[] = Team::where('name', 'like', $what . '%')->get();
        $searches[] = User::regular()->where('name', 'like', '%'.$what . '%')->get();
        $searches[] = Tournaments::where('name', 'like', '%'.$what . '%')->get();
       # $searches[] = Organization::where('name','like',$what.'%')->get();
        $search_data = collect();
        foreach ($searches as $items)
            foreach ($items as $item)
                $search_data->push($item);

        if (count($search_data) == 1) {
            $item = $search_data[0];
            $type = array_get(self::$TYPES_STRING, get_class($item));


            return redirect()->route('public.search.view', ['type' => $type, 'id' => $item->id]);
        }


        return view('home.search.search_results', compact('search_data','what'));
    }

    public function view($type, $id)
    {
        HomeController::shareResource();
        switch ($type) {
            case 'tournaments':
                $tournament = Tournaments::find($id);
                $matchScheduleData = MatchSchedule::where('tournament_id', $id)->whereNull('tournament_group_id')
                    ->orderBy('tournament_round_number')
                    ->get();

                $maxRoundNumber = $matchScheduleData->max('tournament_round_number');
                $schedule_type = !empty($tournament->schedule_type) ? $tournament->schedule_type : 'team';
                $bracket = TournamentsController::getBracketTeams($id, $maxRoundNumber, $schedule_type, false);
                if ($tournament)
                    return view('home.search.tournament', compact('tournament',
                        'maxRoundNumber',
                        'bracket'));
                break;
            case 'users':
                $user = User::regular()
                    ->find($id);
                if ($user)
                    return view('home.search.user', compact('user'));
                break;
            case 'organizations':
                $organization = Organization::find($id);
                if ($organization)
                    return view('home.search.organization', compact('organization'));
                break;
            case 'teams':
                $team = Team::find($id);
                if ($team)
                    return view('home.search.team', compact('team'));
                break;
            case 'matches':
                $match = MatchSchedule::find($id);
                if ($match)
                    return view('home.search.matches', compact('match'));
                break;
            case 'location':
                return view('home.search.location');
                break;
            default:
                return \App::abort(404);
        }

        return \App::abort(404);
    }

    public function viewGallery($type, $id, $album_id = false)
    {
        HomeController::shareResource();
        switch ($type) {
            case 'tournaments':
                $tournament = Tournaments::find($id);
                $photos = $tournament->profile_album_photos()->paginate(15);
                return view('home.search.gallery_album', compact('photos'));
                break;
            default:
                if ($album_id) {
                    $album = Album::find($album_id);
                    $photos = $album->photos()->paginate(15);
                    return view('home.search.gallery_album', compact('album', 'photos'));
                }
        }

        return \App::abort(404);
    }

}
