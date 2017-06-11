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
				<div class="">

				@foreach($coachings as $coaching)

				<div class="col-md-4">
					
						<figure class="snip1174 col-md-4">
						@if($is_owner)
							  <div class="pull-right ed-btn">
                                    <a href="/organization/{{$organisation->id}}/coaching/{{$coaching->id}}/edit" class="edit"><i
                                                class="fa fa-pencil"></i></a>


                                    <a href="/organization/{{$organisation->id}}/coaching/{{$coaching->id}}/delete"
                                       class="delete" title="{{empty($t->isactive)?'Activate':'Deactivate'}}"
                                       data-toggle="tooltip" data-placement="top">
                                        {!! empty($t->isactive)?"<i class='fa fa-ban'></i>":"<i class='fa fa-ban'></i>" !!}</a>
                         </div>
                         @endif

							 <img src="{{$coaching->image_url}}" />
							<figcaption>
								<h2>{{$coaching->title}}</h2>
								<div class="row mgb-20">
									<div class="col-md-6">No. of Players: {{$coaching->number_of_players}}</div>
									<div class="col-md-6">Coach: {{$coaching->coach?$coaching->coach->name:''}}</div>
								</div> <a href="/organization/{{$organisation->id}}/coaching/{{$coaching->id}}">Enter</a> </figcaption>
						</figure>
				</div>
					
				@endforeach
				</div>
			</div>
		</div>


@stop