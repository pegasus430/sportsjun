<?php
use \App\Model\Sport;

?>
<div class="groupstageWrapp">
    <h1>GROUP STAGE</h1>
    <h2>GROUP POINT TABLE</h2>
    @foreach($tournament->groups as $group)
        <div class="col-lg-6 col-md-6">
            <div class="groupbox_AB">
                <h3>{{ $group->name }}</h3>
                <table width="100%" border="1">
                    <tbody>
                    <tr class="headingMAT">
                        <td>POS</td>
                        <td>TEAM</td>
                        <td>M</td>
                        <td>W</td>
                        <td>L</td>
                        <td>D</td>
                        @if(in_array($tournament['sports_id'], [Sport::$SOCCER,Sport::$HOKKEY]))
                            <td>GF</td>
                            <td>GA</td>
                        @endif

                        @if(in_array($tournament['sports_id'],
                            [Sport::$BASKETBALL,Sport::$KABADDI,Sport::$ULTIMATE_FRISBEE,Sport::$WATER_POLO]))
                            <td>PS</td>
                            <td>PA</td>
                        @endif
                        @if(in_array($tournament['sports_id'],
                        [Sport::$TABLE_TENNIS,Sport::$BADMINTON,Sport::$SQUASH, Sport::$THROW_BALL, Sport::$VOLEYBALL]))
                            <td>SW</td>
                            <td> SL</td>
                        @endif
                        <td>PTS</td>
                        @if ( $tournament['sports_id'] == Sport::$CRICKET )
                            <th class="text-center">Net Run Rate</th>
                        @endif
                    </tr>
                    @if ($group->group_teams)
                        <?php $index = 1; ?>
                        @foreach ($group->group_teams->sortByDesc('points') as $team)
                            <tr class="playList">
                                <td>{{$index}}</td>
                                <td>{{$team->name}}</td>
                                <td>{{$team->matches}}</td>
                                <td>{{$team->won}}</td>
                                <td>{{$team->lost}}</td>
                                <td>{{$team->tie}}</td>
                                @if(in_array($tournament['sports_id'], [Sport::$SOCCER,Sport::$HOKKEY,
                                  Sport::$TABLE_TENNIS,Sport::$BADMINTON,Sport::$SQUASH, Sport::$THROW_BALL, Sport::$VOLEYBALL,
                                  Sport::$BASKETBALL,Sport::$KABADDI,Sport::$ULTIMATE_FRISBEE,Sport::$WATER_POLO
                                ]))
                                    <td>{{$team->gf}}</td>
                                    <td>{{$team->ga}}</td>
                                @endif

                                <td>{{ $team->points }}</td>
                                @if ( $tournament['sports_id'] == Sport::$CRICKET )
                                    <td>{{ $team->nrr }}</td>
                                @endif
                            </tr>
                            <?php $index++;?>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>