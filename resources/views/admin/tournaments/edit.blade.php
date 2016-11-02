@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">
<div class="sportsjun-wrap">
<div class="sportsjun-forms sportsjun-container wrap-2">
<div class="form-header header-primary"><h4><i class="fa fa-pencil-square"></i>{{ trans('message.tournament.fields.heading') }}</h4></div>
                  				
				
                 {!! Form::model( $tournament ,(array('route' => array('admin.tournaments.update',$id),'class'=>'form-horizontal','method' => 'put','id' => 'my-tournaments'))) !!}   
			     <div class="form-body">

                        @include ('tournaments._form', ['submitButtonText' => 'Update','formType'=>'edit'])
                        
                        @include ('tournaments._enrolform', ['submitButtonText' => 'Update','formType'=>'edit'])
						</div>	
				 <div class="form-footer">
                  <button type="submit" class="button btn-primary btn-createedit">Update</button>

                  <a class="button btn-primary btn-tournamentedit" href="javascript:void(0)">Next</a>
                  

                </div>	
                {!! Form::close() !!}
					{!! JsValidator::formRequest('App\Http\Requests\CreateTournamentRequest', '#my-tournaments'); !!}
			 
					
      				 
</div>
</div>
</div>



<script type="text/javascript">
    $(function () {
    	$('.enroltypeedit').hide();
    	$('.sold_check').show();
    	var enroltype = $('#enrollment_type').val();
    	//alert(enroltype);
    	if(enroltype != 'online'){
    		$('.btn-tournamentedit').hide();
        	$('.btn-createedit').show();
        	$('.enroltypeedit').show();
    	} else {
    		$('.btn-tournamentedit').show();
        	$('.btn-createedit').hide();
    	}
        $('.payment_detailsedit').hide();
        $('.form_enroledit').hide();
        $("#reg_opening_date").datepicker();
        $("#reg_closing_date").datepicker();
        $('#reg_opening_time').datetimepicker({ format: 'h:mm A' });
        $('#reg_closing_time').datetimepicker({ format: 'h:mm A' });

        $('.payment_formedit').hide();
        $("body").on('click','.btn-tournamentedit', function(){
        	if($("#my-tournaments").valid()){
        		//alert($('.form_enroledit').css('display'));
        		if($('.form_enroledit').css('display') == 'block'){
	        		$('.form_enroledit').hide();
	        		$('.payment_detailsedit').show();
	        		$('.btn-tournamentedit').hide();
	        		$('.btn-createedit').show();
	        	} else {
	        		$('.form_enroledit').show();
	        	}
	            
	            $('.main_tour_formedit').hide();
        	}
       // $("#sub-tournaments").valid();
        	
        })
        $("body").on('click','.add_account_divedit', function(){
        	$('.payment_formedit').show();
        })
        $('body').on('click','.btn-close-m',function(){
        	// alert('ads');
        	// window.location.reload();
        })
        $('#enrollment_type').on('change', function (e) {
        	//alert('asdasd');
            var enroltype = $('#enrollment_type').val();
            if(enroltype != 'online'){
            	$('.btn-tournamentedit').hide();
        		$('.btn-createedit').show();
        		$('.enroltypeedit').show();
            } else {
            	$('.btn-tournamentedit').show();
        		$('.btn-createedit').hide();
        		$('.enroltypeedit').hide();
            }
        })
    });



$( document ).ready(function() {
    var c_id=$('#country_id').val();
     var base_url = window.location.origin;
       $.ajax({
        type: "GET",
        url: base_url + '/admin/paymentgateways/availability',
        data: { 'c_id': c_id},
        success: function(msg) {
           if(msg==0) {
             $("#enrollment_type option[value='online']").remove();
                $('.btn-tournamentedit').hide();
                $('.btn-createedit').show();
                $('.enroltypeedit').show();
           } else {
              $('#enrollment_type').append($('<option>', {
                value: 'online',
                text: 'ONLINE PAYMENT'
              })); 
                
           }
        }
    })
});












$('#country_id').change(function(){
     var c_id=$('#country_id').val();
     var base_url = window.location.origin;
       $.ajax({
        type: "POST",
        url: base_url + '/admin/paymentgateways/availability',
        data: { 'c_id': c_id},
        success: function(msg) {
           if(msg==0) {
             $("#enrollment_type option[value='online']").remove();
                $('.btn-tournamentedit').hide();
        		$('.btn-createedit').show();
        		$('.enroltypeedit').show();
           } else {
              $('#enrollment_type').append($('<option>', {
                value: 'online',
                text: 'ONLINE PAYMENT'
              })); 
                
           }
        }
    })
  
});  













 </script>




@endsection
