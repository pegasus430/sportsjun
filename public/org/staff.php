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
					<h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Staff</h2>
					<div class="create-btn-link">
						<a href="" class="wg-cnlink" data-toggle="modal" data-target="#add-staff-modal"> <i class="fa fa-plus"></i> &nbsp; Invite Staff</a>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-offset-2 col-md-8 bg-white text-center pdtb-30">
					<div>Please add staff to assign as group managers, admin etc.</div>
					<br>
					<h3><b>Steps to follow</b></h3>
					<ol class="steps-list text-left">
						<li> Click <strong>Invite Staff</strong></li>
						<li>Enter <strong>Email ID</strong></li>
						<li>Select <strong>Role</strong></li>
						<li>Click <strong>Invite</strong></li>
					</ol>
				</div>
				<div class="col-md-offset-2 col-md-8">
					<div id="staff_foot_info">*Additional staff members can be invited using email</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="add-staff-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<form method="POST" action="http://dev.sportsjun.com/organization/33/staff" accept-charset="UTF-8" class="form form-horizontal">
						<input name="_token" type="hidden" value="bTCpsu1Uw3wX62asuYCTi28kBaPMCTWTaFyD4fRa">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3>Invite Staff</h3> </div>
						<div class="modal-body">
							<div class="content">
								<div class="input-container">
									<input type="text" id="staff_name" required="required" />
									<label for="Username">Enter Your Staff Name</label>
									<div class="bar"></div>
								</div>
								<div class="input-container">
									<input type="text" id="staff_email" required="" />
									<label for="Username">Enter Your Staff Email (optional)</label>
									<div class="bar"></div>
								</div>
								<div class="input-container select">
									<div>
										<label>Staff Role</label>
										<select class="" name="staff_role">
											<option value="1">Admin</option>
											<option value="12">Alumni Relations</option>
											<option value="5">CEO</option>
											<option value="14">Clinical Co-ordinator</option>
											<option value="7">CO-FOUNDER</option>
											<option value="2">Coach</option>
											<option value="9">Event Planning &amp; Management</option>
											<option value="6">FOUNDER</option>
											<option value="17">Franchise Manager</option>
											<option value="8">Lead Co-ordinator</option>
											<option value="3">Manager</option>
											<option value="11">Marketing and Sponsorship</option>
											<option value="10">Operations and Logistics</option>
											<option value="4">physio</option>
											<option value="16">Sports Club Representatives</option>
											<option value="13">Treasurer</option>
											<option value="15">Website Co-ordinator</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary">Invite</button>
						</div>
					</form>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		<!-- /.modal -->
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