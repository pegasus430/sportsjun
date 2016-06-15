<html lang="en">
<head>
<script type="text/javascript">
$(document).ready(function(){
	$('.create_album').click(function(){
		
		  $("#loader").remove();
		$('#title').val("");
		$("#nameResponse").html("");
		$('#description').val("");
		$('#savebutton').show();
		$('#myModalCreate').modal({
			backdrop: 'static'
		});
	}); 
});
</script>
<style type="text/css">
    .bs-example{
    	margin: 20px;
    }
	
	
</style>
</head>
<body>		

<div id="marketplaceid" class="gallery-pg">
      	
@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
@endif
		

		@if(!empty($user_profile_album) && $action=='user')
 
        <br>
		<div class="stage_head">User Profiles</div>
		<ul class="row list-unstyled market_gallery clearfix gallery-pg"> 
		 <li class="col-xs-6 col-sm-4 col-md-3">
			<div class="thumbnail">
			<div class="ImageWrapper userpro-img">
		 
		 {!! Helper::Images(!empty($user_profile_albums[0]['url'])?$user_profile_albums[0]['url']:'','user_profile',array('height'=>200,'width'=>200) )
		 !!}	
         <a href="{{url('/createphoto/'.$user_profile_album[0]['id'].'/'. $user_profile_album[0]['user_id'].'/'.'1')}}">User Profile Album ( {{ count($user_profile_album) }} )</a>
			 <!--<div class="actions">
			   <ul>
				<li><a href="/createphoto/{{ $user_profile_album[0]['id'] }}/{{ $user_profile_album[0]['user_id'] }}/1">User Profile Album ( {{ count($user_profile_album) }} )</a></li>
									
			<li></li>	
							
				</ul>
				</div>-->

		 </div>		
		 </div>
		 </li>	
		 </ul>

	@endif	
		@if($action=='organization' )
        <div class="stage_head">Form gallery</div>
		<ul class="row list-unstyled market_gallery clearfix gallery-pg"> 
		 <li class="col-xs-6 col-sm-4 col-md-3">
			<div class="thumbnail">
			<div class="ImageWrapper userpro-img">
			
				 {!! Helper::Images(!empty($organization_profile_albums[0]['url'])?$organization_profile_albums[0]['url']:'','form_gallery_organization',array('height'=>200,'width'=>200) )
				 !!}	
				 <a href="{{url('/formgallery/'.$action_id.'/'.'formorganization')}}">Form gallery( {{ count($organization_profile_albums) }} )</a>
			
			 </div>		
			 </div>
		 </li>	
	 </ul>
	@endif
	@if($action=='tournaments' && count($albumcreate) )
        
		<ul class="row list-unstyled market_gallery clearfix gallery-pg"> 
		 <li class="col-xs-6 col-sm-4 col-md-3">
			<div class="thumbnail">
			<div class="ImageWrapper userpro-img">
		 
				 {!! Helper::Images(!empty($tournament_profile_albums[0]['url'])?$tournament_profile_albums[0]['url']:'','form_gallery_tournaments',array('height'=>200,'width'=>200) )
				 !!}	
				 <a href="{{url('/formgallery/'.$action_id.'/'.'formtournaments')}}">Form gallery( {{ count($tournament_profile_albums) }} )</a>
			
			 </div>		
			 </div>
		 </li>	
	 </ul>
	@endif

	             			 
	<p class="help-block" id="Response"></p> 
		<div class="stage_head">Media Gallery</div>
	<ul class="row list-unstyled market_gallery sportsjun-forms clearfix">
@if($action=='team'  )
	@if(isset($albumcreate)&&count($albumcreate))
    	<li class="col-xs-6 col-sm-4 col-md-3">
        	<div class="thumbnail">
                <div class="ImageWrapper">
				
                	<div class="create-album">                    					
                    <button type="button" class="button btn-primary launch-modal create_album" data-toggle="modal" data-target=".bs-example-modal-sm"><i class="fa fa-plus"></i> CREATE ALBUM</button>             			
                   	</div>
					
                </div>
            </div>
        </li>
		@endif	
	
@endif
@if(  $action=='match' )
	@if((isset( $matchalbumcreate)&&count(  $matchalbumcreate)) ||  $flag=='yes')
    	<li class="col-xs-6 col-sm-4 col-md-3">
        	<div class="thumbnail">
                <div class="ImageWrapper">
				
                	<div class="create-album">
                    <button type="button" class="button btn-primary launch-modal create_album" data-toggle="modal" data-target=".bs-example-modal-sm"><i class="fa fa-plus"></i> CREATE ALBUM</button>             			
                   	</div>
					
                </div>
            </div>
        </li>
		@endif	
	
@endif

@if($action=='tournaments')
	@if($result=='1')
    	<li class="col-xs-6 col-sm-4 col-md-3">
        	<div class="thumbnail">
                <div class="ImageWrapper">
				
                	<div class="create-album">
                    					
                    <button type="button" class="button btn-primary launch-modal create_album" data-toggle="modal" data-target=".bs-example-modal-sm"><i class="fa fa-plus"></i> CREATE ALBUM</button>             			
                   	</div>
					
                </div>
            </div>
        </li>
		@endif	
@endif

@if($action=='user' && $userId==$id)
	
    	<li class="col-xs-6 col-sm-4 col-md-3">
        	<div class="thumbnail">
                <div class="ImageWrapper">
				
                	<div class="create-album">
                    
                    <button type="button" class="button btn-primary launch-modal create_album" data-toggle="modal" data-target=".bs-example-modal-sm"><i class="fa fa-plus"></i> CREATE ALBUM</button>             			
                   	</div>
					
                </div>
            </div>
        </li>
	
@endif

@if($action=='organization'  && count($orgalbumcreate)!=0)
	
         <li class="col-xs-6 col-sm-4 col-md-3">
        	<div class="thumbnail">
                <div class="ImageWrapper">
				
                	<div class="create-album">
                    
                    <button type="button" class="button btn-primary launch-modal create_album" data-toggle="modal" data-target=".bs-example-modal-sm"><i class="fa fa-plus"></i> CREATE ALBUM</button>             			
                   	</div>
					
                </div>
            </div>
        </li>
@endif



        @if(isset($album_array)	&& count($album_array)>0 )

		@foreach($album_array as $album)
	
		@if(isset($photo_arrayy[$album['id']][0]['url']))
    	<li class="col-xs-6 col-sm-4 col-md-3 remove_li_{{$album['id']}}">
        	<div class="thumbnail">
            	<div class="ImageWrapper">
                	<a  href="{{url('/createphoto/'.$album['id'].'/'.$album['user_id'].'/0/'.$action.'/'.$action_id)}}">{!! Helper::Images($photo_arrayy[$album['id']][0]['url'],'gallery/'.$photo_arrayy[$album['id']][0]['imageable_type'],array('height'=>200,'width'=>200,
		         'id'=>$photo_arrayy[$album['id']][0]['imageable_id']) )!!}</a>
        			
				@if($action=='team' )
				
			   @if($album['accessflag']=='yes' )
					 <div class="actions">
                    	<ul>
							 <li><a href="#" data-toggle="modal" data-pid="{{ $album['id'] }}" onclick="editalbum({{$album['id'] }})" class="launch-modal editalbum icon-edit"><i class="fa fa-pencil"></i></a></li>
							<li><a href="javascript:void(0);" class="removeAlbumPhoto icon-delete"  data-pid="{{ $album['id'] }}"  data-page="{{'album'}}" title="Delete"><i class="fa fa-remove"></i></a></li>
						</ul>
                     </div>
				 @endif
				@endif
				
				@if( $action=='match' )							
			   @if($album['accessflag']=='yes'|| $flag=="yes" )
					<div class="actions">
                    	<ul>
							 <li><a href="#" data-toggle="modal" data-pid="{{ $album['id'] }}" onclick="editalbum({{$album['id'] }})" class="launch-modal editalbum icon-edit"><i class="fa fa-pencil"></i></a></li>
							<li><a href="javascript:void(0);" class="removeAlbumPhoto icon-delete"  data-pid="{{ $album['id'] }}"  data-page="{{'album'}}" title="Delete"><i class="fa fa-remove"></i></a></li>
						</ul>
                     </div>
				 @endif
				@endif
							
				@if($action=='tournaments' )
				@if($result=='1')
					 <div class="actions">
                    	<ul>
							 <li><a href="#"  data-toggle="modal" data-pid="{{ $album['id'] }}" onclick="editalbum({{$album['id'] }})" class="launch-modal editalbum icon-edit"><i class="fa fa-pencil"></i></a></li>
							<li><a href="javascript:void(0);" class="removeAlbumPhoto icon-delete"  data-pid="{{ $album['id'] }}"  data-page="{{'album'}}" title="Delete"><i class="fa fa-remove"></i></a></li>
						</ul>
                     </div>
				 @endif
				@endif
				@if($action=='organization' && $album['user_id']==$loginUserId)
				
					 <div class="actions">
                    	<ul>
							 <li><a href="#"  data-toggle="modal" data-pid="{{ $album['id'] }}" onclick="editalbum({{$album['id'] }})" class="launch-modal editalbum icon-edit"><i class="fa fa-pencil"></i></a></li>
							<li><a href="javascript:void(0);" class="removeAlbumPhoto icon-delete"  data-pid="{{ $album['id'] }}"  data-page="{{'album'}}" title="Delete"><i class="fa fa-remove"></i></a></li>
						</ul>
                     </div>
				 
				@endif
							
				@if($action=='user' )
				@if($action=='user' && $action_id==$id)
								
					 <div class="actions">
                    	<ul>
							 <li><a href="#"  data-toggle="modal" data-pid="{{ $album['id'] }}" onclick="editalbum({{$album['id'] }})" class="launch-modal editalbum icon-edit"><i class="fa fa-pencil"></i></a></li>
								<li><a href="javascript:void(0);" class="removeAlbumPhoto icon-delete"  data-pid="{{ $album['id'] }}"  data-page="{{'album'}}" title="Delete"><i class="fa fa-remove"></i></a></li>
						</ul>
                     </div>
				 @endif
				  @endif

                   
                 </div>
			</div>
            <div class="caption">
            	<span class="lead">
                	<a   id="editalbum_{{ $album['id'] }}" href="{{url('/createphoto/'.$album['id'].'/'.$album['user_id'].'/0/'.$action.'/'.$action_id)}}">{{ $album['title'] }}</a> <span>( {{ $album_photo_count[$album['id']] }} )</span>
                </span>
            </div>
        </li>
        @else
        <li class="col-xs-6 col-sm-4 col-md-3 remove_li_{{$album['id']}}">
        	<div class="thumbnail">
            	<div class="ImageWrapper">
    			 					
						<a   href="{{url('/createphoto/'.$album['id'].'/'.$album['user_id'].'/0/'.$action.'/'.$action_id)}}">{!! Helper::Images('market_place_default.png','marketplace',array('height'=>200,'width'=>200) )!!}</a>

    			 								 
				@if($action=='team'  )
			   @if($album['accessflag']=='yes')
					  <div class="actions">
                    	   <ul>	
							  <li><a href="#"  data-toggle="modal" data-pid="{{ $album['id'] }}" onclick="editalbum({{$album['id'] }})"    class="launch-modal editalbum icon-edit"><i class="fa fa-pencil"></i></a></li>
							<li><a href="javascript:void(0);" class="removeAlbumPhoto icon-delete"  data-pid="{{ $album['id'] }}"  data-page="{{'album'}}" title="Delete"><i class="fa fa-remove"></i></a></li>		
							</ul>
						</div>																										
				  @endif
				  @endif
				@if($action=='match' )							
			   @if($album['accessflag']=='yes' || $flag=="yes"  )
					   <div class="actions">
                    	 <ul>	
							 <li><a href="#"  data-toggle="modal" data-pid="{{ $album['id'] }}" onclick="editalbum({{$album['id'] }})" class="launch-modal editalbum icon-edit"><i class="fa fa-pencil"></i></a></li>
							<li><a href="javascript:void(0);" class="removeAlbumPhoto icon-delete"  data-pid="{{ $album['id'] }}"  data-page="{{'album'}}" title="Delete"><i class="fa fa-remove"></i></a></li>
						 </ul>
						</div>	
				 @endif
				@endif
							
				@if($action=='tournaments')
				@if($result=='1')
					 <div class="actions">
                    	 <ul>	
							 <li><a href="#"  data-toggle="modal" data-pid="{{ $album['id'] }}" onclick="editalbum({{$album['id'] }})" class="launch-modal editalbum icon-edit" ><i class="fa fa-pencil"></i></a></li>
							<li><a href="javascript:void(0);" class="removeAlbumPhoto icon-delete"  data-pid="{{ $album['id'] }}"  data-page="{{'album'}}" title="Delete"><i class="fa fa-remove"></i></a></li>
						</ul>
					</div>	
				 @endif
				@endif
				@if($action=='organization' && $album['user_id']==$loginUserId)
				
					 <div class="actions">
                    	<ul>
							 <li><a href="#"  data-toggle="modal" data-pid="{{ $album['id'] }}" onclick="editalbum({{$album['id'] }})" class="launch-modal editalbum icon-edit"><i class="fa fa-pencil"></i></a></li>
							<li><a href="javascript:void(0);" class="removeAlbumPhoto icon-delete"  data-pid="{{ $album['id'] }}"  data-page="{{'album'}}" title="Delete"><i class="fa fa-remove"></i></a></li>
						</ul>
                     </div>
				 
				@endif
							
				@if($action=='user' )
				@if($action=='user' && $userId==$id)
						<div class="actions">
                    	   <ul>	
							 <li><a href="#"  data-toggle="modal" data-pid="{{ $album['id'] }}" onclick="editalbum({{$album['id'] }})" class="launch-modal editalbum icon-edit"><i class="fa fa-pencil"></i></a></li>
								<li><a href="javascript:void(0);" class="removeAlbumPhoto icon-delete"  data-pid="{{ $album['id'] }}"  data-page="{{'album'}}" title="Delete"><i class="fa fa-remove"></i></a></li>
						   </ul>
						</div>	
				 @endif
				  @endif
							
                            
				</div>
                </div>
                <div class="caption">
                	<span class="lead">
                    	<a   id="editalbum_{{ $album['id'] }}"  href="{{url('/createphoto/'.$album['id'].'/'.$album['user_id'].'/0/'.$action.'/'.$action_id)}}">{{ $album['title'] }}</a><span> ( {{ $album_photo_count[$album['id']] }} )</span>
                    </span>
                </div>
                
			</li>
			
			</li>
				@endif
				@endforeach	
				@endif
			<div id="album"></div>
    </ul>
	
</div>



    <!-- Modal HTML -->
    <div id="myModalCreate" class="modal fade">
        <div class="modal-dialog sj_modal sportsjun-forms">
            <div class="modal-content">
                <div  class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">{{ trans('message.album.fields.albumheading') }}
					</h4>
                </div>
                <div class="modal-body">
                                    {!! Form::open(array('class'=>'form-horizontal','files' => true)) !!} 
				<!--<div class="form-group">
                        <label class="col-md-4 control-label">{{ trans('message.album.fields.albumname') }}</label>
                        <div class="col-md-6">
                            {!! Form::text('title', null, array('required', 'class'=>'form-control','id'=>'title')) !!}
							 <p class="help-block" id="nameResponse"></p> 
                        </div>
                </div>-->
				<div class="form-group">
                            <label class="col-md-4 control-label">{{ trans('message.album.fields.albumname') }}</label>
                            <div class="col-md-6">
							<input type="text" class="form-control" name="title"  id="title" value="" autocomplete="off">
							<p style="color:#a94442;" class="help-block" id="nameResponse"></p> 
                           </div>
                 </div>
							
				<div class="form-group">
					<label class="col-md-4 control-label">{{ trans('message.album.fields.description') }}</label>
					<div class="col-md-6">
						{!! Form::textarea('description', null, array('class'=>'form-control','style'=>'resize:none','rows'=>3,'id'=>'description')) !!}
					</div>
				</div>

			
				{!! Form::close() !!}
                </div>
                <div class="modal-footer">
                   
                    <button type="button" id="savebutton" class="button btn-primary" onClick="createAlbum();">Save</button>
					 <button type="button" class="button btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


 
</body>
</html> 
<div id="editAlbumDiv"></div>
<script>
$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
	function createAlbum()
	{
		
		var action_id = "{{ $action_id }}";
		var action = "{{ $action }}";
		$("#nameResponse").html('');
		 title =$('#title').val();
		 description =$('#description').val();
		 var b = "{{csrf_token()}}";
		 		 
		 
		 $("#savebutton").before("<div id='loader'></div>");
		  $("#loader").html("<img src="+base_url+"/images/loaderwhite_21X21.gif>");
		   $('#savebutton').hide();				
		 
		$.ajax({
				  url: '{!! ROUTE('user.album.store')!!}',//'/user/album/store',
				  type: "POST",
				  dataType: 'JSON',
				  data: {'title':title,'description':description,'action_id':action_id,'action':action,'_token': b,'duplicate_album':'yes'},
				  
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
						       // $("#savebutton").before("<div id='loader'></div>");
							  // $("#loader").html("<img src="+base_url+"/images/loaderwhite_21X21.gif>");
							   // $('#savebutton').hide();				

							 $("#loader").remove();
				
							 $.ajax({
									type:"GET",
									//  dataType: 'JSON',
									url:urls ,
									 success: function(data) {
										 
										  $('#myModalCreate').modal('toggle');
										  $('#album').append(data.html);
										   $.alert({
												title: "Alert!",
												content: "Album created successfully."
											});
															 
									 }
						      });
					  }
					  else
					  {
						 
								$("#loader").remove();
								$('#savebutton').show();	
									
									if(data.msg=='The title has already been taken.')
									{
										 $("#nameResponse").append(data.msg);
										 $.confirm({
													  
											title: 'Confirmation',
											content: "Same Album name already exists, do you want to create Album with same name?",
											confirm: function() {
											
												$("#nameResponse").html('');
																																	 
												var action_id = "{{ $action_id }}";
													
											   var action = "{{ $action }}";		
																			 
										$.ajax({	 
												   url: '{!! ROUTE('user.album.store')!!}',//'/user/album/store',
												  type: "POST",
												  dataType: 'JSON',
												  data: {'title':title,'description':description,
												  'action_id':action_id,'action':action,'_token': b,'duplicate_album':'no'},
												  
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
											
														 $.ajax({
																type:"GET",
																//  dataType: 'JSON',
																url:urls ,
																 success: function(data) {
																	 
																	  // alert("success");
																	  $('#myModalCreate').modal('toggle');
																	  $('#album').append(data.html);
																	   $.alert({
																			title: "Alert!",
																			content: "Album created successfully."
																		});
																						 
																 }
														  });
													  }
												  else{
													  
													  $("#loader").remove();
															$('#savebutton').show();	
														  $.each(data.msg, function(key, value){
															  
														 if(key=='title')
														$("#nameResponse").append(value);
														  })
													  												  
													  
												  }
											  }
																		 
											}) 
																		 
																		 
											}   
															
										  });
													   
										
									}
								  else
								  {
										 $.each(data.msg, function(key, value){
											  
										 if(key=='title')
										$("#nameResponse").append(value);
												
											
									   });
								  }
						   
											   
				      }
				  }
			}); 
	}	   
						   
							
</script>
<script>
function editalbum(id)
{
		var action_id = "{{ $action_id }}";
		var action = "{{ $action }}";
	     var b =id;
		 var token = "{{csrf_token()}}";
         $.ajax({
            url: base_url + "/editAlbumPhoto" ,
            type: "POST",
           data: {'id':b,'action_id':action_id,'action':action ,'_token': token},
            success: function(response) {
				 
				
               
				  $("#loader").remove();
				$('#savealbum').show();		
			$('#titlenameResponse').html("");
			 $("#editAlbumDiv").html(response);
				 $("#myModal2").modal('show');
            
            }
        });
}
</script>
