<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{csrf_token()}}"/>
    <title>SportsJun - Connecting Sports People</title>
<!--        <link href="{{ asset('/css/app.css') }}" rel="stylesheet">-->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-datepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-datetimepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/jquery-confirm.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/jquery-ui.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/sportsform.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-switch.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/album-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/marketplace-showdetails.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/green.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/_all.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/admin_album.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/aftab.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/admin_style.css') }}" rel="stylesheet">
    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
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
    <!-- Start Admin -->
    <link href="{{ asset('/css/sb-admin-2.css') }}" rel="stylesheet">
    <!-- End Admin -->
    <script src="{{ asset('/js/jquery-2.1.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}" type="text/javascript"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js" type="text/javascript"></script>
    <script src="{{ asset('/js/jquery-2.1.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}" type="text/javascript"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js" type="text/javascript"></script>
    <script src="{{ asset('/js/bootstrap-switch.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/jquery-form.js')}}" type="text/javascript"></script>
    <script type="text/javascript" src="{{elixir('js/admin.scripts.js')}}"></script>
    <script type="text/javascript">
        var base_url = "{{URL::to('/')}}";
        var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    </script>
</head>
<body>
<div id="wrapper" class="container-fluid">
    @include('admin.layouts.menu')

    @yield('content')

    @include ('admin.layouts.footer')
</div>
</body>
</html>
