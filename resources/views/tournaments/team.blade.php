@extends(Helper::check_if_org_template_enabled()?'layouts.organisation':'layouts.app') 
@section('content')
{{-- @include ('tournaments._leftmenu') --}}
<div class="col-lg-8 col-md-10 col-sm-12 col-md-offset-1 col-lg-offset-2 tournament_profile teamslist-pg" style="padding-top: 3px !important;">
        <div class="panel panel-default">
				
				@if (Session::has('message'))
    				<div class="alert alert-info">{{ Session::get('message') }}</div>
                @endif
            <!-- /.panel-heading -->
           
          @if($errors->any())
            <h4 class="error_validation">{{$errors->first()}}</h4>
          @endif

            <div class="panel-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified">
                    <li class="active"><a href="#managedtournaments" data-toggle="tab" aria-expanded="true">Managed Tournaments <span class="t_badge"> {{ count($manageTeamArray) }} </span></a></li>
                    <li class=""><a href="#joinedtournaments" data-toggle="tab" aria-expanded="false">Joined Tournaments <span class="t_badge"> {{ count($joinTeamArray) }} </span></a></li>
                    <li class=""><a href="#followingtournaments" data-toggle="tab" aria-expanded="false">Following Tournaments  <span class="t_badge"> {{ count($followingTeamArray) }} </span></a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="managedtournaments">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                @if(count($manageTeamArray))
                                @foreach($manageTeamArray as $mangkey => $managedTeam)
                                    <div class="t_details">
                                        <div class="row main_tour">
                                            <div class="col-md-2 col-sm-3 col-xs-12 text-center">
                                              <!--<img class="img-circle img-border" src="http://localhost/sportsjun/public/images/sunrisers_hyd.png" style="width: 90%;height:90%;">-->
                                                <!--<img class="img-circle img-border" src="{{ asset('/uploads/'.config('constants.PHOTO_PATH.TOURNAMENT').'/'.$managedTeam['logo']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" style="width: 90%;height:90%;">-->

						{!! Helper::Images($managedTeam['logo'],config('constants.PHOTO_PATH.TOURNAMENT'),array('height'=>90,'width'=>90,'class'=>'img-circle img-border img-scale-down') )!!}
                                            </div>
                                            <div class="col-md-10 col-sm-9 col-xs-12">
                                                    <div class="t_tltle">
                                                    	<div class="pull-left">
                                                        	{{ $managedTeam['name'] }}
                                                        </div>
                                                        <div class="pull-right ed-btn">
                                                            @if($loginUserId==$managedTeam['owner_id'] || $loginUserId==$managedTeam['manager_id'])
                                                            <a href="{{ route('tournaments.edit',[$managedTeam['id']])}}" class="edit" title="Edit" data-toggle="tooltip" data-placement="top"><i class="fa fa-pencil"></i></a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <ul class="t_tags">
                                                        <li>Created By: <span class="green"><a target="_blank" href="{{ url('/editsportprofile/'.$managedTeam['created_by'])}}">{{ $managedTeam['createdUserName'] }}</a></span></li>
                                                        <li>Owner: <span class="green"><a target="_blank" href="{{ url('/editsportprofile/'.$managedTeam['owner_id'])}}">{{ $managedTeam['ownerUserName'] }}</a></span></li>
                                                        <li>Manager: <span class="green"><a target="_blank" href="{{ url('/editsportprofile/'.$managedTeam['manager_id'])}}">{{ $managedTeam['managerUserName'] }}</a></span></li>
                                                    </ul>
                                                    <p class="lt-grey">{{ $managedTeam['description'] }}</p>
													@if((!empty($managedTeam[$managedTeam['id']]) && count($managedTeam[$managedTeam['id']])>0))
                                                    <a href="#" class="show_sub_tournament" parent_tour_id = "{{$managedTeam['id']}}">Tournament events: {{ (!empty($managedTeam[$managedTeam['id']])?count($managedTeam[$managedTeam['id']]):0) }}</a>
													@endif
                                            </div>
                                        </div>
										
					<!--Sub Tournament Display Start-->					
					<div id="subtournament_{{$managedTeam['id']}}" class="row" style="display:none;">

									@if(!empty($managedTeam[$managedTeam['id']]) && count($managedTeam[$managedTeam['id']])>0)
										@foreach($managedTeam[$managedTeam['id']] as $subTour)
										

                                        <div class="sub_tour clearfix">
                                            <div class="col-md-2 col-sm-3 col-xs-12 text-center">


						{!! Helper::Images($subTour['sports_logo'],config('constants.PHOTO_PATH.SPORTS'),array('height'=>90,'width'=>90,'class'=>'img-circle img-border img-scale-down') )!!}
                                            </div>
                                            <div class="col-md-10 col-sm-9 col-xs-12">
                                                    <div class="t_tltle">
                                                    	<div class="pull-left">
                                                        	<a href="{{ url('tournaments/groups').'/'.$subTour['id'] }}">{{ $subTour['name'] }}</a>
                                                        </div>
                                                        <div class="pull-right ed-btn">
                                                        	<a href="javascript:void(0);" class="schedule_match_main edit" onclick="subTournamentEdit({{$subTour['id']}})"><i class="fa fa-pencil"></i></a>
                                                        </div>
                                                    </div>
                                                    <ul class="t_tags">
                                                        <li>Created By: <span class="green"><a target="_blank" href="{{ url('/editsportprofile/'.$subTour['created_by'])}}">{{ $subTour['user_name'] }}</a></span></li>
                                                        <li>Sport: <span class="green">{{ $subTour['sports_name'] }}</span></li>
                                                        <li>Teams: <span class="green">{{ $subTour['team_count'] }}</span></li>
                                                    </ul>
                                                    <p class="lt-grey">{{ $subTour['description'] }}</p>
                                                </div>
                                            </div>
											
										@endforeach
									@endif
									</div>
					<!--Sub Tournament Display End-->						
                                    </div>
                                    

                                @endforeach
                                 @else
                                <div class="message_new_for_team">Organizing Tournaments made easy.</div>
                                <div class="intro_list_container">
                                        <ul class="intro_list_on_empty_pages">
                                                <span class="steps_to_follow">Steps to follow:</span>
                                                <li>Click on the <span class="bold">Create New +</span> button on the top left side, select <span class="bold">Tournament.</span></li>
                                                <li><span class="bold">Firstly,</span> fill the high level tournament details. <span class="bold">Secondly,</span> provide tournament events details.</li>
                                                <li>Post Tournaments for Teams / Players to join</li>
                                        </ul>
                                </div>
                                @endif
                                </td>
                                 </tr>
                            </tbody>
                        </table>
                        <script>
                        	$(document).ready(function(){
								$(".show_sub_tournament").click(function(){
									parent_id = $(this).attr('parent_tour_id');
									$("#subtournament_"+parent_id).slideToggle("1500");
								});
							});
                        </script>
                    </div>
                  



                    <div class="tab-pane fade" id="joinedtournaments">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                @if(count($joinTeamArray))
                                @foreach($joinTeamArray as $joinkey => $joinedTeam)
                                                <div class="t_details">
                                        <div class="row main_tour">
                                            <div class="col-md-2 col-sm-3 col-xs-12 text-center">
                                              <!--<img class="img-circle img-border" src="http://localhost/sportsjun/public/images/sunrisers_hyd.png" style="width: 90%;height:90%;">-->
                                               <!-- <img class="img-circle img-border" src="{{ asset('/uploads/'.config('constants.PHOTO_PATH.TOURNAMENT').'/'.$joinedTeam['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" style="width: 90%;height:90%;">-->
											   	{!! Helper::Images($joinedTeam['url'],config('constants.PHOTO_PATH.TOURNAMENT'),array('height'=>90,'width'=>90,'class'=>'img-circle img-border img-scale-down') )!!}
                                            </div>
                                            <div class="col-md-10 col-sm-9 col-xs-12">
                                                    <div class="t_tltle">
                                                        <a href="{{ url('tournaments/groups').'/'.$joinedTeam['id'] }}">{{ $joinedTeam['name'] }}</a>
                                                        <p class="t_by">By {{ $joinedTeam['user_name'] }}</p>
                                                    </div>
                                                    <ul class="t_tags">
                                                        <li>Sports <span class="green">{{ $joinedTeam['sports_name'] }}</span></li>
                                                        <li>Teams <span class="green">{{ $joinedTeam['team_count'] }}</span></li>
                                                    </ul>
                                                    <p class="lt-grey">{{ $joinedTeam['description'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                @endforeach
                                 @else
                                <div class="message_new_for_team"> Find and Join Tournaments easily.</div>
                                <div class="intro_list_container">
                                        <ul class="intro_list_on_empty_pages">
                                                <span class="steps_to_follow">Steps to follow:</span>
                                                <li><span class="bold">Search</span> for Tournaments in any locality in any sport</li>
                                                <li>Click <span class="bold">Join,</span> your request will be sent to the Tournament Organizer</li>
                                                <li>Once Request is accepted, you are part of the Tournament.</li>
                                        </ul>
                                </div>
                                    @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>




                    <div class="tab-pane fade" id="followingtournaments">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                @if(count($followingTeamArray))
                                @foreach($followingTeamArray as $followkey => $followedTeam)
                                                <div class="t_details">
                                        <div class="row main_tour">
                                            <div class="col-xs-2 text-center">
                                              <!--<img class="img-circle img-border" src="http://localhost/sportsjun/public/images/sunrisers_hyd.png" style="width: 90%;height:90%;">-->
                                              <!--  <img class="img-circle img-border" src="{{ asset('/uploads/'.config('constants.PHOTO_PATH.TOURNAMENT').'/'.$followedTeam['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" style="width: 90%;height:90%;">-->
											  	{!! Helper::Images($followedTeam['url'],config('constants.PHOTO_PATH.TOURNAMENT'),array('height'=>90,'width'=>90,'class'=>'img-circle img-border img-scale-down img-responsive') )!!}

                                            </div>
                                            <div class="col-xs-10">
                                                    <p class="t_tltle">
                                                        <a href="{{ url('tournaments/groups').'/'.$followedTeam['id'] }}">{{ $followedTeam['name'] }}</a>
                                                        <p class="t_by">By {{ $followedTeam['user_name'] }}</p>
                                                    </p>
                                                    <ul class="t_tags">
                                                        <li>Sports <span class="green">{{ $followedTeam['sports_name'] }}</span></li>
                                                        <li>Teams <span class="green">{{ $followedTeam['team_count'] }}</span></li>
                                                    </ul>
                                                    <p class="lt-grey">{{ $followedTeam['description'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                @endforeach
                                 @else
                                <div class="message_new_for_team">Search for Tournaments and Follow easily.</div>
                                <div class="intro_list_container">
                                        <ul class="intro_list_on_empty_pages">
                                                <span class="steps_to_follow">Steps to follow:</span>
                                                <li><span class="bold">Search</span> for Tournaments in any sport</li>
                                                <li>Click <span class="bold">Follow</span></li>
                                                <li>Now, you can easily follow all your favorite Tournaments from this section</li>
                                        </ul>
                                </div>
                                    @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <!-- /.panel-body -->
        </div>
</div>
<div id="displaytournament"></div>

@endsection
