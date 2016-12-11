<nav role="navigation" class="navbar navbar-default">
    <div class="row show-grid">
        <div class="col-sm-2">
            <div class="sports_jun_navbrand col-sm-3">
                <button class="sidebar-toggle"><i class="fa fa-bars"></i></button>
                <a href="/" class="logo">{!! HTML::image('images/SportsJun_Logo.png')!!}</a>
                <div class="mobile_notification">
                    <div class="notfication_bubble">{{Helper::getNotificationsCount()}}</div>
                    <div class="notification_menu_right">
                        <a href="{{ URL::to('user/notifications') }}" >
                            <i class="fa fa-bell fa-fw fa-2x"></i><!-- <i class="fa fa-caret-down"></i>-->
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-8">

            <aside id="main" class="main_menu">
            <!--    <li><a href="{{ URL::to('/') }}"><span class="fa"><img src="{{ url('/images/dashboard.png') }}" height="18" width="18"/></span>Dashboard</a></li>
-->    <li><a href="{{ url('/myschedule',[(isset(Auth::user()->id)?Auth::user()->id:0)]) }}"><span class="fa"><img src="{{ url('/images/Schedule.png') }}" height="18" width="22"/></span>My Schedule</a></li>
                <li><a href="{{ route('organizations') }}"><span class="fa"><img src="{{ url('/images/Organization.png') }}" height="18" width="24"/></span>My Organizations</a></li>
                <li><a href="{{ URL::to('/team/teams') }}"><span class="fa"><img src="{{ url('/images/my-team.png') }}" height="18" width="23"/></span>My Team</a></li>
                <li><a href="{{ url('/user/album/show/user') }}"><span class="fa"><img src="{{ url('/images/gallery.png') }}" height="18" width="22"/></span>My Gallery</a></li>
                <li><a href="{{ url('/tournaments') }}"><span class="fa"><img src="{{ url('/images/Tournaments.png') }}" height="18" width="19"/></span>My Tournaments</a></li>
            <!--<li><a href="{{ url('/facility') }}"><span class="fa"><img src="{{ url('/images/Facility.png') }}" height="18" width="26"/></span>Facility</a></li>-->
                <li><a href="{{ url('/marketplace/myitems') }}" ><span class="fa"><img src="{{ url('/images/Market-Place.png') }}" height="18" width="24"/></span>My Store</a></li>
                <li><a href="{{ url('/mytransactions',[(isset(Auth::user()->id)?Auth::user()->id:0)]) }}" ><span class="fa"><img src="{{ url('/images/Market-Place.png') }}" height="18" width="24"/></span>My Reports</a></li>
                <li class="more hidden" data-width="80">
                    <a href="#"><span class="fa fa-ellipsis-h"></span>More</a>
                    <ul></ul>
                </li>
            </aside>

        </div>

        <div class="col-sm-2 notification_menu">
            <div class="notification_menu_right">
                <ul class="nav navbar-nav navbar-right sportsjun_navbar_right">
                    <li class="dropdown">
                        <div class="notfication_bubble">{{Helper::getNotificationsCount()}}</div>
                        <a href="{{ URL::to('user/notifications') }}" >
                            <i class="fa fa-bell fa-fw fa-2x"></i><!-- <i class="fa fa-caret-down"></i>-->
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <!--<i class="fa fa-user fa-fw fa-2x"></i>-->
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
                        </a>
                        <ul class="dropdown-menu user-menu">
                            <li><a href="{{ route('user.edit',[(isset(Auth::user()->id)?Auth::user()->id:0)]) }}"><i class="fa fa-user fa-fw"></i> User Profile</a></li>
                            <li><a href="{{ url('/showsportprofile',[(isset(Auth::user()->id)?Auth::user()->id:0)]) }}"><i class="fa fa-trophy fa-fw"></i> Sport Profile</a></li>
                            <li><a href="{{ url('/changepassword') }}"><i class="fa fa-lock fa-fw"></i> Change Password</a></li>
                            @if(Auth::user() && Auth::user()->role!='general')
                                <li><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-user fa-fw"></i>Admin</a></li>
                        @endif
                        <!--li><a href="{{ URL::to('user/notifications') }}"><i class="fa fa-envelope fa-fw"></i> Notifications</a> </li-->
                            <li><a href="{{ url('/auth/logout') }}" id="logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>

            </div>

        </div>
    </div>

    @include ('layouts.sidebar_menu')
</nav>

<div class="sportsjun_actions">
    <div class="row show-grid">
        <div class="col-sm-2">

            <div class="sportsjun_actionsleft">
                <div class="dropdown create_new">
                    <a class="dropdown-toggle" data-toggle="dropdown">Create New &nbsp;&nbsp;<i class="fa fa-plus-circle"></i></a>
                    <ul class="dropdown-menu leftmenu-icon">
                        @if(isset(Auth::user()->id) && Auth::user()->profile_updated==0)
                            <li><a href="javascript:void(0);"><i class="ico ico-schedule mgt-10"></i> Match Schedule</a></li>
                        @else
                            <li><a href="#" data-toggle="modal" data-target="#mainmatchschedule" id="main_match_schedule"><i class="ico ico-schedule mgt-10"></i> Match Schedule</a></li>
                        @endif
                        <li><a href="{{ url('/team/create') }}"><i class="ico ico-team mgt-10"></i> Team</a></li>
                        <li><a href="{{ url('/tournaments/create') }}"><i class="ico ico-tournament mgt-10"></i> Tournament</a></li>
                        <li><a href="{{ url('/organization/create') }}"><i class="ico ico-org mgt-10"></i> Organization</a></li>
                        <li><a href="{{ url('/marketplace/create') }}"><i class="ico ico-marketplace mgt-10"></i> Item for Sale</a></li>

                    </ul>
                </div>
                <!-- <div class="create_new ">
                                <a>Create New&nbsp;&nbsp;<i class="fa fa-plus-circle"></i></a> -->
                <!--<ul class="nav navbar-nav navbar-right sportsjun_navbar_right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"></a>
                        <ul class="dropdown-menu">
                            <li><a ><i class="fa fa-user fa-fw"></i> Team</a></li>
                            <li><a ><i class="fa fa-user fa-fw"></i> Organization</a></li>
                            <li><a ><i class="fa fa-user fa-fw"></i> Tournaments</a></li>
                        </ul>
                    </li>
                </ul>-->
                <!-- </div> -->
                <div class="active_inactive">
                    <aside>
                        <ul>
                            <li class="labelwidh"><label>ACTIVE</label></li>
                            <li><a class="cricket_active"></a></li>
                            <li><a class="football_nor"></a></li>
                            <li><a class="basketball_nor"></a></li>
                            <li><a class="vollyball_nor"></a></li>
                            <li><a class="chess_nor"></a></li>
                            <li class="labelwidh"><label>IN ACTIVE</label></li>
                            <li><a class="golf_nor"></a></li>
                            <li><a class="badminton_nor"></a></li>
                            <li><a class="tennis_nor"></a></li>
                            <li><a class="tabletennis_nor"></a></li>
                            <li><a class="hockey_nor"></a></li>
                            <li><a class="nfl_nor"></a></li>
                            <li><a class="carrom_nor"></a></li>
                        </ul>

                    </aside>
                </div>
            </div>
        </div>
        <div class="col-sm-8  col-xs-12">
            <!--    	<button data-toggle="collapse" data-target="#filter-panel" class="btn btn-search">Search <span class="glyphicon glyphicon-search"></span></button>-->
            <div id="filter-panel" class="filter-panel">
                <div class="sportsjun_actionsmiddle sportsjun_mainsearch">

                    {!! Form::open(['url' => 'search','class'=>'form-horizontal','method' => 'GET','id' => 'search-form']) !!}

                    <div class="form-group select-wrap">
                        <label class="sr-only" for="filter-facility">Select service</label>
                        <select name="service" id="service" class="input-sm selectpicker">
                            <option value="" disabled>Select service</option>
                            @foreach (Config::get('constants.SERVICES') as $key=>$val)
                                <option value="{{$key}}" <?php if(isset($request_params->service) && $key == $request_params->service){?> selected="selected"<?php }?> autocomplete="off">{{$val}}</option>
                            @endforeach
                        </select>
                    </div> <!-- form group [Facility] -->

                    <div class="form-group" id="sportsList" <?php if(isset($request_params->service) && $key == 'marketplace'){ ?> style="display: none;" <?php } ?>>
                        <label class="sr-only" for="filter-game">Select sport</label>
                        <select id="sport" name="sport" class="input-sm selectpicker">
                            <option  value="" disabled>Select Sport</option>
                            @foreach (App\Helpers\Helper::getAllSports() as $game)
                                <option value="{{$game->id}}" <?php if(isset($request_params->sport) && $request_params->sport == $game->id){?> selected="selected"<?php }?> autocomplete="off">{{$game->sports_name}}</option>
                            @endforeach
                        </select>
                    </div> <!-- form group [Game] -->

                    <div class="form-group" id="marketplaceCategories" <?php if(isset($request_params->service) && $key != 'marketplace'){ ?> style="display: none;" <?php } ?>>
                        <label class="sr-only" for="filter-game">Select Category</label>
                        <select id="category" name="category" class="input-sm selectpicker">
                            <option value=""  disabled>Select Category</option>
                            @foreach (App\Helpers\Helper::getAllMarketPlaceCategories() as $category)
                                <option value="{{$category->id}}" <?php if(isset($request_params->category) && $request_params->category == $category->id){?> selected="selected"<?php }?> autocomplete="off">{{$category->name}}</option>
                            @endforeach

                        </select>
                    </div> <!-- form group [Game] -->

                    <!-- <div class="form-group input-addon col-sm-2">
                         <label class="sr-only" for="filter-address">Address</label>
                         <i class="glyphicon glyphicon-map-marker"></i>
                         <input type="text" class="form-control input-sm" id="filter-address" placeholder="City, State">
                     </div> -->


                    <div class="form-group input-addon">

                        <label class="sr-only" for="filter-location">By city</label>

                        <i class="glyphicon glyphicon-th-list"></i>
                        <?php //$city=App\Helpers\Helper::getCity();?>
                        <input type="text" class="form-control input-sm" name="search_city" id="search_city" placeholder="By city" value="<?php if(isset($request_params->search_city)){ echo $request_params->search_city; }else{echo isset(Auth::user()->city)?Auth::user()->city:'';}?>" autocomplete="off">

                    </div>
                    <input type="hidden" id="search_city_id" value="<?php if(isset($request_params->search_city_id)){ echo $request_params->search_city_id; }else{echo isset(Auth::user()->city)?Auth::user()->city_id:'';}?>" name="search_city_id" />
                    <!-- form group [Name] -->
                    <!-- form group [Address] -->
                    <div class="form-group input-addon">
                        <label class="sr-only" for="filter-location">By name </label>
                        <i class="glyphicon glyphicon-th-list"></i>
                        <input type="text" class="form-control input-sm" name="search_by" id="search_by" placeholder="By name" value="<?php if(isset($request_params->search_by)  && trim($request_params->search_by) != ''){ echo $request_params->search_by; }?>">
                    </div>

                    <!-- form group [Name] -->
                    <!-- form group [Location] -->
                    @if(isset($request_params->service) && $request_params->service == 'facility')
                        <div class="form-group input-addon cl-group" >
                            <label class="sr-only" for="filter-calender">Calender</label>
                            <i class="glyphicon glyphicon-calendar"></i>
                            <input type="text" class="form-control input-sm" name="looking_date" id="looking_date" placeholder="22-Jan-2016" value="<?php if(isset($request_params->looking_date) && trim($request_params->looking_date) != ''){?> {{$request_params->looking_date}} <?php }?>" autocomplete="off">
                        </div>

                    @endif

                <!-- form group [Location] -->
                    <div class="form-group">
                        <button type="submit" onclick="return checkIscityEmpty();" class="btn btn-gold btn-sm">
                            <span class="glyphicon glyphicon-search"></span> Search
                        </button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="col-sm-2 col-xs-12">
            <div class="sportsjun_actionsright">
                <a class="invite_friends">Invite Friends&nbsp;&nbsp;<i class="fa fa-plus-circle"></i></a>
                <a class="calendar_new"><i class="fa fa-calendar fa-2x"></i></a>
            </div>
        </div>

    </div>

</div>


<div class="sportsjun_actions sm-device">
    <div class="row show-grid">
        <div class="col-xs-6">

            <div class="sportsjun_actionsleft">
                <div class="dropdown create_new">
                    <a class="dropdown-toggle" data-toggle="dropdown">Create New &nbsp;&nbsp;<i class="fa fa-plus-circle"></i></a>
                    <ul class="dropdown-menu leftmenu-icon">
                        @if(isset(Auth::user()->id) && Auth::user()->profile_updated==0)
                            <li><a href="javascript:void(0);"><i class="ico ico-schedule mgt-10"></i> Match Schedule</a></li>
                        @else
                            <li><a href="#" data-toggle="modal" data-target="#mainmatchschedule" id="main_match_schedule"><i class="ico ico-schedule mgt-10"></i> Match Schedule</a></li>
                        @endif
                        <li><a href="{{ url('/team/create') }}"><i class="ico ico-team mgt-10"></i> Team</a></li>
                        <li><a href="{{ url('/tournaments/create') }}"><i class="ico ico-tournament mgt-10"></i> Tournament</a></li>
                        <li><a href="{{ url('/organization/create') }}"><i class="ico ico-org mgt-10"></i> Organization</a></li>
                        <li><a href="{{ url('/marketplace/create') }}"><i class="ico ico-marketplace mgt-10"></i> Item for Sale</a></li>

                    </ul>
                </div>
                <!-- <div class="create_new ">
                                <a>Create New&nbsp;&nbsp;<i class="fa fa-plus-circle"></i></a> -->
                <!--<ul class="nav navbar-nav navbar-right sportsjun_navbar_right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"></a>
                        <ul class="dropdown-menu">
                            <li><a ><i class="fa fa-user fa-fw"></i> Team</a></li>
                            <li><a ><i class="fa fa-user fa-fw"></i> Organization</a></li>
                            <li><a ><i class="fa fa-user fa-fw"></i> Tournaments</a></li>
                        </ul>
                    </li>
                </ul>-->
                <!-- </div> -->
                <div class="active_inactive">
                    <aside>
                        <ul>
                            <li class="labelwidh"><label>ACTIVE</label></li>
                            <li><a class="cricket_active"></a></li>
                            <li><a class="football_nor"></a></li>
                            <li><a class="basketball_nor"></a></li>
                            <li><a class="vollyball_nor"></a></li>
                            <li><a class="chess_nor"></a></li>
                            <li class="labelwidh"><label>IN ACTIVE</label></li>
                            <li><a class="golf_nor"></a></li>
                            <li><a class="badminton_nor"></a></li>
                            <li><a class="tennis_nor"></a></li>
                            <li><a class="tabletennis_nor"></a></li>
                            <li><a class="hockey_nor"></a></li>
                            <li><a class="nfl_nor"></a></li>
                            <li><a class="carrom_nor"></a></li>
                        </ul>

                    </aside>
                </div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="sportsjun_actionsright">
                <a class="invite_friends">Invite Friends&nbsp;&nbsp;<i class="fa fa-plus-circle"></i></a>
                <a class="calendar_new"><i class="fa fa-calendar fa-2x"></i></a>
            </div>
        </div>
        <div class="col-xs-12">
            <button data-toggle="collapse" data-target=".filter-panel" class="btn btn-search">Search <span class="glyphicon glyphicon-search"></span></button>
            <div id="filter-panel" class="collapse filter-panel">
                <div class="sportsjun_actionsmiddle sportsjun_mainsearch">

                    {!! Form::open(['url' => 'search','class'=>'form-horizontal','method' => 'GET','id' => 'search-form']) !!}

                    <div class="form-group select-wrap">
                        <label class="sr-only" for="filter-facility">Select service</label>
                        <select name="service" id="service" class="input-sm selectpicker">
                            <option value="" disabled>Select service</option>
                            <!--<option value="facility">Facility</option>
                            <option value="tournament">Tournament</option>
                            <option value="team">Team</option>
                            <option value="user">Player</option>
                            <option value="marketplace">Market Place</option> -->
                            @foreach (Config::get('constants.SERVICES') as $key=>$val)
                                <option value="{{$key}}" <?php if(isset($request_params->service) && $key == $request_params->service){?> selected="selected"<?php }?>>{{$val}}</option>
                            @endforeach
                        </select>
                    </div> <!-- form group [Facility] -->

                    <div class="form-group">
                        <label class="sr-only" for="filter-game">Select sport</label>
                        <select id="sport" name="sport" class="input-sm selectpicker">
                            <option value="" disabled>Select sport</option>
                            @foreach (App\Helpers\Helper::getAllSports() as $game)
                                <option value="{{$game->id}}" <?php if(isset($request_params->sport) && $request_params->sport == $game->id){?> selected="selected"<?php }?>>{{$game->sports_name}}</option>
                            @endforeach

                        </select>
                    </div> <!-- form group [Game] -->

                    <!-- <div class="form-group input-addon col-sm-2">
                         <label class="sr-only" for="filter-address">Address</label>
                         <i class="glyphicon glyphicon-map-marker"></i>
                         <input type="text" class="form-control input-sm" id="filter-address" placeholder="City, State">
                     </div> -->

                    <!-- form group [Address] -->



                    <!-- form group [Name] -->

                    <div class="form-group input-addon">
                        <label class="sr-only" for="filter-location">By city</label>

                        <i class="glyphicon glyphicon-th-list"></i>
                        <?php //$city=App\Helpers\Helper::getCity();?>
                        <input type="text" class="form-control input-sm" name="search_city" id="search_city"  placeholder="By city" value="<?php if(isset(	$request_params->search_city) ){ echo $request_params->search_city; }else{echo isset(Auth::user()->city)?Auth::user()->city:'';} ?>" autocomplete="off">

                    </div>
                    <input type="hidden" id="search_city_id" value="<?php echo  isset(Auth::user()->city)?Auth::user()->city_id:'';?>" name="search_city_id" />
                    <div class="form-group input-addon">
                        <label class="sr-only" for="filter-location">By name </label>
                        <i class="glyphicon glyphicon-th-list"></i>
                        <input type="text" class="form-control input-sm" name="search_by" id="search_by" placeholder="By name" value="<?php if(isset($request_params->search_by)  && trim($request_params->search_by) != ''){ echo $request_params->search_by; }?>">
                    </div>
                    <!-- form group [Name] -->

                    <!-- form group [Location] -->
                    @if(isset($request_params->service) && $request_params->service == 'facility')
                        <div class="form-group input-addon cl-group" >
                            <label class="sr-only" for="filter-calender">Calender</label>
                            <i class="glyphicon glyphicon-calendar"></i>
                            <input type="text" class="form-control input-sm" name="looking_date" id="looking_date" placeholder="22-Jan-2016" value="<?php if(isset($request_params->looking_date) && trim($request_params->looking_date) != ''){?> {{$request_params->looking_date}} <?php }?>">
                        </div>
                    @endif

                <!-- form group [Location] -->
                    <div class="form-group">
                        <button type="submit"  onclick="return checkIscityEmpty();"  class="btn btn-gold btn-sm">
                            <span class="glyphicon glyphicon-search"></span> Search
                        </button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>


    </div>

</div>
@include ('layouts.addmainschedule',[])
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
//    calcWidth();
    });
    //# sourceURL=pen.js

</script>