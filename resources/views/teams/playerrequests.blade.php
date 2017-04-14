@extends(Helper::check_if_org_template_enabled()?'layouts.organisation':'layouts.app') 
@section('content')
@include ('teams._leftmenu')
<?php //echo '<pre>'; print_r($result); die();?>
<div class="col_middle bg_white_new">
	<div class="container-fluid" style="padding:0;">
		<div style="margin:0;" class="row">
			<div class="col-lg-9">
				@if(count($result))
					@foreach($result as $res)
						@if(count($res['requests']))
						@foreach($res['requests'] as $req)
                        <div class="row">
                            <div class="col-xs-10">
                                <div class="t_details">
                                    <p class="t_tltle">
                                        <strong><a href="#">{{ !empty($req['message'])?$req['message']:'' }}</a></strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
					@endforeach
				@endif
			</div>
			<div class="col-lg-3 search_filters_div" id="suggested_players"></div>
		</div>
	</div>
</div>

@endsection
