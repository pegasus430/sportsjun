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
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/bootstrap-select.css" rel="stylesheet">
    <link href="css/gallery.css" rel="stylesheet">
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
                    <h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Gallery</h2></div>
                <div class="col-md-12">
                    <div align="center">
                        <button class="btn btn-default filter-button" data-filter="all">All</button>
                        <button class="btn btn-default filter-button" data-filter="hdpe">Tennis</button>
                        <button class="btn btn-default filter-button" data-filter="sprinkle">Cricket</button>
                        <button class="btn btn-default filter-button" data-filter="spray">Basketball</button>
                        <button class="btn btn-default filter-button" data-filter="irrigation">Soccer</button>
                    </div>
                    <br/>
                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter hdpe"> <img src="http://fakeimg.pl/365x365/" class="img-responsive"> </div>
                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter sprinkle"> <img src="http://fakeimg.pl/365x365/" class="img-responsive"> </div>
                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter hdpe"> <img src="http://fakeimg.pl/365x365/" class="img-responsive"> </div>
                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter irrigation"> <img src="http://fakeimg.pl/365x365/" class="img-responsive"> </div>
                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter spray"> <img src="http://fakeimg.pl/365x365/" class="img-responsive"> </div>
                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter irrigation"> <img src="http://fakeimg.pl/365x365/" class="img-responsive"> </div>
                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter spray"> <img src="http://fakeimg.pl/365x365/" class="img-responsive"> </div>
                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter irrigation"> <img src="http://fakeimg.pl/365x365/" class="img-responsive"> </div>
                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter irrigation"> <img src="http://fakeimg.pl/365x365/" class="img-responsive"> </div>
                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter hdpe"> <img src="http://fakeimg.pl/365x365/" class="img-responsive"> </div>
                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter spray"> <img src="http://fakeimg.pl/365x365/" class="img-responsive"> </div>
                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter sprinkle"> <img src="http://fakeimg.pl/365x365/" class="img-responsive"> </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <div data-include="footer"></div>
    </div>
    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/bootstrap-select.js"></script>
    <script>
        // Page Active
        jQuery(function () {
            var page = location.pathname.split('/').pop();
            $('#nav li a[href="' + page + '"]').addClass('active')
        });
        // Gallery Filters
        $(document).ready(function () {
            $(".filter-button").click(function () {
                var value = $(this).attr('data-filter');
                if (value == "all") {
                    //$('.filter').removeClass('hidden');
                    $('.filter').show('1000');
                }
                else {
                    //            $('.filter[filter-item="'+value+'"]').removeClass('hidden');
                    //            $(".filter").not('.filter[filter-item="'+value+'"]').addClass('hidden');
                    $(".filter").not('.' + value).hide('3000');
                    $('.filter').filter('.' + value).show('3000');
                }
            });
        });
    </script>
</body>

</html>