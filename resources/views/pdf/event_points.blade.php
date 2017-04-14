@extends('layouts.pdf')

@section('content')		

    <div id="header">
      
        <h2>
        {{strtoupper($orgInfoObj->name)}}<br>
    
        	{{strtoupper($lis->name)}}
            <br/>
            <span class="small">
         	Event Points
            </span>
        </h2>
    </div>
    <style>
        table, td, tr, th {
            border: 1px solid black;
            border-collapse: collapse
        }
        .second {
            background-color: #EFEFEF
        }

        .print_table{
            width:100%;text-align:center;
        }

        
    </style>

				<div class='row'>
					<div class='col-sm-12'>
					@if(count($orgInfoObj->groups))
						<div class='table-responsive' id="teamStatsDiv">
							<table class='table table-striped table-bordered print_table '>
								<thead>

								  </thead>
								<tbody>
								   <tr>
									   <td>
										   <p><b><center>&nbsp;</center></b></p>
										   <br/>
										</td>
								   		@foreach($orgInfoObj->groups as $og)
												<td>
													<p><b><center>{{$og->name}}</center></b></p>
													<br>
													<img src="{!!Helper::ImageCheck('/uploads/org/groups/logo/'.$og->logo)!!}"
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
                                <table class="table table-striped print_table" id="records_table_{{$group->id}}">
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
									<tr>
								
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

@stop
