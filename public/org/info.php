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
                <h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Organization Details</h2></div>
        </div>
        <div class="row">
            <div class="col-md-offset-2 col-md-8 bg-grey">
                <table class="table">
                    <tbody>
                        <tr>
                            <th>Organization Name</th>
                            <td>HYDERABAD CORPORATE OLYMPICS</td>
                        </tr>
                        <tr>
                            <th>Contact Number</th>
                            <td>+919642699877</td>
                        </tr>
                        <tr>
                            <th>POC (Point of Contact) Name</th>
                            <td> Mr.SARATHY</td>
                        </tr>
                        <tr>
                            <th>E-Mail Address</th>
                            <td>hcolympics@gmail.com</td>
                        </tr>
                        <tr>
                            <th>Organization Type</th>
                            <td>corporate</td>
                        </tr>
                        <tr>
                            <th>Facebook</th>
                            <td><a href="https://www.facebook.com/HCOlympics/">https://www.facebook.com/HCOlympics/</a></td>
                        </tr>
                        <tr>
                            <th>Website URL</th>
                            <td> <a href="https://corporatesports.co.in/">https://corporatesports.co.in/</a></td>
                        </tr>
                        <tr>
                            <th>About</th>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td>Hyderabad,Telangana,India</td>
                        </tr>
                    </tbody>
                </table>
            </div>
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