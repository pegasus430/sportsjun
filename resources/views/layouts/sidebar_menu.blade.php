<!-- Overlay for fixed sidebar -->
<div class="sidebar-overlay"></div>

<!-- Material sidebar -->
<aside id="sidebar" class="sidebar sidebar-default sidebar-fixed-left" role="navigation">
    <!-- Sidebar header -->
    <div class="sidebar-header header-cover">
        <!-- Top bar -->
        <!--        <div class="top-bar"></div>-->
        <!-- Sidebar toggle button -->
        <!--
                <button type="button" class="sidebar-toggle">
                    <i class="fa fa-remove"></i>
                </button>
        -->
        <!-- Sidebar brand image -->
        <div class="sidebar-image">
            @if(Session::has('socialuser'))
                <img class="fa fa-user profile_pic_sj" src="{{ Session('avatar')}}" width="28" height="28">
            @else
                @if(Session::has('profilepic'))
                <!-- <img class="fa fa-user profile_pic_sj"  src="{{ asset('/uploads/user_profile/'.Session('profilepic')) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" width="28" height="28"> -->
                    {!! Helper::Images(Session('profilepic'),'user_profile',array('class'=>'fa fa-user profile_pic_sj','height'=>28,'width'=>28))!!}
                @else
                <!-- <img class="fa fa-user profile_pic_sj"  src="{{ asset('/images/default-profile-pic.jpg') }}" width="28" height="28"> -->
                    {!! Helper::Images('default-profile-pic.jpg','user_profile',array('class'=>'fa fa-user profile_pic_sj','height'=>28,'width'=>28))!!}
                @endif
            @endif
        </div>
        <!-- Sidebar brand name -->
        <a class="dropdown-toggle sidebar-brand" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">
            <i class="fa fa-gear"></i> <b class="caret"></b>
        </a>
        <ul class="dropdown-menu sidebar-nav" id="settings-dropdown">
            {{-- <!--<li><a href="{{ url('/changepassword') }}"><i class="fa fa-lock fa-fw"></i> Change Password</a></li>
            <li><a href="{{ route('user.edit',[Auth::user()->id]) }}"><i class="fa fa-user fa-fw"></i> User Profile</a></li>
            <li><a href="{{ url('/showsportprofile',[Auth::user()->id]) }}"><i class="fa fa-trophy fa-fw"></i> Sport Profile</a></li>
            @if(Auth::user()->role=='admin')
             <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-user fa-fw"></i>Admin</a></li>
            @endif
            <li><a href="{{ url('/auth/logout') }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>-->
            --}}

            <li><a href="{{ route('user.edit',[isset(Auth::user()->id)?Auth::user()->id:0]) }}"><i class="fa fa-user fa-fw"></i> User Profile</a></li>
            <li><a href="{{ url('/showsportprofile',[isset(Auth::user()->id)?Auth::user()->id:0]) }}"><i class="fa fa-trophy fa-fw"></i> Sport Profile</a></li>
            <li><a href="{{ url('/changepassword') }}"><i class="fa fa-lock fa-fw"></i> Change Password</a></li>
            @if(Auth::user() && Auth::user()->role=='admin')
                <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-user fa-fw"></i>Admin</a></li>
            @endif
            <li><a href="{{ URL::to('user/notifications') }}"><i class="fa fa-envelope fa-fw"></i> Notifications</a> </li>
            <li><a href="{{ url('/auth/logout') }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Sidebar navigation -->
    <ul class="nav sidebar-nav">
    <!--<li><a href="{{ URL::to('/') }}"><i class="fa"><img src="{{ url('/images/dashboard.png') }}" height="18" width="18"/></i>Dashboard</a></li>-->
        <li><a href="{{ url('/myschedule',[isset(Auth::user()->id)?Auth::user()->id:0]) }}"><i class="fa"><img src="{{ url('/images/Schedule.png') }}" height="18" width="22"/></i>My Schedule</a></li>
        <li><a href="{{ route('organizations') }}"><i class="fa"><img src="{{ url('/images/Organization.png') }}" height="18" width="24"/></i>My Organizations</a></li>
        <li><a href="{{ URL::to('/team/teams') }}"><i class="fa"><img src="{{ url('/images/my-team.png') }}" height="18" width="23"/></i>My Team</a></li>
        <li><a href="{{ url('/user/album/show/user') }}"><i class="fa"><img src="{{ url('/images/gallery.png') }}" height="18" width="22"/></i>My Gallery</a></li>
        <li><a href="{{ url('/tournaments') }}"><i class="fa"><img src="{{ url('/images/Tournaments.png') }}" height="18" width="19"/></i>My Tournaments</a></li>
    <!--<li><a href="{{ url('/facility') }}"><i class="fa"><img src="{{ url('/images/Facility.png') }}" height="18" width="26"/></i>Facility</a></li>-->
        <li><a href="{{ url('/marketplace') }}" ><i class="fa"><img src="{{ url('/images/Market-Place.png') }}" height="18" width="24"/></i>My Store</a></li>
        <li><a href="{{ url('/mytransactions',[(isset(Auth::user()->id)?Auth::user()->id:0)]) }}" ><i class="fa"><img src="{{ url('/images/Market-Place.png') }}" height="18" width="24"/></i>My Reports</a></li>
    </ul>
    <!-- Sidebar divider -->
    <!-- <div class="sidebar-divider"></div>
    
    <!-- Sidebar text -->
    <!--  <div class="sidebar-text">Text</div> -->
</aside>