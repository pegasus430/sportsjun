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

if (typeof SJ.SCORECARD === 'undefined')
{
        (function (z, $) {
                var g = SJ.SCORECARD;
                var o = {
                        init: function () {
                                if ($('#tossModal').length > 0)
                                {
                                        $('#tossModal').modal('show');
                                }
                        },
                        doneTossModal: function() {
                                var toss_winning_team = parseInt($('#tossWonByRadio input[type=radio]:checked').attr('id'));
                                $('#tossWonBy select[name=toss_won]').val(toss_winning_team);
                                //$('#tossWonBy .selectpicker').selectpicker('refresh');
                                $('#tossWonBy select[name=toss_won]').change();
                                var toss_winning_team_name = $('#tossWonBy select option[value='+toss_winning_team+']').html();
                                toss_winning_team_name = SJ.GLOBAL.toTitleCase(toss_winning_team_name);
                                var batRadio = $('#batRadio input[type=radio]:checked').val();
                                var choseTo = 'Bat';
                                if (batRadio != 'bat')
                                {
                                        choseTo = 'Bowl';
                                        toss_winning_team = parseInt($('#tossWonByRadio input[type=radio]:not(:checked)').attr('id'));
                                }
                                var batTeam = $('#bat1stInning .selectpicker').find('option[data-status='+toss_winning_team+']').val();
                                $('#bat1stInning select[name=team]').val(batTeam);
                                //$('#bat1stInning .selectpicker').selectpicker('refresh');
                                $('#bat1stInning select[name=team]').change();
                                $('#tossWonBy').hide();
                                $('#bat1stInning').hide();
                                
                                if ($('#matchTossNote').length == 0)
                                {
                                        $('#tossWonBy').parent().prepend('<div id="matchTossNote">'+ toss_winning_team_name +' won the toss and chose to '+ choseTo +'.</div>');
                                }
                                else
                                {
                                        $('#matchTossNote').html(toss_winning_team_name +' won the toss and chose to '+ choseTo + '.');
                                }
                                
                                var first_batting_team_name = $('#tossWonBy select option[value='+toss_winning_team+']').html();
                                var heading_team_a_batting = $('#team_a_batting').html().toLowerCase();
                                if (heading_team_a_batting.indexOf(first_batting_team_name.toLowerCase()) > -1)
                                {
                                        $('#tossModal').modal('hide');
                                }
                        },
                        done2ndInningModal: function() {
                                var bat2ndInningTeam = parseInt($('#bat2ndInningBatting input[type=radio]:checked').attr('id'));
                                
                                var first_batting_team_name = $('#bat2ndInningBatting label[for='+ bat2ndInningTeam +']').html();
                                
                                bat2ndInningTeam = $('#bat2ndInning select[name=team]').find('option[data-status='+bat2ndInningTeam+']').attr('value');
                                $('#bat2ndInning select[name=team]').val(bat2ndInningTeam);
                                //$('#bat2ndInning .selectpicker').selectpicker('refresh');
                                $('#bat2ndInning select[name=team]').change();
                                $('a[href="#second_innings"]').removeAttr('onclick');
                                
                                var heading_2nd_innings_team_a_batting = $('#second_team_a_batting').html().toLowerCase();
                                if (heading_2nd_innings_team_a_batting.indexOf(first_batting_team_name.toLowerCase()) > -1)
                                {
                                        $('#secondInningsBatModal').modal('hide');
                                }
                        },
                        secondInningBattingOrderModal: function()
                        {
                                if ($('#secondInningsBatModal').length > 0)
                                {
                                        $('#secondInningsBatModal').modal();
                                }
                        },
                        firstInningsModal: function()
                        {
                                if ($('#tossModal').length > 0)
                                {
                                        $('#tossModal').modal();
                                }
                        }
                };
                z.SCORECARD = o;
        })(SJ, $);

        $(function () {
                SJ.SCORECARD.init();
        });
}