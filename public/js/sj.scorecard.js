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
                                if ($("#match_report").length > 0)
                                {
                                        $("#match_report").summernote({
                                                height: 200,
                                                callbacks: {
                                                        onChange: function() {
                                                                $('#match_report').html($('#match_report').summernote('code'));
                                                        }
                                                },
                                                toolbar: [
                                                        ['style', ['style']],
                                                        ['style', ['bold', 'italic', 'underline', 'clear']],
                                                        ['font', ['strikethrough', 'superscript', 'subscript']],
                                                        ['fontsize', ['fontsize']],
                                                        ['color', ['color']],
                                                        ['para', ['ul', 'ol', 'paragraph']],
                                                        ['height', ['height']],
                                                        ['table', ['table']],
                                                        ['insert', ['link', 'hr']],
                                                        ['view', ['fullscreen', 'codeview']]
                                                ]
                                        });
                                }
                                if ($('#tossModal').length > 0)
                                {
                                        $('#tossModal').modal('show');
                                }
                                if ($('#endMatchModal').length > 0)
                                {
                                        $(document).on('ifChecked','#matchResultRadio input', function(){
                                                if (this.value == 'tie')
                                                {
                                                        $('#matchWinnerRadio').slideUp();
                                                        $('.winner_team_id').val('');
                                                }
                                                else
                                                {
                                                        $('#matchWinnerRadio').slideDown();
                                                        $('.winner_team_id').val($('#winner_id').val());
                                                }
                                                $('#match_result').val(this.value).change();
                                        });
                                        
                                        $(document).on('ifChecked','#matchWinnerRadio input', function(){
                                                $('#winner_id').val(this.value).change();
                                                $('.winner_team_id').val(this.value);
                                        });
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
                                $('#save_first_inning').trigger('click');
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
                        },
                        initTeamStats : function()
                        {
                                $('input.team_stat_readonly').each(function(){
                                        var name = $(this).attr("data-id");
                                        var value = $(this).val();
                                        $("input.form_team_stat_readonly[name='"+ name +"']").val(value);
                                });
                        },
                        adjustScore : function(inningName,teamVar,score,operation,currentScore)
                        {
                                var team_id             = (inningName === 'first') ? ($('#team_' + teamVar + '_id').val()) : ($('#team_' + teamVar + '_ids').val());
                                var $form_score         = $("input.form_team_stat_readonly[name='"+ inningName +"_inning[" + team_id + "][score]']");
                                var $heading_score      = $("input.team_stat_readonly[data-id='"+ inningName +"_inning[" + team_id + "][score]']");
                                currentScore            = (typeof currentScore !== 'undefined') ? parseInt(currentScore) : parseInt($form_score.val());
                                currentScore            = (operation === 'add') ? (currentScore + score) : (currentScore - score);
                                $form_score.val(currentScore);
                                $heading_score.val(currentScore);
                        },
                        adjustWicket : function(inningName,teamVar,wicketsCount,operation,currentWickets)
                        {
                                var team_id             = (inningName === 'first') ? ($('#team_' + teamVar + '_id').val()) : ($('#team_' + teamVar + '_ids').val());
                                var $form_wickets       = $("input.form_team_stat_readonly[name='"+ inningName +"_inning[" + team_id + "][wickets]']");
                                var $heading_wickets    = $("input.team_stat_readonly[data-id='"+ inningName +"_inning[" + team_id + "][wickets]']");
                                currentWickets          = (typeof currentWickets !== 'undefined') ? parseInt(currentWickets) : parseInt($form_wickets.val());
                                currentWickets          = (operation === 'add') ? (currentWickets + wicketsCount) : (currentWickets - wicketsCount);
                                $form_wickets.val(currentWickets);
                                $heading_wickets.val(currentWickets);
                        },
                        adjustOver : function(inningName,teamVar,oversCount,operation,currentOvers)
                        {
                                var team_id             = (inningName === 'first') ? ($('#team_' + teamVar + '_id').val()) : ($('#team_' + teamVar + '_ids').val());
                                var $form_overs         = $("input.form_team_stat_readonly[name='"+ inningName +"_inning[" + team_id + "][overs]']");
                                var $heading_overs      = $("input.team_stat_readonly[data-id='"+ inningName +"_inning[" + team_id + "][overs]']");
                                currentOvers            = (typeof currentOvers !== 'undefined') ? parseFloat(currentOvers) : parseFloat($form_overs.val());
                                currentOvers            = (operation === 'add') ? (currentOvers + oversCount) : (currentOvers - oversCount);
                                $form_overs.val(currentOvers);
                                $heading_overs.val(currentOvers);
                        },
                        endMatchModal : function()
                        {
                                if ($('#endMatchModal').length > 0)
                                {
                                    $('#match_result').val('win').change();
                                    $('.winner_team_id').val($('#winner_id').val());
                                    $('#endMatchModal').modal('show');
                                }
                        },
                        endMatch : function()
                        {
                                $('#save_first_inning').trigger('click');
                        }
                };
                z.SCORECARD = o;
        })(SJ, $);

        $(function () {
                SJ.SCORECARD.init();
        });
}