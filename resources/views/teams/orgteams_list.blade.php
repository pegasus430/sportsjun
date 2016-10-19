@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@elseif (session('error_msg'))
    <div class="alert alert-danger">
        {{ session('error_msg')  }}

    </div>
@endif

@foreach($teams as $t)
    <div class="t_details" style="min-height: inherit;">
        <div class="row main_tour">
            <div id="searchresultsDiv">
                <div class="col-sm-2 text-center">
                    {!! Helper::Images((!empty($t->logo)?$t->logo:''),'teams',array('class'=>'img-circle img-border img-scale-down img-responsive','height'=>90,'width'=>90) )!!}
                </div>
                <div class="col-sm-10">
                    <div class="t_tltle">
                        <div class="pull-left">
                            <a href="{{ url('/team/members').'/'.(!empty($t->id)?$t->id:0) }}">{{ !empty($t->teamname)?$t->teamname:'' }}</a>
                            <p class="t_by">By <a target="_blank"
                                                  href="{{ url('/editsportprofile/'.(!empty($t->team_owner_id)?$t->team_owner_id:0))}}">{{  !empty($t->name)?$t->name:'' }}</a>
                            </p>
                        </div>
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
                        <div class="col-xs-4 teams-teamplayers">
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
                    @if(isset($userId) && ($userId == $t->team_owner_id  || $organization->user_id = $userId  || in_array($userId,$manager_ids)))
                        <div class="pull-right">
                            <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#transfer-owner-modal" data-team-id="{{$t->id}}"><i class="fa fa-exchange"></i> Transfer ownership</button>
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

