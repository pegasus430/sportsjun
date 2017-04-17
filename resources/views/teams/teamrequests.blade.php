@extends(Helper::check_if_org_template_enabled()?'layouts.organisation':'layouts.app') 
@section('content')
@include ('teams._leftmenu')
@include('common.requests')
@endsection
