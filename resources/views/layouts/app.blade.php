<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{csrf_token()}}" />
    <title>SportsJun - Connecting Sports People</title>
    <!--<link href="{{ asset('/css/app.css') }}" rel="stylesheet">-->
	<?php $js_version = config('constants.JS_VERSION');$css_version = config('constants.CSS_VERSION'); ?>
    <link href="{{ asset('/css/bootstrap.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/sportsjun.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-datepicker.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-datetimepicker.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/jquery-confirm.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/jquery-ui.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/sportsform.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-switch.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/album-popup.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/marketplace-showdetails.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/aftab.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/scorecard.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/teams.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/green.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/_all.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/sinister.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-select.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">    

    <link rel="stylesheet" href="{{ asset('/css/sidebar-menu.css') }}?v=<?php echo $css_version;?>" />
    <link rel="stylesheet" href="{{ asset('/css/select-multiple.css') }}?v=<?php echo $css_version;?>" />
    
    <!-- Fonts --> 
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,900' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Lato:400,300,700' rel='stylesheet' type='text/css'>
    <link href="{{ asset('/css/font-awesome.min.css') }}" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('/images/favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('/images/favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('/images/favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/images/favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('/images/favicon/apple-icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('/images/favicon/apple-icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('/images/favicon/apple-icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('/images/favicon/apple-icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/images/favicon/apple-icon-180x180.png')}}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('/images/favicon/android-icon-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/images/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('/images/favicon/favicon-96x96.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/images/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    <meta property="fb:app_id" content="{{ env('FACEBOOK_APP_ID') }}" />
    <script src="{{ asset('/js/jquery-2.1.1.min.js') }}?v=<?php echo $js_version;?>"></script>
    <script src="{{ asset('/js/jquery-ui.js') }}?v=<?php echo $js_version;?>"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}?v=<?php echo $js_version;?>"></script>
    <script src="{{ asset('/js/bootstrap-switch.js') }}?v=<?php echo $js_version;?>"></script>
    <script type="text/javascript" src="{{ asset('/js/jquery-form.js')}}?v=<?php echo $js_version;?>"></script>
    <script src="{{ asset('/js/sinister.js') }}?v=<?php echo $js_version;?>" type="text/javascript"></script>
    
    
   	<script src="{{ asset('/js/sidebar-menu.js') }}?v=<?php echo $js_version;?>"></script>

    <script type="text/javascript">
        var base_url = "{{URL::to('/')}}";
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
        var global_record_count = 0;    
    	$(window).load(function() {
			// Animate loader off screen
			$(".page-load").fadeOut("slow");;
		});
    </script>

</head>

<body>
<div class="app-wrap">
	<div class="page-load"></div>
    <div class="showbox" id="spinner" style="display:none;">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    @include('layouts.menu')
    <div class="sports_wrapper">
        <div class="row">
            @yield('content')
        </div>
    </div>
    @include ('layouts.footer')
    </div>
</body>

</html>