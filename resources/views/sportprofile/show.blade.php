@extends(Helper::check_if_org_template_enabled()?'layouts.organisation':'layouts.app') : 'home.layout')

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
                            User does not exist
                        </div>
                    @else
                        <div class="displaymessage sports_profile_msg_arrow_down">
                            {{ trans('message.sports.addsportsprofile') }}
                        </div>
                        <div class="cardheader">
                            {!! nl2br(e(trans('message.sports.fields.sportsrequired')))  !!}
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
                            @if($userId==isset(Auth::user()->id)?Auth::user()->id:0)

                                @if (count($sports))
                                    <input type="hidden" id="user_question" value="1">
                                    <form method="POST" action="{{ route('select-sports') }}">
                                        {{ csrf_field() }}
                                        <ul class="list-inline">
                                            @foreach($sports as $sport)
                                                <li>
                                                    <input id="sports_{{$sport->id}}" type="checkbox" class="hidden switch-class sports-checkbox"  name="sports[]" value="{{$sport->id}}" data-name="{{ $sport->sports_name }}"/>
                                                    <a id="sport_name_{{$sport->id}}" class="btn btn-primary" data-id="{{$sport->id}}" data-name="{{ $sport->sports_name }}"
                                                       href="javascript:void(0)"
                                                       onclick="return selectSport(this);">{{$sport->sports_name}}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="row">
                                            <button id="select_sports" type="submit" class="btn pull-right disabled" disabled>Select Sports </button>
                                        </div>
                                    </form>
                                @else
                                    <div>{{trans('message.sports.nosports')}}</div>
                                @endif

                                @if (count($sports))
                                    <div class="sportsjun-forms" id='sportsjun_forms_{{$sport->id}}'>
                                        {!! Form::open(array('route' => array('sport.update',isset(Auth::user()->id)?Auth::user()->id:0),'class'=>'form-horizontal','method' => 'put')) !!}

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

    <!-- Modal -->
    <div class="modal fade"  id="smiteModal" role="dialog" style="display:none;" data-entered="false">
        <div class="modal-dialog sj_modal sportsjun-forms">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Please enter your Smite nickname</h4>
                </div>

                <div class="modal-body">
                    <div class="sportsjun-forms sportsjun-container wrap-2 sportsjun-forms-modal">
                        <div class="form-body">

                            <div class="section">
                                <label class="form_label">
                                    Smite Nickname
                                    <span  class='required'>*</span>
                                </label>
                            </div>

                            <label for="venue" class="field">
                                <input class="smite-nickname gui-input" placeholder="Your Smite Username" id="smite-username"/>
                            </label>

                            <div class="clearfix"><a id="smite-url" onclick="smiteRedirect(this)" href="https://www.hirezstudios.com/my-account/"> Dont have an account? Register HERE now!</a></div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" name="save_smite_nickname" id="save_smite_nickname" class="button btn-primary">Save</button>
                    <button type="button" class="button btn-secondary" id="close_smite_modal" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function () {
            @if(isset($userId))
          @if($userId==(isset(Auth::user()->id)?Auth::user()->id:0))
              $('a:not("#logout"),.btn:not("#select_sports")').click(function (e) {
                e.preventDefault();
            });
            $('#main_match_schedule').each(function () {
                $(this).removeAttr('data-target');
                $(this).removeAttr('data-toggle');
            });
            @endif
            @endif
        });
    </script>

@endsection
