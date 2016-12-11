<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
         <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Sportsjun - Connecting Sports Community</title>
<?php
$js_version     = config('constants.JS_VERSION');
$css_version    = config('constants.CSS_VERSION');
?>
<!-- Css Files -->
        <link href="{{ asset('/css/jquery-ui.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
        <link href="{{ asset('/css/sportsjun.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
        <link href="{{ asset('/css/album-popup.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
        <link href="{{ asset('/css/marketplace-showdetails.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
       
        <link href="{{ asset('/css/teams.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
        <link href="{{ asset('/css/green.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
        <link href="{{ asset('/css/_all.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">


        <link href="{{ asset('/home/css/bootstrap.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
        <link href="{{ elixir('home/css/home_mix.css') }}?>" rel="stylesheet">


        <link rel="stylesheet" href="{{ asset('/css/sidebar-menu.css') }}?v=<?php echo $css_version;?>" />
        <link rel="stylesheet" href="{{ asset('/css/select-multiple.css') }}?v=<?php echo $css_version;?>" />
        <link rel="stylesheet" href="{{ asset('/css/select-multiple.css') }}?v=<?php echo $css_version;?>" />

    

        <script src="{{ asset('/home/js/jquery.js') }}?v=<?php echo $js_version;?>"></script>
        <script src="{{ asset('/js/jquery-ui.js') }}?v=<?php echo $js_version;?>"></script>

        <!-- public view css -->
        <link href="{{ asset('/css/scorecard.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
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
        @if(isset($match_data))
                @include('scorecards.share_meta')
        @endif
        @if(isset($action))
                @include("album.share_meta")
        @endif

        @if(isset($tournamentInfo[0]['id']))
                @include("tournaments.share_meta")
        @endif
        @if(isset($tournamentDetails[0]['id']))
                @include("tournaments.share_groups_meta")
        @endif
        @if(isset($sports) && isset($userId))
                @include("sportprofile.share_meta")
        @endif
        @if(isset($team_id) && isset($team_players))
                @include("teams.share_meta")
        @endif
</head>
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : "{{env('FACEBOOK_APP_ID')}}",   // App ID from the app dashboard     
      status     : true,                                 // Check Facebook Login status
      xfbml      : true                                  // Look for social plugins on the page
    });

    // Additional initialization code such as adding Event Listeners goes here
  };

  // Load the SDK asynchronously
  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/all.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
<body>
<!--// Wrapper //-->
<div class="kode-wrapper">
        @if (!(isset($is_widget) && $is_widget))
             @include('home.partials.header')
        @endif
                @include('home.partials.search')
        @yield('content')
</div> <div class="kd-divider divider4"><span></span></div>

@include('home.partials.footer')
</div>
<!--// Wrapper //-->
@include('home.partials.modals')

<!-- jQuery (necessary for JavaScript plugins) -->

<script src="{{ asset('/home/js/bootstrap.min.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ elixir('home/js/lib_mix.js') }}"></script>
<script src="{{ elixir('home/js/home_mix.js') }}"></script>

</body>
</html>