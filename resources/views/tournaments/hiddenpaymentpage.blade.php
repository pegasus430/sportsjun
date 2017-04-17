@extends(Helper::check_if_org_template_enabled()?'layouts.organisation':'layouts.app') 
@section('content')


{!! Form::open(array('url' => 'https://test.payu.in/_payment', 'method' => 'post', 'id' => 'payment_submit_form')) !!}



@foreach ($payment_params as $key=>$dt)
{!! Form::hidden($key, $dt) !!}
@endforeach


{!! Form::close() !!}

<script type="text/javascript">
    document.getElementById('payment_submit_form').submit();
</script>

@endsection