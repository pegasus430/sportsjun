@extends(Auth::user() ? 'layouts.app' : 'home.layout')
@section('content')

<?php 
 $team_a_id = $match_data[0]['a_id']; $team_b_id= $match_data[0]['b_id'] ; 
  $match_id=$match_data[0]['id'];
  $tournament_id=$match_data[0]['tournament_id'];

  



    $player_a_ids=$match_data[0]['player_a_ids'];
    $player_b_ids=$match_data[0]['player_b_ids'];

    $match_details=json_decode($match_data[0]['match_details']);

    isset($match_details->preferences)?$preferences=$match_details->preferences:[];
    
    if(isset($preferences->number_of_sets))$set=$preferences->number_of_sets ;
    else $set=5;

    ${'team_'.$match_data[0]['a_id'].'_score'}='0 sets';
    ${'team_'.$match_data[0]['b_id'].'_score'}='0 sets'; 

    $team_a_info='';
    $team_b_info='';

    if(isset($preferences)){
    $current_set=$match_details->current_set;

  ${'team_'.$team_a_id.'_score'}=$match_details->scores->{$team_a_id.'_score'} .' sets';
  ${'team_'.$team_b_id.'_score'}=$match_details->scores->{$team_b_id.'_score'} .' sets';
} else {
  $current_set=0;
}
  
?>



<div class="col_standard soccer_scorecard">
        <div id="team_vs" class="ss_bg">
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
                                    <!--<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/no_logo.png') }}">-->
                                        {!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}

                                    @endif
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <div class="team_detail">
                                    <div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['a_id'] }}">{{ $team_a_name }}</a></div>
                                    <div class="team_city">{{ $team_a_city }}</div>
                                    <div class="team_score" id="team_a_score">{{${'team_'.$team_a_id.'_score'} }}</div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <span class="vs"></span>
                    </div>
                    <div class="team team_two col-xs-5">
                        <div class="row">
                            <div class="col-md-8 col-sm-12 visible-md visible-lg">
                                <div class="team_detail">
                                    <div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['b_id'] }}">{{ $team_b_name }}</a></div>
                                    <div class="team_city">{{ $team_b_city }}</div>
                                    <div class="team_score" id="team_b_score">{{${'team_'.$team_b_id.'_score'} }}</div>
                                    
                                </div>
                            </div>
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
                                    <!--    <img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/no_logo.png') }}">  -->
                                        {!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}

                                    @endif
                                </div>
                            </div>

                            <div class="col-md-8 col-sm-12 visible-xs visible-sm">
                                <div class="team_detail">
                                    <div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['b_id'] }}">{{ $team_b_name }}</a></div>
                                    <div class="team_city">{{ $team_b_city }}</div>
                                    <div class="team_score" id="team_b_score">{{${'team_'.$team_b_id.'_score'} }}</div>


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
                                    <h4>    {{$tournamentDetails['name']}} Tournament </h4>
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
            </div>
        </div>

        <div class="container pull-up">

            <div class="panel panel-default">
                <div class="col-md-12">
                    <h5 class="scoreboard_title">volleyball Scorecard 
                            @if(!empty($match_data[0]['match_category']))
                             <span class='match_type_text'>
                             ({{ucfirst($match_data[0]['match_category']) }})
                             </span>
                                @endif  
                    </h5>

                    <div class="clearfix"></div>
                    <div class="form-inline">
                        @if($match_data[0]['winner_id']>0)

                            <div class="form-group">
                                <label class="win_head">Winner</label>
                                <h3 class="win_team">{{ ($match_data[0]['a_id']==$match_data[0]['winner_id'])?$team_a_name:$team_b_name }}</h3>
                            </div>
                            <BR>
                        @if(!empty($match_data[0]['player_of_the_match']))
                            <div class="form-group">
                                <label class="" style="color:red">PLAYER OF THE MATCH</label>
                                <h4 class="win_team">{{ Helper::getUserDetails($match_data[0]['player_of_the_match'])->name }}</h4>

                            </div>
                        @endif

                        @else
                            @if($match_data[0]['is_tied']>0)

                                <div class="form-group">
                                    <label>Match Result : </label>
                                    <h3 class="win_team">{{ 'Tie' }}</h3>

                                </div>

                              @elseif($match_data[0]['match_result'] == "washout")
                                                     <div class="form-group">
                                                         <label>MATCH ENDED DUE TO</label>
                                                         <h3 class="win_team">Washout</h3>
                                                     </div>
                            @else

                                <div class="form-group">
                                    <label>Winner is Not Updated</label>

                                </div>
                            @endif
                        @endif
                        <p class="match-status mg"><a href="{{ url('user/album/show').'/match'.'/0'.'/'.$action_id }}"><span class="fa" style="float: left; margin-left: 8px;"><img src="{{ asset('/images/sc-gallery.png') }}" height="18" width="22"></span> <b>Media Gallery</b></a></p>
                        @include('scorecards.share')
                        <p class="match-status">@include('scorecards.scorecardstatusview')</p>
                    </div>
                </div>
            </div>

       



  <div class="row">
    <div class="col-sm-12">
  
       <div class="row">


          @if(count($volleyball_a_score))
         
          <!-- Team A Goals Start-->
          <div class="col-sm-10 col-lg-10 col-sm-offset-1">
   <form id='volleyball' onsubmit='return manualScoring(this)'>
          {!!csrf_field()!!}
             

            <div class='row'>
              <div class='col-sm-12'>

               <div class='table-responsive'>
      <table class='table table-striped table-bordered'>
        <thead>
          <tr class='team_fall team_title_head'>
             <th></th>
             
            @for($set_index=1; $set_index<=$set; $set_index++)
              <th>SET {{$set_index}}</th>
            @endfor
             
          </tr>
        </thead>
        <tbody>
          <tr>

            <td>{{$volleyball_a_score['team_name']}}</td>
            
          @for($set_index=1; $set_index<=$set; $set_index++)
            <td>
               
                {{$volleyball_a_score['set'.$set_index]}}
            </td>
          @endfor
        </tr>

          <tr>
            <td>{{$volleyball_b_score['team_name']}} </td>

            @for($set_index=1; $set_index<=$set; $set_index++)
              <td>
               
                 {{$volleyball_b_score['set'.$set_index]}}
                
              </td>
            @endfor
        </tr>

        </tbody>
      </table>
    </div>
    </div>
    </div>
    </form>
    </div>

      @endif
                <!-- Selecting Squads Start-->
                <div class="col-sm-10 col-sm-offset-1">
                    <h3 class="team_bat team_title_head">Playing Squad</h3>

                    <div class='row'>
                        <div class='col-sm-6 col-xs-12'>
                            <div class="table-responsive">
                                <table class="table table-striped">

                                    <tbody id="team_tr_a" >
                                    @foreach($team_a_volleyball_scores_array as $player_a)
                                      
                                        @if($player_a['playing_status']=='P')
                                            <tr class="team_a_playing_row " >
                                                <td>
                                                    {{ $player_a['player_name']   }} 
                                                </td>                                         

                                            </tr>
                                        @endif
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class='col-sm-6 col-xs-12'>

                            <div class="table-responsive">
                                <table class="table table-striped">

                                    <tbody id="team_tr_b" >
                                    @foreach($team_b_volleyball_scores_array  as $player_b)
                                        @if($player_b['playing_status']=='P')
                                            <tr class="team_b_playing_row ">

                                                <td>
                                                    {{ $player_b['player_name']   }} 
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Team A Goals End-->

                <div class="col-sm-10 col-sm-offset-1">
                    <h3 id='team_' class="team_fall team_title_head">Substitute Squad</h3>

                    <div class='row'>
                        <div class='col-sm-6 col-xs-12'>
                            <div class="table-responsive">
                                <table class="table table-striped">

                                    <tbody id="team_tr_a" >
                                    @foreach($team_a_volleyball_scores_array  as $player_a)
                                        @if($player_a['playing_status']=='S')
                                            <tr class="team_a_playing_row  ">
                                                <td>
                                                    {{ $player_a['player_name']   }} 
                                                 </td>

                                            </tr>
                                        @endif
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <div class='col-sm-6 col-xs-12'>

                            <div class="table-responsive">
                                <table class="table table-striped">

                                    <tbody id="team_tr_b" >
                                    @foreach($team_b_volleyball_scores_array  as $player_b)
                                        @if($player_b['playing_status']=='S' )
                                            <tr class="team_a_playing_row">
                                                <td>
                                                    {{ $player_b['player_name']   }} 
                                                </td>

                                            </tr>
                                        @endif
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>


@if(!empty($match_data[0]['match_report']))

        <div class="clearfix"></div>
            <div id="match_report_view" class="summernote_wrapper tab-content col-sm-10 col-sm-offset-1">
                <h3 class="brown1 table_head brown1">Match Report</h3>
                    <div id="match_report_view_inner">
                            {!!$match_data[0]['match_report']!!}
                    </div>
            </div>
@endif


   @include('scorecards.common.referees')
          


    
    <!-- if match schedule type is team -->

    <!-- end -->
    
    <div class="sportsjun-forms text-center scorecards-buttons">
    <input type="hidden" name="match_id" id="match_id" value="{{$match_data[0]['id']}}">
    @if($isValidUser && $isApproveRejectExist)
        
    <button style="text-align:center;" type="button" onclick="scoreCardStatus('approved');" class="button green">Approve</button>
    <button style="text-align:center;" type="button" onclick="scoreCardStatus('rejected');" class="button black">Reject</button><br />  
    
    <textarea name="rej_note" id="rej_note" rows="4" cols="50" placeholder="Reject Note" style="margin:20px 0 10px 0;"></textarea>
    @endif
    </div>


          @if($isValidUser && $match_data[0]['match_status']=='completed')
         <div class="sportsjun-forms text-center scorecards-buttons">
            <input type="hidden" name="match_id" id="match_id" value="{{$match_data[0]['id']}}">
            <br><br>

            <button class="btn btn-event" type="button" onclick="return  SJ.SCORECARD.allow_match_edit_by_admin({{$match_data[0]['id']}})">
                Edit Match
            </button>              
           
        </div>
     @endif
   </div>
   </div>
  </div>
</div>
</div>
</div>

<script>
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
            data: {'scorecard_status': status,'match_id':match_id,'rej_note':rej_note,'sport_name':'volleyball'},
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


function getMatchDetails(){

  var data={
    match_id:{{$match_data[0]['id']}}
  }

  var base_url=base_url || secure_url;
        $.ajax({
            url:  base_url+'/viewpublic/match/getvolleyballDetails', 
            type:'get', 
            dataType:'json',
            data:data,
            success:function(response){
                var left_team_id=response.preferences.left_team_id;
                var right_team_id=response.preferences.right_team_id;

                  $.each(response.match_details, function(key, value){
                      $('.a_'+key).html(value[left_team_id+'_score']);
                      $('.b_'+key).html(value[right_team_id+'_score']);
                  })
            }
        })
}

@if($match_data[0]['match_status']!='completed')
//window.setInterval(getMatchDetails, 10000);
@endif

</script>


<!-- Put plus and minus buttons on left and rights of sets -->

@endsection
