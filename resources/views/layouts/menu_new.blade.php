<!-- /.navbar-header -->
<style class="cp-pen-styles">@import url(http://fonts.googleapis.com/css?family=Slabo+27px);
body {
  font-family: 'Slabo 27px', serif;
}

aside {
  margin-bottom: 20px;
}
aside > ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  font-size: 0;
  text-align: left;
  background: #68DE78;
}
aside > ul > li {
  display: inline-block;
  position: relative;
}
aside > ul > li.more > a .fa {
  color: yellow;
}
aside > ul > li.hidden {
  display: none;
}
aside > ul > li > a {
  border-right: 1px dashed #d1f5d6;
}
aside > ul > li a {
  font-size: 1rem;
  display: block;
  background: #68DE78;
  color: #FFF;
  text-align: center;
  text-decoration: none;
  padding: 10px 20px;
}
aside > ul > li a .fa {
  font-size: 20px;
  display: block;
  margin-bottom: 10px;
}
aside > ul > li a + ul {
  display: none;
  position: absolute;
  top: 100%;
  right: 0;
  margin-right: 0;
}
aside > ul > li a + ul li {
  margin-top: 1px;
}
aside > ul > li a + ul li a {
  padding-left: 16px;
  text-align: left;
  white-space: nowrap;
}
aside > ul > li a + ul li a .fa {
  display: inline-block;
  margin-right: 10px;
  margin-bottom: 0;
}
aside > ul > li a + ul li a:hover {
  background: #28b83c;
}
aside > ul > li:hover > a {
  background: #28b83c;
}
aside > ul > li:hover ul {
  display: block;
}
</style>

<aside role="navigation">
<ul id="main" class="nav navbar-nav sportsjun_nav">
    <li><a href="{{ URL::to('/') }}" class="topmenu_1">{!! HTML::image('images/dashboard.png', '', array('height' => '25px','width' => '25px')) !!}<br><span>Dashboard</span></a></li>
    <li><a href="{{ URL::to('/team') }}" class="topmenu_2">{!! HTML::image('images/my-team.png', '', array('height' => '25px','width' => '32px')) !!}<br><span>My Teams</span></a></li>
    <li><a href="{{ url('/user/album/show/user') }}" class="topmenu_3">{!! HTML::image('images/gallery.png', '', array('height' => '25px','width' => '30px')) !!}<br><span>My Gallery</span></a></li>
    <li><a href="{{ url('/tournaments') }}" class="topmenu_4">{!! HTML::image('images/Tournaments.png', '', array('height' => '25px','width' => '26px')) !!}<br><span>Tournaments</span></a></li>
    <li><a href="{{ url('/facility/create') }}" class="topmenu_5">{!! HTML::image('images/Facility.png', '', array('height' => '25px','width' => '36px')) !!}<br><span>Facility</span></a></li>
    <!-- <li><a href="#" class="topmenu_6">{!! HTML::image('images/coach.png', '', array('height' => '25px','width' => '23px')) !!}<br><span>Coach</span></a></li> -->
    <!--<li><a href="{{ url('/marketplace') }}" class="topmenu_7">{!! HTML::image('images/Market-Place.png', '', array('height' => '25px','width' => '33px')) !!}<br><span>Market Place</span></a></li>-->
	
	<li data-width="80" class="more hidden" style="display: inline-block;">
      <a href="#"><span class="fa fa-ellipsis-h"></span>More</a>
      <ul>
	  <!--<li data-width="112"><a href="#"><span class="fa fa-envelope"></span>Contact Us</a></li>-->
	   <li data-width="80"><a href="{{ url('/marketplace') }}" class="topmenu_7">{!! HTML::image('images/Market-Place.png', '', array('height' => '25px','width' => '33px')) !!}<br><span>Market Place</span></a></li>
	  </ul>
    </li>
	
</ul>
</aside>


<ul class="nav navbar-top-links navbar-right sportsjun_navbar_right">
    <li class="dropdown">
        <div class="notfication_bubble">40</div>
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-envelope fa-fw fa-2x"></i>  <!--<i class="fa fa-caret-down"></i>-->
        </a>
        <ul class="dropdown-menu dropdown-messages">
            <li>
                <a href="#">
                    <div>
                        <strong>John Smith</strong>
                        <span class="pull-right text-muted">
                            <em>Yesterday</em>
                        </span>
                    </div>
                    <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="#">
                    <div>
                        <strong>John Smith</strong>
                        <span class="pull-right text-muted">
                            <em>Yesterday</em>
                        </span>
                    </div>
                    <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="#">
                    <div>
                        <strong>John Smith</strong>
                        <span class="pull-right text-muted">
                            <em>Yesterday</em>
                        </span>
                    </div>
                    <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a class="text-center" href="#">
                    <strong>Read All Messages</strong>
                    <i class="fa fa-angle-right"></i>
                </a>
            </li>
        </ul>
        <!-- /.dropdown-messages -->
    </li>
    <!-- /.dropdown -->
    <li class="dropdown">
        <div class="notfication_bubble">40</div>
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-bell fa-fw fa-2x"></i>  <!--<i class="fa fa-caret-down"></i>-->
        </a>
        <ul class="dropdown-menu dropdown-alerts">
            <li>
                <a href="#">
                    <div>
                        <i class="fa fa-comment fa-fw"></i> New Comment
                        <span class="pull-right text-muted small">4 minutes ago</span>
                    </div>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="#">
                    <div>
                        <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                        <span class="pull-right text-muted small">12 minutes ago</span>
                    </div>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="#">
                    <div>
                        <i class="fa fa-envelope fa-fw"></i> Message Sent
                        <span class="pull-right text-muted small">4 minutes ago</span>
                    </div>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="#">
                    <div>
                        <i class="fa fa-tasks fa-fw"></i> New Task
                        <span class="pull-right text-muted small">4 minutes ago</span>
                    </div>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="#">
                    <div>
                        <i class="fa fa-upload fa-fw"></i> Server Rebooted
                        <span class="pull-right text-muted small">4 minutes ago</span>
                    </div>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a class="text-center" href="#">
                    <strong>See All Alerts</strong>
                    <i class="fa fa-angle-right"></i>
                </a>
            </li>
        </ul>
        <!-- /.dropdown-alerts -->
    </li>
    <!-- /.dropdown -->
    @if (Auth::guest())
    @else
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            @if(Session::has('socialuser'))
            <img src="{{ Session('avatar')}}" height="30" width="30">
            @else 
            @if(Session::has('profilepic'))
            <img src="{{ asset('/uploads/user_profile/'.Session('profilepic')) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" height="30" width="30">
            @else  
            <img src="{{ asset('/images/default-profile-pic.jpg') }}" height="30" width="30">
            @endif  
            @endif
        </a>
        <ul class="dropdown-menu dropdown-user">
            <li><a href="{{ url('/changepassword') }}"><i class="fa fa-user fa-fw"></i> Change Password</a></li>
            <li><a href="{{ route('user.edit',[Auth::user()->id]) }}"><i class="fa fa-user fa-fw"></i> User Profile</a></li>
            <li><a href="{{ route('sport.show',[Auth::user()->id]) }}"><i class="fa fa-user fa-fw"></i> Sport Profile</a></li>
            <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
            </li>
            <li class="divider"></li>
            <li><a href="{{ url('/auth/logout') }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
            </li>
        </ul>
        <!-- /.dropdown-user -->
    </li>
    @endif
    <!-- /.dropdown -->
</ul>

<form class="navbar-form navbar-right sportsjun_search">
    <input type="text" placeholder="Players, Teams..." class="form-control top_nav_search ">
</form>

<div class="navbar-collapse">

    <div class="container-fluid actions_div">
        <div id="spinner" class="ajax-loader" style="display:none;" >
            <img id="img-spinner" src="{{ asset('/images/loader.gif') }}" alt="Loading" />				
        </div>
        <div class="create_new">
            <a href="{{ URL::to('/team/create') }}">Create New&nbsp;&nbsp;<i class="fa fa-plus-circle"></i></a>
        </div>
        <div class="active_inactive">
            <label class="active">Active</label>
            <ul>
                <li><a class="cricket_active"></a></li>
                <li><a class="football_nor"></a></li>
                <li><a class="basketball_nor"></a></li>
                <li><a class="vollyball_nor"></a></li>
            </ul>
            <label>Inactive</label>
            <ul>
                <li><a class="chess_nor"></a></li>
                <li><a class="golf_nor"></a></li>
                <li><a class="badminton_nor"></a></li>
                <li><a class="tennis_nor"></a></li>
                <li><a class="tabletennis_nor"></a></li>
                <li><a class="hockey_nor"></a></li>
                <li><a class="nfl_nor"></a></li>
                <li><a class="carrom_nor"></a></li>
            </ul>
        </div>
        <div class="calendar_right">
            <a class="calendar_new"><i class="fa fa-calendar fa-2x"></i></a>
            <a class="invite_friends">Invite Friends&nbsp;&nbsp;<i class="fa fa-plus-circle"></i></a>
        </div>
    </div>

</div>
<script>
function calcWidth() {
    var navwidth = 0;
    var morewidth = $('#main .more').outerWidth(true);
    $('#main > li:not(.more)').each(function () {
        navwidth += $(this).outerWidth(true);
    });
    var availablespace = $('aside').outerWidth(true) - morewidth;
    if (navwidth > availablespace) {
        var lastItem = $('#main > li:not(.more)').last();
        lastItem.attr('data-width', lastItem.outerWidth(true));
        lastItem.prependTo($('#main .more ul'));
        calcWidth();
    } else {
        var firstMoreElement = $('#main li.more li').first();
        if (navwidth + firstMoreElement.data('width') < availablespace) {
            firstMoreElement.insertBefore($('#main .more'));
        }
    }
    if ($('.more li').length > 0) {
        //$('.more').css('display', 'inline-block');
		$('.more').removeClass( "  hidden" );
    } else {
        //$('.more').css('display', 'none');
		$('.more').addClass( "  hidden" );
    }
}
$(window).on('resize load', function () {
    calcWidth();
});
//# sourceURL=pen.js
</script>

<!-- /.navbar-top-links -->