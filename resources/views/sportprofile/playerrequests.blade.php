@extends(Helper::check_if_org_template_enabled()?'layouts.organisation':'layouts.app') s
@section('content')
@include ('album._leftmenu')
@include('common.requests')
@endsection
