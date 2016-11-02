<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
		<link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet">
        <link href="{{ asset('/js/bootstrap-rating/bootstrap-rating.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/sportsjun.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/bootstrap-datepicker.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/bootstrap-datetimepicker.css') }}" rel="stylesheet">
        <!-- Fonts -->
        <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
        <link href="{{ asset('/css/font-awesome.min.css') }}" rel="stylesheet">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
		<script src="{{ asset('/js/jquery-2.1.1.min.js') }}"></script>
    </head>
    <body>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
               <a class="navbar-brand" href="#">{!! HTML::image('images/SportsJun_Logo.png', '', array('height' => '43px','width' => '180px')) !!}</a>
            </div>
            @include('menu')
			@include('left')
            <!-- /.navbar-static-side -->
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper" style="padding:0;">
			<div class="container-fluid">
               <div class="row" style="padding:0;">
                    <div class="col-lg-9 leftsidebar">
						@yield('content')                        
                    </div>
                    <div class="col-lg-3 rightsidebar">
					
@include('widgets.teamplayer')
					</div>
                </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    @include ('footer')
    </body>
</html>
