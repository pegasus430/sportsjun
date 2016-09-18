
@extends('layouts.app')
@section('content')


	<style type="text/css">
input:read-only { 
    background-color: #f4f4f4;
}
		.alert{display: none;}
		.show_teams{display: none;}
		.player_selected{
			background: #111111;
			background-color: red;
		}
		.btn-yellow-card{
			background: orange;
			border: none;
		}
		.btn-red-card{
			background: darkred;
		}
		.btn-card{
			border: none;
		}
		.btn-red-card-select{
			color: white;
			background: #ff4f4f;
		}
		.btn-red-card-select:hover{
			color: white;
			background: #ff4f5f;
		}
		.btn-yellow-card-select{
			color: white;
			background: orange;
		}
		.btn-yellow-card-select:hover{
			color: white;
			background: orange;
		}
		.btn-goal-card-select{
			color: white;
			background: #aaa;
		}
		.btn-goal-card-select:hover{
			color: white;
			background: #aaa;
		}
		.icon-check{
			color:green;
			border: 1px double #999;
			display: block;
			height: 18px;
			width: 16px;

		}

		.selected-player-button-show{
			background: #ffddee;
			border: none;
		}
		.substituted-player-button-show{
			background: #ddcccc;
			border: none;
		}

		.fa-share{
			color:green;
		}
		.fa-reply{
			color:red;
		}
		.btn-penalty{
            opacity: .2;
        }
        .btn-green-card, .btn-green-card:hover, .btn-green-card:active{
            background: #6Bc26C;
        }
        .btn-penalty-chosen{
        	opacity: 1;
        }

        .btn-secondary-link{
        	background: #ddd;
        }
        .fouls{
        	color:red;
        	font: 23px;
        }

        .not_playing{
        	background-color: #f9f9f9;

        }

        .btn-link:disabled{
        	background: #f8f8f8;
        	background-color: #f8f8f8;
        }




	</style>
	@if (session('status'))
		<div class="alert alert-success">
			{{ session('status') }}
		</div>
	@endif
	
	<?php $team_a_id = $match_data[0]['a_id']; $team_b_id= $match_data[0]['b_id'] ; 
	$match_id=$match_data[0]['id'];
	$tournament_id=$match_data[0]['tournament_id'];

	?>
	<?php
	$match_details=json_decode($match_data[0]['match_details']);
	$preferences=isset($match_details->preferences)?$match_details->preferences:[];
	$a_points=0;
	$b_points=0;
	$a_fouls=0;
	$b_fouls=0;

	 ${'team_'.$match_data[0]['a_id'].'_score'}='0 sets';
    ${'team_'.$match_data[0]['b_id'].'_score'}='0 sets'; 

   	if(count($preferences)){
  ${'team_'.$team_a_id.'_score'}=$match_details->scores->{$team_a_id.'_score'}.' sets';
  ${'team_'.$team_b_id.'_score'}=$match_details->scores->{$team_b_id.'_score'}.' sets';
    }

	$serving_array=Helper::getVolleyballServer($match_id);

	
	

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
										<!--<img  class="img-responsive img-circle" alt="" width="110" height="110" src="{{ url('/uploads/teams/'.$team_a_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/no_logo.png') }}';">-->
										{!! Helper::Images($team_a_logo['url'],'teams',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
									@else
										<!--	<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/no_logo.png') }}">-->
										{!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
									@endif
								@else
									<!--	<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/no_logo.png') }}">	-->
										{!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
									@endif
								</div>
							</div>
							<div class="col-md-8 col-sm-12">
								<div class="team_detail">
									<div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['a_id'] }}">{{ $team_a_name }}</a></div>
									<div class="team_city">{{ $team_a_city }}</div>
									<div class="team_score team_a_score" id="team_a_score">{{${'team_'.$team_a_id.'_score'} }} </div>
									
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
									<div class="team_score team_b_score" id="team_b_score">{{${'team_'.$team_b_id.'_score'} }} </div>
									
								</div>
							</div>
							<div class="col-md-4 col-sm-12">
								<div class="team_logo">
								@if(!empty($team_b_logo))
									@if($team_b_logo['url']!='')
										<!--	<img  class="img-responsive img-circle" alt="" width="110" height="110" src="{{ url('/uploads/teams/'.$team_b_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/no_logo.jpg') }}';">-->
										{!! Helper::Images($team_b_logo['url'],'teams',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
									@else
										<!--<img  class="img-responsive img-circle" height="110" width="110" src="{{ asset('/images/no_logo.jpg') }}">-->
											{!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
											</td>
									@endif
								@else
									<!--<img  class="img-responsive img-circle" height="110" width="110" src="{{ asset('/images/no_logo.png') }}">	-->
										{!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
									@endif
								</div>
							</div>
							<div class="col-md-8 col-sm-12 visible-xs visible-sm">
								<div class="team_detail">
									<div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['b_id'] }}">{{ $team_b_name }}</a></div>
									<div class="team_city">{{ $team_b_city }}</div>
									<div class="team_score team_b_score" id="team_b_score">{{${'team_'.$team_b_id.'_score'} }} </div>
								</div>
							</div>
						</div>
					</div>
				</div>

				 @if(!is_null($match_data[0]['tournament_id']))
                <div class='row'>
                    <div class='col-xs-12'>
                        <div class='match_loc'>
                    <a href="/tournaments/groups/{{$tournamentDetails['id']}}">
                            {{$tournamentDetails['name']}} Tournament
                   </a>   
                        </div>
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

			<div class="row">
				<div class="col-md-12">
					<h5 class="scoreboard_title">Volleyball Scorecard
					@if(!empty($match_data[0]['match_category']))
                             <span class='match_type_text'>
                             ({{ucfirst($match_data[0]['match_category']) }})
                             </span>
                                @endif</h5>

					<div class="clearfix"></div>
					<div class="form-inline">
						@if($match_data[0]['match_status']=='completed' && $match_data[0]['winner_id']>0)

							<div class="form-group">
								<label class="win_head">Winner</label>
								<h3 class="win_team">{{ ($match_data[0]['a_id']==$match_data[0]['winner_id'])?$team_a_name:$team_b_name }}</h3>


							</div>

						@else
							@if($match_data[0]['match_status']=='completed' && $match_data[0]['is_tied']>0)
								<div class="form-group">
									<label>Match Result : </label>
									{{ 'Tie' }}

								</div>
							@else
								<p></p>
								<br>
								@if($match_data[0]['hasSetupSquad'])
									<div id='end_match_button'>
									<button class="btn btn-danger " onclick="return SJ.SCORECARD.soccerSetTimes(this)"></i>End Match</button>
									</div>
								@endif
				 @if($isValidUser && $isForApprovalExist && ($match_data[0]['winner_id']>0 || $match_data[0]['is_tied']>0 || $match_data[0]['has_result'] == 0))  

					<button style="text-align:center;" type="button" onclick="forApproval();" class="btn btn-primary">Send Score for Approval</button>

				@endif

							@endif
						@endif
						<p class="match-status mg"><a href="{{ url('user/album/show').'/match'.'/0'.'/'.$action_id }}"><span class="fa" style="float: left; margin-left: 8px;"><img src="{{ asset('/images/sc-gallery.png') }}" height="18" width="22"></span> <b>Media Gallery</b></a></p>
						@include('scorecards.share')
						<p class="match-status">@include('scorecards.scorecardstatus')</p>
					</div>
				</div>
			</div>


			@if(!$match_data[0]['hasSetupSquad'])
				<div class="row">
					<!-- Selecting Squads Start-->
					<div class="col-sm-8 col-sm-offset-2">
						<h3 class="team_fall table_head">Playing Squad</h3>

						<div class='row'>
							<div class='col-sm-6 col-xs-12'>
								<div class="table-responsive">
									<table class="table table-striped">
										<thead class="thead">
										<tr class='team_fall team_title_head'>
											<th>{{$team_a_name}}</th>

										</tr>
										</thead>
										<tbody id="team_tr_a" >
										@foreach($team_a_players as $player_a)
											<tr class="team_a_playing_row playing_a_{{$player_a['id']}}" >
												<td class="option block select_player_squad" player_type='playing' team_type="team_a"  player_id="{{$player_a['id']}}">
													{{ $player_a['name']   }}
													<span class='pull-right icon-check'>   </span>
												</td>
											</tr>
										@endforeach

										</tbody>
									</table>
								</div>
							</div>

							<div class='col-sm-6 col-xs-12'>

								<div class="table-responsive">
									<table class="table table-striped">
										<thead class="thead">
										<tr class='team_bat'>
											<th>{{$team_b_name}}</th>
										</tr>
										</thead>
										<tbody id="team_tr_b" >
										@foreach($team_b_players as $player_b)
											<tr class="team_b_playing_row playing_b_{{$player_b['id']}} player_details_{{$player_b['id']}}">
												<td class="option block select_player_squad" player_type='playing' team_type="team_b" player_id="{{$player_b['id']}}">

													{{ $player_b['name']   }}
													<span class="pull-right icon-check"> </span>

												</td>
											</tr>
										@endforeach
										</tbody>
									</table>
								</div>

							</div>
						</div>
					</div>
					<!-- Team A Goals End-->

					<div class="col-sm-8 col-sm-offset-2">
						<h3 id='team_' class="team_fall table_head">Substitute Squad</h3>

						<div class='row'>
							<div class='col-sm-6 col-xs-12'>
								<div class="table-responsive">
									<table class="table table-striped">
										<thead class="thead">
										<tr class='team_fall team_title_head'>
											<th>{{$team_a_name}}</th>
										</tr>
										</thead>
										<tbody id="team_tr_a" >
										@foreach($team_a_players as $player_a)
											<tr class="team_a_playing_row  substitute_a_{{$player_a['id']}} player_details_{{$player_a['id']}} ">
												<td class="option block select_player_squad" player_type='substitute' team_type="team_a" player_id="{{$player_a['id']}}">

													{{ $player_a['name']   }}
													<span class='pull-right icon-check'></span>
											</tr>
										@endforeach

										</tbody>
									</table>
								</div>

							</div>

							<div class='col-sm-6 col-xs-12'>

								<div class="table-responsive">
									<table class="table table-striped">
										<thead class="thead">
										<tr class='team_bat'>
											<th>{{$team_b_name}}</th>

										</tr>
										</thead>
										<tbody id="team_tr_b" >
										@foreach($team_b_players as $player_b)
											<tr class="team_a_playing_row substitute_b_{{$player_b['id']}}">
												<td class="option block select_player_squad" player_type='substitute' team_type="team_b" player_id="{{$player_b['id']}}">

													{{ $player_b['name']   }}
													<span class="pull-right icon-check"> </span>
												</td>
											</tr>
										@endforeach

										</tbody>
									</table>
								</div>

							</div>
						</div>
					</div>
				</div>

				<!-- Setup Squads End -->


			@else
			<!-- Scoring Start -->
				<?php $a_count = 1;
					  $set=5;
				?>


			@if(!count($volleyball_a_score))

			 <div class="row">
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


              <form id='serving_order_form' onsubmit=" return submitServingPlayers(this)">
                <div class="col-sm-10 col-sm-offset-1">
                    <h3 id='team_' class="team_fall team_title_head">Serving Order</h3>

                    <div class='row'>
                        <div class='col-sm-6 col-xs-12'>
                            <div class="table-responsive">
                                <table class="table ">
                            
                                    <tbody id="team_tr_a" >
                                    @foreach($team_a_volleyball_scores_array  as $k=>$player_a)
                                        @if($player_a['playing_status']=='P')
                                            <tr class="team_a_playing_row  ">
                                                <td>
                                                 	<div class='col-xs-2'>  {{ $k +1 }} </div>
                                                 	<div class='col-xs-10'>
	               {!!Form::select("serving_a_".($k+1), $active_players_a, $player_a['user_id'], ['class'=>'form-control','id'=>"serving_a_".($k+1)]) !!}
	                                                 </div>
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
                                <table class="table ">

                                    <tbody id="team_tr_b" >
                                    @foreach($team_b_volleyball_scores_array  as $k=>$player_b)
                                        @if($player_b['playing_status']=='P' )
                                            <tr class="team_a_playing_row">
                                                <td>
                                                 <div class='col-xs-2'>  {{ $k +1 }} </div>
                                                 	<div class='col-xs-10'>
	                  {!!Form::select("serving_b_".($k+1), $active_players_b, $player_b['user_id'], ['class'=>'form-control', 'id'=>"serving_b_".($k+1)]) !!}
	                                                 </div>
                                                 
                                                </td>

                                            </tr>
                                        @endif
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>


                        </div>
                    </div>

                    <input type='hidden' name="match_id" value="{{$match_id}}">

                    <div class='col-sm-12'>
                    	<div class='col-sm-4 col-sm-offset-4'><button class="btn btn-primary form-control" type='button' data-toggle="modal" data-target="#chooseModal">Done </button> </div>
                    </div>
             
            </div>

               <div class="modal fade  tossDetail"    id="chooseModal" >
                    <div class="vertical-alignment-helper">
                       <div class="modal-dialog modal-lg vertical-align-center">
                          <div class="modal-content create-team-model create-album-popup model-align">
                            <div class="modal-header text-center">
                                 <button type="button" class="close" data-dismiss="modal">×</button>
                                         <h4>TOSS DETAILS</h4>
                                     </div>
                                  <div class="modal-body">
                                   
                                      <div id="tossWonByRadio" class="form-group">
                                       <div class="toss-detail">
                                         <span class="head">TOSS WON BY</span>
                                          <div class="radio-box">
                                            <div class="radio">
                                                 <input name="team" type="radio" value="{{ $match_data[0]['a_id'] }}" id="{{ $match_data[0]['a_id'] }}" checked="">
                                                <label for="{{ $match_data[0]['a_id'] }}">{{ $team_a_name }}</label>
                                                                                 </div>
                                                                   <div class="radio">
                                     <input name="team" type="radio" value="{{ $match_data[0]['b_id'] }}" id="{{ $match_data[0]['b_id'] }}">
                                          <label for="{{ $match_data[0]['b_id'] }}">{{ $team_b_name }}</label>
                                                                                       </div>
                                                                 </div>
                                                            </div>
                                                       </div>
                                           <div id="batRadio" class="form-group">
                                             <div class="toss-detail">
                                                      <span class="head">ELECTED</span>
                                                           <div class="radio-box">
                                                                           <div class="radio">
                                <input name="elected" type="radio" value="serve" id="bat" checked="">
                                                   <label for="bat">SERVE</label>
                                            </div>
                                          <div class="radio">
                   <input name="elected" type="radio" value="receive" id="bowl">
                                                      <label for="bowl">RECEIVE</label>
                                                                                        </div>
                                                    </div>
                                              </div>
                                        </div>
                                                                              
                                                                        </div>
                            <div class="modal-footer">
                             <button type="submit" class="button btn-primary">Done</button>
                            <button type="button" class="button btn-secondary" data-dismiss="modal">Cancel</button>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>
                               </form>


			@else



<!-- show alert for no results -->

				<div class="row">
					<!-- Team A Goals Start-->
					<div class="col-sm-10 col-lg-10 col-sm-offset-1">
   <form id='volleyball' onsubmit='return manualScoring(this)'>
   				{!!csrf_field()!!}
   					 <div class="row">
					    <div class='col-sm-12'>
					    
					     <span class='pull-right'>   
					     

					        <a href='javascript:void(0)' onclick="enableManualEditing(this)" style="color:#123456;">edit <i class='fa fa-pencil'></i></a> 
					        <span> &nbsp; &nbsp; </span>					       
					    </span>
					    </div>
  					</div>

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
               
                 <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set{{$set_index}}" value="{{$volleyball_a_score['set'.$set_index]}}" name='a_set{{$set_index}}'>

            </td>
          @endfor
        </tr>

          <tr>
            <td>{{$volleyball_b_score['team_name']}} </td>

            @for($set_index=1; $set_index<=$set; $set_index++)
              <td>
               
                  <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new b_set{{$set_index}}" value="{{$volleyball_b_score['set'.$set_index]}}" name='b_set{{$set_index}}'>
                
              </td>
            @endfor
        </tr>

        </tbody>



      </table>
    </div>
							

								<input type='hidden' value="{{$match_data[0]['id']}}" name='match_id'>
								
								<div class="row" id='saveButton'>
								    <div class='col-sm-12'>
								       <center> <input type='submit' class="btn btn-primary" value="Save"></center>
								    </div>
								</div>
							</form>
						</div>
					

					<div id='real_time_scoring'  class="col-sm-12">

						<div class='row'>
							<div class='col-sm-6'>
								<div class='table-responsive'>
									<table class="table table-striped">
										<thead>
											<tr class="thead">
												<th class=""><b>{{$team_a_name}}</b> </th>
											</tr>
										</thead>
										<tbody>
											    @foreach($team_a_volleyball_scores_array  as $player_a)
			                                        @if($player_a['playing_status']=='P' )
			                                            <tr class="team_a_playing_row">
			                                                <td class="player_{{$player_a['user_id']}} server_player">
			                                                {{ $player_a['serving_order']}}  &nbsp;  
			                                                {{ $player_a['player_name']   }} 
			                                                </td>

			                                            </tr>
			                                        @endif
			                                    @endforeach
										</tbody>
									</table>
								</div>

							<center class=" sportsjun-forms "  >
							<button href="javascript:void(0);" data-toggle="modal" data-target="#volleyballSubstituteModalA" class='btn-link btn-other btn-secondary-link  request pull-left' onclick="return false">Substitute A</button>



							<button class="btn-link  btn-green-card serve_{{$team_a_id}} serve "  onclick="return addScore(this)" value='won'  type="button" team_id={{$team_a_id}}>Won Rally</button>

							<button class="btn-link btn-red-card-select serve_{{$team_a_id}} serve"   onclick="return addScore(this)"  value='lost' type="button" team_id={{$team_a_id}}>Lost Rally</button>
								</center>
							</div>


							<div class='col-sm-6'>
								<div class='table-responsive'>
									<table class="table table-striped">
										<thead>
											<tr class="thead">
												<th><b>{{$team_b_name}}</b> </th>
											</tr>
										</thead>
										<tbody>
											    @foreach($team_b_volleyball_scores_array  as $player_b)
			                                        @if($player_b['playing_status']=='P' )
			                                            <tr class="team_a_playing_row">
			                                                <td class="player_{{$player_b['user_id']}} server_player">
			                                                	{{ $player_b['serving_order'] }}  &nbsp; 
			                                                    {{ $player_b['player_name']   }} 
			                                                </td>

			                                            </tr>
			                                        @endif
			                                    @endforeach
										</tbody>									


									</table>
								</div>


						<div class="sportsjun-forms">						
							
							<button  class="btn-link request btn-green-card serve_{{$team_b_id}} serve"   onclick="return addScore(this)" value='won' type="button" team_id={{$team_b_id}}>Won Rally</button>

							<button  class="btn-link request btn-red-card-select serve_{{$team_b_id}} serve "   onclick="return addScore(this)"  value='lost' type="button" team_id={{$team_b_id}}>Lost Rally</button>

							
							<button data-toggle="modal" data-target="#volleyballSubstituteModalB" class='btn-link btn-secondary-link  request pull-right' onclick="return false">Substitute  B</button>
						</div>


							</div>


					</div>
				</div>

<!-- Temporal Data -->
					<div class="col-sm-10 col-sm-offset-1 " style="display:none">
						<h3 id='team_b' class="team_bowl team_title_head">New Records</h3>
						<div class="">
							<div class='row table-stripped'>						
								
								<div class="col-lg-12 col-sm-12 visible-md visible-sm visible-lg not-visible-xs">
									<h5 class="col-sm-3 team_a ">Player</h5>
									<h5 class="col-sm-2 team_a ">Quarter</h5>
									<h5 class="col-sm-2 team_a ">Type</h5>
									<h5 class="col-sm-2 team_a ">Time</h5>									
									<h5 class="col-sm-3 team_a ">Action</h5>
								</div>
								
								<div id="displayGoalsFirstHalfTemporal" class="col-lg-12 col-sm-12" >
								
								</div>

							</div>
							
						</div>

					</div>





					<div id="end_match" class="modal fade">
			 {!! Form::open(array('url' => '', 'method' => 'POST','id'=>'endMatchForm', 'onsubmit'=>'return endMatch(this)')) !!}

						<div class="modal-dialog sj_modal sportsjun-forms">
							<div class="modal-content">
								<div class="alert alert-danger" id="div_failure1"></div>
								<div class="alert alert-success" id="div_success1" style="display:none;"></div>
								<div class="modal-body">
					


									<div class="clearfix"></div>
									<div class="form-inline">
										<div class="form-group">
											<label for="match_result">End of Match Result:</label>
											<select class="form-control selectpicker" name="match_result" id="match_result" onchange="getTeam();SJ.SCORECARD.selectMatchType(this)">
												<option value="" >Select</option>
												<?php if(empty($match_data[0]['tournament_round_number'])) { ?>
												<option <?php if($match_data[0]['is_tied']>0) echo " selected";?> value="tie" >Tie</option>
												<?php } ?>
												<option <?php if($match_data[0]['is_tied']==0 && $match_data[0]['winner_id']>0) echo " selected";?> value="win">win</option>
												<option value="washout" {{!$match_data[0]['has_result']?'selected':''}}>No Result</option>
											</select>
										</div>
										<div class="form-group scorescard_stats" style="margin-top:15px;">
											<label class="show_teams">Select Winner:</label>
											<select name="winner_id" id="winner_id" class="show_teams form-control selectpicker" onchange="selectWinner();">
												<option <?php if (isset($match_data[0]['winner_id']) && $match_data[0]['winner_id']==$match_data[0]['a_id']) echo ' selected';?> value="{{ $match_data[0]['a_id'] }}" >{{ $team_a_name }}</option>
												<option <?php if (isset($match_data[0]['winner_id']) && $match_data[0]['winner_id']==$match_data[0]['b_id']) echo ' selected';?> value="{{ $match_data[0]['b_id'] }}">{{ $team_b_name }}</option>
											</select>
										</div>
										<div class="form-group scorescard_stats">

											<label class="">Select Player of Match:</label>
											<select name="player_of_the_match" id="player_of_the_match" class=" form-control selectpicker" onchange="">
												<option value="0" disabled="">Team A</option>
												@foreach($team_a_volleyball_scores_array as $tm_player)
													<option value="{{$tm_player['user_id']}}" @if($match_data[0]['player_of_the_match']==$tm_player['user_id'])?'selected':'' @endif >{{$tm_player['player_name']}}</option>
												@endforeach
												<option value="0" disabled="">Team B</option>
												@foreach($team_b_volleyball_scores_array as $tm_player)
													<option value="{{$tm_player['user_id']}}" @if($match_data[0]['player_of_the_match']==$tm_player['user_id'])?'selected':'' @endif >{{$tm_player['player_name']}}</option>
												@endforeach
											</select>
										</div>


									</div>


									<div class='row' style="padding-bottom:20px;">
										<center class='col-sm-6 col-sm-offset-3'> <button class='btn btn-primary full-width ' onclick="" type='submit'> Save</button></center>
									</div>

									<div class="modal-footer">
										<button type="button" class="button btn-secondary" data-dismiss="modal">Cancel</button>
									</div>
								</div>
							</div>
						</div>

	<input type='hidden' id='selected_player_id_value' value='0' player_id='0' player_name=''>
	<input type='hidden' id='half_time' value='quarter_1'>
	<input type='hidden' id='selected_team_type' value='team_a'>
	<input type='hidden' id='last_index' value="0" name='last_index'>

	<input type='hidden' id='total_players_a' value="{{count($team_a_players)}}">
	<input type='hidden' id='total_players_b' value="{{count($team_b_players)}}">
	<input type="hidden" id="volleyball_form_data" value="">
	<input type="hidden" name="team_a_count" value="{{ (count($team_a_volleyball_scores_array)>0)?count($team_a_volleyball_scores_array):1 }}" id="team_a_count">
	<input type="hidden" name="team_b_count" value="{{ (count($team_b_volleyball_scores_array)>0)?count($team_b_volleyball_scores_array):1 }}" id="team_b_count">
	<input type="hidden" name="tournament_id" value="{{ $match_data[0]['tournament_id'] }}">
	<input type="hidden" name="team_a_id" value="{{ $match_data[0]['a_id'] }}" id="team_a_id">
	<input type="hidden" name="team_b_id" value="{{ $match_data[0]['b_id'] }}" id="team_b_id">
	<input type="hidden" name="match_id" id='match_id' value="{{ $match_data[0]['id'] }}">
	<input type="hidden" name="team_b_name" value="{{ $team_b_name }}" id="team_b_name">
	<input type="hidden" name="team_a_name" value="{{ $team_a_name }}" id="team_a_name">
	<input type="hidden" name="winner_team_id" value="" id="winner_team_id">
	<input type="hidden" name="delted_ids" value="" id="delted_ids">
	<input type='hidden' name='serving_user_id' value="" id="serving_user_id">
	</form>
					</div>
				</div>

			

		</thead>
		</table>
	</div>

	</div>

	</div>
	<!-- Team B Goals End-->


	</div>

	<!-- Scoring End -->
	@endif


	@endif



	<div class="sportsjun-forms text-center scorecards-buttons">
		<center>
			<ul class="list-inline sportsjun-forms">
				@if($isValidUser)
					<li>

						@if(!$match_data[0]['hasSetupSquad'])
							<button type='button' class='btn-danger btn .' onclick="return confirmSquad()"><i class="fa fa-floppy-o"></i> Confirm Squad</button>
						@else


						@endif
					</li>
				@endif

				
			</ul>
		</center>
	</div>
	</div>
	</div>

	<!-- Start Modals -->

	<div id="volleyballSubstituteModalA" class="modal fade">
		<div class="modal-dialog sj_modal sportsjun-forms">
			<div class="modal-content">
				<div class="alert alert-danger" id="div_failure1"></div>
				<div class="alert alert-success" id="div_success1" style="display:none;"></div>
				<div class="modal-body">
					<form  onsubmit="return volleyballSwapPlayers('form_substitute_a')" id='form_substitute_a'>
						{!!csrf_field()!!}
						<input type='hidden' name='match_id' value="{{$match_id}}">
						<input type='hidden' name='team_id' value="{{$team_a_id}}">
						<div class='row'>
							<div class="table-responsive">
								<center class='table_head'> {{$team_a_name}} Substitute</center>
								<hr>
								<div class='col-sm-6'>
									<!-- Start of Playing 11 of Team A -->
									<table class="table ">
										<tr>
											<th colspan="4">Playing Squad</th>
										</tr>
										<tbody id="">
										@foreach($team_a_volleyball_scores_array as $key=>$team_a_volleyball)
											@if($team_a_volleyball['playing_status']=="P" )
												<tr class="player_details_{{$team_a_volleyball['id']}}">
													<td colspan="3" class='select_left' class="team_a_goal_row player_select " player_id="{{$team_a_volleyball['id']}} "  player_name="{{$team_a_volleyball['player_name']}}" team_name="{{$team_a_name}}" >       {{$team_a_volleyball['player_name']}}
													
													</td>
													<td colspan="1" >
														<input type='checkbox' name="substitute_a_{{$team_a_volleyball['id']}}"  >
													</td>
												</tr>
											@endif
										@endforeach
										</tbody>
									</table>
									<!-- End of substitute of Team B -->
								</div>
								<div class='col-sm-6'>
									<!-- Start of substitute of Team A -->

									<table class="table table-striped">
										<tr>
											<th colspan="4">Substitute Squad</th>
										</tr>
										<tbody id="">
										@foreach($team_a_volleyball_scores_array as $key=>$team_a_volleyball)
											@if($team_a_volleyball['playing_status']=="S" )

												<tr class="player_details_{{$team_a_volleyball['id']}}">
													<td colspan="3" class='select_left' class="team_a_goal_row player_select" player_id="{{$team_a_volleyball['id']}} "  player_name="{{$team_a_volleyball['player_name']}}" team_name="{{$team_a_name}}" >       {{$team_a_volleyball['player_name']}}
														


													</td>
													<td colspan="1" >
														<input type='checkbox' name="substitute_a_{{$team_a_volleyball['id']}}" >
													</td>
												</tr>
											@endif
										@endforeach
										</tbody>
									</table>
									<!--End of substitute of Team B  -->
								</div>

							</div>
						</div>
						<center><label class='col-sm-4 col-sm-offset-4'><input type='number' min='0' placeholder="Time substituted" name='time_substituted' required></label> </center>
						<center> </center>


				</div>



				<div class="modal-footer">
					<button class='btn btn-primary' >Swap Players.</button>
					<button type="button" class="button btn-secondary" data-dismiss="modal">Cancel</button>
				</div>
				</form>
			</div>
		</div>
	</div>


	<div id="volleyballSubstituteModalB" class="modal fade">
		<div class="modal-dialog sj_modal sportsjun-forms">
			<div class="modal-content">
				<div class="alert alert-danger" id="div_failure1"></div>
				<div class="alert alert-success" id="div_success1" style="display:none;"></div>
				<div class="modal-body">
					<div class='row'>
						<form  onsubmit="return volleyballSwapPlayers('form_substitute_b')" id='form_substitute_b'>
							{!!csrf_field()!!}
							<div class="table-responsive">
								<center class='table_head'> {{$team_b_name}} Substitute</center>
								<hr>
								<div class='col-sm-6'>
									<!-- Start of Playing 11 of Team A -->

									<input type='hidden' name='match_id' value="{{$match_id}}">
									<input type='hidden' name='team_id' value="{{$team_b_id}}">
									<table class="table ">
										<tr>
											<th colspan="4">Playing Squad</th>
										</tr>
										<tbody id="">
										@foreach($team_b_volleyball_scores_array as $team_a_volleyball)
											@if($team_a_volleyball['playing_status']=="P" )
												<tr class="player_details_{{$team_a_volleyball['id']}}">
													<td colspan="3" class='select_left' class="team_a_goal_row player_select" player_id="{{$team_a_volleyball['id']}} "  player_name="{{$team_a_volleyball['player_name']}}" team_name="{{$team_a_name}}" >       {{$team_a_volleyball['player_name']}}
													
													</td>
													<td colspan="1" >
														<input type='checkbox' name="substitute_a_{{$team_a_volleyball['id']}}" >
													</td>
												</tr>
											@endif
										@endforeach

										</tbody>
									</table>
									<!-- End of substitute of Team B -->
								</div>
								<div class='col-sm-6'>
									<!-- Start of substitute of Team A -->

									<table class="table table-striped">
										<tr>
											<th colspan="4">Substitute Squad</th>
										</tr>
										<tbody id="">
										@foreach($team_b_volleyball_scores_array as $team_a_volleyball)
											@if($team_a_volleyball['playing_status']=="S" )
												<tr class="player_details_{{$team_a_volleyball['id']}}">
													<td colspan="3" class='select_left' class="team_a_goal_row player_select" player_id="{{$team_a_volleyball['id']}} "  player_name="{{$team_a_volleyball['player_name']}}" team_name="{{$team_a_name}}" >       {{$team_a_volleyball['player_name']}}
														
													</td>
													<td colspan="1" >
														<input type='checkbox' name="substitute_a_{{$team_a_volleyball['id']}}" >
													</td>
												</tr>
											@endif
										@endforeach
										</tbody>
									</table>
									<!--End of substitute of Team B  -->
								</div>

							</div>

							<center><label class='col-sm-4 col-sm-offset-4'><input type='gui-input' placeholder="Time substituted" name='time_substituted' required></label> </center>

					</div>

				</div>

			</div>
			<div class="modal-footer">
				<button class='btn btn-primary' type="submit">Swap Players</button>
				<button type="button" class="button btn-secondary" data-dismiss="modal">Close</button>
			</div>
			</form>
		</div>
	</div>
	</div>


>






	<script>
		$(document).ready(function(){
			getTeam();
		});
		function getTeam()
		{
			var value = $( "#match_result" ).val();
			if(value=='win')
			{
				$("label.show_teams").show();
				$('#winner_id').selectpicker('show');
					selectWinner();
			}else
			{
				$("label.show_teams").hide();
				$('#winner_id').selectpicker('hide');
				
				$('#winner_team_id').val('');
			}
		}
		function selectWinner()
		{
			$('#winner_team_id').val($('#winner_id').val());
			$("#winner_id").hide();
		}
		var team_a_count='{{ (count($team_a_volleyball_scores_array)>0)?count($team_a_volleyball_scores_array):1 }}';
		var team_b_count='{{ (count($team_b_volleyball_scores_array)>0)?count($team_b_volleyball_scores_array):1 }}';
		allownumericwithdecimal();
		checkDuplicatePlayers('select_player_a');
		checkDuplicatePlayers('select_player_b');
		function addNewRow(team,i)
		{

			if(team=='a')//team a
			{

				var get_player_countt= $('[class ^= "team_a_goal_row"]').size();
				var team_cnt = '{{ $team_a_count}}';
				if(get_player_countt >= team_cnt)
				{
					$.alert({
						title: 'Alert!',
						content: 'All the team players are already added to the scorecard.'
					});
					//alert('Players Exceeded.');
					return false;
				}
				team_a_count++;
				$('#team_a_count').val(team_a_count);
				var i=$('#i').val();
			}
			else//team b
			{

				var get_player_countt= $('[class ^= "team_b_goal_row"]').size();
				var team_cnt = '{{ $team_b_count}}';
				if(get_player_countt >= team_cnt)
				{
					$.alert({
						title: 'Alert!',
						content: 'All the team players are already added to the scorecard.'
					});
					//alert('Players Exceeded.');
					return false;
				}
				team_b_count++;
				$('#team_b_count').val(team_b_count);
				var i=$('#j').val();
			}
			var newContent = "<tr class='team_"+team+"_goal_row'><td><select  class='gui-input select_player_"+team+"' name='"+team+"_player_"+i+"' id='"+team+"_player_"+i+"'><option value=''>Select Player</option></select></td>"+
					"<td><input type='text' class='gui-input allownumericwithdecimal team_"+team+"_goals' name='"+team+"_goal_"+i+"' /></td>"+
					//"<td><input type='text' class='gui-input allownumericwithdecimal' name='"+team+"_yellow_card_"+i+"' /></td>"+
					//"<td><input type='text' class='gui-input allownumericwithdecimal'  name='"+team+"_red_card_"+i+"' /></td>"+
					"<td><select name='"+team+"_yellow_card_"+i+"' id='"+team+"_yellow_card_"+i+"'><option value='0'>0</option><option value='1'>1</option></select></td>"+
					"<td><select name='"+team+"_red_card_"+i+"' id='"+team+"_red_card_"+i+"'><option value='0'>0</option><option value='1'>1</option></select></td><td></td>"+

					"</tr>";

			$("#team_tr_"+team).append(newContent);
			if(team=='a')//team a
			{
				var player_a_ids = "{{ $match_data[0]['player_a_ids'] }}";
			}
			else//team b
			{
				var player_a_ids = "{{ $match_data[0]['player_b_ids'] }}";
			}

			$.ajax({
				url: "{{URL('match/getplayers')}}",
				type : 'GET',
				data : {'player_a_ids':player_a_ids},
				dataType: 'json',
				success : function(response){
					var options = "<option value=''>Select Player</option>";
					$.each(response, function(key, value) {
						options += "<option value='" + value['id'] + "'>" + value['name'] + "</option>";
					});
					var val = i-1;
					$("#"+team+"_player_"+val).html(options);

				}
			});

			if(team=='a')//team a
			{
				i++;
				$('#i').val(i);
				individual_team_score('a','team_a_goals');

			}else//team
			{
				i++;
				$('#j').val(i);
				individual_team_score('b','team_b_goals');

			}

			checkDuplicatePlayers('select_player_'+team);
			allownumericwithdecimal();


		}
		//check duplicate players selected
		function checkDuplicatePlayers(select_class)
		{
			$('.'+select_class).on('change',function(){
				// Checking Duplicate players
				var pid=[];
				$('.'+select_class).each(function(){
					if(this.value != ''){
						pid.push(this.value);
					}

				});
				b = {};
				for (var i = 0; i < pid.length; i++) {
					b[pid[i]] = pid[i];
				}
				c = [];
				for (var key in b) {
					c.push(key);
				}
				if(pid.length!=c.length){

					//alert("Duplicate Player Selected.");
					$.alert({
						title: 'Alert!',
						content: 'Duplicate Player Selected.'
					});
					$(this).val('');

				}
			});
		}

		function allownumericwithdecimal()
		{
			$(".allownumericwithdecimal").on("keypress keyup blur",function (event) {
				//this.value = this.value.replace(/[^0-9\.]/g,'');
				$(this).val($(this).val().replace(/[^0-9\.]/g,''));
				if (event.which != 08 && (event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
					event.preventDefault();
				}
			});

		}

		// $('.team_a_goals').keyup(function () {
		// 	team_a_goal = 0;
		// 	$('.team_a_goals').each(function() {
		// 		team_a_goal += Number($(this).val());
		// 	});
		// 	$('#team_a_score').html(team_a_goal);
		// });
		// $('.team_b_goals').keyup(function () {
		// 	team_b_goal = 0;
		// 	$('.team_b_goals').each(function() {
		// 		team_b_goal += Number($(this).val());
		// 	});
		// 	$('#team_b_score').html(team_b_goal);
		// });

		function individual_team_score(team,selected_class)
		{
			//individual total team calculattion with player goals_scored
			$('.'+selected_class).keyup(function () {
				// initialize the sum (total price) to zero
				var sum = 0;

				// we use jQuery each() to loop through all the textbox with 'price' class
				// and compute the sum for each loop
				$('.'+selected_class).each(function() {
					sum += Number($(this).val());
				});
				// set the computed value to 'totalPrice' textbox
				$('#team_'+team+'_score').html(sum);

			});
		}

		//delete row
		var deleted_ids = ',';
		function deleteRow(that, del_id, player_id, record_type)
		{
			$.confirm({
				title: 'Confirmation',
				content: "Are you sure you want to delete this Record?",
				confirm: function() {
					$('#delted_ids').val($('#delted_ids').val() + ","+ del_id);
					$('#form_record_'+del_id).remove();
					$('#team_a_row_'+player_id).attr(record_type, '0');
				},
				cancel: function() {
					// nothing to do
				}
			});
		}

		//Send Approval
		function forApproval()
		{
			var winner_id = $('#match_result').val();
			var db_winner_id = "{{$match_data[0]['winner_id']}}";
			var is_tied = "{{$match_data[0]['is_tied']}}";
			 var has_result = "{{$match_data[0]['has_result']}}";
			
			if(winner_id == '' || (db_winner_id == '' && is_tied == 0 && has_result == 1) )
			{
				$.alert({
					title: 'Alert!',
					content: 'Please Click on End Match Save Match Result then Send.'
				});
				return false;
			}
			$.confirm({
				title: 'Confirmation',
				content: 'Are You Sure You want to Send Score for Approval ?',
				confirm: function() {
					match_id = $('#match_id').val();
					$.ajax({
						url: base_url+'/match/scoreCardStatus',
						type: "post",
						data: {'scorecard_status': 'approval_pending','match_id':match_id},
						success: function(data) {
							if(data.status == 'success') {
								// $.alert({
								// title: 'Alert!',
								// content: data.msg
								// });
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


	<script>
		$(document).ready(function(){
			$('#new_records_match').hide();
			$('#display_penalty_players').hide();
			$('#end_match').hide();
			$('.select_player_squad').css({cursor:'pointer', background:'none'});
			$('.not_playing').css({ background:'#f9f9f9', cursor:'text'});

			$('.select_player').css({cursor:'pointer'})
			window.tempSquadData={				
				team_a:{
					playing:[],
					substitute:[]
				},
				team_b:{
					playing:[],
					substitute:[]
				},
				match_id:{{$match_id}},
				team_a_id:{{$team_a_id}},
				team_b_id:{{$team_b_id}},
				team_a_name:'{{$team_a_name}}',
				team_b_name:'{{$team_b_name}}',
				preferences:{
					number_of_quarters:0,
					quarter_time:0,
					max_fouls:0
				},
				tournament_id:'{{$tournament_id}}'
			}



			//console.log(window.tempSquadData);

			Array.prototype.remove = function() {
				var what, a = arguments, L = a.length, ax;
				while (L && this.length) {
					what = a[--L];
					while ((ax = this.indexOf(what)) !== -1) {
						this.splice(ax, 1);
					}
				}
				return this;
			};
			$('.select_player_squad').click(function(){
				var player_id=$(this).attr('player_id');
				var team_type=$(this).attr('team_type');
				var player_type=$(this).attr('player_type');

				if($(this).hasClass('player_selected'))$(this).removeClass('player_selected').children('.icon-check').html("");
				else {
					$(this).addClass('player_selected').children('.icon-check').html("<i class='fa fa-check'></i>");
				}

				if(!$(this).hasClass('choosen')){
					$(this).addClass('choosen')
					if($(this).parents('tr').hasClass('playing_a_'+player_id))$('.substitute_a_'+player_id).fadeOut();
					if($(this).parents('tr').hasClass('playing_b_'+player_id))$('.substitute_b_'+player_id).fadeOut();
					if($(this).parents('tr').hasClass('substitute_a_'+player_id))$('.playing_a_'+player_id).fadeOut();
					if($(this).parents('tr').hasClass('substitute_b_'+player_id))$('.playing_b_'+player_id).fadeOut();
					if(player_type=='playing'){
						tempSquadData[team_type].playing.push(player_id);
						tempSquadData[team_type].substitute.remove(player_id);
					}
					else{
						tempSquadData[team_type].playing.remove(player_id);
						tempSquadData[team_type].substitute.push(player_id);
					}
				}
				else{
					$(this).removeClass('choosen')
					if($(this).parents('tr').hasClass('playing_a_'+player_id))$('.substitute_a_'+player_id).fadeIn();
					if($(this).parents('tr').hasClass('playing_b_'+player_id))$('.substitute_b_'+player_id).fadeIn();
					if($(this).parents('tr').hasClass('substitute_a_'+player_id))$('.playing_a_'+player_id).fadeIn();
					if($(this).parents('tr').hasClass('substitute_b_'+player_id))$('.playing_b_'+player_id).fadeIn();
					if(player_type=='playing'){
						//tempSquadData[team_type].substitute.push(player_id);
						tempSquadData[team_type].playing.remove(player_id);
					}
					else {
						//tempSquadData[team_type].playing.push(player_id);
						tempSquadData[team_type].substitute.remove(player_id);
					}
				}
			})
		})

		function confirmSquad(){
			//get the total players for each team
			var total_players_a=$('#total_players_a').val();
			var total_players_b=$('#total_players_b').val();
			//get the playing players for each team
			var playing_players_a=tempSquadData.team_a.playing.length;
			var playing_players_b=tempSquadData.team_b.playing.length;
			//get the substitute players for each team
			var substitute_players_a=tempSquadData.team_a.substitute.length;
			var substitute_players_b=tempSquadData.team_b.substitute.length;

			if(playing_players_a!=6){
					$.alert({
						title:"Alert",
						content:"Choose six players for Team A"
					})

					return false;
			}

			if(playing_players_b!=6){
					$.alert({
						title:"Alert",
						content:"Choose six players for Team B"
					})

				return false;
			}

			
			//show errors if all players are not choosen

			$.confirm({
				title:"Alert",
				content:"Are you sure you want to save squad?",
				confirm:function(){
					$(this).attr('disabled', true);
					$.ajax({
						url:base_url+'/match/confirmSquadvolleyball',
						data:tempSquadData,
						type:'post',
						success:function(response){
							window.location=window.location;
						},
						error:function(x,y,z){
							$(this).attr('disabled', false);
						}
					})
				},
				cancel:function(){
			
				}
			});



			return false;
		}

		function saveMatchDetails(){
			var data=$('#volleyball').serialize();
			$.ajax({
				url:base_url+'/match/insertAndUpdatevolleyballCard',
				data:data,
				method:'post',
				success:function(response){
					window.location=window.location.pathname;
				},
				error:function(x,y,z){

				}

			})
			return false;
		}



		function getvolleyballDetails(){
			//load details
				var data={
					match_id:$('#match_id').val(),
					team_a_id:{{$team_a_id}},
					team_b_id:{{$team_b_id}}
				}

					$.ajax({
						url:base_url+'/match/getvolleyballDetails',
						method:'get',
						data:data,
						success:function(response){
							$('#match_details').html(response);
						}
					})
		}

		
		function volleyballSwapPlayers(ser_id){
			var data=$('#'+ser_id).serialize();
			$.ajax({
				url:base_url+'/match/volleyballSwapPlayers',
				data:data,
				method:'post',
				success:function(response){					
					window.location=window.location;
				},
				error:function(x,y,z){

				}

			})
			return false;
		}



	</script>






	<script>
	$('#saveButton').hide();

	var player_a_ids = "{{ $match_data[0]['player_a_ids'] }}";
var player_b_ids = "{{ $match_data[0]['player_b_ids'] }}";

var team_a_id={{$team_a_id}};
var team_b_id={{$team_b_id}};

var team_a_name="{{$team_a_name}}";
var team_b_name="{{$team_b_name}}";
var match_id="{{ $match_data[0]['id'] }}";

var manual=false;
		    function enableManualEditing(that){

      if(!manual){
        $.confirm({
            title:"Alert",
            content:"Do you want to enter points manually?",
            confirm:function(){
                $('.tennis_input_new').removeAttr('readonly');
                $('.tennis_input_new').focus();
                $('#real_time_scoring').hide();
                $('#end_match_button').hide();
                $('#saveButton').show();
                manual=true;
            }, 
            cancel:function(){
               
            }
        })
        
      }
      else{
          $.confirm({
            title:"Alert",
            content:"Do you want to enter points automatically?",
            confirm:function(){    

                 $('.tennis_input_new').attr('readonly', 'readonly');
                 $('#real_time_scoring').show();
                 $('#end_match_button').show();
                 $('#saveButton').hide();
                 manual=false;
            }, 
            cancel:function(){
                
            }
        })

          
      }
    }

     function manualScoring(that){
        var data=$('#volleyball').serialize();

        $.ajax({
            url:base_url+"/match/manualScoringvolleyball",
            type:'post',
            data:data,
            success:function(response){
                window.location=window.location;
            }
        })


        return false;
     }


        function endMatch(that){
        var data=$('#endMatchForm').serialize();

        $.ajax({
            url:base_url+"/match/endMatchRecordvolleyball",
            type:'post', 
            data:data,
            success:function(response){
                window.location=window.location;
            }
        })
        return false;
     }



   
    </script>



<script type="text/javascript">
	
	function submitServingPlayers(that){

		var p_arrays=[];
			for(i=1; i<=6; i++){
				var val=$("#serving_a_"+i).val();
			    if(p_arrays.indexOf(val)!=-1){
			    	$.alert({
			    		title:"Alert!",
			    		content:"Please select one player for each number"
			    	})
			  
			    	$("#serving_a_"+i).focus();
			    	return false;
			    }
				p_arrays.push(val);
			}
			p_arrays=[];
			for(i=1; i<=6; i++){
				var val=$("#serving_b_"+i).val();
			    if(p_arrays.indexOf(val)!=-1){
			    	$.alert({
			    		title:"Alert!",
			    		content:"Please select one player for each number"
			    	})
			    	$("#serving_b_"+i).focus();
			    	return false;
			    }
				p_arrays.push(val);
			}

			
			var data=$(that).serialize();
			$.ajax({
				url:'/match/submitServingPlayersVolleyball',
				type:'post',
				data:data,
				success:function(response){
					window.location=window.location;
				},
				error:function(){
					return false;
				}
			})

		return false;
	}
</script>


<script type="text/javascript">
	//highlight team serving
	$('.serve').attr('disabled', 'disabled');

	teamServing({{$serving_array->team_id}}, {{$serving_array->player_id}})

	function teamServing(team_id, player_id){
		$('.serve').attr('disabled', 'disabled');
		$('.serve_'+team_id).removeAttr('disabled');
		$('.server_player').css({'font-weight':'normal'}).css({background:'transparent'})
		$('.player_'+player_id).css({'font-weight':'bolder'}).css({background:'#ffddee'})
		$('#serving_user_id').val(player_id);
			
	}

</script>

<script>
 function addScore(that){
        var team_id=$(that).attr('team_id');
        var val=$(that).attr('value');
        var player_id=$(that).attr('#serving_user_id');    

          data={
              team_id:team_id,
              player_id:player_id,              
              match_id:match_id,
              action:'add',
              val:val
              }

                    $.ajax({
                        url:'/match/volleyballAddScore',
                        data:data,
                        method:'post',
                        dataType:'json',
                        success:function(response){
                          var left_team_id={{$team_a_id}};
                          var right_team_id={{$team_b_id}}

                            $.each(response.match_details, function(key, value){

                                $('.a_'+key).val(value[left_team_id+'_score']);
                                $('.b_'+key).val(value[right_team_id+'_score']);
                            })

                          

              $('#team_a_score').html(response.scores[left_team_id+"_score"] + ' sets');
              $('#team_b_score').html(response.scores[right_team_id+"_score"] + ' sets');

              	teamServing(response.server.team_id, response.server.player_id)


                        }

                       });
                  return false;
              } 
       </script>

@endsection