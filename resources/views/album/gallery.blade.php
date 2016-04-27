
 <?php //echo "<pre>";print_r($photo_array);exit;?>
@foreach($photo_array as $a)
@foreach($a as $album)
            <?php //echo $album['id'];exit; ?>
            <li class="col-xs-6 col-sm-4 col-md-3 remove_li_{{$album['id']}}" >
               <div class="thumbnail">
                  <div class="ImageWrapper" id="id_{{$album['id']}}">
                     @if($album['imageable_type']=='gallery_user' || $album['imageable_type']=='gallery_team' || $album['imageable_type']=='gallery_tournaments' || $album['imageable_type']=='gallery_facility' || $album['imageable_type']=='gallery_organization' || $album['imageable_type']=='gallery_match')
						
                     <!--<img alt="{{$album['title']}}" src="{{  url('/uploads/gallery/'.$album['imageable_type'].'/'.$action_id.'/'.$album['url']) }}">-->
                     <a href="javascript:void(0);"  data-pid="{{ $album['id'] }}"  class="view_gallery_album">{!! Helper::Images($album['url'],'gallery/'.$album['imageable_type'],array('height'=>200,'width'=>200,'id'=>$action_id ) )
                     !!}</a>  
                    <div class="actions">
                       <!-- <p><a href="javascript:void(0);" class="view_gallery_album" data-pid="{{$album['id']}}">VIEW GALLERY</a></p>-->
						 <ul>
                         	<li><a href="javascript:void(0);"  data-pid="{{ $album['id'] }}"  data-toggle="tooltip" data-placement="top" class="view_gallery_album" title="View Gallery" ><i class="fa fa-info-circle"></i></a></li>
                           
						 <li><a data-pid="{{$album['id']}}"><span>Likes</span>:&nbsp;<label id="likecountid_{{$album['id']}}">{{ $album['like_count'] }}</label></a></li>
						 
							<li>  
							
							
					<div class="" id="main_{{$album['id']}}">
                        <?php $a = explode(',',$album['likes']);?>
                        @if(!in_array($userId,$a) )
                   
                        <a href="javascript:void(0);" class="likecount green" data-pid="{{$album['id']}}"><i class="fa fa-thumbs-up"></i></a> <!--{{ $album['like_count'] }} --></a>
                        @else                     
                        <a href="javascript:void(0);" class="Dislike black" data-pid="{{$album['id']}}" >
						<i class="fa fa-thumbs-down"></i></a> <!--{{ $album['like_count'] }} --></a>
                        
                        @endif
                     </div>
					 
					 
					  <div class="" id="like_{{$album['id']}}" style="display:none">
                        
                           <a href="javascript:void(0);"  class="likecount green" data-pid="{{$album['id']}}"><i class="fa fa-thumbs-up"></i> </a>
						   
                         <!--  <div  id="ab_{{$album['id']}}"> 0 </div>-->
                       
                     </div>
                     <div class="" id="dislike_{{$album['id']}}" style="display:none">                      
                           <a   href="javascript:void(0);" class="Dislike black" data-pid="{{$album['id']}}" ><i class="fa fa-thumbs-down"></i></a> 						   
                         <!--  <div  id="abc_{{$album['id']}}"> 0 </div>-->
                      
                     </div>					 					 
					 </li>
					 	@if($action=='team')
					     @if($album['accessflag']=='yes')
                            <li><a href="javascript:void(0);" class="removeAlbumPhoto"  data-pid="{{ $album['id'] }}"   data-page="{{ 'viewalbum' }}" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-remove"></i></a></li>
						
						@endif					
						@endif
						
						@if($action=='match' )
                        @if($album['accessflag']=='yes' || $flag=='yes')
                            <li><a href="javascript:void(0);" class="removeAlbumPhoto"  data-pid="{{ $album['id'] }}"   data-page="{{ 'viewalbum' }}" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-remove"></i></a></li>
						
						@endif					
						@endif
					
						@if($action=='tournaments')
						@if($result=='1')
						 <li><a href="javascript:void(0);" class="removeAlbumPhoto"  data-pid="{{ $album['id'] }}"   data-page="{{ 'viewalbum' }}" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-remove"></i></a></li>
						 @endif
						@endif
						@if($action=='organization')
							@if(isset($orgphotocreate)&& count($orgphotocreate))
								<li><a href="javascript:void(0);" class="removeAlbumPhoto"  data-pid="{{ $album['id'] }}"   data-page="{{ 'viewalbum' }}" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-remove"></i></a></li>
							 @endif
						@endif
						
						@if($action=='user' && $action_id== $loginid)
							 <li><a href="javascript:void(0);" class="removeAlbumPhoto"  data-pid="{{ $album['id'] }}"   data-page="{{ 'viewalbum' }}" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-remove"></i></a></li>
						 @endif
                        </ul>
                     </div>
                     @else
          
                     <!--<img alt="{{$album['title']}}" src="{{ url('/uploads/user_profile/'.$album['url']) }}">-->
                     {!! Helper::Images($album['url'],'user_profile',array('height'=>200,'width'=>200) )
                     !!}  
					  <div class="actions">
                       <!-- <p><a href="javascript:void(0);" class="view_gallery_album" data-pid="{{$album['id']}}">VIEW GALLERY</a></p>-->
						 <ul>
						 <li>
                          <a href="javascript:void(0);"  data-pid="{{ $album['id'] }}"  data-toggle="tooltip" data-placement="top" class="view_gallery_album"title="View Gallery" ><i class="fa fa-info-circle"></i></a></li>
						  
						
						  @if( $user_id== $loginid)
						    <li><a href="javascript:void(0);" class="removeAlbumPhoto"  data-pid="{{ $album['id'] }}"   data-page="{{ 'userprofile' }}" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-remove"></i></a></li>
						   @endif 
						  
						  
						<?php  /* @if($action=='team')
						   @if($album['accessflag']=='yes')
				    	<li>	
					     <a href="javascript:void(0);" class="removeAlbumPhoto"  data-pid="{{ $album['id'] }}"   data-page="{{ 'viewalbum' }}" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-remove"></i></a></li>
					    @endif
						 @endif
						 @if($action=='tournaments')
                            @if($result=='1')
							 <li><a href="javascript:void(0);" class="removeAlbumPhoto"  data-pid="{{ $album['id'] }}"   data-page="{{ 'viewalbum' }}" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-remove"></i></a></li>
							 @endif
							@endif
								@if($action=='user' && $action_id== $loginid)
								  <li><a href="javascript:void(0);" class="removeAlbumPhoto"  data-pid="{{ $album['id'] }}"   data-page="{{ 'viewalbum' }}" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-remove"></i></a></li>
							 @endif */?>
				      </ul>
                     </div>
                     @endif
                   
                  </div>
               </div>
                <div class="caption">
                	<span class="lead">{{$album['title']}}</span>
                    <br />                    
                    <span class="lt-grey">{{date(config('constants.DATE_FORMAT.VALIDATION_DATE_FORMAT'), strtotime($album['created_at']))}}</span>
                </div>
            </li>
            @endforeach
		            @endforeach
					
					
					
					
					
		 

			