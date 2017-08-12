@extends('admin.layouts.app')
@section('content')
@include ('admin.tournaments._leftmenu')
<div id="page-wrapper">
<div class="col_middle group_stage">
    
    <div class="col-lg-9 leftsidebar group-stage">
	<div class="panel panel-default">
                    <div class="panel-body">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs nav-justified">
					<li class="active"><a href="#group_stage" data-toggle="tab" aria-expanded="true">GROUP STAGE</a>
					</li>
					<li class=""><a href="#final_stage" data-toggle="tab" aria-expanded="false">FINAL STAGE</a>
					</li>
				</ul>
			
                    <div  class="tab-content clearfix">
                        <div id="group_stage" class="tab-pane fade active in">
                        <!-- /.panel-heading -->
							@if($tournament_type=='league' || $tournament_type=='multistage' || $tournament_type=='doublemultistage')
								@include ('tournaments.editablegroup')
							@else
								@include ('tournaments.viewablegroup')
							@endif	
                        </div>
                        <div id="final_stage" class="tab-pane fade" >
                            @include ('tournaments.final',[$tournamentDetails])
                                    
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>        
	</div>
    </div>   
    
    <div class="col-lg-3 rightsidebar">
    <div class="cn_tournament_box">
        	<h4 class="cntb_head">Create New Tournaments</h4>
            <p>Create and mange your sports team with no more paper scoring. Socre all your macthes live and reach all your team's followers.</p>
            <a href="#" class="btn btn-black">Create Team</a>
        </div>
    	<div class="suggestion_box">
        	<h4 class="sb_head">Suggested Tournaments</h4>
        	<div class="row">
            	<div class="sb_hover">
            
                                    	<div class="sb_details">
                                    <div class="col-md-4 col-sm-2 col-xs-2 text-center">
                                      <img class="img-circle img-border" src="http://localhost/sportsjun/public/images/sunrisers_hyd.png" style="width: 90%;height:90%;">
                                    </div>
                                    <div class="col-md-8 col-sm-10 col-xs-10">
                                            <p class="sb_title"><strong>Indian Premier League</strong></p>
                                            <ul class="sb_tags">
                                                <li>
                                                    <small><span class="grey">Sports</span>
                                                    <span class="black">Cricket</span></small>
                                                </li>
                                                <li>
                                                    <small><span class="grey">Teams</span>
                                                    <span class="black">10</span></small>
                                                </li>
                                            </ul>
                                            <div class="sb_join"><a href="#">Join</a></div>
                                    </div>
                                    </div>
                                 </div>
                                </div>      
			<div class="row">
            	<div class="sb_hover">
            
                                    	<div class="sb_details">
                                    <div class="col-md-4 col-sm-2 col-xs-2 text-center">
                                      <img class="img-circle img-border" src="http://localhost/sportsjun/public/images/sunrisers_hyd.png" style="width: 90%;height:90%;">
                                    </div>
                                    <div class="col-md-8 col-sm-10 col-xs-10">
                                            <p class="sb_title"><strong>Indian Premier League</strong></p>
                                            <ul class="sb_tags">
                                                <li>
                                                    <small><span class="grey">Sports</span>
                                                    <span class="black">Cricket</span></small>
                                                </li>
                                                <li>
                                                    <small><span class="grey">Teams</span>
                                                    <span class="black">10</span></small>
                                                </li>
                                            </ul>
                                            <div class="sb_join"><a href="#">Join</a></div>
                                    </div>
                                    </div>
                                 </div>
                                </div> 
			<div class="row">
            	<div class="sb_hover">
            
                                    	<div class="sb_details">
                                    <div class="col-md-4 col-sm-2 col-xs-2 text-center">
                                      <img class="img-circle img-border" src="http://localhost/sportsjun/public/images/sunrisers_hyd.png" style="width: 90%;height:90%;">
                                    </div>
                                    <div class="col-md-8 col-sm-10 col-xs-10">
                                            <p class="sb_title"><strong>Indian Premier League</strong></p>
                                            <ul class="sb_tags">
                                                <li>
                                                    <small><span class="grey">Sports</span>
                                                    <span class="black">Cricket</span></small>
                                                </li>
                                                <li>
                                                    <small><span class="grey">Teams</span>
                                                    <span class="black">10</span></small>
                                                </li>
                                            </ul>
                                            <div class="sb_join"><a href="#">Join</a></div>
                                    </div>
                                    </div>
                                 </div>
                                </div>      
			<div class="row">
            	<div class="sb_hover">
            
                                    	<div class="sb_details">
                                    <div class="col-md-4 col-sm-2 col-xs-2 text-center">
                                      <img class="img-circle img-border" src="http://localhost/sportsjun/public/images/sunrisers_hyd.png" style="width: 90%;height:90%;">
                                    </div>
                                    <div class="col-md-8 col-sm-10 col-xs-10">
                                            <p class="sb_title"><strong>Indian Premier League</strong></p>
                                            <ul class="sb_tags">
                                                <li>
                                                    <small><span class="grey">Sports</span>
                                                    <span class="black">Cricket</span></small>
                                                </li>
                                                <li>
                                                    <small><span class="grey">Teams</span>
                                                    <span class="black">10</span></small>
                                                </li>
                                            </ul>
                                            <div class="sb_join"><a href="#">Join</a></div>
                                    </div>
                                    </div>
                                 </div>
                                </div> 
			<div class="row">
            	<div class="sb_hover">
            
                                    	<div class="sb_details">
                                    <div class="col-md-4 col-sm-2 col-xs-2 text-center">
                                      <img class="img-circle img-border" src="http://localhost/sportsjun/public/images/sunrisers_hyd.png" style="width: 90%;height:90%;">
                                    </div>
                                    <div class="col-md-8 col-sm-10 col-xs-10">
                                            <p class="sb_title"><strong>Indian Premier League</strong></p>
                                            <ul class="sb_tags">
                                                <li>
                                                    <small><span class="grey">Sports</span>
                                                    <span class="black">Cricket</span></small>
                                                </li>
                                                <li>
                                                    <small><span class="grey">Teams</span>
                                                    <span class="black">10</span></small>
                                                </li>
                                            </ul>
                                            <div class="sb_join"><a href="#">Join</a></div>
                                    </div>
                                    </div>
                                 </div>
                                </div>    
        </div>
    </div>
    
</div>	
    
</div>	
@include ('tournaments.addtournamentschedule',[])

<script>
    $(function() {
		var sport_id = $('#sport_id').val();
		var tournament_id = $('#tournament_id').val();
		var schedule_type = $('#schedule_type').val();
        $(".test").autocomplete({
			source: base_url+'/tournaments/getSportTeams/'+sport_id+'/'+tournament_id+'/'+schedule_type,
            minLength: 3,
            select: function(event, ui) {
                $('#response').val(ui.item.id);
                $('#team_name').val(ui.item.value);
            }
        });
    });
	function addTeam(group_id)
    {
		var team_count = $('#team_count').val();
        var token = "<?php echo csrf_token(); ?>";
        var response = $('#response').val();
        var team_name = $('#team_name').val();
        $.ajax({
            url: base_url+'/tournaments/addteamtotournament',
            type: "post",
            dataType: 'JSON',
            data: {'_token': token, 'response': response,'group_id':group_id,'team_name':team_name,'team_count':team_count},
            success: function(data) {
				$( "#msg" ).append( data.success );
				$('#response').val('');
				$('.test').val('');
				location.reload();
            }
        });
    }
	//function to create group
	function createGroup()
	{
		$('#create_group').show();
	}
	function editGroup(group_id)
	{
		$('#edit_group_'+group_id).show();
	}
	function insertgroup(tournament_id,group_numbers)//inset group
	{
		var group = $('#group').val();
		if($.isNumeric(group)==true && group>0)
		{
			var token = "<?php echo csrf_token(); ?>";
			$.ajax({
				url: base_url+'/tournaments/insertTournamentGroup',
				type: "post",
				dataType: 'JSON',
				data: {'_token': token, 'tournament_id': tournament_id,'group_numbers':group_numbers,'group':group},
				success: function(data) {
					// alert(data.success);
					// console.log(data);
					$( "#msg" ).append( data.success );
					$('#group').val('');
					location.reload();
				}
			});
		}else{
			alert('Enter Only Numbers.');
			$('#group').val('');
			location.reload();
			$( "#msg" ).append( 'Enter Only Numbers.' );
		}
			
		
	}
	function editgroupname(group_id)
	{
		var group = $('#group_name_'+group_id).val();
		var token = "<?php echo csrf_token(); ?>";
		$.ajax({
            url: '/tournament/groupedit/'+'edit'+'/'+group_id,
            type: "get",
            dataType: 'JSON',
            data: {'_token': token,'group':group},
            success: function(data) {
                $( "#msg" ).append( data.success );
				location.reload();
            }
        });
	}
</script>
@endsection
