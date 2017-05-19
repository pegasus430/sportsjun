@extends('layouts.organisation')


@section('content')

<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Coaching Sessions</h2>
					<div class="create-btn-link">
						<a href="/organization/{{$organisation->id}}/coaching/create_session" class="wg-cnlink"> <i class="fa fa-plus"></i> &nbsp; Create Coaching Session</a>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="coaching">

				@foreach($coachings as $coaching)
					<div class="col-md-4">
						<figure class="snip1174 navy col-md-4"> <img src="{{$coaching->image_url}}" alt="sq-sample33" />
							<figcaption>
								<h2>{{$coaching->name}}</h2>
								<div class="row mgb-20">
									<div class="col-md-6">No. of Players {{$coaching->number_of_players}}</div>
									<div class="col-md-6">Coach: </div>
								</div> <a href="/organization/{{$organisation->id}}/coaching/{{$coaching->id}}">Enter</a> </figcaption>
						</figure>
					</div>
				@endforeach
				</div>
			</div>
		</div>


@stop