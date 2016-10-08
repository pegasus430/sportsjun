"use strict";
var SJ = SJ || {};

if (typeof SJ.GLOBAL === 'undefined') {
        (function () {
                var _global_js = 'js/sj.global.js';
                window.site_url = (typeof site_url === 'undefined') ? 'http://' + window.location.hostname : site_url;
                window.secure_url = (typeof secure_url === 'undefined') ? 'https://' + window.location.hostname : secure_url;

                var _node = document.createElement('script');
                _node.type = 'text/javascript';
                _node.src = ((window.location.protocol === 'http:') ? site_url : secure_url) + '/' + _global_js;
                document.getElementsByTagName('head')[ 0 ].appendChild(_node);
        })();
}

if (typeof SJ.TEAM === 'undefined')
{
        (function (z, $) {
                var g = SJ.TEAM;
                var o = {
                        init: function () {
                                if ($('#left-menu-sport-name').length > 0)
                                {
                                        this.sportID = $('#left-menu-sport-name').attr('data-sport-id');
                                }
                                if ($('#left-menu-team-name').length > 0)
                                {
                                        this.teamID = $('#left-menu-team-name').attr('data-team-id');
                                        this.scheduleType = $('#left-menu-team-name').attr('data-schedule-type');
                                }
                        },
                        addToTeam: function (sportID, userID) {
                                var title = $("#userFullName span:first").html();
                                var jsflag = '';
                                generateteamsdiv(sportID, 'TEAM_TO_PLAYER', userID, title, jsflag);
                        },
                        joinTeam: function (teamID, userID, teamName) {
                                var id = [teamID];
                                var val = 'PLAYER_TO_TEAM';
                                var user_id = userID;
                                $.confirm({
                                        title: 'Confirm',
                                        content: "Do you want to join " + teamName + "?",
                                        confirm: function () {
                                                $.post(site_url + '/team/saverequest', {flag: val, player_tournament_id: user_id, team_ids: id}, function (response, status) {
                                                        if (status == 'success')
                                                        {
                                                                if (response.status == 'success')
                                                                {
                                                                        $.alert({
                                                                                title: "Alert!",
                                                                                content: 'Request sent successfully.'
                                                                        });
                                                                        $("#hid_flag").val('');
                                                                        $("#hid_val").val('');
                                                                }
                                                                else if (response.status == 'exist')
                                                                {
                                                                        $.alert({
                                                                                title: "Alert!",
                                                                                content: 'Request already sent.'
                                                                        });
                                                                        $("#hid_flag").val('');
                                                                        $("#hid_val").val('');
                                                                }
                                                                else
                                                                {
                                                                        $.alert({
                                                                                title: "Alert!",
                                                                                content: 'Failed to send the request.'
                                                                        });
                                                                        $("#hid_flag").val('');
                                                                        $("#hid_val").val('');
                                                                }
                                                        }
                                                        else
                                                        {
                                                                $.alert({
                                                                        title: "Alert!",
                                                                        content: 'Failed to send the request.'
                                                                });
                                                                $("#hid_flag").val('');
                                                                $("#hid_val").val('');
                                                        }
                                                })
                                        },
                                        cancel: function () {
                                                // nothing to do
                                        }
                                });

                        },
                        scheduleMatch: function() {
                                $('#main_match_schedule').trigger('click');
                                if (typeof this.sportID !== 'undefined')
                                {
                                        $('#main_sports_id').val(this.sportID);
                                }
                        }
                };
                z.TEAM = o;
        })(SJ, $);

        $(function () {
                SJ.TEAM.init();
        });
}