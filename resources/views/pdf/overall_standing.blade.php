@extends('layouts.pdf')

@section('content')

    <div id="header">
       
        <h2>
        	{{strtoupper($orgInfoObj->name)}}
        	<br>
        	
        	{{strtoupper($parent_tournament->name)}}
            <br/>
            <span class="small">
           		Overall Standing
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

        table{
        	  width:100%;text-align:center;
        }

        
    </style>

	<div class='row'>
					<div class='col-sm-12'>
						<div class='table-responsive'>
							<table class='table table-striped table-bordered table-hover'>

						@if(count($orgInfoObj->groups))
								<thead>
									<tr>
											<th>
												<p><b><center>&nbsp;</center></b></p>
												<br/>
									</th>
											@foreach($orgInfoObj->groups as $og)
												<td><p><b><center>{{$og->name}}</center></b></p>
													<br>
													<?php $og->total_points=0;?>
													<img src="{{Helper::ImageCheck('/uploads/org/groups/logo/'.$og->logo)}}"
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
					@endif


@stop