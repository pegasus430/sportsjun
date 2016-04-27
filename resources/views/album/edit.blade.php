<div id="myModal2" class="modal fade">
      <div class="modal-dialog sj_modal sportsjun-forms">
		<div class="modal-content">
                <div  class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Edit Album
					</h4>
                </div>



           <div class="modal-body">
               {!! Form::open(array('class'=>'form-horizontal','files' => true)) !!} 
			    	<div class="form-group">
                            <label class="col-md-4 control-label">{{ trans('message.album.fields.albumname') }}</label>
                            <div class="col-md-6">							
								<input type="text" class="form-control" id="titlename" name="title" value="<?php echo (isset($album_array[0]['title']))?$album_array[0]['title']:''?>">
							   <p  style="color:#a94442;"  class="help-block" id="titlenameResponse"></p> 
									
                            </div>
                    </div>
				
					<div class="form-group">
                            <label class="col-md-4 control-label">{{ trans('message.album.fields.description') }}</label>							
                            <div class="col-md-6">
							<input type="text" class="form-control"  id="description1" name="description" value="<?php echo (isset($album_array[0]['description']))?$album_array[0]['description']:''?>">										
                            </div>
                    </div>
				<input type="hidden" id="idname" value="{{ $album_array[0]['id'] }}">
				
				
				{!! Form::close() !!}
			 </div>
				
				
				 <div class="modal-footer">                    
                    <button type="button" id="savealbum" class="button btn-primary" onclick="saveAlbum();">Save</button>
					<button type="button" class="button btn-secondary" data-dismiss="modal">Close</button>
                </div>				
				
		 </div>
	</div>
</div>
		
<input type="hidden" value="{{$action}}" id="action" name="action">
<input type="hidden" value="{{$action_id}}" id="action_id" name="action_id">
			

<style type="text/css">
    .bs-example{
    	margin: 20px;
    }
	
	
</style>	
				
				
<script>
	function saveAlbum()
	{	
		 title =$('#titlename').val();
		 description =$('#description1').val();
		 $("#titlenameResponse").html('');
		  action_id =$('#action_id').val();
		  action =$('#action').val();
		  id =$('#idname').val();
		  	 var b = "{{csrf_token()}}";
			 
		  $("#savealbum").before("<div id='loader'></div>");
		  $("#loader").html("<img src="+base_url+"/images/loaderwhite_21X21.gif>");
		   $('#savealbum').hide();		
			 
			 
			 
		$.ajax({
				  url: base_url + "/editstore" ,
				  type: "POST",
				  dataType: 'JSON',
				  data: {'title':title,'description':description,'action_id':action_id,'action':action,'id':id,'_token': b,'duplicate_album':'yes'},
				  
				  success: function(data){
					  

				    if(data.status=="success")
					 {
						  // $("#savealbum").before("<div id='loader'></div>");
						  // $("#loader").html("<img src="+base_url+"/images/loaderwhite_21X21.gif>");
						  // $('#savealbum').hide();		
				  			
							 $("#loader").remove();
							
				    	 var lastinsertedid=data.lastinsertedid;					 
					        $('#editalbum_' + lastinsertedid).html(data.name);					 
					    	$('#myModal2').modal('toggle');
					
					 }
				   else
				   {
					   
					   $("#loader").remove(); 
					   $('#savealbum').show();	
					
														
									if(data.msg=='The title has already been taken.')
									{
										 $("#titlenameResponse").append(data.msg);	  
										 $.confirm({
													  
											title: 'Confirmation',
											content: "Same Album name already exists, do you want to continue?",
											confirm: function() {
											
												$("#titlenameResponse").html('');
																																	 
												 action_id =$('#action_id').val();
		                                         action =$('#action').val();	
																			 
										$.ajax({	 
												    url: base_url + "/editstore" ,
												  type: "POST",
												  dataType: 'JSON',
												  data: {'title':title,'description':description,'action_id':action_id,'action':action,'id':id,'_token': b,'duplicate_album':'no'},
												  
											  success: function(data)
											     {	
													var id=data.id;
													 var action=data.action;
													 var action_id=	data.action_id;
													 var page='ajax';
													 var lastinsertedid=data.lastinsertedid;
													 var urls = base_url+'/albumajax/'+action +'/'+id+'/'+action_id+'/'+page+'/'+lastinsertedid;
													 
													if(data.status=="success")
												   {
																		
													 $("#loader").remove();
																			
													var lastinsertedid=data.lastinsertedid;					 
													$('#editalbum_' + lastinsertedid).html(data.name);					 
													$('#myModal2').modal('toggle');
																									 
													 }
													  else{
														  
														    $("#loader").remove(); 
					                                        $('#savealbum').show();		
															  $.each(data.msg, function(key, value){
																  
															 if(key=='title')
						                                     $("#titlenameResponse").append(value);
					
															  })
																										  
														  
													  }
											  }
																		 
											}) 
																		 
																		 
											}   
															
										  });
													   
										
									}
							   else{
				
											
								  $.each(data.msg, function(key, value){
									 if(key=='title')
									$("#titlenameResponse").append(value);								
								
								   });		
							   }					   
									   
				   }

				 }
			}); 
	}
</script>		
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
				
		