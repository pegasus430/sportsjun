<!-- Modal -->
<div id="editsubtournament" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content sportsjun-forms">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ trans('message.tournament.edit_tournament_event_modal.heading') }}</h4>
      </div>

<!--<div class="form-header header-primary"><h4><i class="fa fa-pencil-square"></i>{{ trans('message.tournament.fields.heading') }}</h4></div>-->
                  				
				@if( $roletype=='admin')
                 {!! Form::model( $tournament ,(array('route' => array('admin.tournaments.update',$id),'class'=>'form-horizontal','method' => 'put','id' => 'edit-tournaments'))) !!}   
			         <div class="modal-body">
      	<div class="sportsjun-forms">                 
			     <div class="form-body">
                        @include ('tournaments._form', ['submitButtonText' => 'Update','formType'=>'edit'])
                       @include ('tournaments._enrolform', ['submitButtonText' => 'Update','formType'=>'edit'])
						</div>	
				 <div class="form-footer">
                  <button type="submit" class="button btn-primary">Update</button>

                </div>	
                {!! Form::close() !!}
					{!! JsValidator::formRequest('App\Http\Requests\CreateTournamentRequest', '#edit-tournaments'); !!}
			   @endif
					
					
					
			   @if( $roletype=='user')
			   {!! Form::model( $tournament,(array('route' => array('tournaments.update',$id),'class'=>'form-horizontal edit-only','method' => 'put','id' => 'edit-tournaments'))) !!}   
			         <div class="modal-body">
      	<div class="sportsjun-forms">
				 
			  <div class="form-body">
                       @include ('tournaments._form', ['submitButtonText' => 'Update','formType'=>'edit'])
                       @include ('tournaments._enrolform', ['submitButtonText' => 'Update','formType'=>'edit'])
			    </div>	
				
                
					
      				 
</div>       
      </div>
      <div class="modal-footer">
	  	<button style="display: none;" type="submit" onclick="update();" class="button btn-primary btn-createedit">Update</button>
	  	<a class="button btn-primary btn-tournamentedit">Next</a>
        <button type="button" class="button btn-secondary btn-close-m" data-dismiss="modal">Close</button>
      </div>
	  {!! Form::close() !!}
					{!! JsValidator::formRequest('App\Http\Requests\CreateTournamentRequest', '#edit-tournaments'); !!}

					
				@endif	
    </div>

  </div>
</div>

					<script type="text/javascript">
							$(function() {
								$.validator.addMethod("greater_startdate", function(value, element) {
									 var startDate = $('[name="start_date"]').val();
									  var endDate = $('[name="end_date"]').val();
									  var startDate = startDate.split('/').reverse().join('-');
									var endDate = endDate.split('/').reverse().join('-');										
									return Date.parse(endDate) >= Date.parse(startDate);
								}, "End Date must be equal to or after Start Date");
								$('[name="end_date"]').rules("add", "greater_startdate");
								
								
								$.validator.addMethod("win_loose_points", function(value, element) {
									 var points_win = $('[name="points_win"]').val();
									  var points_loose = $('[name="points_loose"]').val();
									  
									  	if(points_win == '' && points_loose == '')
					        		    return true;
					        	    else
									return (parseInt(points_win)) >= parseInt((points_loose));
								}, " Losing team points must be less than Winning team points");
								$('[name="points_loose"]').rules("add", "win_loose_points");

								$.validator.addMethod("win_tie_points", function(value, element) {
									 var points_win = $('[name="points_win"]').val();
									  var points_tie = $('[name="points_tie"]').val();
									  
									  	if(points_win == '' && points_tie == '')
					        		    return true;
					        	    else
									return (parseInt(points_win)) >= parseInt((points_tie));
								}, " tie match points must be less than Winning team points");
								$('[name="points_tie"]').rules("add", "win_tie_points");
								
								
								
								
								
							});
                    </script>
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
    	}
        $('.payment_detailsedit').hide();
        $('.form_enroledit').hide();
        $("#reg_opening_date").datepicker();
        $("#reg_closing_date").datepicker();
        $('#reg_opening_time').datetimepicker({ format: 'h:mm A' });
        $('#reg_closing_time').datetimepicker({ format: 'h:mm A' });

        $('.payment_formedit').hide();
        $("body").on('click','.btn-tournamentedit', function(){
        	if($("#edit-tournaments").valid()){
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
    $('#editsubtournament').on('hidden.bs.modal', function () {
	    window.location.reload();
	})



$( document ).ready(function() {
     var c_id=$('.edit-only #country_id').val();
     var base_url = window.location.origin;
       $.ajax({
        type: "GET",
        url: base_url + '/admin/paymentgateways/availability',
        data: { 'c_id': c_id},
        success: function(msg) {
           if(msg==0) {
              $(".edit-only #enrollment_type option[value='online']").remove();
                $('.edit-only .btn-tournamentedit').hide();
        		$('.edit-only .btn-createedit').show();
        		$('.edit-only .enroltypeedit').show();
           } else {
              $('.edit-only #enrollment_type').append($('<option>', {
                value: 'online',
                text: 'ONLINE PAYMENT'
              }));
                
           }
        }
    })
});














$('.edit-only #country_id').change(function(){

     var c_id=$('.edit-only #country_id').val();
     var base_url = window.location.origin;
       $.ajax({
        type: "POST",
        url: base_url + '/admin/paymentgateways/availability',
        data: { 'c_id': c_id},
        success: function(msg) {
           if(msg==0) {
             $(".edit-only #enrollment_type option[value='online']").remove();
                $('.edit-only .btn-tournamentedit').hide();
        		$('.edit-only .btn-createedit').show();
        		$('.edit-only .enroltypeedit').show();
           } else {
              $('.edit-only #enrollment_type').append($('<option>', {
                value: 'online',
                text: 'ONLINE PAYMENT'
              })); 
                
           }
        }
    })
  
}); 





	
</script>
