@extends(Helper::check_if_org_template_enabled()?'layouts.organisation':'layouts.app') 
@section('content')
@if(is_numeric(Request::segment(3)) || Request::has('from_org'))
    @include ('album._leftmenu')
@endif
<?php if(is_numeric(Request::segment(3))) {?>
<div id="content" class="col-sm-10" style="height: 610px; background: rgb(255, 255, 255);">
    <div class="col-sm-12 tournament_profile" style="padding-top: 3px !important;">
<?php } else { ?>
    <div class="col-lg-8 col-md-10 col-sm-12 col-md-offset-1 col-lg-offset-2 tournament_profile teamslist-pg" style="padding-top: 3px !important;">
<?php }?>
        <div class="panel panel-default">
                @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                @elseif (session('error_msg'))
                <div class="alert alert-danger">
                    {{ session('error_msg') }}
                </div>
                @endif
            <!-- /.panel-heading -->
            <div class="panel-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified" id="team_ul">
                    <li class="" id="mgt_tab"><a href="#managedteams" data-toggle="tab" aria-expanded="true">{{ trans('message.users.fields.managedteams') }}<span class="t_badge">{{ count($manageTeamArray) }}</span></a>                    </li>
                    <li class="active" id="jnd_tab"><a href="#joinedteams" data-toggle="tab" aria-expanded="false">{{ trans('message.users.fields.joinedteams') }}<span class="t_badge">{{ count($joinTeamArray) }}</span></a>                    </li>
                    <li class="" id="foll_tab"><a href="#followingteam" data-toggle="tab" aria-expanded="false">{{ trans('message.users.fields.followingteam') }}<span class="t_badge">{{ count($followingTeamArray) }}</span></a>                    </li>
                </ul>
                <div class="tab-content">
                            <div class="tab-pane fade" id="managedteams">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                @if(count($manageTeamArray))
                                                @foreach($manageTeamArray as $managed_team)
                                            	<div class="t_details">
                                                <div class="row main_tour">
                                                    <div class="col-md-2 col-sm-3 col-xs-12 text-center">
                                                        @if(count($managed_team['photos']))
                                                        @foreach($managed_team['photos'] as $p)
                                                         <!--  <img class="img-circle img-border" src="{{ asset('/uploads/'.config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH').'/'.(!empty($p['url'])?$p['url']:'')) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" style="width: 90%;height:90%;">-->


        													  {!! Helper::Images((!empty($p['url'])?$p['url']:''),config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border img-scale-down','height'=>90,'width'=>90) )!!}

                                                        @endforeach
                                                        @else
                                                            <!--<img class="img-circle img-border" src="{{ asset('/images/default-profile-pic.jpg') }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" style="width: 90%;height:90%;">-->
        												  {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border  img-scale-down','height'=>90,'width'=>90) )!!}

                                                        @endif
                                                    </div>
                                                    <div class="col-md-10 col-sm-9 col-xs-12">
                                                    	<div class="sm-center">
                                                            <div class="t_tltle">
                                                                <div class="pull-left">
                                                                    <a href="{{ url('/team/members').'/'.(!empty($managed_team['id'])?$managed_team['id']:0) }}">{{ !empty($managed_team['name'])?$managed_team['name']:'' }}</a>
                                                                    <p class="t_by">By <a target="_blank" href="{{ url('/editsportprofile/'.(!empty($managed_team['team_owner_id'])?$managed_team['team_owner_id']:0))}}">{{ !empty($managed_team['user']['name'])?$managed_team['user']['name']:'' }}</a></p>
                                                                </div>
                                                                @if(isset($userId) && ($userId == Auth::user()->id))
                                                                <div class="pull-right ed-btn">
                                                                    <a href="{{ url('/team/edit/'.(!empty($managed_team['id'])?$managed_team['id']:0))}}" class="edit" title="Edit" data-toggle="tooltip" data-placement="top"><i class="fa fa-pencil"></i></a>
                                                                    <a href="{{ url('/team/deleteteam/'.(!empty($managed_team['id'])?$managed_team['id']:0)).'/'.(empty($managed_team['isactive'])?'a':'d')}}" class="delete" title="{{empty($managed_team['isactive'])?'Activate':'Deactivate'}}" data-toggle="tooltip" data-placement="top">
                                                                        {!! empty($managed_team['isactive'])?"<i class='fa fa-check'></i>":"<i class='fa fa-ban'></i>" !!}</a>
                                                                </div>
                                                                @endif
                                                            </div>
                                                            <ul class="t_tags">
                                                                <li>Sport: <span>{{ !empty($managed_team['sports']['sports_name'])?$managed_team['sports']['sports_name']:'' }}</span></li>
                                                                <li>Players: <span>{{ !empty($managed_team['teamplayers'])?count($managed_team['teamplayers']):0 }}</span></li>
                                                            </ul>
                                                            <div class="clearfix"></div>
                                                            <p class="lt-grey">{{ !empty($managed_team['description'])?$managed_team['description']:'' }}</p>                                                             </div>
                                                    </div>
                                                </div>
                                                </div>

                                                @endforeach
                                              	@else
						<div class="message_new_for_team">Create your Team(s), Search and Schedule matches with other teams.</div>
                                                <div class="intro_list_container">
                                                        <ul class="intro_list_on_empty_pages">
                                                                <span class="steps_to_follow">Steps to follow:</span>
                                                                <li>Click on the <span class="bold">Create +</span> button on the top left side, select <span class="bold">Team</span></li>
                                                                <li>Fill all the details and select <span class="bold">Organization</span> (if needed) from drop-down and <span class="bold">Create</span></li>
                                                                <li>Now, search for other Teams and schedule matches easily</li>
                                                        </ul>
                                                </div>
                                              @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade active in" id="joinedteams">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>

                                               @if(count($joinTeamArray))
                                               @foreach($joinTeamArray as $joined_team)
                                            	<div class="t_details">
                                                <div class="row main_tour">
                                                    <div class="col-md-2 col-sm-3 col-xs-12 text-center">
                                                        @if(count($joined_team['photos']))
                                                        @foreach($joined_team['photos'] as $p)
                                                          <!--  <img class="img-circle img-border" src="{{ asset('/uploads/'.config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH').'/'.(!empty($p['url'])?$p['url']:'')) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" style="width: 90%;height:90%;">-->
        												    {!! Helper::Images((!empty($p['url'])?$p['url']:''),config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border img-scale-down','height'=>90,'width'=>90) )!!}
                                                        @endforeach
                                                        @else
                                                          <!--  <img class="img-circle img-border" src="{{ asset('/images/default-profile-pic.jpg') }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" style="width: 90%;height:90%;">-->
        											    {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border  img-scale-down','height'=>90,'width'=>90) )!!}
                                                        @endif
                                                    </div>
                                                    <div class="col-md-10 col-sm-9 col-xs-12">
	                                                    <div class="sm-center">
                                                            <div class="t_tltle">
                                                                <a href="{{ url('/team/members').'/'.(!empty($joined_team['id'])?$joined_team['id']:0) }}">{{ !empty($joined_team['name'])?$joined_team['name']:'' }}</a>
                                                                <p class="t_by">By <a target="_blank" href="{{ url('/editsportprofile/'.(!empty($joined_team['team_owner_id'])?$joined_team['team_owner_id']:0))}}">{{ !empty($joined_team['user']['name'])?$joined_team['user']['name']:'' }}</a></p>
                                                            </div>
                                                            <ul class="t_tags">
                                                                <li>Sport: <span>{{ !empty($joined_team['sports']['sports_name'])?$joined_team['sports']['sports_name']:'' }}</span></li>
                                                                <li>Players: <span>{{ !empty($joined_team['teamplayers'])?count($joined_team['teamplayers']):0 }}</span></li>
                                                            </ul>
                                                            <p class="lt-grey">{{ !empty($joined_team['description'])?$joined_team['description']:'' }}</p>                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                                @endforeach
                                                @else
                                                <div class="message_new_for_team">Search for Teams in your locality and Join easily.</div>
                                                <div class="intro_list_container">
                                                        <ul class="intro_list_on_empty_pages">
                                                                <span class="steps_to_follow">Steps to follow:</span>
                                                                <li><span class="bold">Search</span> for Teams in any locality in any sport</li>
                                                                <li>Click <span class="bold">Join,</span> your request will be sent to the Team Management</li>
                                                                <li>Once Request is accepted, you are part of the Team</li>
                                                        </ul>
                                                </div>
                                              @endif

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="followingteam">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                           @if(count($followingTeamArray))
                                           @foreach($followingTeamArray as $following_team)
                                            <div class="t_details">
                                                <div class="row main_tour">
                                                    <div class="col-md-2 col-sm-3 col-xs-12 text-center">
                                                        @if(count($following_team['photos']))
                                                        @foreach($following_team['photos'] as $p)
                                                         <!--   <img class="img-circle img-border" src="{{ asset('/uploads/'.config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH').'/'.(!empty($p['url'])?$p['url']:'')) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" style="width: 90%;height:90%;">-->
        										   {!! Helper::Images((!empty($p['url'])?$p['url']:''),config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border  img-scale-down','height'=>90,'width'=>90) )!!}

                                                        @endforeach
                                                        @else
                                                           <!-- <img class="img-circle img-border" src="{{ asset('/images/default-profile-pic.jpg') }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" style="width: 90%;height:90%;">-->
        											     {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border  img-scale-down','height'=>90,'width'=>90) )!!}
                                                        @endif
                                                    </div>
                                                    <div class="col-md-10 col-sm-9 col-xs-12">
                                                    	<div class="sm-center">
                                                            <div class="t_tltle">
                                                                <a href="{{ url('/team/members').'/'.(!empty($following_team['id'])?$following_team['id']:0) }}">{{ !empty($following_team['name'])?$following_team['name']:'' }}</a>
                                                                <p class="t_by">By <a target="_blank" href="{{ url('/editsportprofile/'.(!empty($following_team['team_owner_id'])?$following_team['team_owner_id']:0))}}">{{ !empty($following_team['user']['name'])?$following_team['user']['name']:'' }}</a></p>
                                                            </div>
                                                            <ul class="t_tags">
                                                                <li>Sport: <span>{{ !empty($following_team['sports']['sports_name'])?$following_team['sports']['sports_name']:'' }}</span></li>
                                                                <li>Players: <span>{{ !empty($following_team['teamplayers'])?count($following_team['teamplayers']):0 }}</span></li>
                                                            </ul>
                                                            <p class="lt-grey">{{ !empty($following_team['description'])?$following_team['description']:'' }}</p>                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                            @endforeach

                                                @else
                                                <div class="message_new_for_team">Search for Teams and Follow easily.</div>
                                                <div class="intro_list_container">
                                                        <ul class="intro_list_on_empty_pages">
                                                                <span class="steps_to_follow">Steps to follow:</span>
                                                                <li><span class="bold">Search</span> for Teams in any sport</li>
                                                                <li>Click <span class="bold">Follow</span></li>
                                                                <li>Now, you can easily follow all your favorite teams from this section</li>
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
<?php if($userId != Auth::user()->id) {?>
</div>
<?php } ?>
<script type="text/javascript">
    $(document).ready(function() {
    <?php if(session('div_sel_mgt')) { ?>
        $('.nav-tabs a[href="#managedteams"]').tab("show");
    <?php }?>
    <?php if(session('div_sel_org')) { ?>
        $('.nav-tabs a[href="#organization"]').tab("show");
    <?php }?>
    });
</script>
@endsection
