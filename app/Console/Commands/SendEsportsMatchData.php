<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Model\MatchSchedule;
use App\Model\Sport;
use App\Helpers\AllRequests;
use App\Helpers\Esports;
use App\Model\SmiteMatch;

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
        $time1_day = Carbon::now()->subMonth()->subHours(5)->format('Y-m-d');
        $time1_time = Carbon::now()->subMonth()->subHours(6)->format('h:m:s');

        $time2_day = Carbon::now()->addMinute()->format('Y-m-d');
        $time2_time = Carbon::now()->addMinute()->subHours(3)->format('h:m:s');
        $sport = Sport::where('sports_name', strtolower('smite'))->first();

        $matchScheduleData = MatchSchedule::whereBetween('match_start_date', array($time1_day,$time2_day))
            ->whereBetween('match_start_time', array($time1_time, $time2_time))
            ->where('sports_id', $sport->id)
            ->get();

        if (count($matchScheduleData) > 0)
        {
            foreach ($matchScheduleData as $key => $schedule)
            {
                // Set lobby name and password for each match
                $lobbyName = "Smite".str_random(4);
                $password = str_random(5);

                $smiteMatch = SmiteMatch::create([
                    'match_id' => $schedule->id,
                    'match_status' => 'started',
                    'lobby_name' => $lobbyName,
                    'lobby_password' => $password,
                ]);

                // Get players/teams names
                if($schedule->schedule_type=='team')
                {
                    $firstParticipant = AllRequests::getteamname($schedule->a_id);
                    $secondParticipant = AllRequests::getteamname($schedule->b_id);

                    $teamOneOwnerId = AllRequests::getempidonroles($schedule->a_id,'owner');
                    $teamTwoOwnerId = AllRequests::getempidonroles($schedule->a_id,'owner');

                    $playerOneDetails = AllRequests::getUserNameAndEmail($teamOneOwnerId);
                    $playerTwoDetails = AllRequests::getUserNameAndEmail($teamTwoOwnerId);
                }
                else
                {
                    $playerOneDetails = AllRequests::getUserNameAndEmail($schedule->a_id);
                    $playerTwoDetails = AllRequests::getUserNameAndEmail($schedule->b_id);
                    $firstParticipant = $playerOneDetails->name;
                    $secondParticipant = $playerTwoDetails->name;
                }

                // Send match info to user/owner/manager
                AllRequests::sendMatchInfo($schedule->tournament_id,$schedule->schedule_type,$schedule->a_id,$schedule->b_id,$schedule->match_start_date,"Smite", $lobbyName, $password);

                // Send email
                AllRequests::sendMatchInfoEmail($playerOneDetails->name, $playerTwoDetails->id, $playerOneDetails->email, $firstParticipant, $secondParticipant, $lobbyName, $password);
                AllRequests::sendMatchInfoEmail($playerTwoDetails->name, $playerTwoDetails->id, $playerOneDetails->email, $firstParticipant, $secondParticipant, $lobbyName, $password);

            }
            echo "Success";exit;
        }
        else
        {
            echo "No records found.";exit;
        }
    }
}
