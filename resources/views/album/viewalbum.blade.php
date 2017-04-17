@extends(Auth::user() || (isset($is_widget) && $is_widget)?(Helper::check_if_org_template_enabled()?'layouts.organisation':'layouts.app')  : 'home.layout')
@section('content')
@if($action=='tournaments')
	@include ('tournaments._leftmenu')
@elseif($action=='facility')
	@include ('facility._leftmenu')
@elseif($action=='organization')
 
		@if(!Helper::check_if_org_template_enabled())
			@include ('teams.orgleftmenu')
		@endif
@elseif($action=='team')
	@include ('teams._leftmenu')
@elseif($action=='match')
	@include ('album._match_leftmenu')
@else
	@include ('album._leftmenu')	
@endif

@if(Helper::check_if_org_template_enabled())
		<div class="col-md-2">
		</div>
		@endif
<div id="content" class="col-sm-10"> 
			@include ('album.createalbum')	
</div>

@endsection
