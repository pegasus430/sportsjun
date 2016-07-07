<div class="col-sm-2" id="sidebar-left">
    <div class="row">
        <div class="team_view">

            <?php
            if(Auth::user()) $check_user='';
            else $check_user='/viewpublic';
            ?>


            @if($userId == (isset(Auth::user()->id)?Auth::user()->id:0))
                @if(Session::has('socialuser'))
                    <img src="{{ Session('avatar')}}" height="42" width="42">
                @else
                    @if(Session::has('profilepic'))
                        {!! Helper::Images(Session('profilepic'),'user_profile',array('height'=>100,'width'=>100) )!!}
                    @else
                        {!! Helper::Images('default-profile-pic.jpg','images',array('height'=>100,'width'=>100) )!!}
                    @endif
                @endif
            @else
                @if(!empty($userId))
                    {!! Helper::displayOtherUserImage($userId)!!}
                @endif
            @endif

            @if(isset($userId) && ($userId == (isset(Auth::user()->id)?Auth::user()->id:0)) )
                <a href="{{ route('user.edit',[isset(Auth::user()->id)?Auth::user()->id:0]) }}" class="tvp_edit">
                    <span class="fa fa-pencil" title="Edit"></span>
                </a>
            @endif
            <div class="desc" id="userFullName">{!! Helper::getPlayerInfo($userId) !!}</div>
            <?php $follow_unfollow = Helper::checkFollowUnfollow(isset(Auth::user()->id)?Auth::user()->id:0,'PLAYER',$userId);?>
            @if(Auth::user())
                @if(isset(Auth::user()->id)?Auth::user()->id:0 != $userId)
                    <div class="follow_unfollow_player" id="follow_unfollow_player_{{$userId}}" uid="{{$userId}}" val="PLAYER" flag="{{ !empty($follow_unfollow)?0:1 }}"><a href="#" id="follow_unfollow_player_a_{{$userId}}" class="{{ !empty($follow_unfollow)?'sj_unfollow':'sj_follow' }}"><span id="follow_unfollow_player_span_{{$userId}}"><i class="{{ !empty($follow_unfollow)?'fa fa-remove':'fa fa-check' }}"></i>{{ !empty($follow_unfollow)?'Unfollow':'Follow' }}</span></a></div>
                @endif
            @endif
        </div>
        <ul class="nav sidemenu_nav leftmenu-icon" id="side-menu">
        <!--<li><a class="sidemenu_1" href="{{ url('user/info') }}">Info</a></li>-->
            <li><a class="sidemenu_2" href="{{ url($check_user.'/showsportprofile',$userId) }}"><span class="ico ico-members"></span> Sports Profile</a></li>
            <li><a class="sidemenu_3" href="{{ url('team/teams',$userId) }}"><span class="ico ico-teams"></span> Teams</a></li>
            <li><a class="sidemenu_4" href="{{ url($check_user.'/user/album/show').'/user/'.$userId }}"><span class="ico ico-media-gallery"></span> Media Gallery</a></li>
            @if(isset($userId) && ($userId == (isset(Auth::user()->id)?Auth::user()->id:0)))
                <li><a class="sidemenu_5" href="{{ url('sport/playerrequests').'/'.(isset(Auth::user()->id)?Auth::user()->id:0) }}"><span class="ico ico-request"></span> Requests</a></li>
            @endif
            <li><a class="sidemenu_6" href="{{ url('/myschedule',[$userId]) }}"><span class="ico ico-schedule"></span> {{($userId == (isset(Auth::user()->id)?Auth::user()->id:0))?'My Schedule':'Schedule'}}</a></li>
            <li><a class="sidemenu_7" href="{{ url('/following',[$userId]) }}"><span class="ico ico-schedule"></span> Following</a></li>
        </ul>
    </div>
</div>