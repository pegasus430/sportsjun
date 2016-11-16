<?php
namespace App\Http\Controllers;

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
        $query = \Request::get('query');
        $teams = Team::where('name', 'like', $query . '%')->lists('id', 'name');
        $users = User::regular()->where('name', 'like', $query . '%')->lists('id', 'name');
        $tournaments = Tournaments::where('name', 'like', $query . '%')->lists('id', 'name');
        return compact('teams', 'users', 'tournaments');
    }

    public function search()
    {
        HomeController::shareResource();
        $what = \Request::get('what');

        $searches = [];
        $searches[] = Team::where('name', 'like', $what . '%')->get();
        $searches[] = User::regular()->where('name', 'like', $what . '%')->get();
        $searches[] = Tournaments::where('name', 'like', $what . '%')->get();
        $searches[] = Organization::where('name','like',$what.'%')->get();
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
                if ($tournament)
                    return view('home.search.tournament', compact('tournament'));
                break;
            case 'users':
                $user = User::regular()->find($id);
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
}
