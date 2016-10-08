<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>

        <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/bootstrap-datepicker.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/bootstrap-datetimepicker.css') }}" rel="stylesheet">
        <!-- Fonts -->
        <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

<!--        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>-->
        <script src="{{ asset('/js/jquery-2.1.1.min.js') }}"></script>
        <script src="{{ asset('/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('/js/moment.js') }}"></script>
        <script src="{{ asset('/js/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('/js/bootstrap-datetimepicker.js') }}"></script>
        <script src="{{ asset('/js/jquery.blockUI_2.64.js') }}"></script>
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div id="spinner" class="ajax-loader" style="display:none;" >
                    <img id="img-spinner" src="{{ asset('/images/loader.gif') }}" alt="Loading" />				
                </div>
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Laravel</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="{{ url('/') }}">Home</a></li>
                    </ul>

                    <ul class="nav navbar-nav">
                        <li><a href="{{ url('/admin/viewsports') }}">Sports</a></li>
                        <li><a href="{{ url('/admin/listusers') }}">Users</a></li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        @if (Auth::guest())
                        <li><a href="{{ url('/auth/login') }}">Login</a></li>
                        <li><a href="{{ url('/auth/register') }}">Register</a></li>
                        @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} 
                                @if(Session::has('socialuser'))
                                <img src="{{ Session('avatar')}}" height="42" width="42">
                                @else 
                                @if(Session::has('profilepic'))
                                <img src="{{ asset('/uploads/user_profile/'.Session('profilepic')) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" height="42" width="42">
                                @else  
                                <img src="{{ asset('/images/default-profile-pic.jpg') }}" height="42" width="42">
                                @endif  
                                @endif
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/changepassword') }}">Change Password</a></li>
                                <li><a href="{{ route('user.edit',[Auth::user()->id]) }}">Edit Profile</a></li>
                                <li><a href="{{ route('sport.show',[Auth::user()->id]) }}">Sport Profile</a></li>
                                <li><a href="{{ url('/auth/logout') }}">Logout</a></li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')

        <!-- Scripts -->
        @yield('footer')
    </body>
</html>
