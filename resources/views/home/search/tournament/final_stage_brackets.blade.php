<h2>Tournament Bracket</h2>
<?php
$final_matches = $tournament->finalMatches;
if (!$final_matches)
    return;
$final_matches_grouped = $final_matches->groupBy('tournament_round_number');
?>

@if(count($tournament->final_stage_teams_list))
    <div class="clearfix group-flex-content flowchart-object flowchart-action " id='canvas'>
        @for($round = 1; $round<=$maxRoundNumber; $round++)
            <div class="col-sm-2">
                <div class="round">
                    <p>{{$tournament->getRoundStageString($round)}}</p>
                </div>
                <?php $round_matches = array_get($final_matches_grouped, $round);?>
                @if ($round_matches)
                    @foreach ($round_matches as $matchSchedule)
                        <div class="match_set" style="height: {{150 * $round}}px; padding-top:{{ 90*($round-1)}}px">
                            <ul class="window jtk-node">
                                <div class="clearfix">
                              {{--      <span class="tour_match_date fa fa-info" data-toggle="tooltip" data-placement="left"
                                          title="{{$matchSchedule['match_start_date'].$tournament->sports->sports_name.' '.$matchSchedule['match_type']}}"></span>
                                    <span class="tour_score hidden"><a href="#">Match Stats</a></span>
                               --}}
                                </div>
                                <div id="tour_{{$round}}_match_{{$matchSchedule['tournament_match_number']}}">
                                    <li title="{{object_get($matchSchedule->sideA,'name') or 'Bye'}}" data-toggle="tooltip"
                                        data-placement="top">
                                        <img src="{{$matchSchedule->sideALogo or ''}}" class="img-circle img-border"
                                             width="30" height="30px"/>
                                        <span>{{Helper::get_first_20_letters(object_get($matchSchedule->sideA,'name',trans('message.bye')))}}</span>
                                    </li>
                                    <li title="{{object_get($matchSchedule->sideB,'name') or 'Bye'}}" data-toggle="tooltip"
                                        data-placement="top">
                                        <img src="{{$matchSchedule->sideBLogo or ''}}" class="img-circle img-border"
                                             width="30" height="30px"/>
                                        <span>{{Helper::get_first_20_letters(object_get($matchSchedule->sideB,'name',trans('message.bye')))}}</span>
                                    </li>
                                </div>
                            </ul>
                        </div>
                    @endforeach
                @endif
            </div>
        @endfor
    </div>
@else
    <div class="sj-alert sj-alert-info">
        {{ trans('message.tournament.final.nofinalstageteams') }}
    </div>
@endif


<script type="text/javascript">
    window.matches = {{$tournament->final_stage_teams}};
</script>
<script type="text/javascript" src="/js/jsplumb/jsPlumb-2.1.5-min.js"></script>
<script type="text/javascript" src="/js/jsplumb/drawlines.js"></script>
<script type="text/javascript" src="/js/jsplumb/jsPlumb-2.1.5-min.js"></script>
<script type="text/javascript" src="/js/jspdf.js"></script>
