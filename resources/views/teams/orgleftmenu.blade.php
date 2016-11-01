<?php
$orgFollowed = isset($userId) ? $orgInfoObj->followers()->where('user_id', $userId)->count() > 0 : false;
$is_widget = (isset($is_widget) && $is_widget) ? $is_widget : false;

if ($is_widget) {
    $menu_routes = [
            'info' => 'widget.organization.info',
            'staff' => 'widget.organization.staff',
            'groups' => 'widget.organization.groups',
            'members' => 'widget.organization.members',
            'tournaments' => 'widget.organization.tournaments',
            'schedule' => 'widget.organization.schedule',
            'gallery' => 'widget.organization.gallery',
    ];
} else {
    $menu_routes = [
            'info' => 'getorgteamdetails',
            'staff' => 'organization.staff',
            'groups' => 'organization.groups.list',
            'members' => 'organization.members.list',
            'tournaments' => 'organizationTournaments',
            'schedule' => 'organization.schedules.list',
            'gallery' => 'user.album.show',
            'widget' => 'organization.widget.code'
    ];
}
?>


<div class="col-sm-2" id="sidebar-left">
    <div class="row">
        <div class="team_view">
            {!! Helper::makeImageHtml($orgInfoObj->logoImage,array('height'=>100,'width'=>100) )!!}
            <h1>{{ $orgInfoObj->name }}</h1>

            @if(!$is_widget && isset($userId) && isset($orgInfoObj) && ($userId == $orgInfoObj->user_id))
                <a href="{{url('/organization/'.(!empty($id)?$id:0).'/edit')}}" class="tvp_edit">
                    <span class="fa fa-pencil" title="Edit"></span>
                </a>
            @endif
            <div class="locations">
                <i class="fa fa-map-marker"></i>&nbsp;<span
                        style="word-wrap: break-word;">{{ $orgInfoObj->location or "Location" }}</span>
            </div>
            <div class="more desc">{{ $orgInfoObj->about  or 'Description' }}</div>

            @if(!$is_widget)
                <div class="follow_unfollow_organization" id="follow_unfollow_organization_{{$id}}" uid="{{$id}}"
                     val="ORGANIZATION" flag="{{ $orgFollowed ?0:1 }}">
                    <a href="#" id="follow_unfollow_organization_a_{{$id}}"
                       class="{{ $orgFollowed? 'sj_unfollow':'sj_follow' }}">
                       <span id="follow_unfollow_organization_span_{{$id}}">
                           <i class="{{ $orgFollowed ?'fa fa-remove':'fa fa-check' }}"></i>
                           {{ $orgFollowed ?'Unfollow':'Follow' }}</span></a></div>
            @endif
        </div>
        <ul class="nav sidemenu_nav leftmenu-icon" id="side-menu">
            <li>
                <a class="sidemenu_1" href="{{ route($menu_routes['info'],$id)  }}">
                    <span class="ico ico-info"></span>
                    Info
                </a>
            </li>

            <li>
                <a class="sidemenu_4"
                   href="{{ route($menu_routes['staff'],$id)}}">
                    <span class="ico ico-members"></span> Staff
                </a>
            </li>

            <li>
                <a class="sidemenu_4"
                   href="{{ route($menu_routes['groups'],$id)}}">
                    <span class="ico ico-members"></span> Teams (Groups)
                </a>
            </li>

            <li>
                <a class="sidemenu_3"
                   href="{{ route($menu_routes['members'],$id) }}">
                    <span class="ico ico-members"></span>
                    Players
                </a>
            </li>

        <!--<li><a class="sidemenu_2" href="{{ url('/team/matches') }}">Matches</a></li>-->
        <!--<li>
           <a class = "sidemenu_2"
              href = "{{ url('/organizationTeamlist/'.$id) }}">
               <span class = "ico ico-teams"></span>
               Teams
           </a>
       </li>-->

            <li>
                <a class="sidemenu_4"
                   href="{{ route($menu_routes['tournaments'],$id)}}">
                    <span class="ico ico-tournament"></span>
                    Tournaments
                </a>
            </li>

            <li>
                <a class="sidemenu_3"
                   href="{{ route($menu_routes['schedule'],$id) }}">
                    <span class="ico ico-final-stage"></span>
                    Schedule
                </a>
            </li>


            <li>
                <a class="sidemenu_3"
                   @if (!$is_widget)
                   href="{{ route($menu_routes['gallery'], ['test'=>'organization','id'=>0,'team_id'=>$id])}}"
                   @else
                   href="{{ route($menu_routes['gallery'], [$id])}}"
                        @endif
                >
                    <span class="ico ico-media-gallery"></span>
                    Media Gallery
                </a>
            </li>

            @if (!$is_widget)
                <li>
                    <a class="sidemenu_3"
                       href="{{ route($menu_routes['widget'],$id) }}">
                        <span class="ico ico-info"></span>
                        Widget
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
<style>

    .morecontent span {
        display: none;
    }

</style>
<script>
    $(document).ready(function () {
        var showChar = 100;
        var ellipsestext = "...";
        var moretext = "more";
        var lesstext = "less";
        $('.more').each(function () {
            var content = $(this).html();
            if (content.length > showChar) {
                var c = content.substr(0, showChar);
                var h = content.substr(showChar - 1, content.length - showChar);
                var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

                $(this).html(html);
            }

        });

        $(".morelink").click(function () {
            if ($(this).hasClass("less")) {
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


