<div class="col-sm-2" id="sidebar-left">
	<div class="row">
			<div class="team_view">
						{!! Helper::Images(!empty($photo['url'])?$photo['url']:'','organization',array('height'=>100,'width'=>100) )!!}
						<h1>{{ $orgInfo[0]['name'] or "" }}</h1>
					
                                        @if(isset($userId) && isset($orgInfo) && ($userId == $orgInfo[0]['user_id']))
					<a href="{{url('/organization/'.(!empty($id)?$id:0).'/edit')}}" class="tvp_edit">
					  <span class="fa fa-pencil" title="Edit"></span>
					</a>
                                        @endif
		        <div class="locations">
				  <i class="fa fa-map-marker"></i>&nbsp;<span style="word-wrap: break-word;">{{ $orgInfo[0]['location'] or "Location" }}</span>
				 </div>
		            <div class="more desc">{{ $orgInfo[0]['about']  or 'Description' }}</div>
	          </div>
	<ul class="nav sidemenu_nav leftmenu-icon" id="side-menu">
        <li><a class="sidemenu_1" href="{{ url('getorgteamdetails/'.$id) }}"><span class="ico ico-info"></span> Info</a></li>
        <!--<li><a class="sidemenu_2" href="{{ url('/team/matches') }}">Matches</a></li>-->
        <li><a class="sidemenu_2" href="{{ url('/organizationTeamlist/'.$id) }}"><span class="ico ico-teams"></span> Teams</a></li>
      	
		<li><a class="sidemenu_3" href="{{ url('user/album/show').'/organization'.'/0'.'/'.$id }}"><span class="ico ico-media-gallery"></span> Media Gallery</a></li>
        <li>
            <a class = "sidemenu_4"
               href = "{{ route('organization.staff', [$id]) }}">
                <span class = "ico ico-members"></span> Staff
            </a>
        </li>

        <li>
            <a class = "sidemenu_4"
               href = "{{ route('organization.groups.list', [$id]) }}">
                <span class = "ico ico-members"></span> Groups
            </a>
        </li>

		<li><a class="sidemenu_4" href="{{ url('/organizationTournaments/'.$id) }}"><span class="ico ico-tournament"></span> Tournaments</a></li>

	</ul>
    </div>
</div>
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


