@extends('admin.layouts.app')
@section('content')
<div class="">
<div class="sportsjun-wrap">
<div class="sportsjun-forms sportsjun-container wrap-2">
<div class="form-header header-primary">
<h4><i class="fa fa-pencil-square"></i>{{ trans('message.payment_gate_way.payment_gate_way_heading') }}</h4></div>
	{!! Form::open(['url' => 'admin/paymentgateways/create','class'=>'form-horizontal','method' => 'POST','id' => 'paymentgateway-form']) !!}
	<input type="hidden" name="id" value="{{$payment->id}}">
	<div class="form-body">
		<div class="payment_gateway_form">
		    <div class="row">
		        <div class="col-sm-12">
		        	<div class="section">
	                <label class="form_label">Name <span  class='required'>*</span></label>
		            <label class="field prepend-icon">
		            {!! Form::text('name', $payment->name, array('required','class'=>'gui-input','placeholder'=> trans('message.payment_gate_way.fields.name') )) !!}
		            @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
		            <label for="firstname" class="field-icon"><i class="fa fa-trophy"></i></label>  
		            </label>
		           </div>
					
		        </div>
		        @if(isset($countries))
				<div class="col-sm-6">
				    <div class="section">
				        <label class="form_label">{{ trans('message.common.fields.country') }}	<span  class='required'>*</span></label>
				        <label class="field select">
				            {!! Form::select('country_id',$countries, $payment->country_id, array('required','id'=>'country_id','class'=>'form-control','autocomplete'=>'off','placeholder'=>trans('message.common.fields.country'))) !!}
				            @if ($errors->has('country_id')) <p class="help-block">{{ $errors->first('country_id') }}</p> @endif
				            <i class="arrow double"></i>
				        </label>
				    </div>
				</div>
				@endif
				 <div class="col-sm-6 status">                       
		            <div class="section">
		                <label class="form_label"><br/></label> 
		                <label class = "option block">
		                    {!! Form::checkbox('status', 'on', $payment->status, ['id' => 'status', 'class'=>'gui-input']) !!}
		                    <span class = "checkbox"></span>{{ trans('message.payment_gate_way.fields.status') }}
		                    @if ($errors->has('status'))
		                        <p class = "help-block">{{ $errors->first('status') }}</p> @endif
		                </label>
		            </div>
		        </div>
		        <div class="col-sm-12 text-right">   
		        <div class="form-inline clearfix">
					<button type="submit" class="button btn-primary">{{$button_text}}</button>                  
					</div>
	            </div>
	            </div>
		    </div>
	    </div>
    </div>
	{!! Form::close() !!}
	{!! JsValidator::formRequest('App\Http\Requests\CreatePaymentRequest', '#paymentgateway-form'); !!}
</div>
</div>
</div>

@endsection