@extends('layouts.app')
@section('content')

<div class="">
<div class="sportsjun-wrap">
<div class="sportsjun-forms sportsjun-container wrap-2">


<div class="form-header header-primary"><h4>Payment Result</h4></div>

<h3>Thank You. Your order status is {{$data['status']}}.</h3>
<h4>Your Transaction ID for this transaction is {{$data['txnid']}}</h4>
<h4>We have received a payment of INR. {{$data['amount']}} .</h4>


</div>

</div>
</div>
</div>
@endsection