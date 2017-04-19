 @extends(Helper::check_if_org_template_enabled()?'layouts.organisation':'layouts.app') 

@section('content')
<div class="container-fluid">
	<div class="sportsjun-forms sportsjun-container wrap-2">
		 <div class="form-header header-primary"><h4><i class="fa fa-pencil-square"></i>{{ trans('message.marketplace.fields.heading') }}</h4></div><!-- end .form-header section -->   
		  {!! Form::open(['route' => 'marketplace.store','class'=>'form-horizontal','method' => 'POST','id' => 'my-form','name'=>'marketplace_create']) !!}   
                      
		<div class="form-body">
				 @include ('marketplace._form', ['submitButtonText' => 'Create','isCreate' => 'yes'])
		</div>
		<div class="form-footer">
						 <button onclick="btnClick();" type="{{$is_org?'submit':'button'}}" class="button btn-primary"> Create </button>		
         </div>
			  {!! Form::close() !!}			  
                    {!! JsValidator::formRequest('App\Http\Requests\CreateMarketPlaceRequest', '#my-form'); !!}
					<script type="text/javascript">
					    $(function() {
					        $.validator.addMethod("endate_greater_startdate", function(value, element) {
					        	if($('[name="base_price"]').val() == '')
					        		return true;
					        	else
					            	return parseInt($('[name="base_price"]').val()) <= parseInt($('[name="actual_price"]').val())
					        }, "Offer price should be less than Actual price");
					        //$('[name="actual_price"]').rules("add", "endate_greater_startdate");
					        $('[name="base_price"]').rules("add", "endate_greater_startdate");
					    });
					</script>
		</div>
</div>	

@endsection
