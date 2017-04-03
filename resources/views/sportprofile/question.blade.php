@if($sportsCount>7)
    {{'countexceed'}}
@else
@if(isset($viewFlag) && $viewFlag==1)
<div class="remove_follow_sport" style="padding:5px 0 20px 0;"><span onclick="removefollowedsport({{$sportsId}})" class="sport_remove_new"><i class="fa fa-close"></i>Remove Sport</span></div>
@endif
<div class="players_row clearfix" style="padding-top: 8px; padding-bottom: 8px; top:10px;">
    @if($sportDetails->sports_type=='both' || $sportDetails->sports_type=='team')
        <div class="col-md-6">
            <label class="pull-left">{{trans('message.sports.availableforteam')}} <input class="switch-class" type="checkbox" name="chk_available" id="chk_available" sportid="{{ !empty($sportsId)?$sportsId:0 }}" userid="{{ Auth::user()->id }}" {{ in_array($sportsId, $existingAllowedSportsArray)?'checked': '' }}></label>
        </div>
    @endif
    @if($sportDetails->sports_type=='both' || $sportDetails->sports_type=='player')
        <div class="col-md-6">
            <label class="pull-right">{{trans('message.sports.allowfollowerstofollow')}} <input class="switch-class" type="checkbox"  name="chk_follow"  id="chk_follow" sportid="{{ !empty($sportsId)?$sportsId:0 }}" userid="{{ Auth::user()->id }}" {{ in_array($sportsId, $existingAllowedMatchesArray)?'checked': '' }}> </label>	
        </div>
    @endif
</div>
<br />
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

                    @foreach($question['options'] as $option)
                        @if($question['sports_element'] == 'radio_button')

                                <label for="{{ 'question_'.$option['sports_questions_id'].'_'.$i }}" class="option">
                                                {!! Form::radio('question_'.$option['sports_questions_id'], $question['id'].'_'.$option['id'], $option['answer'],array('class'=>'form-control','id'=>'question_'.$option['sports_questions_id'].'_'.$i)) !!}
                                                <span class="radio"></span> {{$option['options']}}
                                  </label>

                        @else
                             <label class="option">
                                 {!! Form::checkbox('question_'.$option['sports_questions_id'].'_'.$option['id'], $question['id'].'_'.$option['id'],$option['answer'], array('class'=>'form-control','id'=>'question_'.$option['sports_questions_id'].'_'.$i)) !!}
                                <span class="checkbox"></span> {{$option['options']}}
                            </label>

                        @endif
                        @if ($errors->has('gender'))
                                <p class="help-block">{{ $errors->first('gender') }}</p>
                        @endif
                    <?php $i++; ?>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="clearfix"></div>
        <br />
        <div class="col-md-9 col-md-offset-3">
            <input type="hidden" name="sports_id" id="sports_id" value="{{$sportsId}}">
            <button type="submit" class="button btn-primary">Update</button>
        </div>
        <div class="clearfix"></div>
<!-- end .form-footer section -->
    @else
        @if(count($exception))
            {{ $exception }}
        @elseif(strtolower($sportDetails->sports_name) == 'smite')

            <label class="col-md-3 col-sm-12 col-xs-12 label_new_head">Smite in-game username:</label>
            <input class="smite-nickname gui-input smite-input" name="username" style="width:50%;height:31px;"placeholder="Your Smite Username" id="smite-username" @if(!empty($gameUsername->username)) value="{{ $gameUsername->username }}" @endif/>
            <button type="button" name="save_smite_nickname" id="save_smite_nickname" class="button btn-primary">Update</button>

            <div class="clearfix"></div>
            <div class="col-md-3 col-sm-12 col-xs-12 smite-register"><a href="https://www.hirezstudios.com/my-account/">Dont have an account? Register HERE now!</a></div>
            <div class="sj-alert sj-alert-info smite-alert" style="display:none;">

            </div>

            <br />
        @else
            <div class="sj-alert sj-alert-info sj-alert-sm">{{ trans('message.sports.noquestion')}}</div>
        @endif
    @endif
</div>

<!--<span id="overall_stats" onclick="togglePlayerStatistic(2)"><b>OVERALL STATS</b></span>-->
<div id="overall_stats_div_{{$sportsId}}" class="tab-pane fade">
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
  

<script type="text/javascript">
    //bootstrap code
    $("#chk_available,#chk_follow").bootstrapSwitch();
    //for avalability on/off
    $("#chk_available").on('switchChange.bootstrapSwitch', function(event, state) {
        updateUserStats(state,$(this).attr('sportid'),$(this).attr('userid'),'available');
    });
    //for follow on/off
    $("#chk_follow").on('switchChange.bootstrapSwitch', function(event, state) {
        //if it is follow then display the sport questions
        // if(state == true)
        // {
            // displaySportQuestions('follow', $(this).attr('sportid'), $(this).attr('userid'));
        // }
        // else //if it is unfollow then delete the sport questions
        // {
            updateUserStats(state,$(this).attr('sportid'),$(this).attr('userid'),'availableteams');
        // }
        
    });

    $('#save_smite_nickname').on('click', function()
    {
        var username = $('#smite-username').val();

        $.ajax({
            url:base_url+'/smite/save_nickname',
            type:'post',
            data: {
                _token: CSRF_TOKEN,
                username: username
            },
            success: function(response){

                if(response.status != "true")
                {
                    $('.smite-alert').show();
                }
                else
                {
                    $('.smite-alert').hide();
                }
            },
            error: function(data) {
                var errors = data.responseJSON;

                $('.smite-alert').text(errors.username[0]);
                $('.smite-alert').show();
            }
        });
    });

    function updateUserStats(state,sportId,userId,dbFlag)
    {
        $.post(base_url+'/sport/updateUserStats',{'flag':state,'sportId':sportId,'userId':userId,'dbFlag':dbFlag},function(response,status){
            if(status != 'success')
            {
                return false;
            }
            else if(status == 'success' && dbFlag=='follow')
            {
                //location.reload(true);
				if(dbFlag=='follow') {
					location.reload(true);
				}
            }
        });

    }
    
    function removeUserStats(state,sportId,userId,dbFlag)
    {
        $.confirm({
			title: 'Confirm',
			content: "Are you sure you want to remove the sport?",
			confirm: function() {
                            updateUserStats(state,sportId,userId,dbFlag);
			},
			cancel: function() {
			    // nothing to do
			}
		}); 
    }
    
    function removefollowedsport(sportId)
    {
        $.confirm({
			title: 'Confirm',
			content: "Are you sure you want to remove the sport?",
			confirm: function() {
                            $.ajax({
                            url: base_url + "/removefollowedsport/"+sportId,
                            type: "GET",
//                            data: {token: CSRF_TOKEN},
                            dataType: "json",
                            success: function(response) {
                                if(response['result']=='success') {
                                    $("#question_div_" + sportId).html('');
                                    $("#sport_name_" + sportId).removeClass("active");
                                    $("#sport_name_" + sportId).attr("onclick", "displaySportQuestions('follow', "+ sportId + "," + response['userId'] +",'')");
                                    if ( response['userSports'] != 0 ) {
                                        
                                        displaySportQuestions('unfollow', response['userSports'][0]['id'], response['userId'],"\'response['userSports'][0]['sports_name']\'");
                                    }
                                }                 
                            }
                        });
			},
			cancel: function() {
			    // nothing to do
			}
		}); 
    }

    $(window).unload(function()
    {
        var username = $('#smite-username').val();
        // If user updated his username to blank /removed username
        if(username == '')
        {
            var sportId = $(".sport-tabs[data-name='smite']").data('id');
            var userId = $(".sport-tabs[data-name='smite']").data('uid');

            updateUserStats(false,sportId,userId,'follow');
        }
    })
</script>
@endif
