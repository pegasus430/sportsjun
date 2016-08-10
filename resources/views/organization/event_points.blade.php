<div id="event_points_{{$lis->id}}" class="modal fade">
		<div class="modal-dialog sj_modal sportsjun-forms">
			<div class="modal-content">
				<div class="alert alert-danger" id="div_failure1"></div>
				<div class="alert alert-success" id="div_success1" style="display:none;"></div>
				<div class="modal-body">

				<div class='row'>
					<div class='col-sm-12'>
						<div class='table-responsive' id="teamStatsDiv">
							<table class='table table-striped table-bordered '>
								<thead>
								
								  </thead>
								<tbody>
								   <tr>
								   			<td>
								   		@foreach($orgInfoObj->groups as $og)
												<td>
													<p><b><center>{{$og->name}}</center></b></p>
													<br>
													<img src="{{url('/uploads/org/groups/logo/'.$og->logo)}}"
														 class='img-responsive img-rounded img-center' height='60px' width='60px'>
												</td>
											@endforeach

								   </tr>
								
										<tr>
											<td>{{$lis->sport->sports_name}} </td>

											@foreach($orgInfoObj->groups as $og)
													<td class="text-center">{{$lis->getGroupPoints($lis->id,$og->id)}}</td>
											@endforeach
										</tr>

								</tbody>
							</table>
						</div>
					</div>
				</div>


					
					


				</div>



				<div class="modal-footer">					
					<button type="button" class="button btn-secondary" data-dismiss="modal">Close</button>
				</div>
				
			</div>
		</div>
	</div>
