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
        <link href="/org/css/font-awesome.min.css" rel="stylesheet">
        <link href="/org/css/bootstrap-select.css" rel="stylesheet">
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
            <div class="pull-left"> <span><i class="fa fa-map-marker"></i> {{$organisation->address}}</span> <a href="#" class="follow"><i class="fa fa-star-o"></i> Follow Us</a> </div>
        </div>
        <div class="col-md-3">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="index.php"><span class="fa fa-home fa-2x"></span></a></li>
                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="fa fa-user fa-2x"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><span class="fa fa-user-circle-o"></span> User Profile</a></li>
                        <li><a href="#"><span class="fa fa-trophy"></span> Sports Profile</a></li>
                        <li><a href="#"><span class="fa fa-lock"></span> Change password</a></li>
                        <li><a href="#"><span class="fa fa-power-off"></span> Logout</a></li>
                    </ul>
                </li>
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
                <li class='nav-item'>
                    <a href="info.php"><img src="/org/images/icons/icon-info.png" alt="" width="16" height="16"> Info</a>
                </li>
                <li class='nav-item'>
                    <a href="staff.php"><img src="/org/images/icons/icon-staff.png" alt="" width="16" height="16"> Staff</a>
                </li>
                <li class='nav-item'>
                    <a href="team.php"><img src="/org/images/icons/icon-group.png" alt="" width="16" height="16"> Team</a>
                </li>
                <li class='nav-item'>
                    <a href="players.php"><img src="/org/images/icons/icon-players.png" alt="" width="16" height="16"> Players</a>
                </li>
                <li class='nav-item'>
                    <a href="tournaments.php"><img src="/org/images/icons/icon-tournament.png" alt="" width="16" height="16"> Tournaments</a>
                </li>
                <li class='nav-item'>
                    <a href="schedule.php"><img src="/org/images/icons/icon-schedule.png" alt="" width="16" height="16"> Schedule</a>
                </li>
                <li class='nav-item'>
                    <a href="coaching.php"><img src="/org/images/icons/icon-coach.png" alt="" width="16" height="16"> Coaching</a>
                </li>
                <li class='nav-item'>
                    <a href="/org/marketplace.php"><img src="/org/images/icons/icon-marketplace.png" alt="" width="16" height="16"> marketplace</a>
                </li>
                <li class='nav-item'>
                    <a href="gallery.php"><img src="/org/images/icons/icon-gallery.png" alt="" width="16" height="16"> Gallery</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
    </div>
    <!-- Page Head -->
    
    @yield('section')


    <!-- Footer -->
    <footer class="container">
    <hr>
    <div class="col-md-12">
        <div class="text-center">Powered by Sportsjun.com </div>
    </div>
    <div class="clearfix"></div>
    <hr> </footer>
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="/org/js/bootstrap.min.js"></script>
    <script src="/org/js/w3data.js"></script>
    <script src="/org/js/bootstrap-select.js"></script>
    <script>
        // HTML Include
        w3IncludeHTML();
        // Page Active
        jQuery(function () {
            var page = location.pathname.split('/').pop();
            $('#nav li a[href="' + page + '"]').addClass('active')
        });
    </script>
</body>

</html>