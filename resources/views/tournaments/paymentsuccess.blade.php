@extends('layouts.app')
@section('content')

<div class="col-lg-12 col-md-10 col-sm-12  tournament_profile teamslist-pg" style="padding-top: 3px !important;">
 <div class="panel panel-default">
   <div class="panel-body">


<div class="form-header header-primary register_form_head"><h4 class='register_form_title successpage'>Payment Result</h4></div>

<h3>Thank You. Your order status is {{$data['status']}}.</h3>
<h4>Your Transaction ID for this transaction is {{$data['txnid']}}</h4>
<h4>We have received a payment of INR. {{$data['amount']}} .</h4>



<div class="form-body">



<div class="row">


      <div class="table-responsive">
        <div class="col-sm-12" id="teamStatsDiv">
          <table class="table table-hover table-striped">
          <thead>
              <tr>
                <th>Tournament</th>
                <th>Payment Name</th>
                <th>Team</th>
                <th>Payment Email</th>
                <th>Payment Phone</th>
                <th>Amount </th>
              </tr>
          </thead>

          <tbody>
              
<?php $i=0;
$options=array();?>
@foreach ($details as $details)
        <tr>
          <td>{{$details['tournament']}}</td>
          
          <td> <i class="fa fa-user"></i> {{$details['name']}}</td>
     
          <td> <i class="fa fa-user"></i> {{$details['team']}}</td>
     
   
          <td><i class="fa fa-envelope"></i> {{$details['email']}}</td>
            
         <td><i class="fa fa-phone"></i> {{$details['phone']}}</td>
     
          <td><i class="fa fa-inr"></i> {{$details['price']}}</td>
     
          </tr>
<?php $i++;?>
@endforeach
          </tbody>

          </table>
      </div>

  </div>



</div>

</div>
</div>
</div>
@endsection