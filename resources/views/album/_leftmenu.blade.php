<div class="col-sm-2" id="sidebar-left">
  <div class="row">
    <div class="team_view">
	  @if($userId == Auth::user()->id)	
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
      @if(isset($userId) && ($userId == Auth::user()->id)) 
      <a href="{{ route('user.edit',[Auth::user()->id]) }}" class="tvp_edit">
      	<span class="fa fa-pencil" title="Edit"></span>
      </a>
      @endif
      <div class="desc">{!! Helper::getPlayerInfo($userId) !!}</div>
      <?php $follow_unfollow = Helper::checkFollowUnfollow(Auth::user()->id,'PLAYER',$userId);?>
      @if(Auth::user()->id != $userId)
      <div class="follow_unfollow_player" id="follow_unfollow_player_{{$userId}}" uid="{{$userId}}" val="PLAYER" flag="{{ !empty($follow_unfollow)?0:1 }}"><a href="#" id="follow_unfollow_player_a_{{$userId}}" class="{{ !empty($follow_unfollow)?'sj_unfollow':'sj_follow' }}"><span id="follow_unfollow_player_span_{{$userId}}"><i class="{{ !empty($follow_unfollow)?'fa fa-remove':'fa fa-check' }}"></i>{{ !empty($follow_unfollow)?'Unfollow':'Follow' }}</span></a></div> 
      @endif
    </div>	
    <ul class="nav sidemenu_nav leftmenu-icon" id="side-menu">
      <!--<li><a class="sidemenu_1" href="{{ url('user/info') }}">Info</a></li>-->
      <li><a class="sidemenu_2" href="{{ url('/showsportprofile',$userId) }}"><span class="ico ico-members"></span> Sports</a></li>
      <li><a class="sidemenu_3" href="{{ url('team/teams',$userId) }}"><span class="ico ico-teams"></span> Teams</a></li>
      <li><a class="sidemenu_4" href="{{ url('user/album/show').'/user/'.$userId }}"><span class="ico ico-media-gallery"></span> Media Gallery</a></li>       
      @if(isset($userId) && ($userId == Auth::user()->id))        
      <li><a class="sidemenu_5" href="{{ url('sport/playerrequests').'/'.Auth::user()->id }}"><span class="ico ico-request"></span> Requests</a></li>
       @endif
      <li><a class="sidemenu_6" href="{{ url('/myschedule',[$userId]) }}"><span class="ico ico-schedule"></span> {{$userId == Auth::user()->id?'My Schedule':'Schedule'}}</a></li>
     
    </ul>
  </div>
</div>