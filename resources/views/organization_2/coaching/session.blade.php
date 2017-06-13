@extends('layouts.organisation')

@section('content')
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> {{$coaching->title}}</h2>
					<div class="session-tabs">
						<ul>						
							<li>
								<a href="#" data-toggle="modal" data-target="#add-player-modal"> <i class="el el-plus-sign"></i> <span>Add Player</span> </a>
							</li>
							<li>
								<a href="/organization/{{$organisation->id}}/coaching/{{$coaching->id}}/invoices"> <i class="el el-check"></i> <span>Invoices</span> </a>
							</li>
							<li>
								<a href="/organization/{{$organisation->id}}/coaching/{{$coaching->id}}/calendar"> <i class="glyphicon glyphicon-calendar"></i> <span>Calendar</span> </a>
							</li>
							<li>
							<a href="/organization/{{$organisation->id}}/coaching/{{$coaching->id}}/settings"> <i class="el el-cogs"></i> <span>Configurations</span> </a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="coaching"> 
				@foreach($coaching->players as $player)
					<div class="col-md-3 col-sm-6">
						<div class="sio-container"> <img src="/org/images/sport-coaching.jpg" alt="Avatar" class="sio-image" />
							<div class="sio-overlay">
								<div class="sio-links">
									<ul>
										<li>
											<a href="#" data-toggle="modal" data-target="#player-coaching-note-modal"> <i class="el el-edit"></i></a>
										</li>
										<li>
											<a href="#" data-toggle="modal" data-target="#player-coaching-pictures-modal"> <i class="fa fa-picture-o"></i></a>
										</li>
										<li>
											<a href="#" data-toggle="modal" data-target="#player-coaching-videos-modal"> <i class="glyphicon glyphicon-facetime-video"></i></a>
										</li>
										<li>
											<a href="#"> <i class="glyphicon glyphicon-list-alt"></i></a>
										</li>
										<li>
											<a href="#" data-toggle="modal" data-target="#coach-assesment-modal"> <i class="el el-check"></i></a>
										</li>
									</ul>
								</div>
							</div>
							<div class="sio-content">
								<div class="sio-pname">{{$player->user->name}}</div>
								<div class="sio-plocation">{{$player->user->address}}</div>
							</div>
						</div>
					</div>
				@endforeach
				</div>
			</div>
		</div>



@include('organization_2.coaching.modals.add_player')

@stop