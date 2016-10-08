<div class="players_row clearfix" style="padding-top: 8px; padding-bottom: 8px; margin-top:8px;margin-bottom:8px;">
    @if($sportDetails->sports_type=='both' || $sportDetails->sports_type=='team')
        <div class="col-md-6">
            <span class="switch-head">{{trans('message.sports.availableforteam_view')}}</span>
            @if((isset(Auth::user()->id)?Auth::user()->id:0) == $userId)
                <input class="switch-class" type="checkbox" name="chk_available" id="chk_available" sportid="{{ !empty($sportsId)?$sportsId:0 }}" userid="{{ (isset(Auth::user()->id)?Auth::user()->id:0) }}" {{ in_array($sportsId, $existingAllowedSportsArray)?'checked': '' }}>
            @else
                <span class="switch_but_show">{{ in_array($sportsId, $existingAllowedSportsArray)?'YES': 'NO' }}</span>
            @endif
        </div>
    @endif
    @if($sportDetails->sports_type=='both' || $sportDetails->sports_type=='player')
        <div class="col-md-6">
            <span class="switch-head">{{trans('message.sports.allowfollowerstofollow_view')}}</span>
            @if((isset(Auth::user()->id)?Auth::user()->id:0) == $userId)
                <input class="switch-class" type="checkbox"  name="chk_follow"  id="chk_follow" sportid="{{ !empty($sportsId)?$sportsId:0 }}" userid="{{ (isset(Auth::user()->id)?Auth::user()->id:0) }}" {{ in_array($sportsId, $existingAllowedMatchesArray)?'checked': '' }}>
            @else
                <span class="switch_but_show">{{ in_array($sportsId, $existingAllowedMatchesArray)?'YES': 'NO' }}</span>
            @endif
        </div>
    @endif
</div>
<div class="panel panel-default">
    <div class="panel-body">
        <ul class="nav nav-tabs nav-justified">
            <li class="active"><a href="#player_info_div_{{$sportsId}}" data-toggle="tab" aria-expanded="true">{{trans('message.sports.sportskill')}}</a></li>
            <li class=""><a href="#overall_stats_div_{{$sportsId}}" data-toggle="tab" aria-expanded="false">{{trans('message.sports.playersportstats')}}</a></li>
        </ul>
        <!--<span id="player_info" onclick="togglePlayerStatistic(1)"><b>PLAYER INFO</b></span>-->
        <div class="tab-content" style="border-left: 0; border-right: 0; border-bottom: 0; margin-top: 10px;">

            <div id="player_info_div_{{$sportsId}}" class="tab-pane fade active in">
                @if (count($sportsQuestions))
                    @foreach($sportsQuestions as $question)
                        <div class="section">
                            <label class="col-md-3 col-sm-12 col-xs-12 label_new_head">{{$question['sports_question']}}</label>
                            <div class="col-md-9 col-sm-12 col-xs-12">
                                <?php $i = 0; ?>
                                <?php $label_class = ''; ?>
                                @foreach($question['options'] as $option)
                                    {{$option['answer']}}
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                <!-- end .form-footer section -->
                @else
                    @if(count($exception))
                        <div class="form-group">{{ $exception }}</div>
                    @else
                        <div class="form-group"><div class="sj-alert sj-alert-info sj-alert-sm">{{ trans('message.sports.noquestion')}}</div></div>
                    @endif
                @endif
            </div>
            <!--<span id="overall_stats" onclick="togglePlayerStatistic(2)"><b>OVERALL STATS</b></span>-->
            <div id="overall_stats_div_{{$sportsId}}" class="tab-pane fade" >
                <div class="col-md-12 nopadding">
                    
                    @if(View::exists($statsview))
                        @include($statsview)
                    @endif
                    @include('sportprofile.statslist')
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
    </div>
</div>
