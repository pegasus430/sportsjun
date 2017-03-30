<?php

namespace App\Console\Commands;

use App\Model\SmiteMatch;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Model\MatchSchedule;
use App\Model\Sport;
use App\Helpers\AllRequests;

class SendEsportsMatchData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'esports:match-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send information about the eSports matches to Managers, Owners or Players';

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
        $time1_day = Carbon::now()->subMonths(1)->format('Y-m-d');
        $time1_time = Carbon::now()->subMonths(1)->format('h:m:s');

        $time2_day = Carbon::now()->addHours(2)->format('Y-m-d');
        $time2_time = Carbon::now()->addHours(2)->format('h:m:s');

        $this->info($time1_day);
        $this->info($time2_day);
        $this->info($time1_time);
        $this->info($time2_time);

        $sport = Sport::where('sports_name', strtolower('smite'))->first();

        $matchScheduleData = MatchSchedule::whereBetween('match_start_date', array($time1_day,$time2_day))
            ->whereBetween('match_start_time', array($time1_time, $time2_time))
            ->where('sports_id', $sport->id)
            ->get();

        if (count($matchScheduleData) > 0)
        {
            foreach ($matchScheduleData as $key => $schedule){
                $this->info($schedule);
                // Set lobby name and password for each match
                $lobbyName = "Smite".str_random(4);
                $password = str_random(5);

                $smiteMatch = SmiteMatch::create([
                    'match_id' => $schedule->id,
                    'match_status' => 'started',
                    'lobby_name' => $lobbyName,
                    'lobby_password' => $password,
                ]);

                // Send match info to user/owner/manager
                AllRequests::sendMatchInfo($schedule->tournament_id,$schedule->schedule_type,$schedule->a_id,$schedule->b_id,$schedule->match_start_date,"Smite", $lobbyName, $password);
            }
            echo "Success";exit;
        }
        else
        {
            echo "No records found.";exit;
        }
    }
}
