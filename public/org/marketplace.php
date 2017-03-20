<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hyderabad Corporate Olympics: Sportsjun</title>
    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/marketplace.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/bootstrap-select.css" rel="stylesheet">
    <link href="css/bootstrap.slider.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
</head>

<body>
    <div class="wrap">
        <!-- Page Head -->
        <div class="page-head jumbotron">
            <!-- Hero Panel -->
            <div data-include="hero-panel"></div>
            <!-- Header -->
            <div data-include="header"></div>
        </div>
        <!-- Body Section -->
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
                        <div class="col-lg-4 col-sm-6">
                            <div class="shop-item">
                                <div class="shop-thumbnail"> <span class="shop-label text-danger">Sale</span>
                                    <a href="shop-single.html" class="item-link"></a> <img src="marketplace/mp_img_1.png" alt="Shop item">
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
                                    <h3 class="shop-item-title"><a href="shop-single.html">Storage Box</a></h3> <span class="shop-item-price">
							<span class="old-price">$49.00</span> $38.00 </span>
                                </div>
                            </div>
                            <!-- .shop-item -->
                        </div>
                        <!-- .col-lg-4.col-sm-6 -->
                        <div class="col-lg-4 col-sm-6">
                            <div class="shop-item">
                                <div class="shop-thumbnail">
                                    <a href="shop-single.html" class="item-link"></a> <img src="marketplace/mp_img_2.png" alt="Shop item">
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
                                    <h3 class="shop-item-title"><a href="shop-single.html">Shoulder Bag</a></h3> <span class="shop-item-price">
							$125.00
						  </span> </div>
                            </div>
                            <!-- .shop-item -->
                        </div>
                        <!-- .col-lg-4.col-sm-6 -->
                        <div class="col-lg-4 col-sm-6">
                            <div class="shop-item">
                                <div class="shop-thumbnail">
                                    <a href="shop-single.html" class="item-link"></a> <img src="marketplace/mp_img_3.png" alt="Shop item">
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
                                    <h3 class="shop-item-title"><a href="shop-single.html">Glass Vase</a></h3> <span class="shop-item-price">
							$62.50
						  </span> </div>
                            </div>
                            <!-- .shop-item -->
                        </div>
                        <div class="clearfix"></div>
                        <!-- .col-lg-4.col-sm-6 -->
                        <div class="col-lg-4 col-sm-6">
                            <div class="shop-item">
                                <div class="shop-thumbnail">
                                    <a href="shop-single.html" class="item-link"></a> <img src="marketplace/mp_img_4.png" alt="Shop item">
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
                                    <h3 class="shop-item-title"><a href="shop-single.html">Alarm Clock</a></h3> <span class="shop-item-price">
							$178.00
						  </span> </div>
                            </div>
                            <!-- .shop-item -->
                        </div>
                        <!-- .col-lg-4.col-sm-6 -->
                        <div class="col-lg-4 col-sm-6">
                            <div class="shop-item">
                                <div class="shop-thumbnail">
                                    <a href="shop-single.html" class="item-link"></a> <img src="marketplace/mp_img_4.png" alt="Shop item">
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
                                    <h3 class="shop-item-title"><a href="shop-single.html">Alarm Clock</a></h3> <span class="shop-item-price">
							$178.00
						  </span> </div>
                            </div>
                            <!-- .shop-item -->
                        </div>
                        <!-- .col-lg-4.col-sm-6 -->
                        <div class="col-lg-4 col-sm-6">
                            <div class="shop-item">
                                <div class="shop-thumbnail">
                                    <a href="shop-single.html" class="item-link"></a> <img src="marketplace/mp_img_4.png" alt="Shop item">
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
                                    <h3 class="shop-item-title"><a href="shop-single.html">Alarm Clock</a></h3> <span class="shop-item-price">
							$178.00
						  </span> </div>
                            </div>
                            <!-- .shop-item -->
                        </div>
                        <!-- .col-lg-4.col-sm-6 -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <div data-include="footer"></div>
    </div>
    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-select.js"></script>
    <script src="js/bootstrap.slider.min.js"></script>
    <script src="js/scripts.js"></script>
    <script>
        // Page Active
        jQuery(function () {
            var page = location.pathname.split('/').pop();
            $('#nav li a[href="' + page + '"]').addClass('active')
        });
    </script>
</body>

</html>