<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Model\SmiteMatch;
use App\Model\Sport;
use App\User;
use App\Helpers\Esports;

class CheckSmiteMatches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'esports:check-smite-matches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if Smite matches have finished.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Query to notify users about the match info (lobby creator, lobby name, lobby password)
        $time1_day = Carbon::now()->subMonth()->subHours(5)->format('Y-m-d');
        $time1_time = Carbon::now()->subMonth()->subHours(6)->format('h:m:s');

        $time2_day = Carbon::now()->addMinute()->format('Y-m-d');
        $time2_time = Carbon::now()->addMinute()->subHours(3)->format('h:m:s');

        /*
        $this->info($time1_day);
        $this->info($time2_day);
        $this->info($time1_time);
        $this->info($time2_time);
        */
        $sport = Sport::where('sports_name', strtolower('smite'))->first();
        $matchScheduleData = SmiteMatch::where('match_status', 'started')->get();

        if (count($matchScheduleData) > 0)
        {
            /*
             TO DO:
            -Store session to db
            -Check if session expired
             */

            $sessionId = Esports::createSmiteSession();

            foreach ($matchScheduleData as $key => $schedule)
            {
                $teamOne = $schedule->match->player_a_ids;
                $teamTwo = $schedule->match->player_b_ids;

                $teamOne = explode(',',$teamOne);
                $teamTwo = explode(',',$teamTwo);

                $matchId = '';
                $matchFound = false;

                foreach($teamOne as $participant)
                {
                    if(empty($participant))
                        continue;

                    $user = User::find($participant);
                    $smiteUsername = '';

                    foreach($user->gameUsernames as $gameUsername)
                    {
                        if($gameUsername->sport_id == $sport->id)
                        {
                            $smiteUsername = $gameUsername->username;
                            break;
                        }
                    }

                    if(empty($smiteUsername))
                        continue;

                    $player = Esports::getSmitePlayer($smiteUsername,$sessionId);

                    if(empty($player))
                        continue;

                    $playerId = $player[0]->Id;

                    $matchHistory = Esports::getMatchHistory($playerId,$sessionId);

                    $matchTime = Carbon::createFromFormat('m/d/Y H:i:s A',$matchHistory->Match_Time, new \DateTimeZone('UTC'));
                    $matchTime->addSeconds($matchHistory->Time_In_Match_Seconds);

                    /* Check if match finished 5 minutes ago */
                    $fiveMinutesAgo = Carbon::now()->subHours(5);
                    if($matchTime < $fiveMinutesAgo)
                        continue;

                    $matchId = $matchHistory->Match;
                    break;
                }

                if(empty($matchId))
                    continue;

                foreach($teamTwo as $participant)
                {
                    if(empty($participant))
                        continue;

                    $user = User::find($participant);
                    $smiteUsername = '';

                    foreach($user->gameUsernames as $gameUsername)
                    {
                        if($gameUsername->sport_id == $sport->id)
                        {
                            $smiteUsername = $gameUsername->username;
                            break;
                        }
                    }

                    if(empty($smiteUsername))
                        continue;

                    $player = Esports::getSmitePlayer($smiteUsername,$sessionId);

                    if(empty($player))
                        continue;

                    $playerId = $player[0]->Id;

                    $matchHistory = Esports::getMatchHistory($playerId,$sessionId);

                    // If match ids are the same, it is the same match
                    if($matchId = $matchHistory->Match) {
                        $matchFound = true;
                        break;
                    }
                }

                if(!$matchFound)
                    continue;

                


/*
                $signature = Esports::createSmiteSignature(config('esports.SMITE.SMITE_PLAYER'));
                $player = Esports::getSmitePlayer($signature,"RadeLackovic",$sessionId);
                var_dump($player);

*/


            }
            echo "Success";exit;
        }
        else
        {
            echo "No records found.";exit;
        }
    }
}
