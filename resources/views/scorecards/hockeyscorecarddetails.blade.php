

    <?php    
    $match_details=json_decode($match_details);
    $first_half=isset($match_details->first_half)?$match_details->first_half:[];
    $second_half=isset($match_details->second_half)?$match_details->second_half:[];
    $penalties=isset($match_details->penalties)?json_decode(json_encode($match_details->penalties), true):[];
    $ball_percentage_a=isset($match_details->{$team_a_id}->ball_percentage)?$match_details->{$team_a_id}->ball_percentage:50;
    $ball_percentage_b=isset($match_details->{$team_b_id}->ball_percentage)?$match_details->{$team_b_id}->ball_percentage:50;
    ?>

    <style type="text/css">
        .alert{display: none;}
        .show_teams{display: none;}
        .player_selected{
            background: #111111;
            background-color: red;
        }
        .btn-yellow-card{
            background: #f4cd73;
            border: none;
        }
        .btn-red-card{
            background: #f42d23;
        }
        .btn-green-card{
            background: #84cd93;
        }
        .btn-card{
            border: none;
        }
        .lose_goal{
            opacity: .2;
        }
        .fa-share{
            color: red;
        }
        .fa-reply{
            color: green;
        }

        .btn-penalty{
            opacity: .2;
        }
        .btn-green-card{
            background: #1B926C;
        }
        .btn-penalty-chosen{
            opacity: 1;
        }



    </style>
    

                <div class="col-sm-10 col-sm-offset-1">
                    <h3 id='team_b' class="team_bowl table_head">MATCH STATITICS</h3>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr class='team_bow'>
                                <th colspan="5"></th>
                            </tr>
                            </thead>
                            
                                <tbody>

                            @if(count($first_half)>0)
                                <tr>
                                    <td colspan="2">{{$match_details->first_half->{"team_{$team_a_id}_goals"} }}</td>
                                    <td class="td_type">Half Time ( <img src='/images/scorecard/hockey.png' height='20px' width='20px' style='font-size:32px'> ) </td>
                                    <td colspan="2">{{$match_details->first_half->{"team_{$team_b_id}_goals"} }}</td>
                                <tr>
                            @endif
                            @if(isset($match_details->{$team_a_id}))
                                <tr>
                                    <td colspan="2">{{$match_details->{$team_a_id}->goals }}</td>
                                    <td class="td_type">Full Time ( <img src='/images/scorecard/hockey.png' height='20px' width='20px' style='font-size:32px'> ) </td>
                                    <td colspan="2">{{$match_details->{$team_b_id}->goals }}</td>
                                <tr>
                                <tr>
                                    <td colspan="2">{{$match_details->{$team_a_id}->red_card_count }}</td>
                                    <td class='td_type'>Red Cards <button class='btn-red-card btn-card' disabled=''>&nbsp;</button>  </td>
                                    <td colspan="2">{{$match_details->{$team_b_id}->red_card_count }}</td>
                                <tr>
                                <tr>
                                    <td colspan="2">{{$match_details->{$team_a_id}->yellow_card_count }}</td>
                                    <td class='td_type'>Yellow Cards <button class='btn-yellow-card btn-card' disabled=''>&nbsp;</button>  </td>
                                    <td colspan="2">{{$match_details->{$team_b_id}->yellow_card_count }}</td>
                                <tr>
                                <tr>
                                    <td colspan="2">{{$ball_percentage_a }} %</td>
                                    <td class='td_type'>Ball Percentage  % </td>
                                    <td colspan="2">{{$ball_percentage_b }} %</td>
                                <tr>
                            @endif
                                </tbody>                           
                        </table>
                    </div>

                </div>
            

            <div class="row">

                <div class="col-sm-10 col-sm-offset-1">
                    <h3 id='' class="team_fall team_title_head ">FULL MATCH DETAILS</h3>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="thead">
                            <tr>
                                <th class='team_bow' colspan="9" ><center>First Half</center></th>
                            </tr>
                            </thead>
                            <tbody id="displayGoalsFirstHalf" >
                                @if(count($first_half) < 1 )
                                    <tr><td colspan="9">No Records</td></tr>
                                @else
                                    <!-- Goals Display -->
                                    @foreach($first_half->goals_details as $fh)

                                        <tr>
                                            @if(isset($fh->team_type) && $fh->team_type=='team_a')
                                                <td colspan="2">{{$fh->player_name}}</td><td>{{$fh->time}}"</td><td><img src='/images/scorecard/hockey.png' height='20px' width='20px' style='font-size:32px'></td><td>{{$fh->current_score}}</td><td colspan="4">&nbsp;</td>
                                            @else
                                                <td colspan="4">&nbsp;</td><td>{{$fh->current_score}}</td><td><img src='/images/scorecard/hockey.png' height='20px' width='20px' style='font-size:32px'></td><td>{{$fh->time}}"</td><td colspan="2">{{$fh->player_name}}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    <!-- Yellow Cards -->
                                    @foreach($first_half->yellow_card_details as $fh)

                                        <tr>
                                            @if(isset($fh->team_type) && $fh->team_type=='team_a')
                                                <td colspan="2">{{$fh->player_name}}</td><td>{{$fh->time}}"</td><td><button class='btn-yellow-card btn-card' disabled="">&nbsp;</button></td><td></td><td colspan="4">&nbsp;</td>
                                            @else
                                                <td colspan="4">&nbsp;</td><td></td><td><button class='btn-yellow-card btn-card' disabled="">&nbsp;</button><td>{{$fh->time}}"</td><td colspan="2">{{$fh->player_name}}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    <!-- Red Cards -->
                                    @foreach($first_half->red_card_details as $fh)

                                        <tr>
                                            @if(isset($fh->team_type) && $fh->team_type=='team_a')
                                                <td colspan="2">{{$fh->player_name}}</td><td>{{$fh->time}}"</td><td><button class='btn-red-card btn-card ' disabled="">&nbsp;</button></td><td></td><td colspan="4">&nbsp;</td>
                                            @else
                                                <td colspan="4">&nbsp;</td><td></td><td><button class='btn-red-card btn-card ' disabled="">&nbsp;</button><td>{{$fh->time}}"</td><td colspan="2">{{$fh->player_name}}</td>
                                            @endif
                                        </tr>
                                    @endforeach

                                @endif

                            </tbody>

                            <thead class="thead">
                            <tr>
                                <th class='' colspan="9" ><center>Second Half</center></th>
                            </tr>
                            </thead>
                            <tbody id="displayGoalsSecondHalf" >
                            @if(count($second_half) < 1 )
                                <tr><td colspan="9">No Records</td></tr>
                            @else
                                <!-- Goals Display -->
                                @foreach($second_half->goals_details as $fh)

                                    <tr>
                                        @if(isset($fh->team_type) && $fh->team_type=='team_a')
                                            <td colspan="2">{{$fh->player_name}}</td><td>{{$fh->time}}"</td><td><img src='/images/scorecard/hockey.png' height='20px' width='20px' style='font-size:32px'></td><td>{{$fh->current_score}}</td><td colspan="4">&nbsp;</td>
                                        @else
                                            <td colspan="4">&nbsp;</td><td>{{$fh->current_score}}</td><td><img src='/images/scorecard/hockey.png' height='20px' width='20px' style='font-size:32px'></td><td>{{$fh->time}}"</td><td colspan="2">{{$fh->player_name}}</td>
                                        @endif
                                    </tr>
                                @endforeach
                                <!-- Yellow Cards -->
                                @foreach($second_half->yellow_card_details as $fh)

                                    <tr>
                                        @if(isset($fh->team_type) && $fh->team_type=='team_a')
                                            <td colspan="2">{{$fh->player_name}}</td><td>{{$fh->time}}"</td><td><button class='btn-yellow-card btn-card' disabled="">&nbsp;</button></td><td>&nbsp;</td><td colspan="4">&nbsp;</td>
                                        @else
                                            <td colspan="4">&nbsp;</td><td>&nbsp;</td><td><button class='btn-yellow-card btn-card' disabled="">&nbsp;</button><td>{{$fh->time}}"</td><td colspan="2">{{$fh->player_name}}</td>
                                        @endif
                                    </tr>
                                @endforeach
                                <!-- Red Cards -->
                                @foreach($second_half->red_card_details as $fh)

                                    <tr>
                                        @if(isset($fh->team_type) && $fh->team_type=='team_a')
                                            <td colspan="2">{{$fh->player_name}}</td><td>{{$fh->time}}"</td><td><button class='btn-red-card btn-card ' disabled="">&nbsp;</button></td><td></td><td colspan="4">&nbsp;</td>
                                        @else
                                            <td colspan="4">&nbsp;</td><td>&nbsp;</td><td><button class='btn-red-card btn-card ' disabled="">&nbsp;</button><td>{{$fh->time}}"</td><td colspan="2">{{$fh->player_name}}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                           
                            </tbody>
                        </table>
                    </div>

                </div>

  