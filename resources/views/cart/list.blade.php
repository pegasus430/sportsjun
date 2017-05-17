@extends(Helper::check_if_org_template_enabled()?'layouts.organisation':'layouts.app') 
@section('content')


<div class="container-fluid col-sm-10 col-sm-offset-1">
	       <div class="row">

    <div class="sportsjun-forms sportsjun-container wrap-1 ">
        

            @if(count(Cart::getContent()->count()))
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                                <tr>
                                        <th>S/N</th>
                                        <th>Item</th>
                                        <th>Image</th>
                                        <th>Details</th>
                                        <th>Price</th>
                                        <th>Discount Price</th>
                                        <th>Total Sales</th>
                                        <th>Action</th>                                        
                                </tr>
                        </thead>
                        <tbody>
                                @foreach(Cart::getContent() as $key=>$item_cart)
                                    <tr>   <?php $item = $item_cart->attributes;?>
                                        <td>{{$key+1}}</td>
                                        <td><a href="{{url('/organization/'.$item->attributes['organization_id'].'/marketplace/'.$item['id'].'/details')}}"  data-pid="{{ $item['id'] }}" data-toggle="tooltip" data-placement="top" title="Edit" class="icon-edit mp_edit">{{$item_cart->attributes['item']}}</a></td>
                                        <td>
                                @if(count($item->photos)>0)
                               <!-- <img src="{{ url('/uploads/marketplace/'.$item->photos[0]['url'])}}" alt="" class="img-responsive" />-->
                            {!! Helper::Images($item->photos[0]['url'],'marketplace',array('class'=>'img-responsive','style'=>'height:50px!important;width:50px!important' ) )
!!} 

                                @else
                                <!--<img src="{{ url('uploads/marketplace/market_place_default.png')}}" alt="" class="img-responsive" />-->
                              {!! Helper::Images('market_place_default.png','marketplace',array('class'=>'img-responsive','style'=>'height:50px;width:50px') )
!!}     
                                @endif</td>
                                        <td>{{$item->item_description}}</td>
                                        <td>{{$item->actual_price}}</td>
                                        <td>{{$item->base_price}}</td>
                                        <td>{{$item->sales}}</td>
                                        <td>                                       
                                       <a href="javascript:void(0);" class="removephoto icon-delete mp_delete"  data-pid="{{ $item['id'] }}" data-toggle="tooltip" data-placement="top" title="Delete" >Remove</a>
                                        </td>

                           
                                    </tr>

                                @endforeach
                        </tbody>
                    </table>

                    <div class=" col-sm-12">

                    <b>Total : </b>
                    <br><br>
                    	<a href="/cart/checkout" class="btn btn-success">Checkout </a>
                    </div>
                </div>


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
                        <!-- .col-lg-4.col-sm-6 -->
                 
                        <!-- .col-lg-4.col-sm-6 -->
                    </div>
                </div>
            </div>
        </div>

@stop