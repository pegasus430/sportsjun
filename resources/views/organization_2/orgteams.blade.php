@extends('layouts.organisation')

@section('content')

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
					<div class="border-box">
						<div class="col-md-2 col-sm-3 col-xs-12 text-center">
							<div class="glyphicon-lg default-img"></div>
							<!--                                    <img data-original="http://www.sportsjun.com/uploads/tournaments/jlXE2OrNeNlDqciAAbAT.png" src="http://www.sportsjun.com/uploads/tournaments/jlXE2OrNeNlDqciAAbAT.png" title="" onerror="this.onerror=null;this.src=&quot;http://www.sportsjun.com/images/default-profile-pic.jpg&quot;" height="90" width="90" class="img-circle img-border img-scale-down img-responsive lazy" style="display: block;"> --></div>
						<div class="col-md-10 col-sm-9 col-xs-12">
							<div class="t_tltle">
								<h4><a   href="{{ url('/team/members').'/'.(!empty($t->id)?$t->id:0) }}">{{ !empty($t->teamname)?$t->teamname:'' }}</a></h4>
								<p class="label label-default">By <a target="_blank"                                         
                                                    href="{{ url('/editsportprofile/'.(!empty($t->team_owner_id)?$t->team_owner_id:0))}}"
                                                 
                                >{{  !empty($t->name)?$t->name:'' }}</a></p>
							</div>
							<hr>
							<div class="clearfix"></div>							
							<p>Owner's Name: <strong>{{ $t->ownersName }} </strong></p>
							<p>Manager Name: <strong> {{ $t->managerName }} </strong></p>
							<p>Coach: <strong> {{ $t->coachsName }}</strong></p>
							  <p>Sport : <strong class='blue match_type_text'>{{Helper::getSportName($t->sports_id)}}</strong> &nbsp;
                        &nbsp; Players : <strong
                                class='blue match_type_text'> {{Helper::getTeamDetails($t->id)->teamplayers->count()}}  </strong>&nbsp;
                        &nbsp; Group : <strong class='blue match_type_text'>

                    @foreach(Helper::getTeamDetails($t->id)->organizationGroups as $og)
                                {{$og->name}},
                            @endforeach
                            </strong>
							<div class="action-bar">
								<button class="btn btn-sm btn-mini btn-edit" href="javascript:void(0);"><i class="fa fa-pencil"></i></button>
								<button class="btn btn-sm btn-mini btn-danger" href="javascript:void(0);"><i class="fa fa-remove"></i></button>
								<button class="btn btn-sm btn-secondary" href="javascript:void(0);"><i class="fa fa-exchange"></i> Transfer Ownership </button>
							</div>
						</div>
					</div>				
			@endforeach
				</div>
			</div>
		</div>

		@include('organization_2.groups.partials.create_team')

@stop