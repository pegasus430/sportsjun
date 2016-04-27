@extends('layouts.app')
@section('content')
@if($action=='tournaments')
	@include ('tournaments._leftmenu')
@elseif($action=='facility')
	@include ('facility._leftmenu')
@elseif($action=='organization')
  @include ('teams.orgleftmenu')
@elseif($action=='team')
	@include ('teams._leftmenu')
@elseif($action=='match')
	@include ('album._match_leftmenu')
@else
	@include ('album._leftmenu')	
@endif
<div id="content" class="col-sm-10"> 
			@include ('album.createalbum')	
</div>

@endsection
