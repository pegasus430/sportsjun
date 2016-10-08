<div id="overall_standing_{{$parent_tournament->id}}" class="modal fade">
		<div class="modal-dialog sj_modal sportsjun-forms">
			<div class="modal-content">
				<div class="alert alert-danger" id="div_failure1"></div>
				<div class="alert alert-success" id="div_success1" style="display:none;"></div>
				<div class="modal-body">
					<div class='row'>
					<div class='col-sm-12'>
						<div class='table-responsive'>
							<table class='table table-striped table-bordered table-hover'>

						@if(count($orgInfoObj->groups))
								<thead>
									<tr>
											<th></th>
											@foreach($orgInfoObj->groups as $og)
												<td><p><b><center>{{$og->name}}</center></b></p>
													<br>
													<?php $og->total_points=0;?>
													<img src="{{url('/uploads/org/groups/logo/'.$og->logo)}}"
														 class='img-responsive img-rounded center-block' height='60px' width='60px'>
												</td>
											@endforeach
								   </tr>								   	
								</thead>
								<tbody id='display_overall_standing_{{$parent_tournament->id}}'>
									
									@foreach($parent_tournament->getGroupPoints as $groupPoints)

										<tr>
											<td>{{$groupPoints->sport->sports_name}} </td>

											@foreach($orgInfoObj->groups as $og)
												<?php 
												
												$og_sports=$parent_tournament->getEachGroupPoints($parent_tournament->id,$og->id, $groupPoints->sports_id);
												$og->total_points+=$og_sports;
												?>

										<td class="text-center">{{$og_sports}}</td>
											@endforeach
											
										</tr>	
									@endforeach


										<tr>
												<th>Total</th>
											@foreach($orgInfoObj->groups as $og)
												<th class="text-center">
													{{ $og->total_points}}
													
												</th>
											@endforeach
										</tr>



						<tr class='add_sport' id='add_sport_overall_standing_{{$parent_tournament->id}}'>

						<form id='form_{{$parent_tournament->id}}' onsubmit="return saveOverallStanding({{$parent_tournament->id}},this)">
							<td>

							
								<select id='add_sport_select_{{$parent_tournament->id}}' name='sport_id' class="gui-input">

							@foreach(Helper::getAllSports() as $all_sports)
								<option value='{{$all_sports->id}}' class="gui-input">{{$all_sports->sports_name}}</option>
							@endforeach
								</select>
							</td>

							@foreach($orgInfoObj->groups as $group_key=>$og)
												<td class="text-center">
													<input type='text' placeholder="0" name='input_{{$group_key}}' group_id={{$og->id}} class="gui-input" required>											
												</td>
								<input type='hidden' name='group_{{$group_key}}' value="{{$og->id}}">
							@endforeach

							<input type='hidden' name="max_index" value="{{{$group_key or ""}}}">
							<input type='hidden' name='parent_tournament' value="{{$parent_tournament->id}}">

							

						</tr>
												

								</tbody>

					@else
						{{trans('message.organization.tournaments.no_player_stats')}}

					@endif
							</table>
						</div>
					</div>
				</div>
					


				</div>


				<center >
					<div class='col-sm-12'>
						<div id='display_notification'> </div>
					</div>
				</center>
				



			<div class="modal-footer">	
				@can('createTeam', $orgInfoObj)

					<button class='button btn-primary' type="button" save='0' onclick="addSport({{$parent_tournament->id}}, this)">Add Sport</button>	
				@endcan
				</form>			
					<button type="button" class="button btn-secondary" data-dismiss="modal" >Close</button>
				</div>
				
			</div>
		</div>
	</div>


	<script>	

	$('.add_sport').hide();


	function addSport(tp_id, that){
			var check=$(that).attr('save');
			console.log('d')

		if(check==0){
			$('#add_sport_overall_standing_'+tp_id).fadeIn();
			$(that).attr('save','1');	
			$(that).attr('type', 'button')	
		}
		else{
			//saveOverallStanding(tp_id);
			//$('#form_'+tp_id).submit();
			$(that).attr('type', 'submit');
			$(that).attr('save','0');	
			
		}

		return false;
	}

	function saveOverallStanding(tp_id, that){

		data=$(that).serialize();
		$.ajax({
			url:base_url+'/parent_tournament/'+tp_id+'/add_sport',
			method:'post',
			data:data,
			success:function(response){
				if(response.indexOf('this sports already exists')!=-1){

					$('#display_notification').addClass('alert alert-danger').html(response);
					setTimeout(function(){
						$('#display_notification').removeClass('alert alert-danger').html('');
					},5000)
				}
				else{
					$('#display_overall_standing_'+tp_id).html(response)

					$('#display_notification').addClass('alert alert-success').html('Sport Added!');
					setTimeout(function(){
						$('#display_notification').removeClass('alert alert-success').html('');
					},5000)
				}
				
			}
	    })

	    return false;
	}
	
	</script>
