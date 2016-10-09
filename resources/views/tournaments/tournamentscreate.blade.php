@extends('layouts.app')
@section('content')
<div class="col-sm-3">
        <div class="row" id="create-tournament-instructions">
                <div class="intro_list_container">
                        <ul class="intro_list_on_empty_pages">
                                <span class="steps_to_follow">Steps to create tournament:</span>
                                <li>Firstly, fill up the <span class="bold">Tournament Details</span> section.</li>
                                <li><span class="bold">Click Update</span></li>
                                <li>Navigate to <span class="bold">Tournament Events</span> tab.</li>
                                <li><span class="bold">Add</span> single/multiple tournament events to a single tournament.</li>
                                <li><span class="bold">Teams / Players</span> will respond to your request to join.</li>
                                <li><span class="bold">Accept / Reject</span> to enroll the Teams / Players into the tournament.</li>
                        </ul>
                </div>
        </div>
</div>
<div class="container-fluid col-sm-6">
<div class="sportsjun-forms sportsjun-container wrap-2">
<div class="form-header header-primary"><h4><i class="fa fa-pencil-square"></i>{{ trans('message.tournament.edit_tournament_detail_page.heading') }}</h4></div>

	<ul class="nav nav-tabs nav-justified tournament_form">
		<li class="active"><a href="#parent_tournament" data-toggle="tab" aria-expanded="true">{{ trans('message.tournament.add_tournament_detail_page.details_tab') }}</a></li>
		<li class=""><a href="#sub_tournament" data-toggle="tab" aria-expanded="false">{{ trans('message.tournament.add_tournament_detail_page.events_tab') }}</a></li>
	</ul>
	<div  class="tab-content">
		<div id="parent_tournament" class="tab-pane fade active in">
			@include('tournaments.parenttournamentcreate')
		</div>
		<div id="sub_tournament" class="tab-pane fade" >
			<div class="form-body">
            	<center>{{'Firsly, add tournament details, and then you\'ll be able to add tournament events.'}}</center>
            </div>            
		</div>
	</div>
</div>
</div>
@endsection