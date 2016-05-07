
@if(count($list) > 0)
                @if($offset == 0)
                <ul class="list-unstyled market_gallery clearfix">
                @endif
				@foreach($list as $lis)
                    <li class="col-xs-12 col-sm-4 col-md-3 mp_border_new remove_li_{{ $lis['id'] }}">
                        <div class="thumbnail">
                            <div class="ImageWrapper">

                            	@if(count($lis->photos)>0)
                               <!-- <img src="{{ url('/uploads/marketplace/'.$lis->photos[0]['url'])}}" alt="" class="img-responsive" />-->
						    {!! Helper::Images($lis->photos[0]['url'],'marketplace',array('class'=>'img-responsive' ) )
!!}	

							    @else
                                <!--<img src="{{ url('uploads/marketplace/market_place_default.png')}}" alt="" class="img-responsive" />-->
							  {!! Helper::Images('market_place_default.png','marketplace',array('class'=>'img-responsive') )
!!}		
                                @endif
 	                         	@if($marketplace=='myitems')
                                <div class="actions">
                                   <ul>
                                       <li><a href="#" data-pid="{{ $lis['id'] }}"  data-page='{{ $marketplace }}' data-toggle="tooltip" data-placement="top" title="View Detail" class="view_gallery icon-info mp_info"><i class="fa fa-info"></i></a></li>
                                       <li><a href="{{url('/marketplace/'.$lis['id'].'/edit')}}"  data-pid="{{ $lis['id'] }}" data-toggle="tooltip" data-placement="top" title="Edit" class="icon-edit mp_edit"><i class="fa fa-pencil"></i></a></li>
                                       <li><a href="javascript:void(0);" class="removephoto icon-delete mp_delete"  data-pid="{{ $lis['id'] }}" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-remove"></i></a></li>
									   @if($lis['approved']=='yes')
									   @if($lis['item_status']=='available')
                                       <li><a href="javascript:void(0);" class="sold icon-edit mp_sold" data-pid="{{ $lis['id'] }}" data-toggle="tooltip" data-placement="top" title="Available"><i class="fa fa-smile-o"></i></a></li>
								       @elseif($lis['item_status']=='sold out')
                                       <li><a href="javascript:void(0);" class="sold icon-edit mp_sold" data-pid="{{ $lis['id'] }}" data-toggle="tooltip" data-placement="top" title="Sold Out"><i class="fa fa-frown-o"></i></a></li>
								       @endif
									   @endif
                                    </ul>
                                </div>
								
								@else
							  <div class="actions">
                                   <ul>
                                       <li><a href="#" data-pid="{{ $lis['id'] }}" data-toggle="tooltip" data-placement="top" title="View Detail" class="view_gallery icon-info"><i class="fa fa-info"></i></a></li>
                                     
                                    </ul>
                                </div>
									@endif
                             </div>
							 @if($marketplace=='myitems')
									@if($lis['approved']=='no')
								 <div class="ribbon nt-app"><span>Not Approved</span></div>
								 @endif
								  @if($lis['approved']=='yes' && $lis['item_status']!='sold out' )
								  <div class="ribbon"><span>Approved</span></div>
								@endif
								
									  @if($lis['item_status']=='sold out')
									 <div class="ribbon sold-out"><span>Sold Out</span></div>
									@endif
							@endif
                        </div>

                        <div class="row caption">
                            <div class="col-md-12 col-xs-12">
                            <p class="head">{{ $lis['item'] }}</p>
                           
							
                            <div><p class="base_price"><span class="fa fa-inr"></span>&nbsp;{{ $lis['base_price'] }}</p><p class="actual_price"><span class="fa fa-inr"></span>&nbsp;<strike>{{ $lis['actual_price'] }}</strike></p><p class="percentage_off">
{{ round((($lis['actual_price'] - $lis['base_price']) / $lis['actual_price'])*100)}}% off
                            </p></div>								
                            <p class="percentage"></strike></p>							
							
						
                        </div>
                            
                        </div>
                    </li>
                    @endforeach
                    @if($offset == 0)
                </ul>
                  @endif
                @else
                
                        <div class="sj-alert sj-alert-info sj-alert-sm message_new_for_team">
                        Post your Old/New Sports Equipment, Buying and Selling made easy.
                        </div>
                        <div class="intro_list_container">
                                <ul class="intro_list_on_empty_pages">
                                        <span class="steps_to_follow">Steps to follow:</span>
                                        <li>Click on the <span class="bold">Create New +</span> button on the top left side, select <span class="bold">Market Place.</span></li>
                                        <li><span class="bold">Upload</span> images and <span class="bold">fill</span> all the details related to item</li>
                                        <li>Your item will be posted, after approval by the Admin</li>
                                </ul>
                        </div>
                    
                
                @endif


          
