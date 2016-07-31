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
                        matchResult: ($('#match_result').val()  !== "undefined") ? $('#match_result').val() : "",
                        matchWinner: ($('#winner_id').val()     !== "undefined") ? $('#winner_id').val()    : "",
                        init: function () {

                                $(".team_a_overs,.team_b_overs,.out_at_over").on("keypress keyup blur",function (e) {
                                        var overs_bowled = $(this).val();
                                        if (overs_bowled.indexOf('.') !== -1)
                                        {
                                                var oversArr = overs_bowled.split('.');
                                                var balls = parseInt(oversArr[1]);
                                                if (balls > 0)
                                                {
                                                        overs_bowled = parseInt(oversArr[0]) + (balls / 6);
                                                        if (overs_bowled.toString().indexOf('.') === -1)
                                                        {
                                                                $(this).val(overs_bowled);
                                                        }
                                                }
                                        }
                                        if (!o.isValidOversInput($(this), e))
                                        {
                                                e.preventDefault();
                                        }
                                });
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
                                                SJ.SCORECARD.matchResult = this.value;
                                                (this.value == 'tie') ? $('#matchWinnerRadio').slideUp() : $('#matchWinnerRadio').slideDown();
                                        });

                                        $(document).on('ifChecked','#matchWinnerRadio input', function(){
                                                SJ.SCORECARD.matchWinner = this.value;
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
                                        if (SJ.SCORECARD.matchResult === "tie")
                                        {
                                                $("#matchWinnerRadio").hide();
                                        }
                                        $('#endMatchModal').modal('show');
                                }
                        },
                        endMatch : function()
                        {
                                $('#match_result').val(SJ.SCORECARD.matchResult).change();
                                if (SJ.SCORECARD.matchResult === "tie")
                                {
                                        $('#winner_id').val("").change();
                                        $('.winner_team_id').val('');
                                }
                                else
                                {
                                        $('#winner_id').val(SJ.SCORECARD.matchWinner).change();
                                        $('.winner_team_id').val(SJ.SCORECARD.matchWinner);
                                }
                                $('#save_first_inning').trigger('click');
                        },
                        isValidOversInput: function(t,e)
                        {
                                var keyCode = e.which;
                                var overs = t.val();
                                if (overs.indexOf('.') !== -1)
                                {
                                        var oversArr = overs.split('.');
                                        if (oversArr[1] !== "" || keyCode < 48 || keyCode > 54)
                                        {
                                                return false;
                                        }
                                }
                                return true;
                        },

                        soccerSetTimes:function(that){
                                //$('.soccer_buttons_disabled').attr('disabled', true);
                                $('#new_records_match').show();
                                $('#end_match').modal('show');
                                return false;

                        },
                        soccerChooseTime:function(that){
                                $('#half_time').val($(that).val());
                        },

                        getSelectedPlayerId:function(){
                                var player_id=$('#selected_player_id_value').val();
                                if(player_id==0){
                                        SJ.SCORECARD.showAlert('Alert!', 'Please select a player from the lineup. ');
                                        return false;
                                }
                                else return player_id;
                        },
                        soccerAddGoal:function(player_id){

                                var player_id=SJ.SCORECARD.getSelectedPlayerId();

                                var index=$('#last_index').val();
                                if(player_id){
                                        $('#new_records_match').show();
                                        $('#new_records_match').show();
                                         var player_content=$('#team_a_row_'+player_id)
                                         if(player_content.is("[goals]") && Number(player_content.attr('goals'))>0){
                                                //if user has a yellow card return false
                                                return false;
                                        }
                                        $('#team_a_row_'+player_id).attr('goals',1);
                                        SJ.SCORECARD.soccerAddField(player_id,'goals', 'Goal');
                                }
                                return false;
                        },

                        soccerRedCard:function(){
                                var player_id=SJ.SCORECARD.getSelectedPlayerId();
                                var index=$('#last_index').val();
                                if(player_id){
                                        $('#new_records_match').show();
                                         var player_content=$('#team_a_row_'+player_id)
                                         if(player_content.is("[red_card]") && Number(player_content.attr('red_card'))>0){
                                                //if user has a yellow card return false
                                                return false;
                                        }
                                        $('#team_a_row_'+player_id).attr('red_card',1);
                                        SJ.SCORECARD.soccerAddField(player_id,'red_card', 'Red Card');
                                }
                                return false;
                        },
                        soccerYellowCard:function(){
                                var player_id=SJ.SCORECARD.getSelectedPlayerId();
                                if(player_id){ 
                                        $('#new_records_match').show();
                                        var player_content=$('#team_a_row_'+player_id)
                                        if(player_content.is("[yellow_card]") && Number(player_content.attr('yellow_card'))>0){
                                                //if user has a yellow card return false
                                                $.alert({
                                                        title:'Alert',
                                                        content:'This player already has a yellow card.'
                                                })
                                                return false;
                                        }
                                        $('#team_a_row_'+player_id).attr('yellow_card',1);
                                        SJ.SCORECARD.soccerAddField(player_id,'yellow_card', 'Yellow Card');                                        
                                }
                                return false;
                        },
                        soccerPenalties:function(){

                        },

                        soccerAddField:function(player_id, record_type,record_type_name){

                                var player_content=$('#team_a_row_'+player_id);                                
                                var user_id= player_content.attr('user_id')
                                var player_name=player_content.attr('player_name');
                                var team_type=player_content.attr('team_type');
                                var team_id=player_content.attr('team_id');
                                var half_time=$('#half_time').val();
                                var match_id=$('#match_id').val();
                                var tournament_id=$('#tournament_id').val();
                                var team_a_id=$('#team_a_id').val();
                                var team_b_id=$('#team_b_id').val();
                               
                                var index=Number($('#last_index').val());
                                index++;

                                $('#last_index').val(index);

                                        if(half_time=='first_half') var displayField='#displayGoalsFirstHalfTemporal';
                                        else var displayField='#displayGoalsSecondHalfTemporal';

                                //create a new form for content
                                $(displayField).append("<form id='form_record_"+index+"' onsubmit='return saveRecord("+index+", \""+record_type+"\", "+player_id+")' class='col-sm-12 col-xs-12'>");
                                var displayFormContent=$('#form_record_'+index);

                                if(team_type=='team_b'){
                                        if(half_time=='first_half'){
                                                displayFormContent.append("<div  class='records col-sm-12 '><div  class='col-sm-4 not-visible-xs'></div><div  class='col-sm-2 col-xs-6'><input type='number' min='0' placeholder='time e.g 20' name='time_"+index+"' class='gui-input col-sm-12 col-xs-12   input_first_half'  required></div><div  class='col-sm-2 col-xs-6'>"+record_type_name+"</div> <div  class='col-sm-2 col-xs-6'>"+player_name+"</div><div  class='col-sm-2 col-xs-6'><a href='#' onclick='deleteRow(this, "+index+", "+player_id+",\""+record_type+"\")' class='btn btn-danger btn-circle btn-sm'><i class='fa fa-remove'></i></a><button class='btn btn-success btn-circle btn-sm saveMatchForm' type='submit' index='"+index+"'><i class='fa fa-check'></i></button></div></div>");
                                        }
                                        else{
                                                displayFormContent.append("<div  class='records col-sm-12 '><div  class='col-sm-4 not-visible-xs'></div><div  class='col-sm-2 col-xs-6'><input type='number' min='0' placeholder='time e.g 20' name='time_"+index+"' class='gui-input col-sm-12 col-xs-12 input_first_half'  required></div><div  class='col-sm-2 col-xs-6'>"+record_type_name+"</div> <div  class='col-sm-2 col-xs-6'>"+player_name+"</div><div  class='col-sm-2 col-xs-6'><a href='#' onclick='deleteRow(this, "+index+", "+player_id+",\""+record_type+"\")' class='btn btn-danger btn-circle btn-sm'><i class='fa fa-remove'></i></a><button class='btn btn-success btn-circle btn-sm saveMatchForm' type='submit' index='"+index+"'><i class='fa fa-check'></i></button></div></div>");
                                        }
                                }
                                else{
                                        if(half_time=='first_half'){
                                                displayFormContent.append("<div  class='records col-sm-12 '><div  class='col-sm-2 col-xs-6'>"+player_name+"</div><div  class='col-sm-2 col-xs-6'>"+record_type_name+"</div><div  class='col-sm-2 col-xs-6'><input type='number' min='0' placeholder='time e.g 20' class=' col-sm-12 col-xs-12  input_second_half'  name='time_"+index+"' required></div><div  class='col-sm-4 not-visible-xs'></div><div  class='col-sm-2 col-xs-6'><a href='#' onclick='deleteRow(this, "+index+", "+player_id+",\""+record_type+"\")' class='btn btn-danger btn-circle btn-sm'><i class='fa fa-remove'></i></a><button class='btn btn-success btn-circle btn-sm saveMatchForm' type='submit' index='"+index+"'><i class='fa fa-check'></i></button></div></div>");
                                        }
                                        else{
                                                displayFormContent.append("<div  class='records col-sm-12 '><div  class='col-sm-2 col-xs-6'>"+player_name+"</div><div  class='col-sm-2 col-xs-6'>"+record_type_name+"</div><div  class='col-sm-2 col-xs-6'><input type='number' min='0' placeholder='time e.g 20' class=' col-sm-12 col-xs-12 input_second_half'  name='time_"+index+"' required></div><div  class='col-sm-4 not-visible-xs'></div><div  class='col-sm-2 col-xs-6'><a href='#' onclick='deleteRow(this, "+index+", "+player_id+",\""+record_type+"\")' class='btn btn-danger btn-circle btn-sm'><i class='fa fa-remove'></i></a><button class='btn btn-success btn-circle btn-sm saveMatchForm' type='submit' index='"+index+"'><i class='fa fa-check'></i></button></div></div>");
                                        }
                                }
                                displayFormContent.append("<input type='hidden' name='player_"+index+"' value='"+player_id+"'>");
                                displayFormContent.append("<input type='hidden' name='record_type_"+index+"' value='"+record_type+"'>");
                                displayFormContent.append("<input type='hidden' name='user_"+index+"' value='"+user_id+"'>");
                                displayFormContent.append("<input type='hidden' name='team_"+index+"' value='"+team_id+"'>");
                                displayFormContent.append("<input type='hidden' name='half_time_"+index+"' value='"+half_time+"'>");
                                displayFormContent.append("<input type='hidden' name='team_type_"+index+"' value='"+team_type+"'>");
                                displayFormContent.append("<input type='hidden' name='player_name_"+index+"' value='"+player_name+"'>");

                                displayFormContent.append("<input type='hidden' name='match_id' value='"+match_id+"'>");
                                displayFormContent.append("<input type='hidden' name='tournament_id' value='"+tournament_id+"'>");
                                displayFormContent.append("<input type='hidden' name='team_a_id' value='"+team_a_id+"'>");
                                displayFormContent.append("<input type='hidden' name='team_b_id' value='"+team_b_id+"'>");
                                displayFormContent.append("<input type='hidden' name='index' value='"+index+"'>");
                        

                        },

                        showAlert:function (title, content){
                                $.alert({ title: title, content: content });
                        },
                        selectMatchType: function (that){
                                        var val=$(that).val();

                                        if(val=='washout'){
                                            $.confirm({
                                                title:'Alert',
                                                content:"All info for this match shall be discarded, and both teams shall have same points, do you want to continue?", 
                                                confirm:function(){
                                                  $('#select_winner').hide();
                                                  $('.scorescard_stats').hide();
                                                  return true
                                                },
                                                cancel:function(){
                                                    $(that).val('win');
                                                    $('#select_winner').show();
                                                    $('.scorescard_stats').show();
                                                }
                                             })
                                        }
                                        else{
                                          $('#select_winner').show();
                                          $('.scorescard_stats').show();
                                        }
                        },
                        tempData:{},

                };
                z.SCORECARD = o;
        })(SJ, $);

        $(function () {
                SJ.SCORECARD.init();
        });
}