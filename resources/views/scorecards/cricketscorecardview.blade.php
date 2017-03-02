@extends(Auth::user() ? 'layouts.app' : 'home.layout')
@section('content')
<div class="col_standard cricket_scorcard">

    <div id="team_vs" class="cs_bg">
    	<div class="container">
        	<div class="row">
                <div class="team team_one col-xs-5">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                        	<div class="team_logo">
                        	@if(!empty($team_a_logo))
								@if($team_a_logo['url']!='')
								<!--<img  class="img-responsive img-circle" alt="" width="110" height="110" src="{{ url('/uploads/teams/'.$team_a_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
							{!! Helper::Images($team_a_logo['url'],'teams',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
								@else
								<!--<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
								{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
								</td>
								@endif
								@else
								<!--<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/no_logo.png') }}">	-->
							{!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
								@endif
                                </div>
                        </div>
                       <div class="col-md-8 col-sm-12">
                        	<div class="team_detail">
                               <div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['a_id'] }}">{{ $team_a_name }}</a></div>
								<div class="team_city">{{$team_a_city}}</div>
                                <div class="team_score"> @if($match_data[0]['match_type']=='test') {{'I st'}} @endif {{($team_a_fst_ing_score>0)?$team_a_fst_ing_score:0}}/{{($team_a_fst_ing_wkt>0)?$team_a_fst_ing_wkt:0}} <span>({{(($team_a_fst_ing_overs>0)?$team_a_fst_ing_overs:0).' ovs'}})</span>

                                @if($match_data[0]['match_type']=='test')
                                II nd {{($team_a_scnd_ing_score>0)?$team_a_scnd_ing_score:0}}/{{($team_a_scnd_ing_wkt>0)?$team_a_scnd_ing_wkt:0}} <span>({{(($team_a_scnd_ing_overs>0)?$team_a_scnd_ing_overs:0).' ovs'}})</span>
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-2">
                    <span class="vs"></span>
                </div>
                <div class="team team_two col-xs-5">
					<div class="row">
                        <div class="col-md-4 col-sm-12">
                        <div class="team_logo">
                        	@if(!empty($team_b_logo))
								@if($team_b_logo['url']!='')
								<!--<img  class="img-responsive img-circle" alt="" width="110" height="110" src="{{ url('/uploads/teams/'.$team_b_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
								{!! Helper::Images($team_b_logo['url'],'teams',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
								@else
								<!--<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
								{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
								</td>
								@endif
								@else
								<!--<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/no_logo.png') }}">	-->
							{!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
								@endif
                               </div>
                        </div>
                        <div class="col-md-8 col-sm-12">
                        	<div class="team_detail">
                                <div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['b_id'] }}">{{ $team_b_name }}</a></div>
								<div class="team_city">{{$team_b_city}}</div>
                                <div class="team_score"> @if($match_data[0]['match_type']=='test') {{'I st'}} @endif {{($team_b_fst_ing_score>0)?$team_b_fst_ing_score:0}}/{{($team_b_fst_ing_wkt>0)?$team_b_fst_ing_wkt:0}} <span>({{(($team_b_fst_ing_overs>0)?$team_b_fst_ing_overs:0).' ovs'}})</span>

                                @if($match_data[0]['match_type']=='test')
                                II nd {{($team_b_scnd_ing_score>0)?$team_b_scnd_ing_score:0}}/{{($team_b_scnd_ing_wkt>0)?$team_b_scnd_ing_wkt:0}} <span>({{(($team_b_scnd_ing_overs>0)?$team_b_scnd_ing_overs:0).' ovs'}})</span>
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(!is_null($match_data[0]['tournament_id']))
                <div class='row'>
                    <div class='col-xs-12'>
                        <center>
                          <a href="/tournaments/groups/{{$tournamentDetails['id']}}">
                                    <h4 class="team_name">    {{$tournamentDetails['name']}} Tournament </h4>
                                  </a>

                       </center>
                    </div>
                </div>
            @endif

            <div class="row">
            	<div class="col-xs-12">
                	<div class="match_loc">
                    	{{ date('jS F , Y',strtotime($match_data[0]['match_start_date'])).' - '.date("g:i a", strtotime($match_data[0]['match_start_time'])).(($match_data[0]['facility_name']!='')?' , '.$match_data[0]['facility_name']:'').(($match_data[0]['address']!='')?' , '.$match_data[0]['address']:'') }}
                    </div>
                </div>
            </div>
            <div class="row">
              <div class="col-xs-12">

                	<h5 class="scoreboard_title">Cricket Scorecard @if($match_data[0]['match_type']!='other')
    											<span class='match_type_text'>({{ $match_data[0]['match_type']=='odi'?strtoupper($match_data[0]['match_type']):ucfirst($match_data[0]['match_type']) }}, {{ucfirst($match_data[0]['match_category']) }})</span>
    									@endif</h5>

                    <div class="form-inline">
    <?php if (!empty($score_status_array['toss_won_by']))
                                        { ?>
                                                    <div id="matchTossNote">
                                                            <?php if ($match_data[0]['a_id'] == $score_status_array['toss_won_by'])
                                                            { ?>
                                                                    {{ ucwords($team_a_name) }}
                                                            <?php }
                                                            else
                                                            { ?>
                                                                    {{ ucwords($team_b_name) }}
                                                            <?php } ?>
                                                            won the toss
                                                            <?php if (!empty($score_status_array['fst_ing_batting']))
                                                            { ?>
                                                                    and chose to
                                                                    <?php if ($score_status_array['toss_won_by'] == $score_status_array['fst_ing_batting'])
                                                                    { ?>
                                                                            Bat.
                    <?php }
                    else
                    { ?>
                                                                            Bowl.
                    <?php } ?>
            <?php } ?>
                                                    </div>
    <?php } ?>
                             @if($match_data[0]['winner_id']>0)

    							  <div class="form-group">
    								<label class="win_head" style="position: absolute;left: 35%;top: 30px;color: #f27676;">Winner</label>
                                    <h3 class="win_team">{{ ($match_data[0]['a_id']==$match_data[0]['winner_id'])?$team_a_name:$team_b_name }}</h3>

    							  </div>

    					@else
                                                    @if($match_data[0]['is_tied']>0)
                                                        <div class="form-group">
                                                            <label>Match Result</label>
                                                            <h3 class="win_team">Tie</h3>
                                                        </div>
                                                    @elseif($match_data[0]['match_result'] == "washout")
                                                        <div class="form-group">
                                                            <label>MATCH ENDED DUE TO</label>
                                                            <h3 class="win_team">Washout</h3>
                                                        </div>
                                                    @else
                                                        <div class="form-group">
                                                            <label>Winner is not updated.</label>
                                                        </div>
                                                    @endif
    					@endif

                            @if($match_data[0]['player_of_the_match']>0)
                            <div id="playerOfTheMatchNote">PLAYER OF THE MATCH - {{(!empty($player_name_array[$match_data[0]['player_of_the_match']]))?$player_name_array[$match_data[0]['player_of_the_match']]:''}}</div>
                            @endif

                            <div class="form-group" id="bat1stInning" style="display:none;">
                                    <label for="team">Ist Ing Batting:</label>
                                    <select class="form-control selectpicker selectpicker_new_span" name="team" id="team" onchange="getTeamName();">
                                            <option value="{{ $match_data[0]['player_a_ids'] }}" <?php if (!empty($score_status_array['fst_ing_batting']) && $match_data[0]['a_id'] == $score_status_array['fst_ing_batting']) echo 'selected'; ?> data-status="{{ $match_data[0]['a_id'] }}" >{{ $team_a_name }}</option>
                                            <option value="{{ $match_data[0]['player_b_ids'] }}" <?php if (!empty($score_status_array['fst_ing_batting']) && $match_data[0]['b_id'] == $score_status_array['fst_ing_batting']) echo 'selected'; ?> data-status="{{ $match_data[0]['b_id'] }}">{{ $team_b_name }}</option>
                                    </select>
                            </div>
                            @if($match_data[0]['match_type']=='test')
                            <div class="form-group" id="bat2ndInning" style="display:none;">
                                    <label for="team">II Ing Batting:</label>
                                    <select class="form-control selectpicker selectpicker_new_span" name="team" id="teams" onchange="getTeamNames();">
                                            <option value="{{ $match_data[0]['player_a_ids'] }}" <?php if (!empty($score_status_array['scnd_ing_batting']) && $match_data[0]['a_id'] == $score_status_array['scnd_ing_batting']) echo 'selected'; ?> data-status="{{ $match_data[0]['a_id'] }}" >{{ $team_a_name }}</option>
                                            <option value="{{ $match_data[0]['player_b_ids'] }}" <?php if (!empty($score_status_array['scnd_ing_batting']) && $match_data[0]['b_id'] == $score_status_array['scnd_ing_batting']) echo 'selected'; ?> data-status="{{ $match_data[0]['b_id'] }}" >{{ $team_b_name }}</option>
                                    </select>
                            </div>
                            @endif

                            <p class="match-status mg"><a href="{{ url('user/album/show').'/match'.'/0'.'/'.$action_id }}"><span class="fa" style="float: left; margin-left: 8px;"><img src="{{ asset('/images/sc-gallery.png') }}" height="18" width="22"></span> <b>Media Gallery</b></a></p>
                            @include('scorecards.share')
                            <p class="match-status">@include('scorecards.scorecardstatus')</p>
                </div>
              </div>
            </div>
        </div>
    </div>



        <div class="container">
	<div class="panel panel-default">
            <div class="panel-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified">
                    <li class="active"><a href="#first_innings" data-toggle="tab" aria-expanded="true">Ist Innings</a></li>
                    @if($match_data[0]['match_type']=='test')
                    <li class=""><a href="#second_innings" data-toggle="tab" aria-expanded="false">2nd Innings </a></li>
                    @endif
                </ul>
                <div  class="tab-content">
                    <div id="first_innings" class="tab-pane fade active in">

                    <!-- /.panel-heading -->
                        @include('scorecards.cricketfirstinningsview')

                        </ul>
                        </center>
                    </div>
                    @if($match_data[0]['match_type']=='test')
                    <div id="second_innings" class="tab-pane fade" >

                        @include('scorecards.cricketsecondinningsview')

                        </ul>
                        </center>
                        </div>
                    </div>
                    @endif
                    <!-- /.panel-body -->

                        <div class="clearfix"></div>
                        @if(!empty($match_data[0]['match_report']))
                        <div id="match_report_view" class="summernote_wrapper tab-content">
                                <h3 class="brown1 table_head">Match Report</h3>
                                <div id="match_report_view_inner">
                                {!! $match_data[0]['match_report'] !!}
                                </div>
                        </div>
                        @endif
                </div>
	<input type="hidden" name="match_id" id="match_id" value="{{$match_data[0]['id']}}">
	@if($isValidUser && $isApproveRejectExist)

        <div class="sportsjun-forms text-center">
           <button type="button" onclick="scoreCardStatus('approved');" class="button btn-primary">Approve</button>
            <button type="button" onclick="scoreCardStatus('rejected');" class="button btn-secondary">Reject</button><br/>
			 <textarea name="rej_note" id="rej_note" rows="4" cols="50" placeholder="Reject Note" style="margin:20px 0 10px 0;"></textarea>
        </div>
	@endif
        </div>
		</div>
	</div>

</div>

<script type="text/javascript" src="{{ asset('/js/html2canvas.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/spin.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/ladda.js') }}"></script>
<script>
var teamId = $('#team option:selected').data('status');
var bating_team = $( "#team option:selected" ).text();
var bowling_team = $('#team option:not(:selected)').text();
$("#team_a_batting").text(bating_team+' Innings');
$("#team_b_bowling").text(bowling_team+' Bowling');
$("#team_b_batting").text(bowling_team+' Innings');
$("#team_a_bowling").text(bating_team+' Bowling');
$("#team_a_extras").text(bating_team+' Extras');
$("#team_b_extras").text(bowling_team+' Extras');

var caption = '<?php echo $tournamentDetails['name'] ?>';
var shareFacebookLadda = Ladda.create( document.querySelector( '.sj-social-ancr-fb' ) );
var shareTwitterLadda = Ladda.create( document.querySelector( '.sj-social-ancr-twt' ) );

function postImageToFacebook(token, filename, mimeType, imageData, message) {
  var fd = new FormData();
  fd.append('file', blobToFile(imageData, filename));
  $.ajax({
      url: "/share/facebook",
      data: fd,
      type: 'POST',
      processData: false,
      contentType: false,
      success: function (response) {
        console.log(window.location.href.substring(0, window.location.href.indexOf("matchpublic"))+response);
        FB.ui({
          method: 'feed',
          link: "http://sportsjun.com/"+ window.location.href.substring(window.location.href.indexOf("matchpublic")),
          picture: window.location.href.substring(0, window.location.href.indexOf("matchpublic"))+response,
          caption: caption,
        }, function(response){});
        shareFacebookLadda.stop();
      },
      error: function (shr, status, data) {
        console.log(shr);
        shareFacebookLadda.stop();
      }
  });


    // var fd = new FormData();
    // fd.append("access_token", token);
    // fd.append("source", imageData);
    // fd.append("no_story", true);
    //
    // $.ajax({
    //     url: "https://graph.facebook.com/me/photos?access_token=" + token,
    //     type: "POST",
    //     data: fd,
    //     processData: false,
    //     contentType: false,
    //     cache: false,
    //     success: function (data) {
    //         FB.api(
    //             "/" + data.id + "?fields=images",
    //             function (response) {
    //                 shareFacebookLadda.stop();
    //
    //                 if (response && !response.error) {
    //
    //                     FB.ui({
    //                       method: 'feed',
    //                       link: "http://sportsjun.com/"+ window.location.href.substring(window.location.href.indexOf("matchpublic")),
    //                       picture: response.images[0].source,
    //                       caption: caption,
    //                     }, function(response){});
    //                 }
    //             }
    //         );
    //     },
    //     error: function (shr, status, data) {
    //         shareFacebookLadda.stop();
    //     },
    //     complete: function (data) {
    //     }
    // });
}

function shareTeamVSOnFacebook() {
  shareFacebookLadda.start();
  html2canvas($("#team_vs"), {
    onrendered: function(canvas) {
        canvas.toBlob(function(blob) {
          FB.getLoginStatus(function (response) {
              if (response.status === "connected") {
                  FB.login(function (response) {
                    if (response.authResponse) {
                      postImageToFacebook(response.authResponse.accessToken, "Canvas to Facebook/Twitter", "image/png", blob, window.location.href);
                    }
                  });
              } else if (response.status === "not_authorized") {
                  FB.login(function (response) {
                    if (response.authResponse) {
                      postImageToFacebook(response.authResponse.accessToken, "Canvas to Facebook/Twitter", "image/png", blob, window.location.href);
                    }
                  });
              } else {
                  FB.login(function (response) {
                    if (response.authResponse) {
                      postImageToFacebook(response.authResponse.accessToken, "Canvas to Facebook/Twitter", "image/png", blob, window.location.href);
                    }
                  });
              }
          });
        });
    }
  });
}

function blobToFile(theBlob, fileName){
    //A Blob() is almost a File() - it's just missing the two properties below which we will add
    theBlob.lastModifiedDate = new Date();
    theBlob.name = fileName;
    return theBlob;
}

function shareTeamVSOnTweeter() {
  shareTwitterLadda.start();
  html2canvas($("#team_vs"), {
    onrendered: function(canvas) {
        canvas.toBlob(function(blob) {
          var fd = new FormData();
          fd.append('file', blobToFile(blob, "image.png"));
          $.ajax({
              url: "/share/twitter",
              data: fd,
              type: 'POST',
              processData: false,
              contentType: false,
              success: function (data) {
                shareTwitterLadda.stop();
              },
              error: function (shr, status, data) {
                shareTwitterLadda.stop();
              }
          });
        });
    }
  });
}

//Send Approve
function scoreCardStatus(status)
{
	var msg = ' Reject ';
	if(status=='approved')
		var msg = ' Approve ';
	$.confirm({
	title: 'Confirmation',
	content: 'Are You Sure You Want To '+msg+' This ScoreCard?',
	confirm: function() {
		match_id = $('#match_id').val();
		rej_note = $('#rej_note').val();
		$.ajax({
            url: base_url+'/match/scoreCardStatus',
            type: "post",
            data: {'scorecard_status': status,'match_id':match_id,'rej_note':rej_note,'sport_name':'Cricket'},
            success: function(data) {
                if(data.status == 'success') {
					window.location.href = base_url+'/match/scorecard/edit/'+match_id;
                }
            }
		});
	},
	cancel: function() {
			// nothing to do
		}
	});

}
</script>
@endsection
