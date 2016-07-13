<?php $image= explode('/',$photo_path);?>
<div class="col-sm-2" id="sidebar-left">
    <div class="row">
        <div class="team_view">
            {!! Helper::Images(!empty($image[1])?$image[1]:'',!empty($image[0])?$image[0]:'',array('height'=>100,'width'=>100) )!!}
            <h1 id="left-menu-team-name" data-team-id="{{$team_id}}" data-schedule-type="{{$sport_schedule_type}}">{{ $team_name or 'Team'}}</h1>
            @if(in_array($team_id,$managing_team_ids))
                <a href="{{ url('/team/edit').'/'.$team_id }}" class="tvp_edit">
                    <span class="fa fa-pencil" title="Edit"></span>
                </a>
            @endif
            <div class="locations"><i class="fa fa-map-marker"></i>&nbsp;<span style="word-wrap: break-word;">{{ !empty($location)?$location:'Location' }}</span>&nbsp;&nbsp;<i class="fa fa-globe"></i>&nbsp;<span id="left-menu-sport-name" data-sport-id="{{$sport_id}}">{{ $sport or 'Sport'}}</span></div>
            <div class="more desc">{{ $description or 'Description' }}</div>
            <?php if (!$user_in_team && $player_available_in_team) { ?>
            <div class="sb_join_team_main">
                <a href="javascript:void(0);" onclick="SJ.TEAM.joinTeam({{$team_id}},{{(isset(Auth::user()->id)?Auth::user()->id:0)}},'{{ $team_name or 'Team'}}');" class="sj_add_but">
                    <span><i class="fa fa-check"></i>Join Team</span>
                </a>
            </div>
            <?php } ?>
            <div class="follow_unfollow_team" id="follow_unfollow_team_{{$team_id}}" uid="{{$team_id}}" val="TEAM" flag="{{ !empty($follow_unfollow)?0:1 }}">
                <a href="javascript:void(0);" id="follow_unfollow_team_a_{{$team_id}}" class="{{ !empty($follow_unfollow)?'sj_unfollow':'sj_follow' }}">
                    <span id="follow_unfollow_team_span_{{$team_id}}"><i class="{{ !empty($follow_unfollow)?'fa fa-remove':'fa fa-check' }}"></i>{{ !empty($follow_unfollow)?'Unfollow':'Follow' }}</span>
                </a>
            </div>
            <?php
            // commenting schedule match because of unreliable js
            /*
            <div class="btn_schedule_match">
                    <a href="javascript:void(0);" onclick="SJ.TEAM.scheduleMatch();">
                            <span><i class="fa fa-check"></i>Schedule Match</span>
                    </a>
            </div>
             *
             */
            ?>
        </div>

        <ul class="nav sidemenu_nav leftmenu-icon" id="side-menu">
            <li><a class="sidemenu_1" href="{{ url('/team/members').'/'.$team_id }}"><span class="ico ico-members"></span> Members</a></li>
        <!--<li><a class="sidemenu_2" href="{{ url('/team/matches') }}">Matches</a></li>-->
            <li><a class="sidemenu_3" href="{{ url('/team/stats').'/'.$team_id.'/'.$sport_id }}"><span class="ico ico-stats"></span> Stats</a></li>
            <li><a class="sidemenu_4" href="{{ url('user/album/show').'/team'.'/0'.'/'.$team_id  }}"><span class="ico ico-media-gallery"></span> Gallery</a></li>
            <li><a class="sidemenu_5" href="{{ url('/team/schedule').'/'.$team_id.'/'.$sport_id }}"><span class="ico ico-schedule"></span> Schedule</a></li>
        <!--        <li><a class="sidemenu_6" href="{{ url('/team/communication') }}"><span class="ico ico-communication"></span> Communication</a></li>
        -->
            @if(in_array($team_id,$managing_team_ids))
                <li><a class="sidemenu_7" href="{{ url('/team/getteamrequests').'/'.$team_id }}"><span class="ico ico-request"></span> Requests</a></li>
            @endif
            @if (Helper::isTeamOwnerorcaptain($team_id,(isset(Auth::user()->id)?Auth::user()->id:0)))
                <li><a class="sidemenu_8" href="{{ url('/team/scores').'/'.$team_id.'/'.$sport_id }}"><span class="ico ico-scores"></span> Scores</a></li>
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