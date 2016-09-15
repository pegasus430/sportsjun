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

if (typeof SJ.TOURNAMENT === 'undefined')
{
        (function (z, $) {
                var g = SJ.TOURNAMENT;
                var o = {
                        init: function () {
                        },
                        joinTournament: function (userID, subTournamentID,sportID,tournamentType,tournamentName) {
                                var sport_id = sportID;
                                var val = tournamentType;
                                var id = subTournamentID;
                                var title = "Tournament";
                                if ($(".team_view h1").length > 0) {
                                        title = $(".team_view h1").html();
                                }
                                var title = (typeof tournamentName !== 'undefined') ? tournamentName : title;
                                var jsflag = 'Tournaments';
                                if(val === 'PLAYER_TO_TOURNAMENT')
                                {
                                        id = [$(this).attr('id')];
                                        var user_id = userID;
                                        $.confirm({
                                                title: 'Confirm',
                                                content: "Do you want to join "+title+"?",
                                                confirm: function() {
                                                $.post(site_url+'/team/saverequest',{flag:val,player_tournament_id:user_id,team_ids:id},function(response,status){
                                                        if(status == 'success')
                                                        {
                                                                if(response.status == 'success')
                                                                {
                                                                         $.alert({
                                                                        title: "Alert!",
                                                                        content: 'Request sent successfully.'
                                                                    });
                                                                                $("#hid_flag").val('');
                                                                                $("#hid_val").val('');
                                                                }
                                                                else if(response.status == 'exist')
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
                                                cancel: function() {
                                                    // nothing to do
                                                }
                                        });   
                                }
                                else
                                {
                                        generateteamsdiv(sport_id,val,id,title,jsflag);	
                                }
                        }
                };
                z.TOURNAMENT = o;
        })(SJ, $);

        $(function () {
                SJ.TOURNAMENT.init();
        });
}