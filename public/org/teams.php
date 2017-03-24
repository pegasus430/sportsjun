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
		<div class="container cards-row">
			<div class="row">
				<div class="col-md-12">
					<h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Team (Groups)</h2>
					<div class="create-btn-link"><a href="" class="wg-cnlink" data-toggle="modal" data-target="#create_team">Create New Team</a></div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4 col-md-3">
					<div class="thumbnail"><img src="images/teams/HyderabadDeccanChargers.png" alt="">
						<div class="caption">
							<h3>HYDERABAD NIZAMS</h3>
							<ul class="card-description">
								<li><strong>No. of Teams:</strong> 9</li>
								<li><strong>Manager:</strong> Ravi Kiran J</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-md-8 col-md-offset-1">
					<div class="border-box">
						<div class="col-md-2 col-sm-3 col-xs-12 text-center">
							<div class="glyphicon-lg default-img"></div>
							<!--                                    <img data-original="http://www.sportsjun.com/uploads/tournaments/jlXE2OrNeNlDqciAAbAT.png" src="http://www.sportsjun.com/uploads/tournaments/jlXE2OrNeNlDqciAAbAT.png" title="" onerror="this.onerror=null;this.src=&quot;http://www.sportsjun.com/images/default-profile-pic.jpg&quot;" height="90" width="90" class="img-circle img-border img-scale-down img-responsive lazy" style="display: block;"> --></div>
						<div class="col-md-10 col-sm-9 col-xs-12">
							<div class="t_tltle">
								<h4><a href="http://www.sportsjun.com/gettournamentdetails/95" id="touname_95">HYDERABAD NIZAMS</a></h4>
								<p class="label label-default">By <a href="#">ISB HYDERABAD</a></p>
							</div>
							<hr>
							<div class="clearfix"></div>
							<ul class="t_tags">
								<li> Status: <span class="green">Completed</span> </li>
								<li> Sport: <span class="green">Soccer</span> </li>
								<li> Tournament Type: <span class="green">Multistage</span> </li>
							</ul>
							<p>Owner's Name: <strong>Bharathi Pitti</strong></p>
							<p>Manager Name: <strong> ISB HYDERABAD</strong></p>
							<p>Coach: <strong>Ravi Kiran J</strong></p>
							<div class="action-bar">
								<button class="btn btn-sm btn-mini btn-edit" href="javascript:void(0);"><i class="fa fa-pencil"></i></button>
								<button class="btn btn-sm btn-mini btn-danger" href="javascript:void(0);"><i class="fa fa-remove"></i></button>
								<button class="btn btn-sm btn-secondary" href="javascript:void(0);"><i class="fa fa-exchange"></i> Transfer Ownership </button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="create_team" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<form method="POST" action="http://dev.sportsjun.com/organization/33/staff" accept-charset="UTF-8" class="form form-horizontal">
						<input name="_token" type="hidden" value="bTCpsu1Uw3wX62asuYCTi28kBaPMCTWTaFyD4fRa">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3>TEAM GROUP DETAILS</h3> </div>
						<div class="modal-body">
							<div class="content">
								<div class="input-container">
									<input type="text" id="group_name" required="required" />
									<label for="Username">Enter Your Group Name</label>
									<div class="bar"></div>
								</div>
								<div class="input-container select">
									<div>
										<label>Group Manager</label>
										<select class="" name="staff_role">
											<option value="1">Manager One</option>
											<option value="2">Manager Two</option>
											<option value="3">Manager Three</option>
										</select>
									</div>
								</div>
								<div class="input-container file">
									<label>Group Logo</label>
									<input type="file" id="staff_email" required="" /> </div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary">Create</button>
						</div>
					</form>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
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
        $(document).on("pageload", function () {
            alert("pageload event fired!");
        });

        // Page Active
        jQuery(function () {
            var page = location.pathname.split('/').pop();
            $('#nav li a[href="' + page + '"]').addClass('active')
        });
    </script>
</body>

</html>