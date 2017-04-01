<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Model\SmiteMatch;
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

        $matchScheduleData = SmiteMatch::where('match_status', 'started')->get();

        //$this->info(SmiteMatch::find(1)->match);

        //$this->info($matchScheduleData);

        if (count($matchScheduleData) > 0)
        {
            foreach ($matchScheduleData as $key => $schedule)
            {
                $teamOne = $schedule->match->player_a_ids;
                $teamTwo = $schedule->match->player_b_ids;

                $teamOne = explode(',',$teamOne);
                $teamTwo = explode(',',$teamTwo);

                foreach($teamOne as $participant)
                {
                    if(empty($participant))
                        continue;

                    $this->info($participant);
                }
                /*
                $signature = Esports::createSmiteSignature(config('esports.SMITE.SMITE_PLAYER'));
                $player = Esports::getSmitePlayer($signature,"RadeLackovic",$sessionId);
                var_dump($player);
                $signature = Esports::createSmiteSignature(config('esports.SMITE.SMITE_MATCHHISTORY'));
                $matchHistory = Esports::getMatchHistory($signature,"RadeLackovic",$sessionId);
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
