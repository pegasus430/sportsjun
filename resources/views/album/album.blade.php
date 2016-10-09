
        @if(isset($album_array)	&& count($album_array)>0 )
 <?php //echo "<pre>";print_r($photo_arrayy);exit;?>
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
    			 					
						<a  href="{{url('/createphoto/'.$album['id'].'/'.$album['user_id'].'/0/'.$action.'/'.$action_id)}}">{!! Helper::Images('market_place_default.png','marketplace',array('height'=>200,'width'=>200) )!!}</a>

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