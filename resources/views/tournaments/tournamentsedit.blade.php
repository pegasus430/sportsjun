@extends('layouts.app')
@section('content')
<div class="">
<div class="sportsjun-wrap">
<div class="sportsjun-forms sportsjun-container wrap-2">
<div class="form-header header-primary"><h4><i class="fa fa-pencil-square"></i>{{ trans('message.tournament.fields.heading') }}</h4></div>
	<ul class="nav nav-tabs nav-justified tournament_form">
		<li class="active"><a href="#parent_tournament" data-toggle="tab" aria-expanded="true">Tournament</a></li>
		<li class=""><a href="#sub_tournament" data-toggle="tab" aria-expanded="false">Tournament Event</a></li>
	</ul>
	<div  class="tab-content">
		<div id="parent_tournament" class="tab-pane fade active in">
			@include('tournaments.parenttournamentedit')
		</div>
		<div id="sub_tournament" class="tab-pane fade" >
        	<div class="form-body">
		<!--<a id="b_fall_wkt" onclick="" class="addmore"><span class="glyphicon glyphicon-plus-sign"></span> Add Sub Tournament </a>-->
		
		 <a href="#" data-toggle="modal" data-target="#subtournament" class="add-tour"><i class="fa fa-plus"></i> Add Tournament Event</a>
		
			@if(!empty($subTournamentArray) && count($subTournamentArray)>0)
				@include('tournaments.viewsubtournaments')
			@endif
			</div>
		</div>
	</div>
</div>
</div>
</div>
@include('tournaments.create')	

@endsection