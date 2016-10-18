<div id="event_points_{{$lis->id}}" class="modal fade">
		<div class="modal-dialog sj_modal sportsjun-forms">
			<div class="modal-content">
				<div class="alert alert-danger" id="div_failure1"></div>
				<div class="alert alert-success" id="div_success1" style="display:none;"></div>
				<div class="modal-body">

				<div class='row'>
					<div class='col-sm-12'>
					@if(count($orgInfoObj->groups))
						<div class='table-responsive' id="teamStatsDiv">
							<table class='table table-striped table-bordered '>
								<thead>

								  </thead>
								<tbody>
								   <tr>
									   <td>
										   <p><b><center>&nbsp;</center></b></p>
										   <br/>
										   {!! Helper::makeImageHtml($lis->logoImage,array('height'=>60,'width'=>60,'class'=>'img-responsive img-rounded center-block') )!!}</td>
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
						<hr>
					@endif

						@foreach($lis->groups as $group)
			<div id="group_{{ $group->id }}">

            <div class="group_no clearfix">
            	<div class="pull-left"><h4 class="stage_head">{{ $group->name }}</h4></div>

            </div>



			<div class="cstmpanel-tabs">
				<!-- Nav tabs -->


				<div class="tab-content sportsjun-forms">
                <?php $table_count = 0;?>


					<div class="tab-pane fade active in" id="teams_{{ $group->id }}">


                            <div class="clearfix"></div>
                            <div class="table-responsive groups-table">
								<?php $table_count = 0;?>
                                <table class="table table-striped" id="records_table_{{$group->id}}">
                                    <thead class="thead">
                                    <tr>
                                    <th>Team</th>
                                    <th>Mat</th>
                                    <th>Won</th>
                                    <th>Lost</th>
									<th>Draw</th>
									<th>Pts</th>
                                    </tr>
                                    </thead>
                                <tbody>
                                @if(count($group->group_teams))
                                    @foreach($group->group_teams->sortByDesc('points') as $team)
										<?php
											$won = array_get($team,'won',0);
											$lost = array_get($team,'lost',0);
											$tie = array_get($team,'tie',0);

											$matches_count = $won+$lost+$tie;


										?>
                                    <tr id="row_{{$team['id']}}" class="group_row_{{$group->id}}">
                                    <td><b>{{ $team['name'] }}</b></td>
                                    <td> {{$matches_count}}
										{{-- !empty($match_count[$group->id][$team['team_id']])?$match_count[$group->id][$team['team_id']]:0 --}}</td>
                                    <td>{{ $won }}</td>
                                    <td>{{ $lost }}</td>
									<td>{{ $tie }}</td>
									<td>{{ !empty($team['points'])?$team['points']:0 }}</td>

                                    @endforeach
                                @else
                                    <tr id="no_teams_{{$group->id}}"><td colspan="6">{{trans('message.tournament.empty_teams') }}</td></tr>
                                @endif
                                </tbody>
                                </table>

                            </div>




						</div>
					</div>

			</div>
			</div>
			@endforeach




					</div>
				</div>






				</div>



				<div class="modal-footer">
					<button type="button" class="button btn-secondary" data-dismiss="modal">Close</button>
				</div>

			</div>
		</div>
	</div>
