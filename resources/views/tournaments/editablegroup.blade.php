<p class="help-block" id="msg"></p> 
				    @if (session('status'))
                    <div class="alert alert-success" >
                        {{ session('status') }}
                    </div>
                    @endif
                        <!-- /.panel-heading -->
			@foreach($tournament as $tour)
			@foreach($tour->groups as $group)
			<div id="group_{{ $group->id }}">
            
            <div class="group_no clearfix">
            	<div class="pull-left"><h4 class="stage_head">{{ $group->name }}</h4></div>
                <div class="pull-right ed-btn">
                	<a onclick="editGroup({{ $group->id }});" class="edit"><i class="fa fa-pencil"></i></a>
                    <a href="#" onclick="deleteGroup({{ $tournament_id }},{{ $group->id }})" class="delete" ><i class="fa fa-remove"></i></a>
                </div>
            </div>
            
            <div id="edit_group_{{ $group->id }}" class="group_edit">
				<div class="form-group"><input type="text" class="gui-input" id="group_name_{{ $group->id }}" value="{{ $group->name }}"></div>
				<button type="button" name="editgroup" id="editgroup" onClick="editgroupname({{ $group->id }});" class="button btn-primary">
						Update Group Name
				</button>
			</div>
            
			<div class="cstmpanel-tabs">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs nav-justified">
					<li class="active"><a href="#teams_{{ $group->id }}" data-toggle="tab" aria-expanded="true">Teams</a></li>
					<li class=""><a href="#matches_{{ $group->id }}" data-toggle="tab" aria-expanded="false" style="border-right: 1px solid #ddd">Matches</a></li>
				</ul>
				<!-- Tab panes -->
				{!! Form::open(array('url' => '', 'files'=> true)) !!}
				<div class="tab-content sportsjun-forms">
                <?php $table_count = 0;?>
				@if(!empty($team_details[$group->id]))
						<?php $table_count=count($team_details[$group->id]);?>	

                        @endif
					<div class="tab-pane fade active in" id="teams_{{ $group->id }}">
                    <div class="action-panel group-multiselect">
                    	<div class="action-btns">
                    		 @if($tournamentDetails[0]['schedule_type']=='team')
	                        	<label>
	                            	<input type="radio" name="team_selection" class="selectTeamClass" groupId="{{$group->id}}" checked="true" value="reqested_teams"/>Requested Teams</label>
	                            <label>
	                            	<input type="radio" name="team_selection" class="selectTeamClass" groupId="{{$group->id}}" value="all_teams"/>All Teams
	                            </label>
	                        @else
	                            <label>
	                            	<input type="radio" name="team_selection" class="selectTeamClass" groupId="{{$group->id}}" checked="true" value="reqested_teams"/>Requested Teams</label>
	                            <label>
	                            	<input type="radio" name="team_selection" class="selectTeamClass" groupId="{{$group->id}}" value="all_teams"/>Add Player
	                            </label>
	                             <label>
	                                <input type="radio" name="team_selection" class="selectTeamClass" groupId="{{$group->id}}"  value="invite_player"/>Invite Player
	                            </label>
                       		 @endif
						</div>
                        <div class="ui-widget" id="all_teams_div_{{$group->id}}" style="display:none;">
							<div class="form-group"><input type="text" id="user" class="gui-input test" placeholder="Add Team"></div>
							<input id="response" name="response" class="form-control" type="hidden">
							<input id="team_name" name="team_name" class="form-control" type="hidden">
							<button type="button" name="add_team" id="add_team" onClick="addTeam({{ $group->id }},'auto',{{$table_count}});" class="btn-link btn-primary-link" style="margin:-5px 0 0 0;">
							Save
							</button>
					</div>

			 @if($tournamentDetails[0]['schedule_type']!='team')
                     <div id="invite_player_div_{{$group->id}}" style="display:none;" >
                        <div class="row">
                            <div class="col-sm-4">
                                <label class="tab_new_label_txt"><b>Non-Registered User</b></label>
                                <div class="form-group">
                                    <input type="text" id="player_name_{{$group->id}}" class="gui-input" placeholder="Player name">
                                </div>    
                        
                                <div class="form-group" >
                                    <input type="text" id="player_email_{{$group->id}}" class="gui-input" placeholder="Email (optional)">
                                </div>
                            </div>

                            <div class='col-sm-3'>
                            <br><br>
                            <button type="button" name="invite_team_button" id="invite_team_button" onClick="addTeam({{ $group->id }},'invite',{{$table_count}});" class="btn-link btn-primary-link">Invite</button>
                            </div>
                        </div>
                    </div>
                @endif

						
                            <div id="req_teams_div_{{$group->id}}">
								<span style="margin-right: 15px;">{!! Form::select('req_team[]',$requestedTeams,null, array('multiple'=>true,'class'=>'multiselect req_team_class','id'=>$group->id.'_req_team')) !!}
								<i class="arrow double"></i></span>
								<button type="button" name="add_req_team" id="add_req_team" onClick="addTeam({{ $group->id }},'select',{{$table_count}});" class="btn-link btn-primary-link" style="margin:-5px 0 0 0;">
								Save
								</button>
							</div>
                            <div class="clearfix"></div>
                            <div class="table-responsive groups-table">
								<?php $table_count = 0;?>
                                <table class="table table-striped" id="records_table_{{$group->id}}">
                                    <thead class="thead">
                                    <tr>
                                    <th>Rank </th>
                                    <th>Name</th>
                                    <th>Mat</th>
                                    <th>Won</th>
                                    <th>Lost</th>
                                    <th>Draw</th>
                                @if(in_array($sports_id, [4,11]))                               		
                                    <th>GF</th>
                                    <th>GA</th>
                                @endif
                                    <th>Points</th>
                                    @if ( $tour['sports_id'] == 1 )
                                    <th>Net Run Rate</th>
                                    @endif
                                    <th></th>
                                    </tr>
                                    </thead>
                                <tbody>
                                @if(!empty($team_details[$group->id]))
                                <?php $table_count=count($team_details[$group->id]);?>	
                                    @foreach($team_details[$group->id] as $key_team=>$team)

               <?php $match_count_details=Helper::getMatchGroupDetails($tournament_id, $group->id, $team['team_id']);?>
                                    
                                    <tr id="row_{{$team['id']}}" class="group_row_{{$group->id}}">
                                    <td>{{ ($key_team + 1) }}</td>
                                    <td>{{ $team['name'] }}</td>
                                    <td>{{ !empty($match_count[$group->id][$team['team_id']])?$match_count[$group->id][$team['team_id']]:0 }}</td>
                                    <td>{{ !empty($team['won'])?$team['won']:0 }}</td>
                                    <td>{{ !empty($team['lost'])?$team['lost']:0 }}</td>

                           		    <td>{{ !empty($team['tie'])?$team['tie']:0 }}</td>
                                @if(in_array($sports_id, [4,11]))                               		
                                    <td>{{ !empty($team['gf'])?$team['gf']:0 }}</td>
                                   <td>{{ !empty($team['ga'])?$team['ga']:0 }}</td>                                 
                                @endif

                                    <td>{{ !empty($team['points'])?$team['points']:0 }}</td>
                                    @if ( $tour['sports_id'] == 1 )
                                    <td>{{ !empty($net_run_rate[$team['team_id']])?$net_run_rate[$team['team_id']]:"--" }}</td>
                                    @endif
                                    <td><a href="#" class="btn btn-danger btn-circle btn-sm" onclick="deleteTeam({{$tournament_id}},{{$team['tournament_group_id']}},{{$team['id']}},{{$team['team_id']}});"><i class="fa fa-remove"></i></a></td>
                                    </tr>	
                                    @endforeach
                                @else
                                    <tr id="no_teams_{{$group->id}}">
			                                @if(in_array($sports_id, [4,11]))                               		
			                                    <td colspan="9">
			                                @else
			                                	<td colspan="7">
                                			@endif
                                    	{{trans('message.tournament.empty_teams') }}</td></tr>
                                @endif
                                </tbody>
                                </table>
                                <input type="hidden" id="sport_id" value="{{ $tour['sports_id'] }}">
                                <input type="hidden" id="tournament_id" value="{{ $tour['id'] }}">
                                <input type="hidden" id="team_count" value="{{ $tour->groups_teams }}">
                                <meta name="_token" content="<?php echo csrf_token(); ?>" />
                            </div>
							
							

						
						</div>
					</div>
					<div class="tab-pane fade" id="matches_{{ $group->id }}">
                    <div class="action-panel">

                    	<div class="pull-left half-width col-xs-12 col-sm-6"> <input class='dark-border full-width form-control' placeholder="filter match e.g team name, date" onkeyup="filterDiv(this, {{$group->id}})"></div>
                    
						<div class="btn btn-event pull-right" onclick="schedulegroupmatches({{ $group->id }})"><i class="fa fa-calendar"></i> {{ trans('message.schedule.fields.schedulematch') }}</div>
                        <div class="clearfix"></div>
						<!--<h4>matches</h4>-->
						@if(!empty($match_details[$group->id]))

						<div class="schedule_table_new">
							<div>
							<?php $i=1;?>
							@foreach($match_details[$group->id] as $match)
							<?php 
								$class='schedule_new_req_nor';	
								if($i % 2 == 0)
								{
									$class='schedule_new_req_alt';	
								}else
								{
									
									$class='schedule_new_req_nor';	
								}
							?>
							@if($match['a_id']!='' && $match['b_id'])
								@if($match['schedule_type']=='team')
							<div class="row <?php echo $class;?> row_to_filter_{{$group->id}}">
						
								<div class="col-md-3 schedule_new_team_img">
								@if(!empty($team_logo[$match['a_id']]))
									@if($team_logo[$match['a_id']]['url']!='')
									<!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/teams/'.$team_logo[$match['a_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
									<div class="team_player_sj_img">
										{!! Helper::Images($team_logo[$match['a_id']]['url'],'teams',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
									</div>						
									@else
									<!--<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
									<div class="team_player_sj_img">
										{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
									</div>
									@endif
								@else
								<!--<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
								<div class="team_player_sj_img">
                                	{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
                                </div>
								@endif
								{{ 'VS' }}
								@if(!empty($team_logo[$match['b_id']]))
									@if($team_logo[$match['b_id']]['url']!='')
									<!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/teams/'.$team_logo[$match['b_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
									<div class="team_player_sj_img">
										{!! Helper::Images($team_logo[$match['b_id']]['url'],'teams',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
									</div>                    
									@else
								<!--	<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
									<div class="team_player_sj_img">
										{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}		
									</div>
									@endif
								@else
								<!--<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
								<div class="team_player_sj_img">
                                	{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
                                </div>
								@endif	
								</div>
								<div class="col-md-6 col-sm-8 schedule_new_team_txt">
									
                                	<h4 class="tour-title">
                                    	{{ $team_name_array[$match['a_id']] }}
                                        {{ 'VS' }}                                        
                                        {{ $team_name_array[$match['b_id']] }}
                                    </h4>
                                    <br>									
									<span class="match-detail-score">{{ Helper::displayDateTime($match['match_start_date'] . (isset( $match['match_start_time'] ) ? " " . $match['match_start_time'] : ""), 1) }}</span>
									
									<span class='sports_text'>{{ isset($sport_name)?$sport_name:'' }}</span>
									
									@if($match['match_type']!='other')
											<span class='match_type_text'>({{ $match['match_type']=='odi'?strtoupper($match['match_type']):ucfirst($match['match_type']) }}, {{ucfirst($match['match_category'])}})</span>
									@endif
									<br/>
									<!-- match_details -->
									
									<span class=''>{{$match['address']}}</span><br>
									Status: <span class='event_date'>{{ ucfirst($match['match_status']) }}</span> <br>
									Scores: <span class='blue'>{{Helper::getMatchDetails($match['id'])->scores}} </span> <br>
									@if(!is_null($match['winner_id']))
								<span class='red'>Winner: {{Helper::getMatchDetails($match['id'])->winner}} </span>
								
									@endif

									<br>
									@if(!empty($add_score_link[$match['id']]))
										@if($add_score_link[$match['id']]==trans('message.schedule.viewscore'))
											<span class="tournament_score"><a href="{{ url('match/scorecard/view/'.$match['id']) }}">{{$add_score_link[$match['id']]}}</a></span>										
										@else
											<span class="tournament_score"><a href="{{ url('match/scorecard/edit/'.$match['id']) }}">{{$add_score_link[$match['id']]}}</a></span>
										@endif

								{{--
														@if($match['sports_id']==1)
									<span class=""><a href="javascript:void(0)" class="text-primary" data-toggle="modal" data-target="#match_summary">Match Summary</a></span>

							      @include('tournaments.match_stats.match_summary')
								@endif
							--}}							



									@endif	

									

										
								</div>
								
								
	
								<div class="col-md-3 col-sm-4 schedule_new_team_edit">

						@if(!empty($match['player_of_the_match']))
								<div class='visible-xs-block'>
									<hr>
								</div>
								<center><h5 class=' tour-title'>Player of the Match</h5></center>
								<br>
									<?php $player_of_the_match=Helper::getUserDetails($match['player_of_the_match']);
									?>
								{!! Helper::Images($player_of_the_match->logo, 'user_profile',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
								
								<center><br><a href='/editsportprofile/{{$player_of_the_match->id}}'>{{$player_of_the_match->name}}</a>
											
							    </center>
						@else
							<div class="edit-link pull-right" onclick="editMatchSchedule({{$match['id']}},1,'','myModal')"><i class="fa fa-pencil"></i>			
							 {{ trans('message.tournament.fields.edit_schedule') }}
							 </div>	
					 	@endif							
								
								</div>
							</div>
							@else
							<div class="row <?php echo $class;?>">
								<div class="col-md-3 schedule_new_team_img">
								
								@if($user_profile[$match['a_id']]['url']!='')
								<!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/user_profile/'.$user_profile[$match['a_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
								
                                <div class="team_player_sj_img">
                                	{!! Helper::Images($user_profile[$match['a_id']]['url'],'user_profile',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
                                </div>	
                                
								@else
							<!--	<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
                             	<div class="team_player_sj_img">
                                	{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
                                </div>	
					
					@endif
					{{'VS'}}
					
					@if($user_profile[$match['b_id']]['url']!='')
								<!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/user_profile/'.$user_profile[$match['b_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
								<div class="team_player_sj_img">
                                	{!! Helper::Images($user_profile[$match['b_id']]['url'],'user_profile',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
                                </div>		
								@else
							<!--	<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
                            <div class="team_player_sj_img">
                            	{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
                                </div>
                                		
								@endif
					
								</div>
                                
                                <div class="col-md-6 schedule_new_team_txt">
                                	<h4 class="tour-title">
                                    	{{ $user_name[$match['a_id']] }}
                                        {{ 'VS' }}                                        
                                        {{ $user_name[$match['b_id']] }}
                                    </h4>
                                    <br>
									
									<span class="match-detail-score">{{ Helper::displayDateTime($match['match_start_date'] . (isset( $match['match_start_time'] ) ? " " . $match['match_start_time'] : ""), 1) }}</span>
									<span class='sports_text'>{{ isset($sport_name)?$sport_name:'' }}</span>


									@if($match['match_type']!='other')
											<span class='match_type_text'>({{ $match['match_type']=='odi'?strtoupper($match['match_type']):ucfirst($match['match_type']) }})</span>
									@endif


											<br/>

									<span class=''>{{$match['address']}}</span><br>
									Status: <span class='event_date'>{{ ucfirst($match['match_status']) }}</span> <br>
									Scores: <span class='blue'>{{Helper::getMatchDetails($match['id'])->scores}} </span> <br>
				@if(!is_null($match['winner_id']))
						<span class='red'>Winner: {{Helper::getMatchDetails($match['id'])->winner}} </span>								
				@endif
					<br>

									@if(!empty($add_score_link[$match['id']]))
										@if($add_score_link[$match['id']]==trans('message.schedule.viewscore'))
											<span class="tournament_score"><a href="{{ url('match/scorecard/view/'.$match['id']) }}">{{$add_score_link[$match['id']]}}</a></span>
										@else
											<span class="tournament_score"><a href="{{ url('match/scorecard/edit/'.$match['id']) }}">{{$add_score_link[$match['id']]}}</a></span>
										@endif
										
									@endif	
								</div>
								
								
	
								<div class="col-md-3 schedule_new_team_edit">
									<div class="edit-link pull-right" onclick="editMatchSchedule({{$match['id']}},1,'','myModal')"><i class="fa fa-pencil"></i>{{ trans('message.tournament.fields.edit_schedule') }}</div>
								</div>						
							</div>
							@endif
							@else
							{{trans('message.tournament.empty_schedule') }}
							@endif
							<?php $i++; ?>
							@endforeach	
							</div>
						</div>
						@else
                        	<div class="clearfix"></div>
							<center>{{trans('message.tournament.empty_schedule') }}</center>
						@endif
						</div>
					</div>
					
				</div>
				<input type="hidden" name="schedule_type" id="schedule_type" value="{{ $schedule_type }}">
				{!!Form::close()!!}
			</div>
			</div>
			@endforeach
			@endforeach
            <hr />
            <div class="form-inline clearfix" style="margin-top: 12px;">
				<button type="button" class="button btn-primary" onclick="createGroup();" style="margin: 8px 15px 8px 0;">Add More Groups</button>
                <div id="create_group" style="display:none;">
					<div  class="form-group"><input id="group" class="gui-input" placeholder="No of Groups "></div>
                    <button type="button" name="add_group" id="add_group" onClick="insertgroup({{ $tour->id }},{{ $tour->groups_number }});" class="button btn-primary">Create Group</button>                    
				</div>
            </div>
            <hr />
			<!-- /.panel-body -->

	
<script type="text/javascript">

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
	function addTeam(group_id,label,prev_team_coount)
    {
		var team_count = $('#team_count').val();
		var row_count = $('[class ^= "group_row_'+group_id+'"]').size();
        var token = "<?php echo csrf_token(); ?>";
        var tournament_id = "{{$tournament_id}}";
		var schedule_type = $('#schedule_type').val();
		var name='';
		var email='';
		var flag='';
		var response='';

		team_name='';
		if(label=='auto')
		{
			str_val = $('#response').val();
			response = $.makeArray( str_val );
			team_name = $('#team_name').val();

		}

		else if(label=='invite')
		{
			name= $('#player_name_'+group_id).val();
			email= $('#player_email_'+group_id).val();

			str_val = $('#response').val();
			response='invite_player';
			team_name = $('#team_name').val();

			 if(name=='')
                {
                        $.alert({
                                        title: 'Alert!',
                                        content: "Enter the Player's name"
                                });
                        $('#player_name_'+group_id).focus();
                        return false;
                }
           
		}
		else
		{
			response = $('#'+group_id+'_req_team').val();
			team_name = $('#'+group_id+'_req_team option:selected').text();
			
			var selected_team_count = $("#"+group_id+"_req_team option[value!='']:selected").length;
			if((selected_team_count+row_count) > team_count)
			{
				alert("{{trans('message.tournament.team_count_exceeded') }}");
				$('#'+group_id+'_req_team option').attr('selected', false);
				return false;
			}
			
		}
		if(response=='' || response==null)
		{
			$.alert({
						title: 'Alert!',
						content: "{{trans('message.tournament.select_team') }}"
					});
			return false;		
		}
			
       
        $.ajax({
            url: base_url+'/tournaments/addteamtotournament',
            type: "post",
            dataType: 'JSON',
            data: {'_token': token, 'response': response,'group_id':group_id,'team_name':team_name,'team_count':team_count,'name':name, 'email':email, 'tournament_id':tournament_id, 'flag':label},
            success: function(response) {
				if(response.length>0)
				{
					 var trHTML = '';
					 $.each(response, function (i, item) {
						 trHTML = '<tr id="row_'+item.id+'">'+
						 		 '<td></td>'+
                                 '<td>' + item.name + '</td>'+
                                 '<td>' + item.match_id + '</td>'+
                                 '<td>' + item.won + '</td>'+
                                 '<td>' + item.lost + '</td>'+
                                 '<td> </td>'+
                              @if(in_array($sports_id, [4,11]))
                                 '<td> </td>'+
                                 '<td> </td>'+
                              @endif
                                 '<td>' + item.points + '</td>'+
                                 '<td><a href="#" class="btn btn-danger btn-circle btn-sm" onclick="deleteTeam('+tournament_id+','+item.tournament_group_id+','+item.id+','+item.team_id+');"><i class="fa fa-remove"></i></a></td>'+
                                 '</tr>';
						 $('#records_table_'+group_id).append(trHTML);
					});
					//$('#records_table_'+group_id).append(trHTML);
					$('#no_teams_'+group_id).hide();
					 $('#response').val('');
					 $('.test').val('');
					 $('#player_name_'+group_id).val('');
					 $('#player_email_'+group_id).val('');
					 
					 
					 
					//replace requested team select box options
					$.ajax({
						url: "{{URL('tournaments/getRequestedTeams')}}",
							type : 'GET',
							data : {'tournament_id':tournament_id,'schedule_type':schedule_type},
							dataType: 'json',
							success : function(response){
									var options = "<option value=''>Select Team</option>";
									$.each(response, function(key, value) {
									options += "<option value='" + key + "'>" + value + "</option>";
									});
									$(".req_team_class").html(options);
									$('.req_team_class').selectMultiple('refresh');

							}
					});
					 
				}
				else if(typeof(response.result!=='undefined')){
					$.alert({
						title: 'Alert!',
						content: response.message
					});
					 $('#response').val('');
					 $('.test').val('');
					
				}
				else
				{
					//$( "#msg" ).append( data.success );
					$.alert({
						title: 'Alert!',
						content: "Maximum count in this Group is ["+team_count+"]"
					});
					 $('#response').val('');
					 $('.test').val('');
					 location.reload();
				}
				
            }
        });
    }
	//function to create group
	function createGroup()
	{
		$('#create_group').show();
		$('#create_group').css('display','inline-block');
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
            url:  base_url+'/tournament/groupedit/'+'edit'+'/'+group_id,
            type: "get",
            dataType: 'JSON',
            data: {'_token': token,'group':group},
            success: function(data) {
                $( "#msg" ).append( data.success );
				location.reload();
            }
        });
	}
	//modal popup call
	function schedulegroupmatches(tournament_group_id)
	{
                var scheduletype = '{{$schedule_type}}';
		$("#myModal").modal();
                $('#bye').prop("selectedIndex","0");
                $("#match_start_date").val("");
                $("#match_start_time").val("");
		//clearing all the values on modal window load
//		clearModal();
                //populating radio button based on selected radio button and default is team
                $.ajax({
                    type: 'GET',
                    url: base_url + '/tournament/getsubtournamentdetails/'+{{$tournament_id}},
//                    data: {tournamentId:tournamentId, roundNumber:roundNumber, matchNumber:matchNumber},
                    dataType: 'json',
                    beforeSend: function() {
                        $.blockUI({width: '50px', message: $("#spinner").html()});
                    },
                    success: function(response) {
                        $.unblockUI();
                        var tournamentDetails = response['tournamentDetails'];
                        $(".modal-body #tournament_group_id").val(tournament_group_id);
                        $(".modal-body #tournament_id").val({{$tournament_id}});
                        $(".modal-body #scheduletype").val('{{$schedule_type}}');
                        //$(".modal-body #main_venue").val(tournamentDetails['facility_name']);
                        //$(".modal-body #main_facility_id").val(tournamentDetails['facility_id']);
                        autofillsubtournamentdetails(tournamentDetails);
                    }
                });
	}
	
	//delete team
	function deleteTeam(tournament_id,tournament_group_id,id,team_id)
	{
		$.confirm({
			title: 'Confirmation',
			content: "Are you sure you want to delete this Team?",
			confirm: function() {
				
				$.ajax({
					url:  base_url+'/tournament/schedule/delete',
					type: "get",
					data:{'tournament_id': tournament_id,'tournament_group_id':tournament_group_id,'team_id':team_id},
					dataType: 'JSON',
					success: function(data) {
						if(data.msg=='true')
						{
							$.ajax({
								url:  base_url+'/tournament/team/delete/'+id,
								type: "get",
								dataType: 'JSON',
								success: function(data) {
									if(data.success!='')
									{
										$.alert({
											title: 'Alert!',
											content: data.success
										});
										$('#row_'+id).remove();
										 //replace requested team select box options
										var schedule_type = $('#schedule_type').val();
										 $.ajax({
											url: "{{URL('tournaments/getRequestedTeams')}}",
												type : 'GET',
												data : {'tournament_id':tournament_id,'schedule_type':schedule_type},
												dataType: 'json',
												success : function(response){
														var options = "<option value=''>Select Team</option>";
														$.each(response, function(key, value) {
														options += "<option value='" + key + "'>" + value + "</option>";
														});
														$(".req_team_class").html(options);
														$('.req_team_class').selectMultiple('refresh');

												}
										});
										
									}
								}
							});
						}
						else
						{
							$.alert({
									title: 'Alert!',
									content: data.msg
								});
						}
					}
				});
				
			},
			cancel: function() {
				// nothing to do
			}
		});

	}
	//delete Group
	function deleteGroup(tour_id,tournament_grp_id)
	{
		
		$.confirm({
			title: 'Confirmation',
			content: "Are you sure you want to delete this Group?",
			confirm: function() {
				
				$.ajax({
					url:  base_url+'/tournament/deleteGroupTeams',
					type: "get",
					dataType: 'JSON',
					data: {'tournament_id': tour_id,'tournament_group_id':tournament_grp_id},
					success: function(data) {
						if(data.status=='true')
						{
							$.ajax({
								url:  base_url+'/tournament/groupedit/'+'delete'+'/'+tournament_grp_id,
								type: "get",
								dataType: 'JSON',
								success: function(response) {
										$.alert({
										title: 'Alert!',
										content:  response.success
									});
									//location.reload();
									$('#group_'+tournament_grp_id).remove();
									
										 //replace requested team select box options
										var schedule_type = $('#schedule_type').val();
										 $.ajax({
											url: "{{URL('tournaments/getRequestedTeams')}}",
												type : 'GET',
												data : {'tournament_id':tour_id,'schedule_type':schedule_type},
												dataType: 'json',
												success : function(response){
														var options = "<option value=''>Select Team</option>";
														$.each(response, function(key, value) {
														options += "<option value='" + key + "'>" + value + "</option>";
														});
														$(".req_team_class").html(options);

												}
										});
									
								}
							});
						}else
						{
							$.alert({
									title: 'Alert!',
									content:  "{{trans('message.tournament.schedule_delete_fail')}}"
								});
						}
					}
				});
				
			},
			cancel: function() {
				// nothing to do
			}
		});
		
		
		
	}



</script>

