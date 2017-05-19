@extends('layouts.organisation')

@section('content')

<?php $is_widget = false;?>

<div class="container cards-row">
			<div class="row">
				<div class="col-md-12">
					<h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> {{$group?$group->name.' (Teams)': 'All Teams'}}</h2>
					<div class="create-btn-link"><a href="/organization/{{$organisation->id}}/create_team" class="wg-cnlink" >Create New Team</a></div>
				</div>
			</div>
			<div class="row">
			@if($group)
				    <div class="col-sm-4 col-md-3">
                    <div class="thumbnail"><img src="/uploads/org/groups/logo/{{$group->logo }}" alt="">
                        <div class="caption">
                            <a href="{{route('organizationTeamlist',['id'=>$id, 'group'=>$group->id])}}"> <h3>{{$group->name}}</h3> </a>
                            <ul class="card-description">
                                <li><strong>No. of Teams:</strong> {{$group->teams->count()}}</li>
                                <li><strong>Manager:</strong> {{$group->manager->name}}</li>
                            </ul>
                        </div>
                    </div>
                </div>       
			@endif

				<div class="col-md-8 col-md-offset-1">
					@foreach($teams as $t)
    <div class="t_details" style="min-height: inherit;">
        <div class="row main_tour">
            <div id="searchresultsDiv">
                <div class="col-sm-2 text-center">
                    {!! Helper::Images((!empty($t->logo)?$t->logo:''),'teams',array('class'=>'img-circle img-border img-scale-down img-responsive','height'=>90,'width'=>90) )!!}
                </div>
                <div class="col-sm-10">
                    <div class="t_tltle">
                        <div class="pull-left"><a
                                    @if (!$is_widget)
                                        href="{{ url('/team/members').'/'.(!empty($t->id)?$t->id:0) }}"
                                    @else
                                        href="#" style="pointer-events:none;text-decoration: none"
                                        <?php /* href="{{ route('widget.team.info', (!empty($t->id)?$t->id:0))}}" */ ?>
                                    @endif
                            >{{ !empty($t->teamname)?$t->teamname:'' }}</a>

                            <p class="t_by">By <a target="_blank"
                                                  @if (!$is_widget)
                                                    href="{{ url('/editsportprofile/'.(!empty($t->team_owner_id)?$t->team_owner_id:0))}}"
                                                  @else
                                                  href="#"  style="pointer-events:none;text-decoration: none"
                                                  <?php /*  href="{{ route('widget.member.info', (!empty($t->team_owner_id)?$t->team_owner_id:0))}}" */ ?>
                                                  @endif
                                >{{  !empty($t->name)?$t->name:'' }}</a>
                            </p>
                        </div>
                        @if (!$is_widget)
                            @if(isset($userId) && ($userId == $t->team_owner_id))
                                <div class="pull-right ed-btn">
                                    <a href="{{ url('/team/edit/'.(!empty($t->id)?$t->id:0))}}" class="edit"><i
                                                class="fa fa-pencil"></i></a>


                                    <a href="{{ url('/team/deleteteam/'.(!empty($t->id)?$t->id:0)).'/'.(empty($t->isactive)?'a':'d')}}"
                                       class="delete" title="{{empty($t->isactive)?'Activate':'Deactivate'}}"
                                       data-toggle="tooltip" data-placement="top">
                                        {!! empty($t->isactive)?"<i class='fa fa-check'></i>":"<i class='fa fa-ban'></i>" !!}</a>
                                </div>
                            @endif
                        @endif
                        <div class="col-xs-8 teams-teamplayers">
                            <p><b>Owner's name:</b> {{ $t->ownersName }} </p>
                            <p><b>Manager name:</b> {{ $t->managersName }} </p>
                            <p><b>Coach:</b> {{ $t->coachsName }} </p>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <p class="lt-grey">{{ !empty($t->description)?$t->description:'' }}</p>
                    <br>
                    <?php
                         $manager_ids = $t->playersByRole(\App\Model\TeamPlayers::$ROLE_MANAGER)->lists('id')->all();
                    ?>
                    @if(!$is_widget && isset($userId) && ($userId == $t->team_owner_id  || $organization->user_id = $userId  || in_array($userId,$manager_ids)))
                        <div class="pull-right">
                            <button type="button" class="btn btn-info btn-block" data-toggle="modal"
                                    data-target="#transfer-owner-modal" data-team-id="{{$t->id}}"><i
                                        class="fa fa-exchange"></i> Transfer ownership
                            </button>
                        </div>
                    @endif
                    <p>Sport : <span class='blue match_type_text'>{{Helper::getSportName($t->sports_id)}}</span> &nbsp;
                        &nbsp; Players : <span
                                class='blue match_type_text'> {{Helper::getTeamDetails($t->id)->teamplayers->count()}}  </span>&nbsp;
                        &nbsp; Group : <span class='blue match_type_text'>

                    @foreach(Helper::getTeamDetails($t->id)->organizationGroups as $og)
                                {{$og->name}},
                            @endforeach
                    </span>

                </div>

            </div>
        </div>
    </div>
    @include('teams.modal.change_ownership')
@endforeach

				</div>
			</div>
		</div>

		@include('organization_2.groups.partials.create_team')

@stop