@extends('layouts.organisation')

@section('content')
<div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Marketplace</h2> </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="row">
                        <div class="sidebar-marketplace">
                            <div class="col-md-12">
                                <div class="sidebar-title-icon"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></div>
                                <h3 class="sidebar-title"><span>Filter</span> by price</h3>
                                <p>
                                    <input id="price_slider" type="text" class="span2" value="" data-slider-min="10" data-slider-max="1000" data-slider-step="5" data-slider-value="[250,450]" /> </p>
                                <p>Price: <b>$ 10</b> - <b>$ 1000</b></p>
                            </div>
                        </div>
                        <div class="sidebar-marketplace">
                            <div class="col-md-12">
                                <div class="sidebar-title-icon"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></div>
                                <h3 class="sidebar-title"><span>Products</span> Categories</h3>
                                <ul class="product-categories">
                                	@foreach($categories as $cat)
                                		{{$cat->name}}
                                	@endforeach
                                    <li class="cat-item cat-parent"><a href="#">Clothing</a>
                                        <ul class="children">
                                            <li class="cat-item"><a href="#">Hoodies</a></li>
                                            <li class="cat-item"><a href="#">T-shirts</a></li>
                                        </ul>
                                    </li>
                                    <li class="cat-item cat-parent"><a href="#">Music</a>
                                        <ul class="children">
                                            <li class="cat-item"><a href="#">Albums</a></li>
                                            <li class="cat-item"><a href="#">Singles</a></li>
                                        </ul>
                                    </li>
                                    <li class="cat-item cat-parent"><a href="#">Posters</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="marketplace-result-count pull-left"> Showing 1–6 of 23 results</p>
                            <form class="marketplace-ordering pull-right" method="get">
                                <select name="orderby" class="orderby">
                                    <option value="menu_order" selected="selected">Default sorting</option>
                                    <option value="date">Sort by newness</option>
                                    <option value="price">Sort by price: low to high</option>
                                    <option value="price-desc">Sort by price: high to low</option>
                                </select>
                            </form>
                        </div>
                        <hr>
                        <div class="clearfix"></div>

                       @foreach($marketplace as $item)
                        <div class="col-lg-4 col-sm-6">
                            <div class="shop-item">
                                <div class="shop-thumbnail"> <span class="shop-label text-danger">Sale</span>
                                    <a href="shop-single.html" class="item-link"></a> <img src="/org/marketplace/mp_img_1.png" alt="Shop item">
                                    <div class="shop-item-tools">
                                        <a href="#" class="add-to-whishlist" data-toggle="tooltip" data-placement="top" title="" data-original-title="Wishlist"> <i class="fa fa-heart-o"></i> </a>
                                        <a href="#" class="add-to-cart"> <em>Add to Cart</em>
                                            <svg x="0px" y="0px" width="32px" height="32px" viewBox="0 0 32 32">
                                                <path stroke-dasharray="19.79 19.79" stroke-dashoffset="19.79" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" d="M9,17l3.9,3.9c0.1,0.1,0.2,0.1,0.3,0L23,11"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="shop-item-details">
                                    <h3 class="shop-item-title"><a href="shop-single.html">{{$item->item}}</a></h3> <span class="shop-item-price">
							<span class="old-price">${{$item->actual_price}}</span> ${{$item->base_price}}</span>
                                </div>
                            </div>
                            <!-- .shop-item -->
                        </div>
                        @endforeach
                        <!-- .col-lg-4.col-sm-6 -->
                 
                        <!-- .col-lg-4.col-sm-6 -->
                    </div>
                </div>
            </div>
        </div>
@stop


@section('end_scripts')
    <script src="/org/js/bootstrap.slider.min.js"></script>
@stop