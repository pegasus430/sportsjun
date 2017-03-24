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
                <div class="col-sm-4 bg-white pdtb-30">
                    <h5 class="text-center"><b>Steps to create tournament</b></h5>
                    <ol class="steps-list text-left">
                        <li>Firstly, fill up the <strong>Tournament Details</strong> section.</li>
                        <li><strong>Click Update</strong></li>
                        <li>Navigate to <strong>Tournament Events</strong> tab.</li>
                        <li><strong>Add</strong> single/multiple tournament events to a single tournament.</li>
                        <li><strong>Teams / Players</strong> will respond to your request to join.</li>
                        <li><strong>Accept / Reject</strong> to enroll the Teams / Players into the tournament.</li>
                    </ol>
                </div>
                <div class="col-sm-8">
                    <div class="wg wg-dk-grey no-shadow no-margin">
                        <div class="wg-wrap clearfix">
                            <h4 class="no-margin pull-left"><i class="fa fa-pencil-square"></i> Edit Tournament / League Details</h4></div>
                    </div>
                    <div class="wg no-margin tabbable-panel create_tab_form">
                        <div class="tabbable-line">
                            <ul class="nav nav-tabs ">
                                <li class="active"> <a href="#tournament_details" data-toggle="tab">
								TOURNAMENT DETAILS </a> </li>
                                <li class=""> <a href="#tournament_events" data-toggle="tab" aria-expanded="false">
								TOURNAMENT EVENTS </a> </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tournament_details">
                                    <br>
                                    <form action="" class="form create-form clearfix">
                                        <div class="input-container one-col">
                                            <input type="text" id="tournament_name" required="required">
                                            <label for="tournament_name">Tournament Name <span class="req">&#42;</span></label>
                                            <div class="bar"></div>
                                        </div>
                                        <div class="input-container two-col file nomgbtm">
                                            <label>Group Logo</label>
                                            <input type="file" id="staff_email" required=""> </div>
                                        <div class="input-container select two-col nomgbtm">
                                            <div>
                                                <label>Group Manager</label>
                                                <select class="" name="staff_role">
                                                    <option value="1">Manager One</option>
                                                    <option value="2">Manager Two</option>
                                                    <option value="3">Manager Three</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="input-container two-col">
                                            <input type="text" id="contact_number" required="required">
                                            <label for="contact_number">Contact Number <span class="req">&#42;</span></label>
                                            <div class="bar"></div>
                                        </div>
                                        <div class="input-container two-col">
                                            <input type="text" id="alternate_number" required="required">
                                            <label for="alternate_number">Alternate Number</label>
                                            <div class="bar"></div>
                                        </div>
                                        <div class="input-container two-col">
                                            <input type="text" id="manager_name" required="required">
                                            <label for="manager_name">Manager Name <span class="req">&#42;</span></label>
                                            <div class="bar"></div>
                                        </div>
                                        <div class="input-container two-col">
                                            <input type="text" id="email" required="required">
                                            <label for="email">Email <span class="req">&#42;</span></label>
                                            <div class="bar"></div>
                                        </div>
                                        <div class="input-container select one-col">
                                            <label>Description</label>
                                            <div>
                                                <textarea class="textarea" style="resize:none" rows="3" name="description" cols="50" maxlength="250"></textarea>
                                                <div class="characterLeft"><span class="characterLefts">250 </span> characters left</div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Create</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="tournament_events">
                                    <div class="text-center">Firsly, add tournament details, and then you'll be able to add tournament events.</div>
                                </div>
                            </div>
                        </div>
                    </div>
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