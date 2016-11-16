@extends('layouts.app')
@section('content')


{!! Form::open(array('url' => 'https://test.payu.in/_payment', 'method' => 'post', 'id' => 'payment_submit_form')) !!}



@foreach ($data as $key=>$dt)
{!! Form::hidden($key, $dt) !!}
@endforeach


{!! Form::close() !!}

<script type="text/javascript">
    document.getElementById('payment_submit_form').submit();
</script>

@endsection