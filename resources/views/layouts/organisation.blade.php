<!DOCTYPE html>
 

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$organisation->name}}: Sportsjun</title>
    <!-- CSS -->
        <link href="/org/css/bootstrap.css" rel="stylesheet">
        <link href="/org/css/main.css" rel="stylesheet">
            <link href="/org/css/marketplace.css" rel="stylesheet"> 
        <link href="/org/css/font-awesome.min.css" rel="stylesheet">
        <link href="/org/css/bootstrap-select.css" rel="stylesheet">        
     <link href="/org/css/bootstrap.slider.css" rel="stylesheet">

     @yield('styles')
</head>

<body>

<div class="page-head jumbotron">
        <!-- Hero Panel -->
       <div class="container">
    <div class="row">
        <div class="col-md-2">
            <div class="glyphicon-lg default-img" style="width: 100px; height: 100px;"></div>
            <!--<img src="http://placehold.it/110x110/6a737b/ffffff" class="img-circle"> --></div>
        <div class="col-md-7">
            <h1>{{$organisation->name}}</h1>
            <div class="pull-left"> <span><i class="fa fa-map-marker"></i> {{$organisation->location}}</span> <a href="#" class="follow"><i class="fa fa-star-o"></i> Follow Us</a> </div>
        </div>
        <div class="col-md-3">
        <ul class="nav navbar-nav navbar-right">
                <li><a href="/organization/{{$organisation->id}}"><span class="fa fa-home"></span></a></li>
                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="fa fa-user"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><span class="fa fa-user-circle-o"></span> User Profile</a></li>
                        <li><a href="#"><span class="fa fa-trophy"></span> Sports Profile</a></li>
                        <li><a href="#"><span class="fa fa-lock"></span> Change password</a></li>
                        <li><a href="#"><span class="fa fa-power-off"></span> Logout</a></li>
                    </ul>
                </li>
                <li><a href="index.php"><span class="fa fa-shopping-cart"></span><span class="cart-bubble">0</span></a></li>
                <!--
                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-plus"></span> Create New</a>
    <ul class="dropdown-menu">
        <li><a href="#"><span class="fa fa-trophy"></span> Tournament</a></li>
        <li><a href="#"><span class="fa fa-id-card-o"></span> Coaching Session</a></li>
    </ul>
</li>
-->
            </ul>
        </div>
    </div>
</div> 
        <!-- Header -->
      <header>

          <nav class='container'>
        <div class="scroll row nav-icons">
            <ul class="col-md-12" id="nav">
                <li class='nav-item li_info'>
                    <a href="/getorgteamdetails/{{$organisation->id}}"><img src="/org/images/icons/icon-info.png" alt="" width="16" height="16"> Info</a>
                </li>
                <li class='nav-item li_staff'>
                    <a href="/organization/{{$organisation->id}}/staff"><img src="/org/images/icons/icon-staff.png" alt="" width="16" height="16"> Staff</a>
                </li>
                <li class='nav-item li_team' >
                    <a href="/organization/{{$organisation->id}}/groups"><img src="/org/images/icons/icon-group.png" alt="" width="16" height="16"> Team</a>
                </li>
                <li class='nav-item'>
                    <a href="/organization/{{$organisation->id}}/players"><img src="/org/images/icons/icon-players.png" alt="" width="16" height="16"> Players</a>
                </li>
                <li class='nav-item'>
                    <a href="/organizationTournaments/{{$organisation->id}}"><img src="/org/images/icons/icon-tournament.png" alt="" width="16" height="16"> Tournaments</a>
                </li>
                <li class='nav-item'>
                    <a href="/organization/{{$organisation->id}}/schedules"><img src="/org/images/icons/icon-schedule.png" alt="" width="16" height="16"> Schedule</a>
                </li>
                <li class='nav-item'>
                    <a href="coaching.php"><img src="/org/images/icons/icon-coach.png" alt="" width="16" height="16"> Coaching</a>
                </li>
                <li class='nav-item'>
                    <a href="/organization/{{$organisation->id}}/marketplace"><img src="/org/images/icons/icon-marketplace.png" alt="" width="16" height="16"> marketplace</a>
                </li>
                <li class='nav-item'>
                    <a href="/organization/{{$organisation->id}}/gallery"><img src="/org/images/icons/icon-gallery.png" alt="" width="16" height="16"> Gallery</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
    </div>

    <div class="container">
            <div class="alert alert-danger" style="display: none;">

            </div>

            <div class="alert alert-success" style="display: none;">

            </div>
    </div>
    <!-- Page Head -->
    
    @yield('content')


    <!-- Footer -->
   <footer class="container">
    <hr>
    <div class="col-md-12">
        <div class="social-icons-wrapper text-center"> <a href="#" target="_self" class="google-plus"><i class="fa fa-google-plus"></i></a> <a href="#" target="_self" class="twitter"><i class="fa fa-twitter"></i></a> <a href="#" target="_self" class="facebook"><i class="fa fa-facebook"></i></a> <a href="#" target="_self" class="linkedin"><i class="fa fa-linkedin"></i></a> </div>
        <div class="text-center copyright"> Powered by
            <br> <img src="/org/images/sportsjun-logo.png" height=40 alt="" /></div>
    </div>
    <div class="clearfix"></div>
    <hr> </footer>
    <!-- jQuery --> 
    <script src="/org/js/jquery.min.js"></script>
    <script src="/org/js/bootstrap.min.js"></script>
    <script src="/org/js/w3data.js"></script>
    <script src="/org/js/bootstrap-select.js"></script>
    <script type="text/javascript">
            var is_organization=true;
    </script>
    @include ('layouts.footer_scripts')
    @yield('end_scripts')
        <script type="text/javascript" src='/org/js/scripts.js'></script>
    <script>
        // HTML Include
        w3IncludeHTML();
        // Page Active
        jQuery(function () {
            var page = location.pathname.split('/').pop();
            $('#nav li a[href="' + page + '"]').addClass('active')
        });
    </script>

    <script type="text/javascript">
    var contenteditable_val =''

         $('[contenteditable="true"]').focus(function(){
                contenteditable_val = $(this).html();
                console.log(contenteditable_val);
         })

        $('[contenteditable="true"]').blur(function(){

        if(contenteditable_val!=$(this).html()){
            data={
                value:$(this).html(),
                model:$(this).attr('model'),
                field:$(this).attr('field')
            }
           that= $(this);
                $.ajax({
                    url:'/organization/{{$organisation->id}}/update_fields',
                    data:data,
                    success:function(){
                        show_alert('success', 'Updated')
                    },
                    error:function(){
                        show_alert('danger', 'Failed to Update');
                        $(that).html(contenteditable_val);
                        console.log(contenteditable_val)
                    }
                })
            }
              
        })

        function show_alert(type, message){
            $('.alert-'+type).show().html(message)
            window.setTimeout(function() {
                $('.alert-'+type).hide().html('');
            }, 3000);
        }
    </script>
</body>

</html>