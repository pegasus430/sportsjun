@foreach($parent_tournament->getGroupPoints as $groupPoints)

										<tr>
											<td>{{$groupPoints->sport->sports_name}} </td>

											@foreach($orgInfoObj->groups as $og)
												<?php 
												
												$og_sports=$parent_tournament->getEachGroupPoints($parent_tournament->id,$og->id, $groupPoints->sports_id);
												$og->total_points+=$og_sports;
												?>

										<td class="text-center">{{$og_sports}}</td>
										</tr>
											@endforeach
											
										</tr>	
									@endforeach


										<tr>
												<th>Total</th>
											@foreach($orgInfoObj->groups as $og)
												<th class="text-center">
													{{ $og->total_points}}
													
												</th>
										</tr>
											@endforeach


					{{--

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

							<input type='hidden' name="max_index" value="{{$group_key}}">
							<input type='hidden' name='parent_tournament' value="{{$parent_tournament->id}}">					

						</tr>

					--}}
												