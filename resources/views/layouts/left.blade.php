@if(isset($left) && ($left != 0 || $top == 7))
<div class="navbar-default sidebar my_team_sidebarnew" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        @if(isset($top) && $top == 7)
        <!-- <ul class="nav sidemenu_nav" id="side-menu">
            <li><a class="sidemenu_1" href="{{-- url('/marketplace') --}}">All</a></li>
            <li><a class="sidemenu_2" href="{{-- url('/marketplace/myitems') --}}">My Items</a></li>
            <li><a class="sidemenu_3" href="{{-- url('/marketplace/create') --}}">Create</a></li>
        </ul>  -->        
        @elseif(isset($top) && $top == 2)
		<div class="team_view">
			<img src="{{ url('/uploads/'.(!empty($photo_path)?$photo_path:'')) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" height="100" width="100">
			<h1>{{ $team_name or 'Team'}}</h1>
			<div class="locations"><i class="fa fa-map-marker"></i>&nbsp;<span>{{ $location or 'Location'}}</span>&nbsp;&nbsp;<i class="fa fa-map-marker"></i>&nbsp;<span>{{ $sport or 'Sport'}}</span></div>
			<p>{{ $description or 'Description' }}</p>
		</div>		
        <ul class="nav sidemenu_nav" id="side-menu">
            <li><a class="sidemenu_1" href="{{ url('/team/members').'/'.$team_id }}">Members</a></li>
            <li><a class="sidemenu_2" href="{{ url('/team/matches') }}">Matches</a></li>
            <li><a class="sidemenu_3" href="{{ url('/team/stats') }}">Stats</a></li>
            <!--<li><a class="sidemenu_4" href="{{ url('/team/gallery') }}">Gallery</a></li>-->
            <li><a class="sidemenu_4" href="{{ url('user/album/show').'/team'.'/0'.'/'.$team_id  }}">Gallery</a></li>
            <li><a class="sidemenu_5" href="{{ url('/team/schedule').'/'.$team_id.'/'.$sport_id }}">Schedule</a></li>
            <li><a class="sidemenu_6" href="{{ url('/team/communication') }}">Communication</a></li>
        </ul>
        @elseif(isset($top) && $top == 4)
        <ul class="nav sidemenu_nav" id="side-menu">
            <li><a class="sidemenu_1" href="{{ url('/tournament/group') }}">Group</a></li>
            <li><a class="sidemenu_1" href="{{ url('/tournament/gallery') }}">Media Gallery</a></li>
            <li><a class="sidemenu_1" href="{{ url('/tournament/matches') }}">Matches</a></li>
        </ul>        
        @elseif(isset($top) && $top == 5)
        <ul class="nav sidemenu_nav" id="side-menu">
            <li><a class="sidemenu_1" href="{{ url('/facility') }}">All</a></li>
            <li><a class="sidemenu_2" href="{{ url('/facility/myitems') }}">My Items</a></li>
            <li><a class="sidemenu_3" href="{{ url('/facility/create') }}">Create</a></li>            
        </ul>
		@elseif(isset($top) && $top == 3)
        <ul class="nav sidemenu_nav" id="side-menu">
            <li><a class="sidemenu_1" href="{{ url('user/info') }}">Info</a></li>
            <li><a class="sidemenu_2" href="{{ route('sport.show',[Auth::user()->id]) }}">Sports</a></li>
            <li><a class="sidemenu_3" href="{{ url('user/team') }}">Teams</a></li>            
            <li><a class="sidemenu_4" href="{{ url('user/album/show').'/user' }}">Media Gallery</a></li>           
        </ul>
		@elseif(isset($top) && $top == 6)
        <ul class="nav sidemenu_nav" id="side-menu">
            <li><a class="sidemenu_1" href="{{ url('tournaments') }}">Info</a></li>          
            <li><a class="sidemenu_2" href="{{ url('user/album/show').'/tournaments' }}">Media Gallery</a></li>           
        </ul>
		@elseif(isset($top) && $top == 8)
        <ul class="nav sidemenu_nav" id="side-menu">
            <li><a class="sidemenu_1" href="{{ url('/facility/create') }}">Info</a></li>          
            <li><a class="sidemenu_2" href="{{ url('user/album/show').'/facility' }}">Media Gallery</a></li>           
        </ul>
        @elseif(isset($top) && $top == 9)
        <ul class="nav sidemenu_nav" id="side-menu">
            <li><a class="sidemenu_1" href="javascript:void(0);" onclick="searchtypecall('user','{{$search}}')">Users</a></li>
            <li><a class="sidemenu_2" href="javascript:void(0);" onclick="searchtypecall('facility','{{$search}}')">Facilities</a></li>
            <li><a class="sidemenu_3" href="javascript:void(0);" onclick="searchtypecall('organization','{{$search}}')">Organizations</a></li>
            <li><a class="sidemenu_4" href="javascript:void(0);" onclick="searchtypecall('team','{{$search}}')">Teams</a></li>
            <li><a class="sidemenu_5" href="javascript:void(0);" onclick="searchtypecall('tournament','{{$search}}')">Tournaments</a></li>
        </ul>        
        @endif
    </div>
</div>
@endif
