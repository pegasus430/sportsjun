<script type="text/javascript">
$(document).ready(function(){
	$('.launch-modals').click(function(){
		contact_number = $("#contact_number").val();
		if(contact_number!='')
		{
			$('#showVerification').modal({
				backdrop: 'static'
			});
		}
		
	}); 
});
</script>
<?php 
$readonly='';
if($isCreate=='no')
	$readonly='readonly';

if($is_org) $isCreate = 'false';
?>
<input type="hidden" name="time_token" id="time_token" value="<?php echo md5(time());?>">
<div class="row">
<div class="col-sm-6">
    <div class="section">
    	<label class="form_label">Select Category <span  class='required'>*</span></label>
        <label class="field select">
             {!! Form::select('marketplace_category_id', $marketPlaceCategories, null,array('required','class'=>'gui-input','placeholder'=> trans('message.marketplace.fields.category'))) !!}              
                @if ($errors->has('marketplace_category_id')) <p class="help-block">{{ $errors->first('marketplace_category_id') }}</p> @endif
            <i class="arrow double"></i>                    
        </label>  
    </div>
</div>
    <!-- end section -->
<div class="col-sm-6">    
    <div class="section">
    	<label class="form_label">Item Name <span  class='required'>*</span></label>
        <label for="item" class="field prepend-icon">
             {!! Form::text('item', null, array('required','class'=>'gui-input','placeholder'=>trans('message.marketplace.fields.item'))) !!}
            @if ($errors->has('item')) <p class="help-block">{{ $errors->first('item') }}</p> @endif
            <label for="item" class="field-icon"><i class="fa fa-shopping-cart"></i></label>
        </label>
    </div>
    </div> 
</div>

<div class="row">
<div class="col-sm-12"> 
<div class="section">
	<label class="form_label">Item Description</label>
	<label class="field prepend-icon">
		   {!! Form::textarea('item_description', null, array('class'=>'gui-textarea','placeholder'=>trans('message.marketplace.fields.itemdescription'),'style'=>'resize:none','rows'=>3)) !!}
        @if ($errors->has('item_description')) <p class="help-block">{{ $errors->first('item_description') }}</p> @endif
                    <label for="itemdescription" class="field-icon"><i class="fa fa-align-justify"></i></label>
		
	</label>
</div>
</div> 
</div> 

<input type="hidden" name="organization_id" value="{{Session::get('organization_id')}}">

<div class="row">

<div class="col-sm-6">    
    <div class="section">
    	<label class="form_label">Actual Price <span  class='required'>*</span></label>
        <label class="field prepend-icon">
               {!! Form::text('actual_price', null, array('class'=>'gui-input','placeholder'=>trans('message.marketplace.fields.actualprice'))) !!}
            @if ($errors->has('actual_price')) <p class="help-block">{{ $errors->first('actual_price') }}</p> @endif
            			<label for="actualprice" class="field-icon"><i class="fa fa-inr"></i></label>
        </label>
    </div>
</div> 
<div class="col-sm-6">
    <div class="section">
    	<label class="form_label">Offer Price</label>
        <label class="field prepend-icon">
                {!! Form::text('base_price', null, array('required','class'=>'gui-input','placeholder'=>trans('message.marketplace.fields.baseprice'))) !!}
            @if ($errors->has('base_price')) <p class="help-block">{{ $errors->first('base_price') }}</p> @endif
			<label for="baseprice" class="field-icon"><i class="fa fa-inr"></i></label>
        </label>
    </div>
</div> 
</div>

<div class="row">
<div class="col-sm-6">
    <div class="section">
        <label class="form_label">Item Type <span  class='required'>*</span></label>
        <label class="field select">
              {!! Form::select('item_type', $enum,null,array('class'=>'gui-input','placeholder'=>trans('message.marketplace.fields.type')),Input::old('item_type')) !!}
            @if ($errors->has('item_type')) <p class="help-block">{{ $errors->first('item_type') }}</p> @endif
            <i class="arrow double"></i>                    
        </label>  
    </div>
</div>
<div class="col-sm-6">
    <div class="section">
       <label class="form_label">Contact number <span  class='required'>*</span></label>
         <label class="field prepend-icon">
                {!! Form::text('contact_number', null, array('required','class'=>'gui-input','placeholder'=>'Contact Number','id'=>'contact_number',$readonly)) !!}
            @if ($errors->has('contact_number')) <p class="help-block">{{ $errors->first('contact_number') }}</p> @endif
			
			@if($isCreate=='yes')
            <!--<a href="javascript:void(0);" class="btn btn-lg btn-primary launch-modals otp_but" data-toggle="modal" data-target=".bs-example-modal-sm" id="sendOTP">Send OTP</a>-->
			<a href="javascript:void(0);" style="display:none;" id="verifiedOTP">Your Mobile Number Has Been Verified</a>
			@endif
			@if($isCreate=='no')
				<a href="javascript:void(0);">Your Mobile Number Has Been Verified</a>
			@endif
			<!--<div id="showVerification" style="display:none;"><input type="textbox" name="verificationcode"/><a href="javascript:void(0);" id="verifyOTP">Verify</a></div>-->
			
	
			
			<label for="mobile_phone" class="field-icon"><i class="fa fa-mobile-phone"></i></label>  
        </label>
		
	</div>
  
</div>
<div id="showVerification" class="modal fade">
        <div class="modal-dialog sj_modal sportsjun-forms">
            <div class="modal-content">
                <div  class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Verification
					</h4>
                </div>
                <div class="modal-body">
                                    
				<div class="form-group">
                            <label class="col-md-4 control-label">Verification Code</label>
                            <div class="col-md-6">
							<input type="textbox" name="verificationcode"/>
                           </div>
                 </div>
							
				
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" id="verifyOTP">Verify</a>
                </div>
            </div>
        </div>
</div>
</div><!-- end section -->

@include ('common.address', ['mandatory' => ''])
@include ('common.upload')
<label class="form_label">{{  trans('message.marketplace.fields.gallery') }} </label>
@include ('common.uploadfield', ['uploadLimit' => 'null','field'=>'photos'])
@if(isset($marketplace))
@include('common.editphoto',['photos'=>$marketplace->photos,'type'=>'marketplace'])
@endif

<script>

var is_from_create = '{{$isCreate}}';

@if($is_org)
	is_from_create = 'no';
@endif

if(is_from_create=='yes')
{
	function btnClick()
	{
		//contact_number = $("#contact_number").val();
		//if(contact_number!='')
		if($('#my-form').valid()) //if form is valid
		{
			if (window.location.href.indexOf("marketplace/create") > -1 && $('[name=contact_number]').valid()) {  
				$('[name=verificationcode]').val('');	
					var data = {"mobileNumber": $('[name=contact_number]').val(),"time_token":$('[name=time_token]').val()};
					$.ajax({
						url: base_url + '/marketplace/generateOTP',
						type: 'POST',
						dataType: 'json',               
						data: data,
						success: function(response){
							if(response.message == 'OTP SENT SUCCESSFULLY'){
								$('#showVerification').modal('show');
								//verifyOTP();								
							}else{
								$('#sendOTP').show();
								$('#showVerification').modal('hide');
								$.alert({
									title: "Alert!",
									content: response.message
								});
							}
						},
						error: function(jqXHR, textStatus, ex) {
							//console.log(textStatus + "," + ex + "," + jqXHR.responseText);
						}
					});
			}
			/*token = $('#time_token').val();
			 $.ajax({
				url: base_url+'/marketplace/isOtpSent',
				type: "post",
				dataType: 'JSON',
				data: {'contact_number': contact_number,'token':token},
				success: function(data) {
					if(data.status == 'fail') {
						$.alert({
							title: 'Alert!',
							content: 'Mobile Verification Required.'
						});
						$('#sendOTP').show();
						$('#verifiedOTP').hide();
						//location.reload();	
						return false;
						
					}
				}
			});*/
		}
	}
}
</script>




