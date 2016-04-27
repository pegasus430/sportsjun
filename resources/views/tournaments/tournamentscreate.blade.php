@extends('layouts.app')
@section('content')
<div class="container-fluid">
<div class="sportsjun-forms sportsjun-container wrap-2">
<div class="form-header header-primary"><h4><i class="fa fa-pencil-square"></i>{{ trans('message.tournament.fields.heading') }}</h4></div>

	<ul class="nav nav-tabs nav-justified tournament_form">
		<li class="active"><a href="#parent_tournament" data-toggle="tab" aria-expanded="true">Tournament</a></li>
		<li class=""><a href="#sub_tournament" data-toggle="tab" aria-expanded="false">Tournament events</a></li>
	</ul>
	<div  class="tab-content">
		<div id="parent_tournament" class="tab-pane fade active in">
			@include('tournaments.parenttournamentcreate')
		</div>
		<div id="sub_tournament" class="tab-pane fade" >
			<div class="form-body">
            	<center>{{'Create Tournament to Add Tournament Event.'}}</center>
            </div>            
		</div>
	</div>
</div>
</div>
@endsection