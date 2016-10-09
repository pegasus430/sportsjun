
<div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">

	<div class="team_view">
		<img src="{{ url('/uploads/'.(!empty($photo_path)?$photo_path:'')) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" height="100" width="100">
		<h1>{{ $team_name or 'Team'}}</h1> 
        @if(in_array($team_id,$managing_team_ids))
        <a href="{{ url('/team/edit').'/'.$team_id }}">
          <span class="glyphicon glyphicon-edit" title="Edit"></span>
        </a>
        @endif
		<div class="locations"><i class="fa fa-map-marker"></i>&nbsp;<span style="word-wrap: break-word;">{{ !empty($location)?$location:'Location' }}</span>&nbsp;&nbsp;<i class="fa fa-map-marker"></i>&nbsp;<span>{{ $sport or 'Sport'}}</span></div>
		<div class="more">{{ $description or 'Description' }}</div>
	</div>	
	<ul class="nav sidemenu_nav" id="side-menu">
        <li><a class="sidemenu_1" href="{{ url('/team/members').'/'.$team_id }}">Members</a></li>
        <!--<li><a class="sidemenu_2" href="{{ url('/team/matches') }}">Matches</a></li>-->
        <li><a class="sidemenu_3" href="{{ url('/team/stats').'/'.$team_id.'/'.$sport_id }}">Stats</a></li>
        <li><a class="sidemenu_4" href="{{ url('user/album/show').'/team'.'/0'.'/'.$team_id  }}">Gallery</a></li>
        <li><a class="sidemenu_5" href="{{ url('/team/schedule').'/'.$team_id.'/'.$sport_id }}">Schedule</a></li>
        <li><a class="sidemenu_6" href="{{ url('/team/communication') }}">Communication</a></li>          
	</ul>
</div>
</div>

      
        </nav>
<style>

.morecontent span {
    display: none;
}

</style>
<script>
$(document).ready(function() {
    var showChar = 100;
    var ellipsestext = "...";
    var moretext = "more";
    var lesstext = "less";
    $('.more').each(function() {
        var content = $(this).html();
        if(content.length > showChar) {
            var c = content.substr(0, showChar);
            var h = content.substr(showChar-1, content.length - showChar);
            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
 
            $(this).html(html);
        }
 
    });
 
    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
});
</script>