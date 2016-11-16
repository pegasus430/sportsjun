<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php
    $js_version = config('constants.JS_VERSION');
    $css_version = config('constants.CSS_VERSION');
    ?>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $page_title or 'Sportsjun - Connecting Sports Community' }}</title>
    <!-- ********** CSS ********** -->
    <link href="/themes/sportsjun/css/style.css" rel="stylesheet" type="text/css">
    <link href="/themes/sportsjun/css/font-awesome.css" rel="stylesheet" type="text/css">
    <link href="/themes/sportsjun/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="/themes/sportsjun/css/stylesheet.css" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/themes/sportsjun/css/owl.carousel.css" rel="stylesheet" type="text/css">
    <link href="/themes/sportsjun/css/custom.css" rel="stylesheet" type="text/css">
    <link href="/themes/sportsjun/css/backslider.css" rel="stylesheet" type="text/css">
    <link href="/themes/sportsjun/css/old.css" rel="stylesheet" type="text/css">
    <link href="/themes/sportsjun/css/style_add.css" rel="stylesheet" type="text/css">

    @yield('head')


</head>

<body>
@include('home.partials.header')
<?php
if (isset($errors) && ($errors->count()))
    $error = $errors->keys()[0] . ': ' . $errors->first();
?>
@if(isset($error) && ($error))
    <script>
        alert('{{$error}}');
    </script>
@endif
<div class="clearfix">
    @yield('content')
</div>

@include('home.partials.footer')
<!-----------------popup---------------------->
@include('home.partials.modals')

<script src="/themes/sportsjun/js/jquery.min.js" type="text/javascript"></script>
<script src="/themes/sportsjun/js/bootstrap.min.js"></script>
<script src="{{ asset('/home/js/sj.global.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ asset('/home/js/sj.user.js') }}?v=<?php echo $js_version;?>"></script>
<!-- ********** ********** -->
<script src="/themes/sportsjun/js/owl.carousel.js"></script>
<script src="/themes/sportsjun/js/backslider.js"></script>

<script>
    $(document).ready(function () {
        $(".box_noti").click(function () {
            var id = $(this).attr("id");
            $(".notific_wrapp").hide();
            $("#" + id + "_drop").show();
        });

        $(document).mouseup(function (e) {
            var container = $(".notific_wrapp");
            if (!container.is(e.target) // if the target of the click isn't the container...
                    && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                container.hide();
            }
        });


    });
</script>
<script type="text/javascript">

    $(document).ready(function () {

        $('#page_effect').fadeIn(2000);
        $(".round_test").addClass("round");

    });

</script>
<!----

<script>
    //homepage slide show
    $(function($) {
        var url = 'images';


        $.supersized({

            // Functionality
            slide_interval: 5000, // Length between transitions
            transition: 1, // 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
            transition_speed: 700, // Speed of transition

            // Components
            slide_links: 'blank', // Individual links for each slide (Options: false, 'num', 'name', 'blank')
            slides: [ // Slideshow Images
                {
                    image: url + '/slider.jpg',
                    title: 'Some testimonials. Thanks for letting us share great memories.',
                    description: 'Flat 30% cash back',
                    price: '$499'
                }, {
                    image: url + '/slider1.jpg',
                    title: 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. ',
                    description: 'With luxuary accommodation',
                    price: '$609'
                },

                {
                    image: url + '/slider.jpg',
                    title: 'Lorem Ipsum Prabhul. ',
                    description: 'With luxuary accommodation',
                    price: '$609'
                }
            ]
        });
    });
</script>


----->
<script>
    $(document).ready(function () {


        $('.mainwarppslide').owlCarousel({
            nav: false,
            autoplay: true,
            pagination: true,
            loop: true,
            dots: true,

            responsive: {
                0: {items: 1},
                600: {items: 1},
                900: {items: 1},
                1000: {items: 1}
            }
        });


        $('.testimornial').owlCarousel({
            nav: true,
            autoplay: true,
            pagination: true,
            loop: false,
            dots: true,

            responsive: {
                0: {items: 1},
                600: {items: 1},
                900: {items: 2},
                1000: {items: 2}
            }
        });

        $('.clientsSlide').owlCarousel({
            nav: true,
            autoplay: true,
            pagination: true,
            loop: false,
            dots: true,

            responsive: {
                0: {items: 2},
                600: {items: 3},
                900: {items: 4},
                1000: {items: 4}
            }
        });


    });

</script>
<script>

    $(document).ready(function () {


                $(document).delegate('*[data-toggle="lightbox"]', 'click', function (event) {
                    event.preventDefault();
                    $(this).ekkoLightbox();
                });

                var topPos = $(".total_menu_wrap").offset().top; // get the initial position for div


                $(window).scroll(function () {
                    var scroll = $(window).scrollTop();
                    if ($(window).scrollTop() >= topPos) {
                        $(".total_menu_wrap").addClass("menu_fix");
                    }
                    else {
                        $(".total_menu_wrap").removeClass("menu_fix");
                    }
                });
                //--------teach-down- it will work
            }
    )
</script>

</body>
</html>
