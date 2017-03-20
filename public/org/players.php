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
                    <h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Players in your organization</h2>
                    <div class="create-btn-link"> <a href="" class="wg-cnlink" data-toggle="modal" data-target="#add_player" style="margin-right: 150px;">Add Player</a> <a href="" class="wg-cnlink" data-toggle="modal" data-target="#invite_player">Invite Player</a></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="search-box">
                        <div class="sb-label">Filter by teams in your organization</div>
                        <div class="input-group col-md-12">
                            <input type="text" class="form-control input-lg" placeholder="" /> <span class="input-group-btn">
							<i class="fa fa-search"></i>
						</span> </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="search-reasult">
                        <div class="no-records"><i class="fa fa-frown-o" aria-hidden="true"></i> No Records Found</div>
                        <div class="sr-table">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Name</th>
                                            <th>Teams</th>
                                            <th>Sports</th>
                                            <th class="text-center">Ratings</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="circle-mask">
                                                    <canvas id="canvas" class="circle" width="96" height="96"></canvas>
                                                </div>
                                            </td>
                                            <td> <a class="player-name" onclick="openNav()">
                                                    Rajendra Prasad Raju
                                                </a></td>
                                            <td>Titans</td>
                                            <td>Cricket</td>
                                            <td>
                                                <div id="stars" class="starrr"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="circle-mask">
                                                    <canvas id="canvas" class="circle" width="96" height="96"></canvas>
                                                </div>
                                            </td>
                                            <td> <a class="player-name" onclick="openNav()">
                                                    Rajendra Prasad Raju
                                                </a></td>
                                            <td>Titans</td>
                                            <td>Cricket</td>
                                            <td>
                                                <div id="stars" class="starrr"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="circle-mask">
                                                    <canvas id="canvas" class="circle" width="96" height="96"></canvas>
                                                </div>
                                            </td>
                                            <td> <a class="player-name" onclick="openNav()">
                                                    Rajendra Prasad Raju
                                                </a></td>
                                            <td>Titans</td>
                                            <td>Cricket</td>
                                            <td>
                                                <div id="stars" class="starrr"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="circle-mask">
                                                    <canvas id="canvas" class="circle" width="96" height="96"></canvas>
                                                </div>
                                            </td>
                                            <td> <a class="player-name" onclick="openNav()">
                                                    Rajendra Prasad Raju
                                                </a></td>
                                            <td>Titans</td>
                                            <td>Cricket</td>
                                            <td>
                                                <div id="stars" class="starrr"></div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <div data-include="footer"></div>
    </div>
    <!-- Modal Add Player -->
    <div class="modal fade" id="add_player" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="http://dev.sportsjun.com/organization/33/staff" accept-charset="UTF-8" class="form form-horizontal">
                    <input name="_token" type="hidden" value="bTCpsu1Uw3wX62asuYCTi28kBaPMCTWTaFyD4fRa">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3>Add Player</h3> </div>
                    <div class="modal-body">
                        <div class="content row">
                            <div class="input-container two-col">
                                <input type="text" id="player_name" required="required" />
                                <label for="Username">Player Name</label>
                                <div class="bar"></div>
                            </div>
                            <div class="input-container two-col">
                                <input type="text" id="player_email" required="" />
                                <label for="Username">Player Email</label>
                                <div class="bar"></div>
                            </div>
                            <div class="input-container two-col">
                                <input type="text" id="player_dob" required="required" />
                                <label for="Username">DOB</label>
                                <div class="bar"></div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="input-container two-col">
                                <input type="text" id="parent_name" required="" />
                                <label for="Username">Parent Name</label>
                                <div class="bar"></div>
                            </div>
                            <div class="input-container two-col">
                                <input type="text" id="parent_name" required="" />
                                <label for="Username">Parent Email</label>
                                <div class="bar"></div>
                            </div>
                            <div class="input-container one-col">
                                <input type="text" id="area" required="required" />
                                <label for="Username">Area</label>
                                <div class="bar"></div>
                            </div>
                            <div class="input-container select two-col">
                                <div>
                                    <label>Country</label>
                                    <select class="" name="staff_role">
                                        <option value="1">India</option>
                                        <option value="12">USA</option>
                                        <option value="5">UK</option>
                                        <option value="14">Canada</option>
                                        <option value="7">Australia</option>
                                        <option value="2">Japan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="input-container select two-col">
                                <div>
                                    <label>State</label>
                                    <select class="" name="staff_role">
                                        <option value="1">India</option>
                                        <option value="12">USA</option>
                                        <option value="5">UK</option>
                                        <option value="14">Canada</option>
                                        <option value="7">Australia</option>
                                        <option value="2">Japan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="input-container select two-col">
                                <div>
                                    <label>City</label>
                                    <select class="" name="staff_role">
                                        <option value="1">India</option>
                                        <option value="12">USA</option>
                                        <option value="5">UK</option>
                                        <option value="14">Canada</option>
                                        <option value="7">Australia</option>
                                        <option value="2">Japan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Player</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- Modal Invite Player -->
    <div class="modal fade" id="invite_player" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="http://dev.sportsjun.com/organization/33/staff" accept-charset="UTF-8" class="form form-horizontal">
                    <input name="_token" type="hidden" value="bTCpsu1Uw3wX62asuYCTi28kBaPMCTWTaFyD4fRa">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3>Invite Player</h3> </div>
                    <div class="modal-body">
                        <div class="content row">
                            <div class="input-container">
                                <input type="text" id="staff_name" required="required" />
                                <label for="Username">Enter Player Name</label>
                                <div class="bar"></div>
                            </div>
                            <div class="input-container">
                                <input type="text" id="staff_email" required="" />
                                <label for="Username">Enter Player Email</label>
                                <div class="bar"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Player</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!-- Overlay -->
    <div id="myNav" class="overlay"> <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class="overlay-content"> Player data goes here...</div>
    </div>
    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/ratings.js"></script>
    <script src="js/bootstrap-select.js"></script>
</body>

</html>