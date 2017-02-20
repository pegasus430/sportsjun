<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hyderabad Corporate Olympics: Sportsjun</title>
    <!-- CSS -->
    <div w3-include-html="inc/main-css.html"></div>
</head>

<body>
    <!-- Page Head -->
    <div class="page-head jumbotron">
        <!-- Hero Panel -->
        <div w3-include-html="inc/hero-panel.html"></div>
        <!-- Header -->
        <div w3-include-html="inc/header.html"></div>
    </div>
    <!-- Body Section -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Players</h2></div>
        </div>
    </div>
    <!-- Footer -->
    <div w3-include-html="inc/footer.html"></div>
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/w3data.js"></script>
    <script src="js/bootstrap-select.js"></script>
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