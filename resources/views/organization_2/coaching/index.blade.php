@extends('layouts.organisation')


@section('content')

<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Coaching Sessions</h2>
					@if($is_owner)
					<div class="create-btn-link">
						<a href="/organization/{{$organisation->id}}/coaching/create_session" class="wg-cnlink"> <i class="fa fa-plus"></i> &nbsp; Create Coaching Session</a>
					</div>
					@endif
				</div>
			</div>
			<div class="row ">
				<div class="wrap-2 ">

				@foreach($coachings as $coaching)
					<div class="col-md-12 col-sm-12">
						<div class="row">
						<div class="col-sm-8">
							<h3 class="text-primary">{{$coaching->title}}</h3>
							<hr class="text-primary">
						</div> 
						<div class="col-sm-4">
						</div>
					</div>

					<a href="javascript:void(0)" data-toggle='accordion' data-target='#view_coaching_details'>View Details</a>

					<div class="collapse" id='view_coaching_details'>

					</div>

					<br>
					<a href="/organization/{{$organisation->id}}/coaching/{{$coaching->id}}" class="btn btn-waning">Enter</a>
					</div>
				@endforeach
				</div>
			</div>
		</div>


@stop