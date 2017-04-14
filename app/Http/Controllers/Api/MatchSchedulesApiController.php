<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\MatchSchedule;
use App\User;


class  MatchSchedulesApiController extends BaseApiController
{
    /**
     * Match - List
     * @return array
     */

    function getList()
    {
        $schedules = $this
            ->applyFilter(MatchSchedule::select(), [])
            ->paginate(50);

        $map = [
            'id',
            'tournament_id',
            'tournament_group_id',
            'tournament_round_number',
            'tournament_match_number',
            'sports_id',
            'facility_id',
            'facility_name',
            'created_by',
            'match_category',
            'schedule_type',
            'match_type',
            'match_start_date',
            'match_start_time',
            'match_end_date',
            'match_end_time',
            'match_location',
            'longitude',
            'latitude',
            'address',
            'city_id',
            'city',
            'state_id',
            'state',
            'country_id',
            'country',
            'zip',
            'match_status',
            'match_invite_status',
            'a_id',
            'a_name' => 'sideA.name',
            'a_logo' => 'sideA.logoImage',
            'b_id',
            'b_name' => 'sideB.name',
            'b_logo' => 'sideB.logoImage',
            'player_a_ids',
            'player_b_ids',
            'winner_id',
            'looser_id',
            'is_tied',
            'match_details',
            'hasSetupSquad',
            'match_report',
            'player_of_the_match',
            'scoring_status',
            'score_added_by',
            'isactive',
            'has_result',
            'match_result',
            'game_type',
            'number_of_rubber',
            'a_score',
            'b_score',
            'is_third_position',
            'selected_half_or_quarter'
        ];

        return $this->PaginatedMapResponse($schedules, $map);
    }

    /**
     * Match - Info
     * @param $id
     * @return array
     */

    public function getInfo($id)
    {
        $schedule = MatchSchedule::whereId($id)->firstOrFail();

        function getSideField($side)
        {
            return [
                'type' => 'model',
                'source' => $side,
                'fields' => function ($obj, $base) use ($side) {
                    $fields = ['id', 'name', 'logoImage'];

                    if ($base->schedule_type == 'team') {
                        $fields['players'] = [
                            'type' => 'value',
                            'value' => function ($obj, $base) use ($side) {
                                $result = [];
                                $ids = ($side == 'sideA') ? explode(',', trim($base->player_a_ids, ',')) : explode(',', trim($base->player_b_ids, ','));
                                $team_id = ($side == 'sideA') ? $base->a_id : $base->b_id;
                                $users = User::whereIn('id', $ids)->with(['userdetails' => function ($query) use ($team_id) {
                                    return $query->where(['team_id' => $team_id]);
                                }])
                                    ->get()->keyBy('id');
                                $ids = array_unique($ids);
                                foreach ($ids as $id) {
                                    if (isset($users[$id]))
                                        $result[] = [
                                            'id' => $id,
                                            'name' => $users[$id]['name'],
                                            'logoImage' => $users[$id]->logoImage,
                                            'role' => object_get($users[$id]->userdetails->first(), 'role')
                                        ];
                                }
                                return $result;
                            }
                        ];
                    }
                    return $fields;
                }
            ];
        }


        $map = [
            'tournament_id',
            'match_id' => 'id',
            'schedule_type',
            'tournament_group_id',
            'tournament_round_number',
            'tournament_match_number',
            'sports_id',
            'facility_id',
            'facility_name',
            'created_by',
            'match_category',
            'match_type',
            'match_start_date',
            'match_start_time',
            'match_end_date',
            'match_end_time',
            'match_location',
            'longitude',
            'latitude',
            'address',
            'city_id',
            'city',
            'state_id',
            'state',
            'country_id',
            'country',
            'zip',
            'match_status',
            'match_invite_status',
            'winner_id',
            'looser_id',
            'is_tied',
            'match_details',
            'hasSetupSquad',
            'match_report',
            'player_of_the_match',
            'scoring_status',
            'score_added_by',
            'isactive',
            'has_result',
            'match_result',
            'game_type',
            'number_of_rubber',
            'a_score',
            'b_score',
            'is_third_position',
            'selected_half_or_quarter',

            'Sides' => [
                'type' => 'model',
                'fields' => [
                    getSideField('sideA'),
                    getSideField('sideB')
                ],
            ]
        ];


        return $this->ModelMapResponse($schedule, $map);
    }

    public function getScores($id)
    {
        $schedule = MatchSchedule::whereId($id)->firstOrFail();

        $user_ids = array_unique(array_merge(explode(',', $schedule->player_a_ids), explode(',', $schedule->player_b_ids)));


        $sports_name = strtolower($schedule->sport->sports_name);
        $stats = [];
        $statistics = [];
        if ($sports_name) {
            $tableName = $sports_name . '_player_matchwise_stats';
            if (\Schema::hasTable($tableName))
                $stats = \DB::table($tableName)
                    ->whereMatchId($schedule->id)
                    ->whereNull('deleted_at')
                    ->get();

        }


        $map = [
            'Sport' => [
                'type' => 'model',
                'source' => 'sport',
                'fields' => [
                    'id',
                    'sports_name'
                ]
            ],
            "schedule_type",
            "match_category",
            "facility_name",
            "address",
            'match_type',
            'match_status',
            "match_start_date",
            "match_start_time",
            "match_end_date",
            "match_end_time",
            'winner',
            'match_details'=>[
                'type'=> 'value',
                'value'=> json_decode($schedule->match_details)
            ],
            'Sides' => [
                'type' => 'array',
                'fields' => [
                    'sideA.name',
                    'sideB.name',
                ]
            ],

            'player_of_the_match' => [
                'type' => 'value',
                'value' => function ($schedule) {
                    $id = $schedule->player_of_the_match;;
                    $player = User::whereId($id)->first();
                    if ($player)
                        return array_only($player->toArray(), ['id', 'name']);
                }
            ],
            'stats' => [
                'type' => 'value',
                'value' => $stats
            ]
        ];


        return $this->ModelMapResponse($schedule, $map);
    }

}