@extends(Auth::user() ? 'layouts.app' : 'home.layout')
@section('content')
    @include ('album._leftmenu')
    <div id="content-team" class="col-sm-10">
        @include('sportprofile.share')


        <div class="col-sm-9 tournament_profile" id='content_to_share'>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @if(count($userSports))
                <?php $i=0;
                $j=0;
                ?>
                <div class="displaymessage sports_profile_msg_arrow_down">
                    {{ trans('message.sports.addsportsprofile') }}
                </div>
                <input type="hidden" id="user_question" value="2">
                <input type="hidden" id="userSportCount" value="{{count($userSports)}}">
                <div class="sports_profile pdspc">
                    <div class="panel panel-default sportsprofile-tabs">
                        <div class="panel-body" style="position: relative;">
                            <ul class="nav nav-tabs sptabs-top">
                                @foreach($userSports as $userSport)

                                    <?php
                                    if($i==0) {
                                        $activeClass='active';
                                        $ariaExpanded='true';
                                    }else{
                                        $activeClass='';
                                        $ariaExpanded='false';
                                    }
                                    ?>
                                    <li class="<?php echo $activeClass;?>">
                                        <a href="#addplayer_{{$userSport->id}}" data-toggle="tab" aria-expanded="<?php echo $ariaExpanded?>"
                                           onclick="displaySportQuestions('unfollow',{{$userSport->id}},{{$userId}},'{{$userSport->sports_name}}');">{{$userSport->sports_name}}</a>
                                        @if($userId==isset(Auth::user()->id)?Auth::user()->id:0)
                                            <span class="btn-tooltip" data-toggle="tooltip" data-placement="top" title="Remove {{$userSport->sports_name}}" onclick="removeUserStats('false',{{$userSport->id}},{{ isset(Auth::user()->id)?Auth::user()->id:0 }},'follow');"><i class="fa fa-remove"></i></span>
                                        @endif
                                    </li>
                                    <?php $i++;?>
                                @endforeach

                                @if(count($sports) && $userId==(isset(Auth::user()->id)?Auth::user()->id:0))
                                    <li class=dropdown id="unfollowedSportsLi">
                                        <a href=# id=myTabDrop1 class=dropdown-toggle data-toggle=dropdown aria-controls=myTabDrop1-contents><i class="fa fa-plus-circle"></i></a>
                                        <ul class=dropdown-menu aria-labelledby=myTabDrop1 id=myTabDrop1-contents>
                                            @foreach($sports as $sport)
                                                <li><a href="javascript:void(0)" onclick="appendTabElement('follow',{{$sport->id}},{{$userId}},'{{$sport->sports_name}}');" role=tab id="sport_name_{{$sport->id}}" data-toggle=tab aria-controls=dropdown1>{{$sport->sports_name}}</a>
                                                </li>
                                                <div class="tab-pane fade" id="addplayer_{{$sport->id}}" style="display: none;">
                                                    <div class="sportsjun-forms custom_form" id='sportsjun_forms_{{$sport->id}}'>
                                                        {!! Form::open(array('route' => array('sport.update',$userId),'class'=>'form-horizontal','method' => 'put')) !!}
                                                        <div class="form-body">
                                                            <div id='question_div_{{$sport->id}}' class="question_div_class"></div>
                                                        </div>
                                                        {!! Form::close() !!}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="tab-content" id="addPlayerDiv" style="margin-top: 0px;">
                            @foreach($userSports as $userSport)
                                <?php
                                if($j==0) {
                                    $fadeInClass='active in';
                                }else{
                                    $fadeInClass='';
                                }
                                ?>
                                <div class="tab-pane fade <?php echo $fadeInClass;?>" id="addplayer_{{$userSport->id}}">
                                    <div class="sportsjun-forms custom_form" id='sportsjun_forms_{{$userSport->id}}'>
                                        {!! Form::open(array('route' => array('sport.update',$userId),'class'=>'form-horizontal','method' => 'put')) !!}
                                        <div class="form-body" style="padding: 0 15px;">
                                            @if(!$selfProfile && Auth::user())
                                                <div class="sj_actions_new">
                                                    <div class="sb_join_p_main">
                                                        <a href="javascript:void(0);" onclick="SJ.TEAM.addToTeam({{$userSport->id}},{{$userId}});" class="sj_add_but">
                                                            <span><i class="fa fa-plus"></i>Add To Team</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                            <div id='question_div_{{$userSport->id}}' class="question_div_class"></div>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                                <?php $j++;?>
                            @endforeach
                        </div>
                    </div>
                </div>
                @include ('widgets.teamspopup')
            @else
                @if (count($sports))
                    @foreach($sports as $sport)
                        <a id="sport_name_{{$sport->id}}" class="btn btn-success" href="javascript:void(0)" onclick="displaySportQuestions('follow',{{$sport->id}},{{$userId}},'{{$sport->sports_name}}');">{{$sport->sports_name}}</a>
                    @endforeach
                    <input type="hidden" id="user_question" value="1">
                @else
                    <div>{{trans('message.sports.nosports')}}</div>
                @endif
            @endif
        </div>
    <!--                       <div class="sportsjun-forms">
  {!! Form::open(array('route' => array('sport.update',$userId),'class'=>'form-horizontal','method' => 'put')) !!}
            <div class="form-body">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <div id='question_div'></div>
  </div>
  {!! Form::close() !!}
            </div>-->
        <div class="col-sm-3 col-xs-12" id="sidebar-right">
            <div id="suggested_teams"> </div>
            <div id="suggested_tournaments"> </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
            @if (count($userSports))
            displaySportQuestions('unfollow', {{$userSports[0]['id']}}, {{$userId}},'{{$userSports[0]['sports_name']}}');
            @endif
        });
    </script>

@endsection