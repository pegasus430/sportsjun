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
								<tbody>
									
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

								</tbody>
							</table>
						</div>
					</div>
				</div>
					


				</div>



				<div class="modal-footer">	
					<button class='button btn-primary' onclick="reloadGroupTeams()">Refresh</button>				
					<button type="button" class="button btn-secondary" data-dismiss="modal">Close</button>
				</div>
				
			</div>
		</div>
	</div>


	<script>	
	function reloadGroupTeams(){
			$.ajax({
				url:base_url+'/reloadgroupteampoints',
				success:function(){
					window.location=window.location;
				}
				})
	}
	</script>
