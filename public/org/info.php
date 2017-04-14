<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hyderabad Corporate Olympics: Sportsjun</title>
    <!-- CSS -->
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/bootstrap-select.css" rel="stylesheet">
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
</head>

<body>
	<div class="wrap">
		<!-- Page Head -->
		<div class="page-head jumbotron">
			<!-- Hero Panel -->
			<div data-include="hero-panel"></div>
			<!-- Header -->
			<div data-include="header"></div>
		</div>
		<!-- Body Section -->
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Organization Details</h2></div>
			</div>
			<div class="row">
				<div class="col-md-offset-2 col-md-8 bg-white">
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
		<div data-include="footer"></div>
	</div>
    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/bootstrap-select.js"></script>
    <script>

        // Page Active
        jQuery(function () {
            var page = location.pathname.split('/').pop();
            $('#nav li a[href="' + page + '"]').addClass('active')
        });
    </script>
</body>

</html>