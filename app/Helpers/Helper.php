<?php

namespace App\Helpers;

use App\Model\Photo;
use App\Model\BankDocuments;
use App\Model\Team;
use App\Model\Sport;
use App\Model\UserStatistic;
use App\Model\Notifications;
use App\Model\MatchSchedule;
use App\Model\MatchScheduleRubber;
use App\Model\TournamentParent;
use App\Model\Tournaments;
use App\Model\TeamPlayers;
use App\Model\throwballScore;
use App\Model\kabaddiScore;
use App\Model\volleyballScore;
use App\Model\VolleyballPlayerMatchwiseStats;
use App\Model\KabaddiPlayerMatchwiseStats;
use App\Model\ThrowballPlayerMatchwiseStats;
use App\Model\TournamentMatchPreference;
use App\Helpers\AllRequests;
use App\User;
use App\Model\Organization;
use File;
use Auth;
use DB;
use Intervention\Image\Facades\Image;
use Log;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use View;
use Carbon\Carbon;
use Route;
use PDO;
use Session;
use App\Helpers\SendMail;
use App\Model\TournamentGroupTeams;
use App\Model\TournamentFinalTeams;
use App\Model\OrganizationGroupTeamPoint;
use App\Model\ArcheryStatistic;
use App\Model\ArcheryTeamStats;
use App\Model\ArcheryArrowStats;
use App\Model\BasicSettings;
use Request;

class Helper
{

    public static function full_name($first_name, $last_name)
    {
        return $first_name . ', ' . $last_name;
    }

    public static function uploadPhotos(
        $photoList,
        $photoType,
        $id,
        $albumID = '',
        $coverPic = '',
        $imageableType,
        $user_id = '',
        $action = '',
        $action_id = '',
        $image_type = ''
    ) {
        //echo $photolist.','.$phototype.','.$id;
        // Resize the files
        // Upload to specified folder (File storage or CDN)
        $photos = array_filter(explode(',', $photoList));
        $oldFilePath = public_path('uploads/temp/');
        $newFilePath = public_path('uploads/' . $photoType . '/');
        if ($action != '' && $action_id != '')//changed for gallery page
        {
            $newFilePath = $newFilePath . '/' . $image_type . '/' . $action_id . '/';
        }
        if (!file_exists($newFilePath)) {
            File::makeDirectory($newFilePath, $mode = 0777, true, true);
        }
        $insertedphotoids = array();
        // echo"<pre>";print_r($photos);
        foreach ($photos as $key => $value) {
            //rename($value, $photoType.'/image1.jpg');
            $filename = explode('####SPORTSJUN####', $value);
            $title = $filename[0];
            $url = $filename[1];

            if (File::move($oldFilePath . $value, $newFilePath . $url)) {
                //die("Couldn't rename file");
                $photosArray = array(
                    'user_id' => $user_id,
                    'album_id' => $albumID,
                    'title' => $title,
                    'url' => $url,
                    'is_album_cover' => $coverPic,
                    'imageable_id' => $id,
                    'imageable_type' => $imageableType
                );

                $photo = Photo::create($photosArray);
                $insertedphotoids[] = $photo->id;
            }
        }
        return $insertedphotoids;
    }

    public static function uploadImage(
        UploadedFile $image,
        $photoType,
        $id,
        $albumID = '',
        $coverPic = '',
        $imageableType,
        $user_id = '',
        $action = '',
        $action_id = '',
        $image_type = ''
    ) {
        $newFilePath = public_path('uploads/' . $photoType . '/');
        if ($action != '' && $action_id != '')//changed for gallery page
        {
            $newFilePath = $newFilePath . '/' . $image_type . '/' . $action_id . '/';
        }
        if (!file_exists($newFilePath)) {
            File::makeDirectory($newFilePath, $mode = 0777, true, true);
        }


        $newFileName = str_random(20) . '.' . $image->getClientOriginalExtension();

        try {
            $image->move($newFilePath, $newFileName);
            $photosArray = array(
                'user_id' => $user_id,
                'album_id' => $albumID,
                'title' => $image->getClientOriginalName(),
                'url' => $newFileName,
                'is_album_cover' => $coverPic,
                'imageable_id' => $id,
                'imageable_type' => $imageableType
            );

            $photo = Photo::create($photosArray);
            return $photo->id;

        } catch (FileException $ex) {

            Log::error($ex->getMessage());
        }
        return false;
    }

    public static function uploadImageSimple($image,$type){
        $newFilePath = public_path('uploads/' . $type . '/');

        if (!file_exists($newFilePath)) {
            \File::makeDirectory($newFilePath, $mode = 0777, true, true);
        }

        $newFileName = str_random(20) . '.' . $image->getClientOriginalExtension();
        try {
            $image->move($newFilePath, $newFileName);
            return $newFileName;
        } catch (FileException $ex) {

            Log::error($ex->getMessage());
        }
        return false;
    }


    public static function ImageFit($path, $w, $h, $position = 'top')
    {
        if (!trim($path)) {
            return;
        }
        $parts = pathinfo($path);
        if (!isset($parts['dirname'])) {
            return;
        }
        $relative_path =$parts['dirname'] . '/fit/';
        $filename =  $relative_path . $parts['filename'] . '_' . $w . '_' . $h . '.' . $parts['extension'];

        $filepath = public_path($filename);
        if (\File::exists($filepath) && ($modified = File::lastModified($filepath)) &&  $modified <  Carbon::yesterday()->timestamp) {
            return $filename;
        } else {
            if (\File::exists(public_path($path))) {
                if(!\File::exists(public_path($relative_path))) {
                    \File::makeDirectory(public_path($relative_path));
                }
                Image::make(public_path($path))->fit($w, $h,null,$position)->save(public_path($filename));
                return $filename;
            } else {
                return '';
            }
        }
    }


    /*--------   file moving from temp to attachment function      ---------------------------*/
    public static function moveImage($imagearray,$vendor_bank_account_id) {
            $photos = array_filter(explode(',', $imagearray));
            $oldFilePath = public_path('uploads/temp/'); 
            $newFilePath = public_path('uploads/attachments/');
            if (!file_exists($newFilePath)) {
            File::makeDirectory($newFilePath, $mode = 0777, true, true);
            }
            $moved_images_names=array();
            $i=0;
            foreach ($photos as $key => $value) {
            $filename = explode('####SPORTSJUN####', $value);
            $title = $filename[0];
            $url = $filename[1];
            $newFileName = str_random(10) . $title;
           

            if (File::move($oldFilePath.$value, $newFilePath. $newFileName)) {

                $photosArray = array(
                    'vendor_bank_account_id' => $vendor_bank_account_id,
                    'name' => $newFileName,
                    'location' => 'attachments/'. $newFileName,
                );
               

                $photo = BankDocuments::create($photosArray);
                $insertedphotoids[] = $photo->id;
               
              
            }
            $i++;
        }
          return $insertedphotoids;
    }
     
     /*---------  file moving from temp to attachment function  ------------------------*/ 






    public static function printQueries()
    {
        DB::enableQueryLog();
        dd(DB::getQueryLog());
    }

    public static function setMenuToSelect($top, $left, $marketPlaceCategories = '')
    {
        View::share('top', $top);
        View::share('left', $left);
        View::share('marketPlaceCategories', $marketPlaceCategories);
    }

    public static function getAllSports()
    {
        $sports = Sport::get();
        return $sports;
    }

    //function to send left menu variables
    public static function leftMenuVariables($team_id)
    {
        $teams = Team::with('sports', 'photos')->where('id', $team_id)->first();
        $location = Helper::addressInfo($teams['city'], $teams['state'], $teams['country']);
        $userId = isset(Auth::user()->id) ? Auth::user()->id : 0;      //user or guest
        $managing_teams = Helper::getManagingTeamIds($userId);
        $managing_team_ids = array();
        //follow/unfollow
        $follow_unfollow = Helper::checkFollowUnfollow(isset(Auth::user()->id) ? Auth::user()->id : 0, 'TEAM',
            $team_id);
        if (!empty($managing_teams)) {
            $managing_team_ids = explode(',', trim($managing_teams, ','));
        }

        // check if user already exists in team
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $exist_array = DB::select("SELECT count(*) as count
                FROM `team_players` tp  
                WHERE tp.user_id = $userId "
            . "AND tp.team_id IN ($team_id) "
            . "AND `status` != 'rejected' "
            . "AND tp.deleted_at IS NULL ");
        DB::setFetchMode(PDO::FETCH_CLASS);
        $user_in_team = ($exist_array[0]['count'] > 0) ? true : false;

        if (!$user_in_team)     // if the user already doesn't exist in team
        {
            $player_available_in_team = DB::table('teams')
                ->select('teams.player_available')
                ->where('teams.player_available', 1)
                ->whereNull('teams.deleted_at')
                ->where('teams.isactive', '=', 1)
                ->where('teams.id', '=', $team_id)
                ->get();
        }
        $player_available_in_team = (isset($player_available_in_team[0]->player_available) && !empty($player_available_in_team[0]->player_available)) ? true : false;
        $sport_schedule_type = (!empty($teams->sports['sports_type'])) ? $teams->sports['sports_type'] : '';
        View::share([
            'sport_schedule_type' => $sport_schedule_type,
            'team_name' => (!empty($teams['name']) ? $teams['name'] : ''),
            'location' => (!empty($location) ? $location : ''),
            'sport' => (!empty($teams['sports']['sports_name']) ? $teams['sports']['sports_name'] : 'NA'),
            'description' => (!empty($teams['description']) ? $teams['description'] : ''),
            'photo_path' => (count($teams['photos']) ? ('teams/' . $teams['photos'][0]['url']) : ''),
            'sport_id' => (!empty($teams['sports_id']) ? $teams['sports_id'] : '0'),
            'team_id' => (!empty($teams['id']) ? $teams['id'] : '0'),
            'managing_team_ids' => $managing_team_ids,
            'follow_unfollow' => $follow_unfollow,
            'user_in_team' => $user_in_team,
            'player_available_in_team' => $player_available_in_team
        ]);
    }

    public static function getPlayerInfo($userId)
    {
        if (!$userId) {
            return false;
        }
        $user = User::where('id', $userId)->first();
        $name = '';
        $dob = '';
        $location = '';
        if (count($user)) {
            if (count($user->dob) && $user->dob != '0000-00-00') {
//                $age = Carbon::createFromFormat('Y-m-d', $user->dob)->diff(Carbon::now())->format('%y years, %m months and %d days');
                $age = Carbon::createFromFormat('Y-m-d', $user->dob)->diff(Carbon::now())->format('%y years');
            }
            $name = $user->name;
            $dob = !empty($age) ? $age : '';
            $location = Helper::addressInfo($user->city, $user->state, $user->country);
        }
        $content = View::make('common.userdetails',
            array('name' => $name, 'dob' => $dob, 'location' => $location))->render();
        return $content;
    }

    public static function addressInfo($city, $state, $country)
    {
        if ($city != 'null' && $city != '') {
            $city = $city;
        } else {
            $city = '';
        }

        if ($state != 'null' && $state != '') {
            $state = ', ' . $state;
        } else {
            $state = '';
        }

        if ($country != 'null' && $country != '') {
            $country = ', ' . $country;
        }
        $location = trim($city . $state . $country, ',');
        return $location;
    }

    public static function getTeamCity($team_id)
    {
        if ($team_id) {
            $getTeamAddress = Team::where('id', $team_id)->get(array('city', 'state', 'country'));
            $address = Helper::addressInfo($getTeamAddress[0]['city'], $getTeamAddress[0]['state'],
                $getTeamAddress[0]['country']);
            return $address;
        }
        return '';
    }

    public static function getUserCity($user_id)
    {
        $getTeamAddress = User::where('id', $user_id)->get(array('city', 'state', 'country', 'dob'));
        $dob = $getTeamAddress[0]['dob'];
        $age = 0;
        if ($dob != '') {
            $age = Carbon::createFromFormat('Y-m-d', $dob)->diff(Carbon::now())->format('%y years');
        }
        $address = Helper::addressInfo($getTeamAddress[0]['city'], $getTeamAddress[0]['state'],
            $getTeamAddress[0]['country']);
        return nl2br($address . " \n Age:" . $age);
    }

    public static function address($address, $city, $state, $country)
    {
        if ($address != 'null' && $address != '') {
            $address = $address;
        } else {
            $address = '';
        }
        if ($city != 'null' && $city != '') {
            $city = ',' . $city;
        } else {
            $city = '';
        }
        if ($state != 'null' && $state != '') {
            $state = ',' . $state;
        } else {
            $state = '';
        }
        if ($country != 'null' && $country != '') {
            $country = ',' . $country;
        }
        $location = $address . $city . $state . $country;
        //View::share( location => $location);
        return $location;
    }

    //function to get the match types
    public static function getMatchTypes($sportName, $from_tournament = false)
    {
        $existingSports = array('CRICKET', 'TENNIS', 'TABLE TENNIS', 'FOOTBALL', 'OTHERS', 'BADMINTON', 'SQUASH');
        $matchTypes = array();

        if ($from_tournament) {
            $tour = 'TOURNAMENT_';
        } else {
            $tour = '';
        }

        if (!empty($sportName)) {
            $finalSportName = in_array($sportName, $existingSports) ? $sportName : 'OTHERS';
            //building match types array
            foreach (config('constants.ENUM.' . $tour . 'SCHEDULE.MATCH_TYPE.' . $finalSportName) as $key => $val) {
                $matchTypes[$key] = $val;
            }
        }
        return $matchTypes;
    }

    public static function convert_number_to_words($number)
    {
        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'fourty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
            1000000 => 'million',
            1000000000 => 'billion',
            1000000000000 => 'trillion',
            1000000000000000 => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int)($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int)($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string)$fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

    //check given string is exists in given array or not
    public static function isExist($existing_ids_str, $new_id)
    {
        $existing_ids_str = trim($existing_ids_str, ',');
        $existing_ids = explode(',', $existing_ids_str);
        if (in_array($new_id, $existing_ids)) {
            return true;
        } else {
            return false;
        }
    }

    //function to get managing teams
    public static function getManagingTeamIds($userId)
    {
        $managingTeamIds = '';
        if (!empty($userId)) {
            //$managingTeamIds = UserStatistic::where('user_id', $userId)->pluck('managing_teams');
            $modelObj = new \App\Model\Team;
            $teamDetails = $modelObj->getTeamsByRole($userId);
            $managingTeamIdsNoStatus = $teamDetails[0]->managing_teams;
            if (!empty($managingTeamIdsNoStatus)) {
                $query = "select group_concat(id) as team_ids from teams where id in ($managingTeamIdsNoStatus) and isactive=1";

                $managingTeamsIds = DB::select(DB::raw("$query"));
                if (!empty($managingTeamsIds)) {
                    $managingTeamIds = $managingTeamsIds[0]->team_ids;
                }
            }

        }
        return $managingTeamIds;
    }

    //function to get following sports
    public static function getFollowingSportIds($userId)
    {
        $followingSportIds = '';
        if (!empty($userId)) {
            $followingSportIds = UserStatistic::where('user_id', $userId)->pluck('following_sports');
        }
        return $followingSportIds;
    }

    //function to get managing teams
    public static function getManagingTeams($userId, $sportId = 0)
    {
        // die('uid '.$userId.' sid '.$sportId);
        $managingTeamIds = Helper::getManagingTeamIds($userId);
        $managingTeams = array();
        if (!empty($managingTeamIds)) {
            $managing_team_ids = explode(',', trim($managingTeamIds, ','));
            if (!empty($sportId)) {
                $managingTeams = Team::whereIn('id', $managing_team_ids)->where('sports_id', $sportId)->get(array(
                    'id',
                    'name',
                    'team_level'
                ));
            } else {
                $managingTeams = Team::whereIn('id', $managing_team_ids)->get(array('id', 'name', 'team_level'));
            }
        }
        return $managingTeams;
    }

    //function to get managing teams for popup
    public static function getManagingTeamsForPopUp($userId, $sportId, $request_type, $player_tournament_id)
    {
        $query = '';
        if ($request_type == 'TEAM_TO_PLAYER') {
            $query = "select distinct a.team_id as id,t.name,b.user_id 
                    from team_players a
                    left join team_players b on a.team_id=b.team_id and b.user_id=$player_tournament_id and b.deleted_at is null and b.status != 'rejected' 
                    inner join teams t on a.team_id=t.id and t.deleted_at is null 
                    where a.user_id=$userId and a.deleted_at is null and a.role in ('owner','manager') and b.user_id is null and t.sports_id=$sportId and t.isactive=1 
                    order by a.team_id";
        } elseif ($request_type == 'TEAM_TO_TOURNAMENT') {
            /*$query = "select tp.team_id as id,tm.name
                        from team_players tp
                        inner join teams tm on tp.team_id=tm.id
                        where tp.user_id=$userId and tp.deleted_at is null and tp.role in ('owner','manager') and tm.sports_id=$sportId and tm.isactive=1
                        and tp.team_id not in
                        (
                            select team_id from (

                                select distinct tg.team_id
                                from tournaments t
                                inner join tournament_group_teams tg on t.id=tg.tournament_id and tg.deleted_at is null
                                where t.id = $player_tournament_id and t.schedule_type='team' and t.deleted_at is null  and tg.team_id in
                                (select team_id from team_players where user_id=$userId and role in ('owner','manager') and deleted_at is null)

                                union

                                select distinct tg.team_id
                                from tournaments t
                                inner join tournament_final_teams tg on t.id=tg.tournament_id and tg.deleted_at is null
                                where t.id = $player_tournament_id and t.schedule_type='team' and t.deleted_at is null  and tg.team_id in
                                    (select team_id from team_players where user_id=$userId and role in ('owner','manager') and deleted_at is null)

                            )
                            as x
                            order by x.team_id
                        )

                        ";*/

            $teams_query = " select GROUP_CONCAT(team_id) AS team_ids from (
                            
                                select distinct tg.team_id 
                                from tournaments t 
                                inner join tournament_group_teams tg on t.id=tg.tournament_id and tg.deleted_at is null
                                where t.id = $player_tournament_id and t.schedule_type='team' and t.deleted_at is null  and tg.team_id in
                                (select team_id from team_players where user_id=$userId and role in ('owner','manager') and deleted_at is null)
                                
                                union 
                                
                                select distinct tg.team_id 
                                from tournaments t 
                                inner join tournament_final_teams tg on t.id=tg.tournament_id and tg.deleted_at is null
                                where t.id = $player_tournament_id and t.schedule_type='team' and t.deleted_at is null  and tg.team_id in
                                    (select team_id from team_players where user_id=$userId and role in ('owner','manager') and deleted_at is null)       
                                
                            )
                            as x";
            $teams_result = DB::select(DB::raw($teams_query));
            $team_ids = $teams_result[0]->team_ids;

            $query = "select tp.team_id as id,tm.name,IF(FIND_IN_SET(tp.team_id,'" . $team_ids . "'),1,0) AS team_status
                        from team_players tp 
                        inner join teams tm on tp.team_id=tm.id
                        where tp.user_id=$userId and tp.deleted_at is null and tp.role in ('owner','manager') and tm.sports_id=$sportId and tm.isactive=1;
        ";
            //dd($teams_result[0]->team_ids);
        }
        $teams = DB::select(DB::raw("$query"));
        return $teams;
    }

    //function to get managing teams with team level
    public static function getManagingTeamsWithTeamLevel($userId, $sportId = 0)
    {
        $managingteams = Helper::getManagingTeams($userId, $sportId);
        $managingteamsfinal = array();
        if (count($managingteams)) {
            foreach ($managingteams as $teams) {
                if (!empty($teams['id']) && !empty($teams['name']) && !empty($teams['team_level'])) {
                    array_push($managingteamsfinal,
                        array('id' => $teams['id'], 'name' => ($teams['name'] . ' (' . $teams['team_level'] . ')')));
                }
            }
        }
        return $managingteamsfinal;
    }

    //function to display user following sports
    public static function getUserFollowingSportNames($following_sports)
    {
        $following_sports_ids = explode(',', trim($following_sports, ','));
        $following_sport_names = '';
        if (count($following_sports_ids)) {
            foreach ($following_sports_ids as $sportId) {
                $sport_name = Sport::where('id', $sportId)->pluck('sports_name');
                $following_sport_names .= $sport_name . ' ,';
            }
        }
        return trim($following_sport_names, ',');
    }

    //function to get notifications
    public static function getNotifications($limit = 0, $offset = 0)
    {
        $userId = isset(Auth::user()->id) ? Auth::user()->id : 0;      //user or guest
        $limitString = '';
        if (!empty($limit)) {
            $limitString = " limit $offset,$limit ";
        }
        $queryString = "";
        /*$notifications = DB::select(DB::raw("select id,request_id,type,message,url,is_read,
                        case
                            WHEN (TIMESTAMPDIFF(HOUR,created_at,now()) = 0) THEN concat(TIMESTAMPDIFF(MINUTE,created_at,now()),' minute(s) ago')
                            WHEN (TIMESTAMPDIFF(HOUR,created_at,now()) > 0 && TIMESTAMPDIFF(HOUR,created_at,now()) < 24 ) THEN concat(TIMESTAMPDIFF(HOUR,created_at,now()),' hour(s) ago')
                            WHEN (TIMESTAMPDIFF(HOUR,created_at,now()) >= 24 ) THEN concat(TIMESTAMPDIFF(DAY,created_at,now()),' day(s) ago')
                            ELSE '0 minutes ago'
                        end as diff_time
                        from notifications where user_id=$userId and is_read=0 order by created_at desc $limitString"));*/
        $notifications = DB::select(DB::raw("select n.id,n.request_id,n.type,n.message,n.url,n.is_read,
                        case 
                            WHEN (TIMESTAMPDIFF(HOUR,n.created_at,now()) = 0) THEN concat(TIMESTAMPDIFF(MINUTE,n.created_at,now()),' minute(s) ago')
                            WHEN (TIMESTAMPDIFF(HOUR,n.created_at,now()) > 0 && TIMESTAMPDIFF(HOUR,n.created_at,now()) < 24 ) THEN concat(TIMESTAMPDIFF(HOUR,n.created_at,now()),' hour(s) ago')
                            WHEN (TIMESTAMPDIFF(HOUR,n.created_at,now()) >= 24 ) THEN concat(TIMESTAMPDIFF(DAY,n.created_at,now()),' day(s) ago')
                            ELSE '0 minutes ago'
                        end as diff_time,
                        case r.type 
                            when 1  then (select logo from users where id=r.from_id)
                            when 2  then (select logo from users where id=r.from_id)
                            when 3  then (select logo from teams where id=r.from_id)
                            when 4  then (select logo from teams where id=r.from_id)
                            when 5  then (select logo from teams where id=r.from_id)
                            when 6  then (select logo from users where id=r.from_id)
                            else NULL
                            end as logo,
                        case r.type 
                            when 1  then 'USERS_PROFILE'
                            when 2  then 'USERS_PROFILE'
                            when 3  then 'TEAMS_FOLDER_PATH'
                            when 4  then 'TEAMS_FOLDER_PATH'
                            when 5  then 'TEAMS_FOLDER_PATH'
                            when 6  then 'USERS_PROFILE'
                            else NULL
                            end as logo_type
                        from notifications n 
                        left join request r on n.request_id=r.id and r.deleted_at is null 
                        where n.user_id=$userId and n.is_read=0 order by n.id desc $limitString"));
        return $notifications;
    }

    //public function get notficaitons count
    public static function getNotificationsCount()
    {
        $userId = isset(Auth::user()->id) ? Auth::user()->id : 0;      //user or guest
        // $notifications = Notifications::where('user_id',$userId)->where('is_read',0)->count();
        $notifications = DB::select(DB::raw("select sum(x.notification_count) as notifications_count
                            from
                            (
                            select count(id) as notification_count from notifications where user_id=$userId and request_id=0 and is_read=0
                            union all
                            select count(distinct request_id) as notification_count from notifications where user_id=$userId and request_id!=0 and is_read=0
                            ) as x"));
        $notifications_count = 0;
        if (count($notifications)) {
            $notifications_count = $notifications[0]->notifications_count;
        }
        return $notifications_count;
    }

    public static function getCricketStats($teamStats, $teamId)
    {
        $winCountOdi = 0;
        $looseCountOdi = 0;
        $isTiedOdi = 0;
        $winCountTtewnty = 0;
        $looseCounttTtewnty = 0;
        $isTiedTtewnty = 0;
        $winCountTest = 0;
        $looseCountTest = 0;
        $isTiedTest = 0;
        $odiWinPercentage = 0;
        $tTwentyWinPercentage = 0;
        $testWinPercentage = 0;

        $match_type = config('constants.ENUM.SCHEDULE.MATCH_TYPE.CRICKET');
            
        
        foreach($match_type as $i=>$value){
            ${'winCount' . $i} = 0;
            ${'looseCount' . $i} = 0;
            ${'isTied' . $i} = 0;
            ${$i . 'WinPercentage'} = 0;
        }

        if (count($teamStats)) {
            foreach ($teamStats as $stats) {
                if ($stats['match_type'] == 'odi') {
                    if ($stats['winner_id'] == $teamId) {
                        $winCountOdi = $winCountOdi + 1;
                    } else {
                        if ($stats['looser_id'] == $teamId) {
                            $looseCountOdi = $looseCountOdi + 1;
                        } else {
                            if ($stats['is_tied']) {
                                $isTiedOdi = $isTiedOdi + 1;
                            }
                        }
                    }
                } else {
                    if ($stats['match_type'] == 't20') {
                        if ($stats['winner_id'] == $teamId) {
                            $winCountTtewnty = $winCountTtewnty + 1;
                        } else {
                            if ($stats['looser_id'] == $teamId) {
                                $looseCounttTtewnty = $looseCounttTtewnty + 1;
                            } else {
                                if ($stats['is_tied']) {
                                    $isTiedTtewnty = $isTiedTtewnty + 1;
                                }
                            }
                        }
                    } else {
                        if ($stats['match_type'] == 'test') {
                            if ($stats['winner_id'] == $teamId) {
                                $winCountTest = $winCountTest + 1;
                            } else {
                                if ($stats['looser_id'] == $teamId) {
                                    $looseCountTest = $looseCountTest + 1;
                                } else {
                                    if ($stats['is_tied']) {
                                        $isTiedTest = $isTiedTest + 1;
                                    }
                                }
                            }
                        }
                    }
                }

                 foreach($match_type as $i=>$value){

                    if ($stats['match_type'] == $i) {
                        if ($stats['winner_id'] == $teamId) {
                            ${'winCount' . $i} = ${'winCount' . $i} + 1;
                        } else {
                            if ($stats['looser_id'] == $teamId) {
                                ${'looseCount' . $i} = ${'looseCount' . $i} + 1;
                            } else {
                                if ($stats['is_tied']) {
                                    ${'isTied' . $i} = ${'isTied' . $i} + 1;
                                }
                            }
                        }
                    }

                }

            }
        }
        $odiTotalMatches = $winCountOdi + $looseCountOdi + $isTiedOdi;
        if ($odiTotalMatches != 0) {
            $odiWinPercentage = number_format(($winCountOdi / ($odiTotalMatches)) * 100, 2);
        }
        $odiStatsArray = [
            'totalMatches' => $odiTotalMatches,
            'winCount' => $winCountOdi,
            'looseCount' => $looseCountOdi,
            'isTied' => $isTiedOdi,
            'wonPercentage' => $odiWinPercentage
        ];

        $tTwentyTotalMatches = $winCountTtewnty + $looseCounttTtewnty + $isTiedTtewnty;
        if ($tTwentyTotalMatches) {
            $tTwentyWinPercentage = number_format(($winCountTtewnty / ($tTwentyTotalMatches)) * 100, 2);
        }
        $tTwentyStatsArray = [
            'totalMatches' => $tTwentyTotalMatches,
            'winCount' => $winCountTtewnty,
            'looseCount' => $looseCounttTtewnty,
            'isTied' => $isTiedTtewnty,
            'wonPercentage' => $tTwentyWinPercentage
        ];

        $testTotalMatches = $winCountTest + $looseCountTest + $isTiedTest;
        if ($testTotalMatches) {
            $testWinPercentage = number_format(($winCountTest / ($testTotalMatches)) * 100, 2);
        }
        $testStatsArray = [
            'totalMatches' => $testTotalMatches,
            'winCount' => $winCountTest,
            'looseCount' => $looseCountTest,
            'isTied' => $isTiedTest,
            'wonPercentage' => $testWinPercentage
        ];


         foreach($match_type as $i=>$value){
            ${$i . 'TotalMatches'} = ${'winCount' . $i} + ${'looseCount' . $i} + ${'isTied' . $i};
            if (${$i . 'TotalMatches'}) {
                ${$i . 'WinPercentage'} = number_format((${'winCount' . $i} / (${$i . 'TotalMatches'})) * 100, 2);
            }
            ${$i . 'StatsArray'} = [
                'totalMatches' => ${$i . 'TotalMatches'},
                'winCount' => ${'winCount' . $i},
                'looseCount' => ${'looseCount' . $i},
                'isTied' => ${'isTied' . $i},
                'wonPercentage' => ${$i . 'WinPercentage'}
            ];


        }

        // $finalArray = [
        //     'odiStatsArray' => $odiStatsArray,
        //     'tTwentyStatsArray' => $tTwentyStatsArray,
        //     'testStatsArray' => $testStatsArray
        // ];

        $finalArray=[];         
        foreach($match_type as $i=>$value){
            $finalArray[$i . 'StatsArray'] = ${$i . 'StatsArray'};
        }


        return $finalArray;
    }

    public static function getTennisTableTennisStats($teamStats, $teamId)
    {
        $winCountSingles = 0;
        $looseCountSingles = 0;
        $isTiedSingles = 0;
        $winCountDoubles = 0;
        $looseCountDoubles = 0;
        $isTiedDoubles = 0;
        $winCountMixed = 0;
        $looseCountMixed = 0;
        $isTiedmixed = 0;
        $singlesWinPercentage = 0;
        $doublesWinPercentage = 0;
        $mixedWinPercentage = 0;

        $washoutSingles = 0;
        $washoutDoubles = 0;

        if (count($teamStats)) {
            foreach ($teamStats as $stats) {
                if ($stats['match_type'] == 'singles') {
                    if ($stats['winner_id'] == $teamId) {
                        $winCountSingles = $winCountSingles + 1;
                    } else {
                        if ($stats['looser_id'] == $teamId) {
                            $looseCountSingles = $looseCountSingles + 1;
                        } else {
                            if ($stats['is_tied']) {
                                $isTiedSingles = $isTiedSingles + 1;
                            } else {
                                if ($stats['has_result'] == 0) {
                                    $washoutSingles = $washoutSingles + 1;
                                }
                            }
                        }
                    }
                } else {
                    if ($stats['match_type'] == 'doubles') {
                        if ($stats['winner_id'] == $teamId) {
                            $winCountDoubles = $winCountDoubles + 1;
                        } else {
                            if ($stats['looser_id'] == $teamId) {
                                $looseCountDoubles = $looseCountDoubles + 1;
                            } else {
                                if ($stats['is_tied']) {
                                    $isTiedDoubles = $isTiedDoubles + 1;
                                } else {
                                    if ($stats['has_result'] == 0) {
                                        $washoutDoubles = $washoutDoubles + 1;
                                    }
                                }
                            }
                        }
                    } else {
                        if ($stats['match_type'] == 'mixed') {
                            if ($stats['winner_id'] == $teamId) {
                                $winCountMixed = $winCountMixed + 1;
                            } else {
                                if ($stats['looser_id'] == $teamId) {
                                    $looseCountMixed = $looseCountMixed + 1;
                                } else {
                                    if ($stats['is_tied']) {
                                        $isTiedmixed = $isTiedmixed + 1;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $singlesTotalMatches = $winCountSingles + $looseCountSingles + $isTiedSingles + $washoutSingles;
        if ($singlesTotalMatches) {
            $singlesWinPercentage = number_format(($winCountSingles / ($singlesTotalMatches)) * 100, 2);
        }
        $singlesStatsArray = [
            'totalMatches' => $singlesTotalMatches,
            'winCount' => $winCountSingles,
            'looseCount' => $looseCountSingles,
            'isTied' => $isTiedSingles,
            'wonPercentage' => $singlesWinPercentage,
            'washout' => $washoutSingles
        ];

        $doublesTotalMatches = $winCountDoubles + $looseCountDoubles + $isTiedDoubles + $washoutDoubles;

        if ($doublesTotalMatches) {
            $doublesWinPercentage = number_format(($winCountDoubles / ($doublesTotalMatches)) * 100, 2);
        }
        $doublesStatsArray = [
            'totalMatches' => $doublesTotalMatches,
            'winCount' => $winCountDoubles,
            'looseCount' => $looseCountDoubles,
            'isTied' => $isTiedDoubles,
            'wonPercentage' => $doublesWinPercentage,
            'washout' => $washoutDoubles
        ];

        $mixedTotalMatches = $winCountMixed + $looseCountMixed + $isTiedmixed;
        if ($mixedTotalMatches) {
            $mixedWinPercentage = number_format(($winCountMixed / ($mixedTotalMatches)) * 100, 2);
        }
        $mixedStatsArray = [
            'totalMatches' => $mixedTotalMatches,
            'winCount' => $winCountMixed,
            'looseCount' => $looseCountMixed,
            'isTied' => $isTiedmixed,
            'wonPercentage' => $mixedWinPercentage
        ];

        $finalArray = [
            'singlesStatsArray' => $singlesStatsArray,
            'doublesStatsArray' => $doublesStatsArray,
            'mixedStatsArray' => $mixedStatsArray
        ];

        return $finalArray;
    }

    public static function getSoccerStats($teamStats, $teamId)
    {
        $winCountOthers = 0;
        $looseCountOthers = 0;
        $isTiedothers = 0;
        $othersWinPercentage = 0;
        $washoutCount = 0;

        if (count($teamStats)) {
            foreach ($teamStats as $stats) {
                if ($stats['match_type'] == 'other') {
                    if ($stats['winner_id'] == $teamId) {
                        $winCountOthers = $winCountOthers + 1;
                    } else {
                        if ($stats['looser_id'] == $teamId) {
                            $looseCountOthers = $looseCountOthers + 1;
                        } else {
                            if ($stats['is_tied']) {
                                $isTiedothers = $isTiedothers + 1;
                            } else {
                                if ($stats['has_result'] == 0) {
                                    $washoutCount = $washoutCount + 1;
                                }
                            }
                        }
                    }
                }
            }
        }
        $othersTotalMatches = $winCountOthers + $looseCountOthers + $isTiedothers;
        if ($othersTotalMatches) {
            $othersWinPercentage = number_format(($winCountOthers / ($othersTotalMatches)) * 100, 2);
        }
        $othersStatsArray = [
            'totalMatches' => $othersTotalMatches,
            'winCount' => $winCountOthers,
            'looseCount' => $looseCountOthers,
            'isTied' => $isTiedothers,
            'wonPercentage' => $othersWinPercentage,
            'washout' => $washoutCount
        ];

        $finalArray = ['othersStatsArray' => $othersStatsArray];

        return $finalArray;
    }

    public static function getHockeyStats($teamStats, $teamId)
    {
        $winCountOthers = 0;
        $looseCountOthers = 0;
        $isTiedothers = 0;
        $othersWinPercentage = 0;
        $washoutCount = 0;

        if (count($teamStats)) {
            foreach ($teamStats as $stats) {
                if ($stats['match_type'] == 'other') {
                    if ($stats['winner_id'] == $teamId) {
                        $winCountOthers = $winCountOthers + 1;
                    } else {
                        if ($stats['looser_id'] == $teamId) {
                            $looseCountOthers = $looseCountOthers + 1;
                        } else {
                            if ($stats['is_tied']) {
                                $isTiedothers = $isTiedothers + 1;
                            } else {
                                if ($stats['has_result'] == 0) {
                                    $washoutCount = $washoutCount + 1;
                                }
                            }
                        }
                    }
                }
            }
        }
        $othersTotalMatches = $winCountOthers + $looseCountOthers + $isTiedothers;
        if ($othersTotalMatches) {
            $othersWinPercentage = number_format(($winCountOthers / ($othersTotalMatches)) * 100, 2);
        }
        $othersStatsArray = [
            'totalMatches' => $othersTotalMatches,
            'winCount' => $winCountOthers,
            'looseCount' => $looseCountOthers,
            'isTied' => $isTiedothers,
            'wonPercentage' => $othersWinPercentage,
            'washout' => $washoutCount
        ];

        $finalArray = ['othersStatsArray' => $othersStatsArray];

        return $finalArray;
    }

    public static function getArcheryStats($team_id){
        $teamStats = ArcheryStatistic::whereTeamId($team_id)->first();
        $teamStats['event_level']=$teamStats;
        $teamStats['match_level']=ArcheryTeamStats::whereTeamId($team_id)->whereNotNull('tournament_id')->get();

       // dd($teamStats['match_level']->count());

        for($i=10; $i>=5; $i--){
            $pts = 0; 

            foreach(ArcheryArrowStats::whereTeamId($team_id)->whereNotNull('tournament_id')->get() as $st){

           if($st) {
                for($j=1; $j<=10; $j++){
                    if($st->{'arrow_'.$j}==$i){
                        $pts++;
                    }
                }             
            }
            }

            $teamStats['pt_'.$i]= $pts;

        }

        return $teamStats;
    }


    //function to get sport name
    public static function getSportName($sports_id)
    {
        $sportName = null;
        if (is_numeric($sports_id)) {
            $sportName = Sport::where('id', $sports_id)->pluck('sports_name');
        }
        return $sportName;
    }

    public static function getImagePath($imgsrc, $imgtype, $details = '',$default = '/images/default-profile-pic.jpg')
    {
        if (!$imgsrc || $imgsrc == '')  {
            return $default;
        }
        $uploads = 'uploads';
        switch ($imgtype) {
            case 'tournaments':
            case 'teams':
            case 'organization':
            case 'facility':
            case 'marketplace':
            case 'form_gallery_tournaments':
            case 'form_gallery_facility':
            case 'form_gallery_organization':
            case 'images':
                return '/' . $uploads . '/' . $imgtype . '/' . $imgsrc;
            case 'gallery/gallery_user':
            case 'gallery/gallery_team':
            case 'gallery/gallery_tournaments':
            case 'gallery/gallery_facility':
            case 'gallery/gallery_organization':
            case 'gallery/gallery_match':
            case 'user_profile':
                $id = '';
                if (isset($details['id'])) {
                    $id = $details['id'];
                }
                return '/' . $uploads . '/' . $imgtype . '/' . $id . '/' . $imgsrc;
            default:
                return '/' . $uploads . '/' . $imgtype . '/' . $imgsrc;
        }
    }

    public static function Images($imgsrc, $imgtype, $details = '', $from_local = false,$default = true)
    {
        $path = self::getImagePath($imgsrc, $imgtype, $details,$default ?  '/images/default-profile-pic.jpg': false);
        if ($path)
            return self::makeImageHtml($path, $details, $from_local);

    }

    public static function makeImageHtml($path, $details = '', $from_local = false)
    {
        if ($from_local) {
            $globalurl = public_path() . '/';
        } else {
            $globalurl = url();
        }

        //$globalurl = 'http://localhost/sportsjun/public/';
        if (isset($details['width'])) {
            $width = $details['width'];
        } else {
            $width = '';
        }
        if (isset($details['height'])) {
            $height = $details['height'];
        } else {
            $height = '';
        }
        if (isset($details['class'])) {
            $class = $details['class'];
        } else {
            $class = '';
        }
        if (isset($details['title'])) {
            $title = $details['title'];
        } else {
            $title = '';
        }
        $url = $globalurl . $path;
        $img = "<img data-original='$url' src='$url' title='$title' onerror=this.onerror=null;this.src=\"$globalurl/images/default-profile-pic.jpg\" height=$height  width=$width   class='$class lazy' >";
        return $img;
    }


    public static function ImageCheck($path)
    {
        return (File::exists(public_path($path)) && File::isFile(public_path($path))) ? $path : 'images/default-profile-pic.jpg';
    }

    //getting the current route
    public static function getcurrentroute()
    {
        //getting the route
        $routeName = Route::currentRouteAction();
        list($controller, $method) = explode('@', $routeName);
        $controller = preg_replace('/.*\\\/', '', $controller);
        return array('controller_name' => $controller, 'function_name' => $method);
    }

    public static function buildMyMatchScheduleData($searchArray)
    {
        $teamIds = '';
        $playerIds = '';
        $teamLogoArray = [];
        $playerLogoArray = [];
        $teamNameArray = [];
        $playerNameArray = [];


//        $userId = isset(Auth::user()->id)?Auth::user()->id:0;      //user or guest
        $userId = $searchArray['userId'];

        if($userId){
            $team_lists = User::find($userId)->userdetails;
        }
        $matchSchedules = MatchSchedule::with(array(
            'sport' => function ($q3) {
                $q3->select('id', 'sports_name', 'sports_type');
            }
        ))->where(function ($query) use ($userId, $team_lists) {
            $query->where('player_a_ids', 'LIKE', '%' . $userId . '%')->orWhere('player_b_ids', 'LIKE',
                '%' . $userId . '%')
                  ->orWhere('player_or_team_ids', 'like',
                '%' . $userId . '%');

            if($userId){
                foreach($team_lists as $team_list){
                  $query->orWhere('player_or_team_ids', 'like',
                '%' . $team_list->team_id . '%');
                }
            }
 
        })->whereNotNull('match_start_date');
        if (!empty($searchArray['fromDate']) && !empty($searchArray['toDate'])) {
            $matchSchedules->whereBetween('match_start_date', [$searchArray['fromDate'], $searchArray['toDate']]);
        }
        if (!empty($searchArray['sportsId'])) {
            $matchSchedules->where('sports_id', $searchArray['sportsId']);
        }
        if (!empty($searchArray['matchStatus'])) {
            $matchSchedules->where('match_status', $searchArray['matchStatus']);
        }
        if (!empty($searchArray['limit'])) {
            $matchSchedules->orderby('match_start_date', 'desc');
            $matchSchedules->orderby('match_start_time', 'desc');
            $matchSchedules->limit($searchArray['limit'])->offset($searchArray['offset']);
        }


        $matchScheduleData = $matchSchedules->get([
            'match_schedules.id',
            'match_start_date',
            'match_start_time',
            'match_end_date',
            'match_end_time',
            'winner_id',
            'a_id',
            'b_id',
            'sports_id',
            'facility_name',
            'match_category',
            'match_type',
            'match_status',
            'match_invite_status',
            'schedule_type',
            'scoring_status',
            'score_added_by',
            'created_by'
        ]);


        if (count($matchScheduleData)) {
            $matchScheduleData = $matchScheduleData->toArray();
            foreach ($matchScheduleData as $key => $schedule) {
                $matchScheduleData[$key]['a_logo'] = '';
                $matchScheduleData[$key]['b_logo'] = '';
                $matchScheduleData[$key]['a_name'] = '';
                $matchScheduleData[$key]['b_name'] = '';
                if ($schedule['schedule_type'] == 'team') {
                    $teamIds .= $schedule['a_id'] . ',' . $schedule['b_id'] . ',';
                } else {
                    $playerIds .= $schedule['a_id'] . ',' . $schedule['b_id'] . ',';
                }
            }
        }

        if (!empty($teamIds)) {
            $teamIdArray = explode(',', rtrim($teamIds, ','));
            $teamLogos = Photo::whereIn('imageable_id', $teamIdArray)
                ->where('imageable_type', config('constants.PHOTO.TEAM_PHOTO'))
                ->where('is_album_cover', 1)
                ->get(['url', 'imageable_id']);
            $teamNames = Team::whereIn('id', $teamIdArray)->get(['id', 'team_owner_id', 'sports_id', 'name']);

            if (count($teamLogos)) {
                foreach ($teamLogos->toArray() as $teamkey => $teamLogo) {
                    $teamLogoArray[$teamLogo['imageable_id']] = $teamLogo['url'];
                }
            }

            if (count($teamNames)) {
                foreach ($teamNames as $teamName) {
                    $teamNameArray[$teamName['id']] = $teamName['name'];
                }
            }
        }

        if (!empty($playerIds)) {
            $playerIdArray = explode(',', rtrim($playerIds, ','));
            $playerLogos = Photo::whereIn('user_id', $playerIdArray)
                ->where('imageable_type', config('constants.PHOTO.USER_PHOTO'))
                ->where('is_album_cover', 1)
                ->get(['url', 'imageable_id', 'user_id']);

            $playerNames = User::whereIn('id', $playerIdArray)->get(['id', 'name']);

            if (count($playerLogos)) {
                foreach ($playerLogos->toArray() as $playerLogo) {
                    $playerLogoArray[$playerLogo['user_id']] = $playerLogo['url'];
                }
            }

            if (count($playerNames)) {
                foreach ($playerNames as $playerName) {
                    $playerNameArray[$playerName['id']] = $playerName['name'];
                }
            }
        }

        if (!empty($matchScheduleData)) {
            foreach ($matchScheduleData as $key => $schedule) {
                if ($schedule['schedule_type'] == 'team') {
                    if (!empty($teamLogoArray)) {
                        if (array_key_exists($matchScheduleData[$key]['a_id'], $teamLogoArray)) {
                            $matchScheduleData[$key]['a_logo'] = $teamLogoArray[$matchScheduleData[$key]['a_id']];
                        }
                        if (array_key_exists($matchScheduleData[$key]['b_id'], $teamLogoArray)) {
                            $matchScheduleData[$key]['b_logo'] = $teamLogoArray[$matchScheduleData[$key]['b_id']];
                        }
                    }
                    if (!empty($teamNameArray)) {
                        if (array_key_exists($matchScheduleData[$key]['a_id'], $teamNameArray)) {
                            $matchScheduleData[$key]['a_name'] = $teamNameArray[$matchScheduleData[$key]['a_id']];
                        }
                        if (array_key_exists($matchScheduleData[$key]['b_id'], $teamNameArray)) {
                            $matchScheduleData[$key]['b_name'] = $teamNameArray[$matchScheduleData[$key]['b_id']];
                        }
                    }
                } else {
                    if (!empty($playerLogoArray)) {
                        if (array_key_exists($matchScheduleData[$key]['a_id'], $playerLogoArray)) {
                            $matchScheduleData[$key]['a_logo'] = $playerLogoArray[$matchScheduleData[$key]['a_id']];
                        }
                        if (array_key_exists($matchScheduleData[$key]['b_id'], $playerLogoArray)) {
                            $matchScheduleData[$key]['b_logo'] = $playerLogoArray[$matchScheduleData[$key]['b_id']];
                        }
                    }
                    if (!empty($playerNameArray)) {
                        if (array_key_exists($matchScheduleData[$key]['a_id'], $playerNameArray)) {
                            $matchScheduleData[$key]['a_name'] = $playerNameArray[$matchScheduleData[$key]['a_id']];
                        }
                        if (array_key_exists($matchScheduleData[$key]['b_id'], $playerNameArray)) {
                            $matchScheduleData[$key]['b_name'] = $playerNameArray[$matchScheduleData[$key]['b_id']];
                        }
                    }
                }
            }
        }
//                  dd($matchScheduleData);
        return $matchScheduleData;

    }

    //function to check if the logged in user is owner or manager for a team
    public static function checkIfOwnerOrManger($schedule)
    {
        $ownerOneId = AllRequests::getempidonroles($schedule['a_id'], 'owner');
        if (count($ownerOneId)) {
            if ($schedule['created_by'] == $ownerOneId) {
                return true;
            }
        }

        $managerOneId = AllRequests::getempidonroles($schedule['a_id'], 'manager');
        if (count($managerOneId)) {
            if ($schedule['created_by'] == $managerOneId) {
                return true;
            }
        }

        if (empty($schedule['b_id'])) {
            return false;
        }

        $ownerTwoId = AllRequests::getempidonroles($schedule['b_id'], 'owner');
        if (count($ownerTwoId)) {
            if ($schedule['created_by'] == $ownerTwoId) {
                return true;
            }
        }
        $managerTwoId = AllRequests::getempidonroles($schedule['b_id'], 'manager');
        if (count($managerTwoId)) {
            if ($schedule['created_by'] == $managerTwoId) {
                return true;
            }
        }
        return false;
    }

    public static function buildMyListViewData($matchScheduleData, $userId)
    {
        $managingTeams = Helper::getManagingTeamIds($userId);
        $managingTeamsArray = explode(',', (trim($managingTeams, ',')));
        $isOwner = 0;
        $scoreOwner = 0;
        foreach ($matchScheduleData as $key => $schedule) {
            if ($schedule['schedule_type'] == 'team') {
//                if (in_array($schedule['a_id'], $managingTeamsArray) || in_array($schedule['b_id'], $managingTeamsArray)) {
                $isOwner = Helper::checkIfOwnerOrManger($schedule);
            } else {
//                if ($schedule['a_id'] == $userId || $schedule['b_id'] == $userId) {
                if ($schedule['created_by'] == $userId) {
                    $isOwner = 1;
                }
            }

            $matchStartDate = Carbon::createFromFormat('Y-m-d', $schedule['match_start_date']);

//            if (!empty($schedule['winner_id']) && $schedule['match_status']=='completed') {
            if ($schedule['match_status'] == 'completed') {
                if ($schedule['scoring_status'] == 'approval_pending') {
                    $matchScheduleData[$key]['winner_text'] = trans('message.schedule.scorecardapproval');
                } else {
                    $matchScheduleData[$key]['winner_text'] = trans('message.schedule.fullscoreboard');
                }
            } else {
                if (Carbon::now()->gte($matchStartDate)) {
                    $match_details = MatchSchedule::where('id', $schedule['id'])->get();
                    $scoreOwner = Helper::isValidUserForScoreEnter($match_details->toArray());
                    if ($scoreOwner) {
                        if ($schedule['match_invite_status'] == 'accepted') {
//                        $matchScheduleData[$key]['winner_text'] = trans('message.schedule.addscore');
                            $matchScheduleData[$key]['winner_text'] = Helper::getCurrentScoringStatus($schedule);
                        } else {
                            if ($schedule['match_invite_status'] == 'pending') {
                                $matchScheduleData[$key]['winner_text'] = trans('message.schedule.pending');
                            } else {
                                $matchScheduleData[$key]['winner_text'] = trans('message.schedule.rejected');
                            }
                        }
                    }
                } else {
                    if ($isOwner) {
                        $matchScheduleData[$key]['winner_text'] = trans('message.schedule.edit');
                    }
                }
            }
//            $matchScheduleData[$key]['match_start_date'] = $matchStartDate->toFormattedDateString();
            $matchScheduleData[$key]['match_start_date'] = Helper::getFormattedTimeStamp($schedule);
            $isOwner = 0;
        }
        return $matchScheduleData;
    }

    public static function isTournamentOwner($tournamentManagerId, $tournamentParentId)
    {
        $userId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        $tournamentOwner = TournamentParent::where('id', $tournamentParentId)->first([
            'owner_id',
            'name',
            'manager_id'
        ]);
        if ($userId == $tournamentManagerId || $userId == $tournamentOwner->owner_id || $userId == $tournamentOwner->manager_id) {
            return true;
        }
        return false;

    }

    public static function getImageModel($params)
    {
        $appPrefix = 'App\\Model\\';
        $imageType = $params['imageType'];
        if ($imageType == config('constants.PHOTO.USER_PHOTO') || $imageType == config('constants.PHOTO.GALLERY_USER')) { // User
            $appPrefix = 'App\\';
        } elseif ($imageType == config('constants.PHOTO.MARKETPLACE_PHOTO')) { // Marketplace
            $model = 'MarketPlace';
        } elseif ($imageType == config('constants.PHOTO.SPORT_LOGO')) { // Sports
            $model = 'Sports';
        } elseif ($imageType == config('constants.PHOTO.FACILITY_LOGO') || $imageType == config('constants.PHOTO.GALLERY_FACILITY') || $imageType == config('constants.PHOTO.FACILITY_PROFILE')) { // Facility
            $model = 'Facilityprofile';
        } elseif ($imageType == config('constants.PHOTO.TEAM_PHOTO') || $imageType == config('constants.PHOTO.GALLERY_TEAM')) { // Teams
            $model = 'Team';
        } elseif ($imageType == config('constants.PHOTO.TOURNAMENT_LOGO') || $imageType == config('constants.PHOTO.GALLERY_TOURNAMENTS') || $imageType == config('constants.PHOTO.TOURNAMENT_PROFILE')) { // Tournament
            $model = 'Tournaments';
        } elseif ($imageType == config('constants.PHOTO.ORGANIZATION_LOGO') || $imageType == config('constants.PHOTO.GALLERY_ORGANIZATION') || $imageType == config('constants.PHOTO.ORGANIZATION_PROFILE')) { // Organization
            $model = 'Organization';
        }
        $modelName = $appPrefix . $model;
        return $modelName;
    }

    public static function getAllMarketPlaceCategories()
    {
        $categories = \App\Model\MarketPlaceCategories::get();
        return $categories;
    }

    public static function isApprovalExist($matchData, $isForApproval = '')
    {
        $loginUserId = Auth::user()->id;
        $loginUserRole = Auth::check() ? Auth::user()->role : null;
        $team_a_id = $matchData[0]['a_id'];
        $team_b_id = $matchData[0]['b_id'];
        $score_status_array = json_decode($matchData[0]['score_added_by'], true);
        if ($loginUserRole == 'admin') // if admin login
        {
            if ($matchData[0]['scoring_status'] == 'approval_pending') {
                return true;
            } else {
                return false;
            }

        } else {
            if (!empty($matchData[0]['tournament_id'])) // if match has tournament
            {
                return false;
            } else {
                if ($isForApproval == 'yes') {
                    if (!empty($score_status_array['added_by']) && ($matchData[0]['scoring_status'] == 'rejected' || $matchData[0]['scoring_status'] == '') && ($matchData[0]['winner_id'] != '' || $matchData[0]['is_tied'] > 0 || $matchData[0]['has_result'] == 0)) {
                        return true;
                    } else {
                        return false;
                    }

                } else {
                    if ($matchData[0]['schedule_type'] == 'player') {
                        $valid_user_for_approve = ($score_status_array['active_user'] != $loginUserId);
                    } else {
                        $valid_user_for_approve = (self::isTeamOwnerorcaptain($team_a_id,
                                $loginUserId) && self::isTeamOwnerorcaptain($team_b_id, $loginUserId));
                        if (!$valid_user_for_approve) {
                            $valid_user_for_approve = ($score_status_array['active_user'] != $loginUserId);
                        }
                    }

                    if (!empty($score_status_array['added_by'])
                        && $valid_user_for_approve
                        && $matchData[0]['scoring_status'] == 'approval_pending'
                    ) {
                        return true;
                    } else {
                        return false;
                    }
                }

            }
        }

    }

    public static function isTeamOwner($teamId)
    {
        $managingTeamsArray = array();
        $userStatistic = UserStatistic::where('user_id', Auth::user()->id)->first();
        if (!empty($userStatistic)) {
            $managingTeamsArray = explode(',', trim($userStatistic->managing_teams, ','));
        }
        if (count($managingTeamsArray) && in_array($teamId, $managingTeamsArray)) {
            return true;
        }
        return false;
    }

    public static function isTeamOwnerorcaptain($team_id, $loginUserId)
    {
        $user_role = TeamPlayers::where('user_id', $loginUserId)->where('team_id', $team_id)->pluck('role');
        if ($user_role == 'owner' || $user_role == 'captain' || $user_role == 'manager') {
            return true;
        }
        return false;
    }

    public static function isValidUserForScoreEnter($matchData)
    {
        //compatabitle layer for old code
        $matchData = isset($matchData[0]) ? $matchData[0] : $matchData;

        $loginUserId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        $loginUserRole = Auth::check() ? Auth::user()->role : null;
        $team_a_id = $matchData['a_id'];
        $team_b_id = $matchData['b_id'];
        if ($loginUserRole == 'admin')// if admin login
        {
            return true;
        } else {
            if (!empty($matchData['tournament_id']))// if match has tournament
            {
                $tournamentDetails = Tournaments::where('id', '=', $matchData['tournament_id'])->first();
                $tournamentManagerId = $tournamentDetails['manager_id'];
                $tournamentOwner = TournamentParent::where('id',
                    $tournamentDetails['tournament_parent_id'])->first(['owner_id', 'name', 'manager_id']);
                if ($loginUserId == $tournamentManagerId || $loginUserId == $tournamentOwner->owner_id || $loginUserId == $tournamentOwner->manager_id) {
                    return true;
                }
                return false;
            } else {


                //if schedule type is player
                $schedule_type = $matchData['schedule_type']; // if schedule type is player
                if ($schedule_type == 'player') {
                    if ($team_a_id == $loginUserId || $team_b_id == $loginUserId) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    $is_team_a_valid = Helper::isTeamOwnerorcaptain($team_a_id, $loginUserId);
                    $is_team_b_valid = Helper::isTeamOwnerorcaptain($team_b_id, $loginUserId);
                    if ($is_team_a_valid || $is_team_b_valid) {
                        return true;
                    } else {
                        return false;
                    }
                }

            }
        }
    }

    public static function isValidUserForTournamentGallery($tournamentid, $loginUserId)
    {
        $tournamentDetails = Tournaments::where('id', '=', $tournamentid)->first();
        $tournamentManagerId = $tournamentDetails['manager_id'];
        $tournamentOwner = TournamentParent::where('id', $tournamentDetails['tournament_parent_id'])->first([
            'owner_id',
            'name',
            'manager_id'
        ]);
        if ($loginUserId == $tournamentManagerId || $loginUserId == $tournamentOwner->owner_id || $loginUserId == $tournamentOwner->manager_id) {
            return true;
        } else {
            return false;
        }

    }

    public static function get_first_letters($string)
    {
        return strtoupper(preg_replace('/(\B.|\s+)/', '', $string));
    }

    public static function get_first_20_letters($string)
    {
        return ucfirst(substr($string, 0, 10));
    }

    //tournament left menu
    public static function getLeftMenuData($parent_tournament_id, $manager_id, $sub_tournament_details)
    {
        $userId = isset(Auth::user()->id) ? Auth::user()->id : 1;
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $exist_array = DB::select("SELECT DISTINCT t.id as item
                        FROM `tournaments` t
                        INNER JOIN `tournament_final_teams` f ON f.tournament_id = t.id AND f.team_id = $userId
                        WHERE t.schedule_type = 'individual' AND t.id IN ($parent_tournament_id) AND t.type != 'league'  
                        UNION ALL
                        SELECT DISTINCT t.id 
                        FROM `tournaments` t
                        INNER JOIN `tournament_group_teams` g ON g.tournament_id = t.id AND g.team_id = $userId
                        WHERE t.schedule_type = 'individual' AND t.id IN ($parent_tournament_id) AND t.type != 'knockout' ");
        DB::setFetchMode(PDO::FETCH_CLASS);

        $parent_tournament_details = TournamentParent::where('id', $parent_tournament_id)->get();

        $menu_details = array();
        $sub_tournament_name = $sub_tournament_details[0]['name'];
        $sport_name = Sport::where('id', $sub_tournament_details[0]['sports_id'])->pluck('sports_name');
        $match_type = $sub_tournament_details[0]['match_type'];
        if ($match_type != 'other') {
            $match_type = $match_type == 'odi' ? '(' . strtoupper($match_type) . ')' : '(' . ucfirst($match_type) . ')';
        }
        if (!empty($parent_tournament_details)) {
            $menu_details['logo'] = $parent_tournament_details[0]['logo'];
            $menu_details['name'] = $parent_tournament_details[0]['name'] . ' ' . $sub_tournament_name;
            $menu_details['location'] = '';
            $menu_details['description'] = $parent_tournament_details[0]['description'];
            $menu_details['is_owner'] = Helper::isTournamentOwner($manager_id, $parent_tournament_id);
            $menu_details['path'] = config('constants.PHOTO_PATH.TOURNAMENT');
            $menu_details['id'] = $parent_tournament_id;
            $menu_details['sports_matchtype'] = $sport_name . ' ' . $match_type;
            $menu_details['exist_array'] = $exist_array;
            $menu_details['sub_tournament_details'] = $sub_tournament_details[0];
        }
        return $menu_details;
    }

    //get the sports based on requirement
    public static function getDevelopedSport($is_schedule_available, $is_scorecard_available)
    {
        $sports = Sport::where('is_schedule_available', $is_schedule_available)->where('is_scorecard_available',
            $is_scorecard_available)->orderBy('id')->lists('sports_name', 'id')->all();
        return $sports;
    }

    //insert in match schedules when player is added to team
    public static function insertTeamPlayersInSchedules($team_id, $user_id)
    {
        //get schedule data with team a
        $matchDetailArray = array();
        $matchDetails = MatchSchedule::where('a_id', $team_id)->where('match_status', 'scheduled')->get([
            'player_a_ids',
            'id'
        ]);
        if (!empty($matchDetails)) {
            $matchDetailArray = $matchDetails->toArray();
            foreach ($matchDetailArray as $matchId) {
                $get_a_player_ids = $matchId['player_a_ids'] . $user_id . ',';
                MatchSchedule::where('id', $matchId['id'])->where('a_id',
                    $team_id)->update(['player_a_ids' => $get_a_player_ids]);
            }

        }

        //get schedule date with team b_id
        $matchDetailArrayTeamb = array();
        $matchDetailsTeamb = MatchSchedule::where('b_id', $team_id)->where('match_status',
            'scheduled')->get(['player_b_ids', 'id']);
        if (!empty($matchDetailsTeamb)) {
            $matchDetailArrayTeamb = $matchDetailsTeamb->toArray();
            foreach ($matchDetailArrayTeamb as $match_id) {
                $get_b_player_ids = $match_id['player_b_ids'] . $user_id . ',';
                MatchSchedule::where('id', $match_id['id'])->where('b_id',
                    $team_id)->update(['player_b_ids' => $get_b_player_ids]);
            }

        }
    }

    //function to remove an element from an array
    public static function removeFromArray($existing_ids_str, $new_id)
    {
        $existing_ids_str = trim($existing_ids_str, ',');
        $existing_ids = explode(',', $existing_ids_str);
        $key = array_search($new_id, $existing_ids);
        unset($existing_ids[$key]);
        $final_str = null;
        if (count($existing_ids)) {
            $final_str = implode(',', $existing_ids);
            $final_str = ',' . $final_str . ',';
        }
        return $final_str;
    }

    //remove player from the match schedule if score is not entered on that match
    public static function removePlayersFromMatch($team_id, $user_id)
    {
        $matchDetailArray = array();
        $matchDetails = MatchSchedule::where('a_id', $team_id)->where('match_status',
            'scheduled')->whereNull('score_added_by')->get(['player_a_ids', 'id']);
        if (!empty($matchDetails)) {
            $matchDetailArray = $matchDetails->toArray();
            foreach ($matchDetailArray as $matchId) {
                $get_a_player_ids = $matchId['player_a_ids'];
                $a_players = Helper::removeFromArray($get_a_player_ids, $user_id);
                MatchSchedule::where('id', $matchId['id'])->update(['player_a_ids' => $a_players]);
            }

        }
        //get schedule date with team b_id
        $matchDetailArrayTeamb = array();
        $matchDetailsTeamb = MatchSchedule::where('b_id', $team_id)->where('match_status',
            'scheduled')->whereNull('score_added_by')->get(['player_b_ids', 'id']);
        if (!empty($matchDetailsTeamb)) {
            $matchDetailArrayTeamb = $matchDetailsTeamb->toArray();
            foreach ($matchDetailArrayTeamb as $match_id) {
                $get_b_player_ids = $match_id['player_b_ids'];
                $b_players = Helper::removeFromArray($get_b_player_ids, $user_id);
                MatchSchedule::where('id', $match_id['id'])->update(['player_b_ids' => $b_players]);
            }

        }
    }

    //get the current scoring status
    public static function getCurrentScoringStatus($scheduleData)
    {
        $returnText = trans('message.schedule.addscore');
        $score_status_array = json_decode($scheduleData['score_added_by'], true);
        if (empty($score_status_array['added_by'])) {
            $returnText = trans('message.schedule.addscore');
        } else {
            if (!empty($score_status_array['added_by']) && !empty($scheduleData['scoring_status'])) {
                if ($scheduleData['scoring_status'] == 'rejected') {
                    $returnText = trans('message.schedule.editscore');
                } else {
                    $returnText = trans('message.schedule.viewscore');
                }
            } else {
                if (!empty($score_status_array['added_by']) && empty($scheduleData['scoring_status'])) {
                    if ($score_status_array['added_by'] == Auth::user()->id) {
                        $returnText = trans('message.schedule.editscore');
                    } else {
                        $returnText = trans('message.schedule.viewscore');
                    }
                }
            }
        }

        return !empty($returnText) ? $returnText : '';
    }

    // get the date and time based on application format
    public static function getFormattedTimeStamp($scheduleData)
    {
        $match_start_time = '';
        $match_start_date = date(config('constants.DATE_FORMAT.VALIDATION_DATE_FORMAT'),
            strtotime($scheduleData['match_start_date']));
        if (!empty($scheduleData['match_start_time']) && $scheduleData['match_start_time'] != '00:00:00') {
            $match_start_time = date(config('constants.DATE_FORMAT.VALIDATION_TIME_FORMAT'),
                strtotime($scheduleData['match_start_time']));
        }
        return $match_start_date . ' ' . $match_start_time;
    }

    public static function displayOtherUserImage($userid)
    {
        $logo = User::where('id', $userid)->first(['logo']);
        return Helper::Images(count($logo) ? $logo->logo : "", 'user_profile', array('height' => 100, 'width' => 100));

    }

    public static function displayDate($date, $format = 0)
    {
        $format = ($format == 1) ? 'DISPLAY_DATE_FORMAT' : 'PHP_DISPLAY_DATE_FORMAT';
        return date(config('constants.DATE_FORMAT.' . $format), strtotime($date));
    }

    public static function displayDateTime($date, $format = 0)
    {
        $format = ($format == 1) ? config('constants.DATE_FORMAT.DISPLAY_DATE_FORMAT') . ' g:i A' : 'd-m-Y H:i:s';
        return date($format, strtotime($date));
    }

    public static function displayDateFormat($date, $format = 'd-m-Y H:i:s')
    {
        return date($format, strtotime($date));
    }

    public static function storeDate($date, $flag = '')
    {
        $date = str_replace('/', '-', $date);
        if ($flag == 'date') {
            return date("Y-m-d", strtotime($date));
        } else {
            return date("Y-m-d H:i:s", strtotime($date));
        }
    }

    //function to check follow/unfollow
    public static function checkFollowUnfollow($userId, $type, $typeId)
    {
        $query = "select 
                    case WHEN deleted_at is null THEN 1
                    ELSE 0
                    end flag 
                from followers where user_id=$userId and type='$type' and type_id=$typeId";
        $result = DB::select(DB::raw("$query"));
        if (!empty($result) && $result[0]->flag) {
            return $result[0]->flag;
        }
        return 0;
    }

    // Get the joined tournaments of the logged in user
    public static function getJoinedTournaments()
    {
        $query = '';
        $userId = isset(Auth::user()->id) ? Auth::user()->id : 0;      //user or guest
        $query = "select u.name as user_name,s.sports_name,t.id,t.name,tp.logo as url,t.description,
                        case 
                                WHEN t.type='knockout' THEN IFNULL(t.final_stage_teams,0)
                                ELSE (select IFNULL(count(id),0) from tournament_group_teams where tournament_id=t.id group by t.id)
                        end as team_count
                        from tournaments t 
                        inner join tournament_parent tp on tp.id=t.tournament_parent_id
                        inner join users u on u.id=t.created_by
                        inner join sports s on s.id=t.sports_id 
                        where t.id in (
                        select tg.tournament_id 
                        from tournaments t 
                        inner join tournament_group_teams tg on t.id=tg.tournament_id and tg.deleted_at is null
                        where t.schedule_type='team' and t.deleted_at is null  and tg.team_id in
                        (select team_id from team_players where user_id=$userId and status='accepted' and deleted_at is null)
                        group by tg.tournament_id

                        union 

                        select tg.tournament_id 
                        from tournaments t 
                        inner join tournament_final_teams tg on t.id=tg.tournament_id and tg.deleted_at is null
                        where t.schedule_type='team' and t.deleted_at is null  and tg.team_id in
                        (select team_id from team_players where user_id=$userId and status='accepted' and deleted_at is null)
                        group by tg.tournament_id

                        union

                        select tg.tournament_id 
                        from tournaments t 
                        inner join tournament_group_teams tg on t.id=tg.tournament_id and tg.deleted_at is null
                        where t.schedule_type='individual' and tg.team_id=$userId and t.deleted_at is null 
                        group by tg.tournament_id

                        union 

                        select tg.tournament_id 
                        from tournaments t 
                        inner join tournament_final_teams tg on t.id=tg.tournament_id and tg.deleted_at is null
                        where t.schedule_type='individual' and tg.team_id=$userId and t.deleted_at is null
                        group by tg.tournament_id	
                        ) 
                        order by t.id";
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $tournaments = DB::select(DB::raw("$query"));
        DB::setFetchMode(PDO::FETCH_CLASS);
        return $tournaments;
    }

    /**
     * sortMultiDimArrayNumeric() - returns the sorted array based on the values of the provided key
     *
     * @param string $key
     * @return mixed comparison result
     *
     * @author Anoop Naik <anoopnaik2016@gmail.com>
     */
    public static function sortMultiDimArrayNumeric($key)
    {
        return function ($teamA, $teamB) use ($key) {
            if ($teamA[$key] == $teamB[$key]) {
                return 0;
            }
            return ($teamA[$key] < $teamB[$key]) ? 1 : -1;
        };
    }


    public static function getTeamDetails($team_id)
    {
        $team_model = Team::find($team_id);
        $team_model->gallery_title = "Photo Album of $team_model->name";
        $team_model->gallery_sharing = "Photo Album of $team_model->name";
        $team_model->gallery_url = "/uploads/gallery/team/$team_model->id/$team_model->logo";
        return $team_model;
    }

    public static function getTournamentDetails($tournament_id)
    {
        $t_model = Tournaments::find($tournament_id);
        $t_model->gallery_title = "Photo Album of $t_model->name";
        $t_model->gallery_sharing = " $t_model->name is a Tournament taking place which takes from $t_model->start_date to $t_model->end_date at $t_model->location";
        $t_model->gallery_url = "/uploads/gallery/tournaments/$t_model->id/$t_model->logo";
        return $t_model;
    }

    public static function getUserDetails($user_id)
    {
        $model = User::find($user_id);
        $model->gallery_title = "Photo Album of $model->name";
        $model->gallery_sharing = "$model->name is $model->gender sport player. $model->name leaves at $model->location.  Click here to view his full gallery.";
        $model->gallery_url = "/uploads/gallery/users/$model->logo";
        return $model;
    }

    public static function getOrganisationDetails($organization_id)
    {
        $model = Organization::find($organization_id);
        $model->gallery_title = "Photo Album of $model->name";
        $model->gallery_sharing = "$model->name is a sport organization with ... Click here to see its full gallery";
        $model->gallery_url = "/uploads/gallery/organisations/$model->logo";
        return $model;
    }


    public static function  sendEmailPlayers($match_details=[], $match_type=''){ 

                    $team_a_id=explode(',', $match_details['player_a_ids']);
                    $team_b_id=explode(',', $match_details['player_b_ids']); 
                    $tournament_id = $match_details['tournament_id'];

                if($match_details['schedule_type']=='player'){
                    $team_a_name   =  User::find($match_details['a_id'])->name;
                    $team_b_name   =  User::find($match_details['b_id'])->name; 
                }
                else{
                    $team_a_name   =  Team::find($match_details['a_id'])->name;
                    $team_b_name   =  Team::find($match_details['b_id'])->name;
                }
       

                //send email to  team_a players
                    foreach ($team_a_id as $key => $player_id) {
        Helper::send_match_email_to_user($match_details, $player_id,$team_a_name, $team_b_name,'endmatchnotification_followers');
             
                    }
                //send email to team_b players
                    foreach ($team_b_id as $key => $player_id) {
        Helper::send_match_email_to_user($match_details, $player_id,$team_a_name, $team_b_name,'endmatchnotification');
                    }

                //Send Email to followers (tournaments and organizations )

                if(!is_null($tournament_id )){
                    $tournament=Tournaments::find( $tournament_id );
                    $followers = $tournament->followers;

                    foreach ($followers as $key => $follower) {
                        $player_id = $follower->user_id;
        Helper::send_match_email_to_user($match_details, $player_id,$team_a_name, $team_b_name,'endmatchnotification_followers');
             
                    }

                    $organization = $tournament->tournamentParent->organization;
                if($organization){
                    $followers = $organization->followers;

                    foreach ($followers as $key => $follower) {
                        $player_id = $follower->user_id;
        Helper::send_match_email_to_user($match_details, $player_id,$team_a_name, $team_b_name,'endmatchnotification_followers');
                        
                    }
                }

                }
    }

    public static function start_match_email($match_details){

           $tournament_id = $match_details['tournament_id'];

                if($match_details['schedule_type']=='player'){
                    $team_a_name   =  User::find($match_details['a_id'])->name;
                    $team_b_name   =  User::find($match_details['b_id'])->name; 
                }
                else{
                    $team_a_name   =  Team::find($match_details['a_id'])->name;
                    $team_b_name   =  Team::find($match_details['b_id'])->name;
                }

            if(!is_null($tournament_id )){
                    $tournament=Tournaments::find( $tournament_id );
                    $followers = $tournament->followers;

                    foreach ($followers as $key => $follower) {
                        $player_id = $follower->user_id;
        Helper::send_match_email_to_user($match_details, $player_id,$team_a_name, $team_b_name,'startmatchnotification_followers');
             
                    }

                    $organization = $tournament->tournamentParent->organization;
                if($organization){
                    $followers = $organization->followers;

                    foreach ($followers as $key => $follower) {
                        $player_id = $follower->user_id;
        Helper::send_match_email_to_user($match_details, $player_id,$team_a_name, $team_b_name,'startmatchnotification_followers');
                        
                    }
                }

                }
    }

    public static function send_match_email_to_user($match_details, $player_id,$team_a_name, $team_b_name,$view){

         if(!is_null($user=User::find($player_id))){  
                            $user_name=$user->name;
                            $data=[
                                'match_type'    =>  $match_details['match_type'], 
                                'match_date'    =>  $match_details['match_start_date'],
                                'team_a_name'   =>  $team_a_name,
                                'team_b_name'   =>  $team_b_name,
                                'user_name'     =>  $user_name,
                                'match_id'      =>  $match_details['id'],
                                'user_id'       =>  $player_id,
                                'sports_name'   =>  '',

                                ];
                            $mail_data=[
                                    'view'      =>  'emails.'.$view,
                                    'subject'   =>  'Match : '. strtoupper($team_a_name) .' vs ' . strtoupper($team_b_name) . ' Scorecard (sportsJun)' ,
                                    'to_user_id'=>  $player_id,
                                    'to_email_id'=> $user->email,
                                    'view_data' =>  $data,
                                    'flag'      =>  $match_details['$match_type'],
                                    'send_flag' =>  1,
                                     'type'          =>  $view
                            ];

                        SendMail::sendmail($mail_data);
        }

    }

    public static function getScoresFromMatchDetails($match_details, $sports_id, $a_id, $b_id)
    {
        switch ($sports_id) {
            case '5':           //badminton
                $scores = $match_details->scores;
                return $scores->{$a_id . '_score'} . ' sets - ' . $scores->{$b_id . '_score'} . ' sets';
                break;
            case in_array($sports_id, ['13', '17', '7']):           //squash
                $scores = $match_details->scores;
                return $scores->{$a_id . '_score'} . ' sets - ' . $scores->{$b_id . '_score'} . ' sets';
                break;
            case '4':           //soccer
                return $match_details->{$a_id}->goals . ' - ' . $match_details->{$b_id}->goals;
                break;
            case '11':           //hockey
                return $match_details->{$a_id}->goals . ' - ' . $match_details->{$b_id}->goals;
                break;
            case in_array($sports_id, [6, 14, 15, 16]):           //basketball
                return $match_details->{$a_id}->total_points . ' - ' . $match_details->{$b_id}->total_points;
                break;
            case '1':           //cricket
                return Team::find($a_id)->name . " (" . $match_details->{$a_id}->fst_ing_score . "/" . $match_details->{$a_id}->fst_ing_wkt . (!empty($match_details->{$a_id}->scnd_ing_overs) ? ", " . $match_details->{$a_id}->scnd_ing_score . "/" . $match_details->{$a_id}->scnd_ing_wkt : "") . ") &nbsp;" .
                Team::find($b_id)->name . " (" . $match_details->{$b_id}->fst_ing_score . "/" . $match_details->{$b_id}->fst_ing_wkt . (!empty($match_details->{$b_id}->scnd_ing_overs) ? ", " . $match_details->{$b_id}->scnd_ing_score . "/" . $match_details->{$b_id}->scnd_ing_wkt : "") . ")";
                break;
            default:
                return '';
                break;
        }
    }


    public static function getMatchDetails($match_id, $game_type = 'normal')
    {

        if ($game_type == 'normal') {
            $match_model = MatchSchedule::find($match_id);
        }
        if ($game_type == 'rubber') {
            $match_model = MatchScheduleRubber::find($match_id);
        }

        //get the winner
        if (!empty($match_model->winner_id)) {
            if ($match_model->schedule_type == 'player') {
                $match_model->winner = User::find($match_model->winner_id)->name;
            } else {
                $match_model->winner = Team::find($match_model->winner_id)->name;
            }
        } else {
            $match_model->winner = '';
        }

        if ($match_model->match_details != null) {
            $match_details = json_decode($match_model->match_details);
            $a_id = $match_model->a_id;
            $b_id = $match_model->b_id;
            $match_model->scores = self::getScoresFromMatchDetails($match_details, $match_model->sports_id, $a_id,
                $b_id);
        } else {
            $match_model->scores = ' - ';
        }

        if ($match_model->game_type != 'normal') {
            $match_model->scores = $match_model->a_score . ' - ' . $match_model->b_score;
        }
        return $match_model;
    }

    public static function updateOrganizationTeamsPoints($tournament_id = null)
    {
        $tournaments_teams = DB::table('tournament_group_teams')
            ->join('tournaments', 'tournaments.id', '=', 'tournament_group_teams.tournament_id')
            ->join('organization_group_teams', 'organization_group_teams.team_id', '=',
                'tournament_group_teams.team_id')
            ->join('organization_groups', 'organization_groups.id', '=',
                'organization_group_teams.organization_group_id')
            ->select('tournament_group_teams.*', 'organization_groups.*', 'tournaments.*',
                'organization_group_teams.*');
        // ->groupBy('tournaments.id')
        // ->groupBy('organization_group_teams.organization_group_id') ;

        if (!empty($tournament_id)) {
            $tournaments_teams = $tournaments_teams->where('tournaments.id', '=', $tournament_id);
        }
        $tournaments_teams = $tournaments_teams->get();

        foreach ($tournaments_teams as $organization_group_team) {
            $tournament_id = $organization_group_team->tournament_id;
            $organization_id = $organization_group_team->organization_id;
            $organization_group_id = $organization_group_team->organization_group_id;
            $sports_id = $organization_group_team->sports_id;
            $organization_group_points = Helper::getGroupPoints($tournament_id, $organization_group_id);

            $check = OrganizationGroupTeamPoint::whereTournamentId($tournament_id)->whereOrganizationGroupId($organization_group_id)->first();

            if (!empty($check)) {
                $check->points = $organization_group_points;
                $check->save();
            } else {

                if (empty($organization_group_points)) {
                    $organization_group_points = 0;
                }


                $new_ogtp = new OrganizationGroupTeamPoint;

                $new_ogtp->organization_id = $organization_id;
                $new_ogtp->tournament_id = $tournament_id;
                $new_ogtp->sports_id = $sports_id;
                $new_ogtp->points = $organization_group_points;
                $new_ogtp->organization_group_id = $organization_group_id;
                $new_ogtp->tournament_parent_id = $organization_group_team->tournament_parent_id;

                $new_ogtp->save();

            }
        }


        return $tournaments_teams;
    }

    public static function getGroupPoints($tournament_id, $organization_group_id)
    {
        $team_id = DB::table('organization_group_teams')
            ->where('organization_group_id', $organization_group_id)
            ->lists('team_id');
        $teams = null;
        if ($team_id) {
            $teams = TournamentGroupTeams::whereTournamentId($tournament_id);
            if (is_array($team_id)) {
                $teams->whereIn('team_id', $team_id);
            } else {
                $teams->where('team_id', $team_id);
            }
            $teams = $teams->get();
        }
        if (!$teams) {
            return 0;
        }

        $final_points = $teams->sum('final_points');
        $points = $final_points ? $final_points : $teams->sum('points');
        return $points;


    }

    public function getOrganizationTeamPoints($tournament_id = '')
    {
        return updateOrganizationTeamsPoints();
    }


    public static function getRoundStage($tournament_id, $round_number)
    {

        $tournaments = Tournaments::find($tournament_id);
        $count = $tournaments->final_stage_teams;
        $total_rounds = ceil(log($count, 2));
        $diff = $total_rounds - $round_number;

        $round_names = [];
        $round_names[-1] = 'WINNER';
        $round_names[-2] = '';
        $round_names[0] = 'FINAL';
        $round_names[1] = 'SEMI FINAL';
        $round_names[2] = 'QUARTER FINAL';

        for ($i = 3; $i <= 8; $i++) {
            $round_names[$i] = "ROUND " . ($total_rounds - $i);
        }

        return $round_names[$total_rounds - $round_number];

    }

    public static function getMatchGroupDetails($tournament_id, $tournament_group_id, $team_id)
    {

        $details = [];
        $details['gf'] = 0;
        $details['ga'] = 0;
        $details['tie'] = 0;
        $tournament = Tournaments::find($tournament_id);
        $sports_id = $tournament->sports_id;
        $matches = MatchSchedule::whereTournamentId($tournament_id)
            ->whereTournamentGroupId($tournament_group_id)
            ->where(function ($query) use ($team_id) {
                $query->where('a_id', '=', $team_id)
                    ->orWhere('b_id', '=', $team_id);
            });
        $match_models = $matches->get();

        foreach ($match_models as $key => $value) {
            if ($value->is_tied == 1) {
                $details['tie']++;
            }
        }

        switch ($sports_id) {

            case ($sports_id == 4 || $sports_id == 11):
                //die(json_encode($team_id));
                foreach ($match_models as $key => $match) {
                    if ($match->a_id == $team_id) {         //sets the home and againts team
                        $gf_team = $match->a_id;
                        $ga_team = $match->b_id;
                    } elseif ($match->b_id == $team_id) {
                        $gf_team = $match->b_id;
                        $ga_team = $match->a_id;
                    }
                    $match_details = json_decode($match->match_details);
                    if (!empty($match->match_details)) {
                        $details['gf'] += $match_details->{$gf_team}->goals;
                        $details['ga'] += $match_details->{$ga_team}->goals;
                    }

                }


                break;

            case in_array($sports_id, [3, 5, 13, 17, 7]):
                //die(json_encode($team_id));
                foreach ($match_models as $key => $match) {

                    if ($match->game_type == 'normal') {
                        if ($match->a_id == $team_id) {         //sets the home and againts team
                            $gf_team = $match->a_id;
                            $ga_team = $match->b_id;
                        } elseif ($match->b_id == $team_id) {
                            $gf_team = $match->b_id;
                            $ga_team = $match->a_id;
                        }
                        $match_details = json_decode($match->match_details);
                        if (!empty($match->match_details)) {
                            $details['gf'] += $match_details->scores->{$gf_team . '_score'};
                            $details['ga'] += $match_details->scores->{$ga_team . '_score'};
                        }
                    } else {
                        if ($match->a_id == $team_id) {         //sets the home and againts team
                            $details['gf'] += $match->a_score;
                            $details['ga'] += $match->b_score;
                        } elseif ($match->b_id == $team_id) {
                            $details['gf'] += $match->b_score;
                            $details['ga'] += $match->a_score;
                        }
                    }

                }

                break;
       
            case in_array($sports_id, [6,14,15,16]):
            foreach ($match_models as $key => $match) {
                  if ($match->a_id == $team_id) {         //sets the home and againts team

                        $gf_team = $match->a_id;
                        $ga_team = $match->b_id;
                    } elseif ($match->b_id == $team_id) {
                        $gf_team = $match->b_id;
                        $ga_team = $match->a_id;
                    }
                    $match_details = json_decode($match->match_details);
                    if (!empty($match->match_details)) {
                        $details['gf'] += $match_details->{$gf_team}->total_points;
                        $details['ga'] += $match_details->{$ga_team}->total_points;
                    }

                }
                break;

            default:

                break;
        }

        return $details;
    }


    public static function displayEmptyDash($number, $type=null){

            if($number){
                return $number;
            }
            else {

                if($type || $type==0)  return $type;
                else       return '-';
            }

    }

    public static function getVolleyballServer($match_id, $sports_name = 'volleyball')
    {

        switch ($sports_name) {
            case 'volleyball':
                $team_server = volleyballScore::whereMatchId($match_id)->where('elected', 'serve')->first();
                $player_stats = new VolleyballPlayerMatchwiseStats;
                break;
            case 'throwball':
                $team_server = throwballScore::whereMatchId($match_id)->where('elected', 'serve')->first();
                $player_stats = new ThrowballPlayerMatchwiseStats;
                break;

            case 'kabaddi':
                $team_server = kabaddiScore::whereMatchId($match_id)->where('elected', 'serve')->first();
                $player_stats = new KabaddiPlayerMatchwiseStats;
                break;

            default:
                # code...
                break;
        }

        if (isset($team_server)) {
            $team_id = $team_server->team_id;
            $player_serving = $player_stats::whereMatchId($match_id)->whereTeamId($team_id)->where('serving_order',
                1)->first();

            $serving_array = json_decode(json_encode(
                [
                    'team_id' => $team_id,
                    'player_id' => empty($player_serving) ? 0 : $player_serving->user_id,
                    'team_name' => Team::find($team_id)->name,
                    'player_name' => empty($player_serving) ? '' : User::find($player_serving->user_id)->name
                ]

            ));

        } else {
            $serving_array = json_decode(json_encode(
                [
                    'team_id' => 0,
                    'player_id' => 0,
                    'team_name' => '',
                    'player_name' => ''
                ]
            ));
        }

        return $serving_array;
    }

    public static function getMatchSettings($tournament_id, $sports_id = 5)
    {
        if (is_null($tournament_id)) {
            $settings = json_encode(config('constants.SPORTS_PREFERENCES.' . $sports_id));
        } else {
            $sports_id = Tournaments::find($tournament_id)->sports_id;
            $settings = TournamentMatchPreference::where('tournament_id', $tournament_id)->first();
            if (count($settings)) {
                $settings = $settings->settings;
            } else {
                $settings = json_encode(config('constants.SPORTS_PREFERENCES.' . $sports_id));
                $tmp = new TournamentMatchPreference;
                $tmp->tournament_id = $tournament_id;
                $tmp->sports_id = $sports_id;
                $tmp->settings = $settings;
                $tmp->save();
            }

        }
        return json_decode($settings);
    }

    public static function getThirdPosition($tournament_id, $round)
    {

        $check_schedule = MatchSchedule::whereTournamentId($tournament_id)
            ->whereTournamentRoundNumber($round)
            ->whereTournamentMatchNumber(2)
            ->first();


        $semi_final_schedule = MatchSchedule::whereTournamentId($tournament_id)
            ->whereTournamentRoundNumber($round - 1)
            ->get();

        //dd($semi_final_schedule);

       
    if(!(isset($semi_final_schedule[0]) && isset($semi_final_schedule[1]))){
        return 0;
    }

     $matchScheduleDetails = $semi_final_schedule[0];


        if ($matchScheduleDetails['schedule_type'] == 'team') {
            $player_a_ids = TeamPlayers::select(DB::raw('GROUP_CONCAT(DISTINCT user_id) AS player_a_ids'))->where('team_id',
                $semi_final_schedule[0]->looser_id)->pluck('player_a_ids');
            $player_b_ids = TeamPlayers::select(DB::raw('GROUP_CONCAT(DISTINCT user_id) AS player_b_ids'))->where('team_id',
                $semi_final_schedule[1]->looser_id)->pluck('player_b_ids');
        } else {
            $player_a_ids = $semi_final_schedule[0]->looser_id;
            $player_b_ids = $semi_final_schedule[1]->looser_id;

        }
    




        if (!$check_schedule) {

            $scheduleArray[] = [
                'tournament_id' => $matchScheduleDetails['tournament_id'],
                'tournament_round_number' => $round,
                'tournament_match_number' => 2,
                'sports_id' => $matchScheduleDetails['sports_id'],
                'facility_id' => $matchScheduleDetails['facility_id'],
                'facility_name' => $matchScheduleDetails['facility_name'],
                'created_by' => $matchScheduleDetails['created_by'],
                'match_category' => $matchScheduleDetails['match_category'],
                'schedule_type' => $matchScheduleDetails['schedule_type'],
                'match_type' => $matchScheduleDetails['match_type'],
                'match_location' => $matchScheduleDetails['match_location'],
                'city_id' => $matchScheduleDetails['city_id'],
                'city' => $matchScheduleDetails['city'],
                'state_id' => $matchScheduleDetails['state_id'],
                'state' => $matchScheduleDetails['state'],
                'country_id' => $matchScheduleDetails['country_id'],
                'country' => $matchScheduleDetails['country'],
                'zip' => $matchScheduleDetails['zip'],
                'match_status' => 'scheduled',
                'a_id' => $semi_final_schedule[0]->looser_id,
                'b_id' => $semi_final_schedule[1]->looser_id,
                'player_a_ids' => !empty($player_a_ids) ? (',' . trim($player_a_ids) . ',') : null,
                'player_b_ids' => !empty($player_b_ids) ? (',' . trim($player_b_ids) . ',') : null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'game_type' => $matchScheduleDetails['game_type'],
                'number_of_rubber' => $matchScheduleDetails['number_of_rubber'],
                'is_third_position' => 1,
            ];

            MatchSchedule::insert($scheduleArray);
            $check_schedule = MatchSchedule::whereTournamentId($tournament_id)
                ->whereTournamentRoundNumber($round)
                ->whereTournamentMatchNumber(2)
                ->first();
        } else {
            //    if(empty($check_schedule->match_start_date) && $check_schedule->match_status!='completed'){
            $check_schedule->player_a_ids = $player_a_ids;
            $check_schedule->player_b_ids = $player_b_ids;
            $check_schedule->a_id = $semi_final_schedule[0]->looser_id;
            $check_schedule->b_id = $semi_final_schedule[1]->looser_id;
            $check_schedule->save();
            //  }
        }

        $isOwner = 0;
        if (Helper::isTournamentOwner($check_schedule->tournament->manager_id,
            $check_schedule->tournament->tournament_parent_id)
        ) {
            $isOwner = 1;
        }

        if ($semi_final_schedule[0]->match_status == 'completed' && empty($semi_final_schedule[0]->looser_id)) {
            $check_schedule->match_status = 'completed';
            $check_schedule->winner_id = $semi_final_schedule[1]->looser_id;
        }

        if ($semi_final_schedule[1]->match_status == 'completed' && empty($semi_final_schedule[1]->looser_id)) {
            $check_schedule->match_status = 'completed';
            $check_schedule->winner_id = $semi_final_schedule[0]->looser_id;
        }

        $check_schedule->save();


        if (!empty($check_schedule['match_start_date'])) {
            $matchStartDate = Carbon::createFromFormat('Y-m-d', $check_schedule['match_start_date']);
            if (!empty($check_schedule['winner_id']) && $check_schedule['match_status'] == 'completed') {
                $check_schedule['winner_text'] = trans('message.schedule.matchstats');
            } //                            else if (Carbon::now()->gte($matchStartDate) && $schedule['match_invite_status']=='accepted') {
            else {
                if (Carbon::now()->gte($matchStartDate)) {
                    if ($isOwner) {
                        $check_schedule['winner_text'] = trans('message.schedule.addscore');
                    } else {
                        $check_schedule['winner_text'] = trans('message.schedule.viewscore');
                    }
                }
            }
        }


        return $check_schedule;
    }


    public static function getFinalPoints($tournament_id, $team_id)
    {
        $final_team = TournamentFinalTeams::whereTournamentId($tournament_id)
            ->whereTeamId($team_id)
            ->first();
        if ($final_team) {
            return $final_team->points;
        } else {
            return '-';
        }
    }

    //update group points after group level is completed

    public static function updateGroupPoints($tournament_id, $group_id, $team_id, $position)
    {
        $tournament = Tournaments::find($tournament_id);
        $group_teams = tournamentgroupteams::whereTournamentId($tournament_id)
            ->whereTournamentGroupId($group_id)
            ->whereTeamId($team_id)
            ->first();

        if ($tournament->group_is_ended && !count($tournament->tournament_final_teams)) {

            if ($tournament->{'p_' . $position}) {
                $group_teams->final_points = $tournament->{'p_' . $position};
                $group_teams->save();
            }

        }
    }

    public static function getAverageStats($stats)
    {
        $avgStats = [];
        if(!count($stats) > 0)
            return array();

        foreach($stats as $stat)
        {
            foreach($stat as $attribute=>$val)
            {
                $avgStats[$attribute] += $val;
            }
        }

        foreach($avgStats as $attribute=>$val)
        {
            $avgStats[$attribute] = $avgStats[$attribute] / count($stats);
        }
    }

    public static function get_fielder_team($user_id, $tournament_id){
    $team=TeamPlayers::join('teams', 'teams.id','=', 'team_players.team_id')
        ->leftjoin('tournament_group_teams','tournament_group_teams.team_id','=','teams.id')
        ->leftjoin('tournament_final_teams','tournament_final_teams.team_id','=','teams.id') 
        ->where(function($query) use($tournament_id, $user_id) {
            $query
            ->where('tournament_group_teams.tournament_id',$tournament_id)                           
            ->orwhere('tournament_final_teams.tournament_id',$tournament_id);
        })    
        ->where('team_players.user_id',$user_id)
        ->first();

        return $team;
    }


    public static function check_if_org_template_enabled(){
        $response = false; 

        $request = Request::url();      


          $allow_newtemplate_setting  = BasicSettings::where('name', 'organization_new_template')->first();
          if($allow_newtemplate_setting && $allow_newtemplate_setting->description=='1'){
             $new_template=true;
          }
          else {
            return false;
          }

        
          if(Auth::user()->type==1 && count(Auth::user()->organizations)){
             $organization = Auth::user()->organizations[0];
             session::put('organization_id',$organization->id);
             return true;
          }
          else{

        //if user is not organization but view organization pages, allow
            $url = Request::url();
            $id = Request::route()->parameter('id');

                if(str_contains($url,'org')){
                     $org = Organization::find($id); 
                    if($org) session::put('organization_id',$id);
                    return true;
                }    

          }
        
        return false;
    }


}

