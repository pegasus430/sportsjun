@extends('layouts.app')

@section('content')
<!--<div class="container">

  <div class="col-sm-11">
  <div class="row bs-wizard">


    Three Step Class Need To Add Here "Active", "Complete", "Disabled"

    <div class="col-xs-6 bs-wizard-step disabled">
       <div class="text-center bs-wizard-stepnum">Step 1</div>
       <div class="progress"><div class="progress-bar"></div></div>
       <a href="#" class="bs-wizard-dot"></a>
     </div>

     <div class="col-xs-6 bs-wizard-step complete"> complete
       <div class="text-center bs-wizard-stepnum">Step 2</div>
       <div class="progress"><div class="progress-bar"></div></div>
       <a href="#" class="bs-wizard-dot"></a>
     </div>
           </div>
    </div>
</div>-->

<div class="col-sm-9 col-sm-offset-2">
    <div class="row">
        <div class="col-md-8 col-md-offset-1">
                <div class="card hovercard">
					@if($userExists=='false')
					<div class="cardheader">
                        User doesnot exist
                    </div>
					@else
					<div class="displaymessage sports_profile_msg_arrow_down">
						{{ trans('message.sports.addsportsprofile') }}
					</div>
                    <div class="cardheader">
                        {{ trans('message.sports.fields.sportsprofile') }}
                    </div>
                    <div class="avatar">
                        @if(Session::has('socialuser'))
                        <img src="{{ Session('avatar')}}" height="42" width="42">
                        @else
                        @if(Session::has('profilepic'))
                       <!-- <img src="{{ asset('/uploads/user_profile/'.Session('profilepic')) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" height="42" width="42">-->
				   	 {!! Helper::Images(Session('profilepic'),'user_profile',array('height'=>42,'width'=>42) )!!}
                        @else
                      <!--  <img src="{{ asset('/images/default-profile-pic.jpg') }}" height="42" width="42">-->
				   	 {!! Helper::Images('default-profile-pic.jpg','images',array('height'=>42,'width'=>42) )!!}
                        @endif
                        @endif
                    </div>

                    <div class="info">
                               <div class="desc">{!! Helper::getPlayerInfo($userId) !!}</div>
                    </div>

                    <div class="bottom">

                            @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                            @endif
                           @if($userId==Auth::user()->id)
<!--                            <div class="pull-right" style="margin-bottom: 8px;"><a href="{{ URL::to('/skip') }}" class="skip">Skip</a></div>
                            <div class="clearfix"></div>-->


                            @if (count($sports))
                            <input type="hidden" id="user_question" value="1">
                            <ul class="list-inline">
                            @foreach($sports as $sport)
                             <li>
                                @if($userId==Auth::user()->id)
                                    <a id="sport_name_{{$sport->id}}" class="btn btn-primary" href="javascript:void(0)" onclick="displaySportQuestions('follow',{{$sport->id}},{{$userId}},'{{$sport->sports_name}}');">{{$sport->sports_name}}</a>
                                @else
                                    <a id="sport_name_{{$sport->id}}" class="btn btn-primary" href="javascript:void(0)" onclick="displaySportQuestions('unfollow',{{$sport->id}},{{$userId}},'{{$sport->sports_name}}');">{{$sport->sports_name}}</a>
                                @endif
                              </li>
                            @endforeach
                            </ul>
                            @else
                            <div>{{trans('message.sports.nosports')}}</div>
                            @endif

                            @if (count($sports))
                              <div class="sportsjun-forms" id='sportsjun_forms_{{$sport->id}}'>
                                    {!! Form::open(array('route' => array('sport.update',Auth::user()->id),'class'=>'form-horizontal','method' => 'put')) !!}

                                    @foreach($sports as $sport)
                                        <div id='question_div_{{$sport->id}}' class="question_div_class"></div>
                                    @endforeach
                                   {!! Form::close() !!}
                                </div>
                            @endif

                            @else
                                <div class="sj-alert sj-alert-info">
                                    {{trans('message.sports.nousersports')}}
                                </div>
                            @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
$(function () {
@if($userId==Auth::user()->id)
    $('a:not("#logout"),.btn').click(function(e){e.preventDefault();});
    $('#main_match_schedule').each(function(){
       $(this).removeAttr('data-target');
       $(this).removeAttr('data-toggle');
    });
@endif
});
</script>

@endsection
