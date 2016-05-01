<!DOCTYPE html>
<html lang="en">
        <head>
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
                <title>Sportsjun - Connecting Sports Community</title>

                <!-- Css Files -->
                <link href="home/css/bootstrap.css" rel="stylesheet">
                <link href="home/css/font-awesome.css" rel="stylesheet">
                <link href="home/css/themetypo.css" rel="stylesheet">
                <link href="home/css/style.css" rel="stylesheet">
                <link href="home/css/widget.css" rel="stylesheet">
                <link href="home/css/color.css" rel="stylesheet">
                <link href="home/css/flexslider.css" rel="stylesheet">
                <link href="home/css/owl.carousel.css" rel="stylesheet">
                <link href="home/css/jquery.bxslider.css" rel="stylesheet">    
                <link href="home/css/prettyphoto.css" rel="stylesheet">
                <link href="home/css/responsive.css" rel="stylesheet">

                <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
                <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
                <!--[if lt IE 9]>
                  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
                <![endif]-->
                <meta name="csrf-token" content="{{ csrf_token() }}">
        </head>
        <body>
                <!--// Wrapper //-->
                <div class="kode-wrapper">
                        <header id="mainheader" class="kode-header-absolute">

                                <!--// TopBaar //-->
                                <div class="kode-topbar">
                                        <div class="container">
                                                <div class="row">
                                                        <div class="col-md-6 kode_bg_white">
                                                                <!--<ul class="top_slider_bxslider">
                                                                        <li><span class="kode-barinfo"><strong>Latest News : </strong> Lorem ipsum dolor sit amet, cons ecte tuer adipiscing elit,</span></li>
                                                                        <li><span class="kode-barinfo"><strong>Latest News : </strong> Lorem ipsum dolor sit amet, cons ecte tuer adipiscing elit,</span></li>
                                                                        <li><span class="kode-barinfo"><strong>Latest News : </strong> Welcome visitor you can Login or Create an Account</span></li>
                                                                </ul>-->
                                                        </div>
                                                        <div class="col-md-6">
                                                                <ul class="kode-userinfo">
                                                                        <li><a href="aboutus.html"><i class="fa fa-list"></i> About SportsJun</a></li>
                                                                        <li><a href="#"><i class="fa fa-user"></i> My Account</a></li>
                                                                        <li><a href="javascript:void(0);" data-toggle="modal" data-target="#home-login-modal"><i class="fa fa-sign-in"></i> Login</a></li>
                                                                        <li><a href="javascript:void(0);" data-toggle="modal" data-target="#home-register-modal"><i class="fa fa-user-plus"></i> Register</a></li>
                                                                </ul>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                                <!--// TopBaar //-->

                                <div class="header-8">
                                        <div class="container">
                                                <!--NAVIGATION START-->
                                                <div class="kode-navigation pull-left">
                                                        <ul>
                                                                <li><a href="index.html">Home</a></li>
                                                                <!--						 <li><a href="aboutus.html">About Us</a></li>
                                                                -->                          <li><a href="#howitworks">How It Works</a></li>
                                                                <li><a href="features.html">Features</a></li>


                                                        </ul>
                                                </div>
                                                <!--NAVIGATION END--> 
                                                <!--LOGO START-->	
                                                <div class="logo">
                                                        <a href="index.html" class="logo"><img src="home/images/sportsjun.png"  alt=""></a>
                                                </div>
                                                <!--LOGO END-->	
                                                <!--NAVIGATION START-->
                                                <div class="kode-navigation">
                                                        <ul>
                                                                <li><a href="sports.html">Sports</a></li>
                                                                <li><a href="#">Tournaments</a></li>
                                                                <!--                        <li><a href="#">Teams and Players</a></li>
                                                                -->						<!--<li><a href="#">Players</a></li>-->
                                                                <li><a href="#">Market Place</a></li>
                                                        </ul>
                                                </div>
                                                <!--NAVIGATION END-->  
                                                <nav class="navbar navbar-default">

                                                        <!-- Brand and toggle get grouped for better mobile display -->
                                                        <div class="navbar-header">
                                                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                                                                        <span class="sr-only">Toggle navigation</span>
                                                                        <span class="icon-bar"></span>
                                                                        <span class="icon-bar"></span>
                                                                        <span class="icon-bar"></span>
                                                                </button>
                                                        </div>

                                                        <!-- Collect the nav links, forms, and other content for toggling -->
                                                        <div class="collapse navbar-collapse" id="navbar-collapse">
                                                                <ul class="nav navbar-nav">
                                                                        <li><a href="#">Home</a></li>
                                                                        <li><a href="#">About Us</a></li>
                                                                        <li><a href="#">How It Works</a></li>
                                                                        <li><a href="#">Features</a></li>
                                                                        <li><a href="#">Tournaments</a></li>
                                                                        <li><a href="#">Players</a></li>
                                                                        <li><a href="#">Market Place</a></li>
                                                                        <li><a href="#">Contact Us</a></li>
                                                                </ul>
                                                        </div>
                                                        <!--<div class="collapse navbar-collapse" id="navbar-collapse">
                                                          <ul class="nav navbar-nav">
                                                             <li><a href="index.html">Home</a>
                                                                                      <ul class="children">
                                                                <li><a href="blog-large.html">Boxed Layout</a></li>
                                                              </ul>
                                                                                     </li>
                                                                                   <li><a href="team-info.html">About Us</a></li>
                                                            <li><a href="#">Blog</a>
                                                              <ul class="children">
                                                                <li><a href="bloggrid-v2.html">Blog Grid</a></li>
                                                                                            <li><a href="blog-large.html">Blog Large</a></li>
                                                                <li><a href="blog-detail.html">Blog Detail</a>
                                                                  <ul class="children">
                                                                    <li><a href="blog-detail-soundcloud.html">Single SoundCloud</a></li>
                                                                    <li><a href="blog-detail-video.html">Single Video</a></li>
                                                                  </ul>
                                                                </li>
                                                              </ul>
                                                            </li>
                                                            <li><a href="fixer-list.html">Fixture</a>
                                                              <ul class="children">
                                                                <li><a href="fixer_list.html">All Matches</a></li>
                                                                <li><a href="fixer_list_view.html">List View</a></li>
                                                                <li><a href="fixture_full_view.html">Full View</a></li>
                                                                                            <li><a href="fixture-detail.html">Fixture detail</a></li>
                                                              </ul>
                                                            </li>
                                                                                    <li><a href="gallery-two.html">Our Gallery</a>
                                                                                          <ul class="children">
                                                                                            <li><a href="gallery-two.html">Gallery 2 Column</a></li>
                                                                                            <li><a href="gallery-three.html">Gallery 3 Column</a></li>
                                                                                            <li><a href="gallery-four.html">Gallery 4 Column</a></li>
                                                                                            <li><a href="gallery-full-width.html">Gallery Full Width</a></li>
                                                                                          </ul>
                                                                                    </li>
                                                                                   <li>
                                                                                          <a href="#">Team & Player</a>
                                                                                          <ul class="children">
                                                                                            <li><a href="player-list.html">Players</a></li>
                                                                                            <li><a href="player-detail.html">Player detail</a></li>
                                                                                            <li><a href="team-detail.html">Team Detail</a></li>
                                                                                          </ul>
                                                                                    </li>
                                                                                  <li><a href="#">Shop</a>
                                                                                          <ul class="children">
                                                                                            <li><a href="product-list.html">Product list</a></li>
                                                                                            <li><a href="product-detail.html">Product Detail</a></li>
                                                                                          </ul>
                                                                                    </li>
                                                            <li><a href="#">ShortCode</a>
                                                              <ul class="children">
                                                                <li><a href="accordian.html">Accordion</a></li>
                                                                <li><a href="button.html">Button</a></li>
                                                                <li><a href="frame.html">Image Frame</a></li>
                                                                                             <li><a href="faq.html">Faq</a></li>
                                                                <li><a href="list.html">List</a></li>
                                                                <li><a href="map.html">Map</a></li>
                                                                <li><a href="message.html">Message</a></li>
                                                                <li><a href="sepratore.html">Separator</a></li>
                                                                <li><a href="skills.html">Skills</a></li>
                                                                <li><a href="table.html">Table</a></li>
                                                                <li><a href="tabs.html">Tabs</a></li>
                                                                <li><a href="skills.html">Skills</a></li>
                                                                <li><a href="video.html">Video</a></li>
                                                              </ul>
                                                            </li>
                                                            <li class="last"><a href="#">contact Us</a>
                                                              <ul class="children">
                                                                <li><a href="contact-us.html">Contact V1</a></li>
                                                                <li><a href="contact-ustwo.html">Contact V2</a></li>
                                                              </ul>
                                                            </li>
                                                          </ul>
                                                        </div>--><!-- /.navbar-collapse -->

                                                </nav>
                                        </div>
                                </div>
                        </header>

                        <!--// Main Banner //-->
                        <div id="mainbanner">
                                <div class="flexslider">
                                        <ul class="slides">
                                                <li>
                                                        <img src="home/extra-images/slide1.jpg" alt="" />
                                                        <div class="container">
                                                                <div class="kode-caption">       
                                                                        <h2>Your Path to Sports <span>Career</span> and <span>Fame</span></h2>
                                                                        <div class="clearfix"></div>
                                                                      <!--<p>You. Your team.<br>
                                                    Your sports organization.<br>
                                                    One product.<br>
                                                    Everything you need.</p>-->
                                                                        <div class="clearfix"></div>
                                                                        <a class="kode-modren-btn thbg-colortwo" href="javascript:void(0);" data-toggle="modal" data-target="#home-login-modal">Login</a>&nbsp;&nbsp;<span class="or_text">OR</span>&nbsp;&nbsp;
                                                                        <a class="kode-modren-btn thbg-colortwo" href="javascript:void(0);" data-toggle="modal" data-target="#home-register-modal">Register</a>
                                                                </div>
                                                        </div>
                                                </li>
                                                <!--<li>
                                                  <img src="home/extra-images/slide2.jpg" alt="" />
                                                  <div class="container">
                                                    <div class="kode-caption">       
                                                            <h2>Soccer</h2> 
                                                        <div class="clearfix"></div>          
                                                      <p>Sed ut perspiciatis unde omnis iste natus <br>error sit accusantium dolore</p>
                                                      <div class="clearfix"></div>
                                                      <a class="kode-modren-btn thbg-colortwo" href="#">Read More</a>
                                                    </div>
                                                  </div>
                                                </li>-->
                                                <!--<li>
                                                  <img src="home/extra-images/slide3.jpg" alt="" />
                                                  <div class="container">
                                                    <div class="kode-caption">       
                                                            <h2>Tennis</h2>
                                                        <div class="clearfix"></div>        
                                                      <p>Sed ut perspiciatis unde omnis iste natuserror sit accusantium dolore</p>
                                                      <div class="clearfix"></div>
                                                      <a class="kode-modren-btn thbg-colortwo" href="#">Read More</a>
                                                    </div>
                                                  </div>
                                                </li>-->



                                        </ul>
                                </div>
                        </div>
                        <!--// Main Banner //-->

                        <!--// Main Content //-->
                        <div class="kode-content padding-top-0">

<!--		<section class="pick-event padding-30-topbottom margin-top-minus-40">
        <div class="container">
        <div class="heading">
            <h2>Search & Connect</h2>
        </div>
        <div class="form">
                <div class="cover">
                <input type="text">
                <i class="fa fa-search"></i>                    </div>
      <div class="cover">
                <input type="text">
                <i class="fa fa-calendar"></i>                    </div>
      <div class="cover">
                <div class="dropdown">
                    <button aria-expanded="true" data-toggle="dropdown" id="dropdownMenu1" type="button" class="dropdown-toggle">
                    Dropdown
                    <i class="fa fa-bars"></i>                            </button>
                    <ul aria-labelledby="dropdownMenu1" role="menu" class="dropdown-menu">
                        <li role="presentation"><a href="#" tabindex="-1" role="menuitem">Action</a></li>
                        <li role="presentation"><a href="#" tabindex="-1" role="menuitem">Another action</a></li>
                        <li role="presentation"><a href="#" tabindex="-1" role="menuitem">Something else here</a></li>
                        <li role="presentation"><a href="#" tabindex="-1" role="menuitem">Separated link</a></li>
                    </ul>
                </div>
            </div>
      <div class="cover">
                <a class="kode-modren-btn thbg-colortwo">Find Event</a>                    </div>
      </div>
    </div>
</section>		-->

                                <!--// Page Content //-->
                                <section class="kode-pagesection padding-30-topbottom bg-squre-pattren" id="howitworks">
                                        <div class="container">
                                                <div class="row">

                                                        <div class="kode-result-list shape-view col-md-12">
                                                                <div class="heading heading-12">
                                                                        <h2><span class="left"></span>How SportsJun Works<span class="right"></span></h2>
                                                                </div>
                                                                <div class="clear clearfix"></div>

                                                                <div class="col-md-12 how_img_dis">
                                                                        <h1>Track all Your Sports Performances with one profile</h1>                                  
                                                                        <img src="home/extra-images/track.png">

                                                                </div>

                                                                <!--<div class="row">
                                                                
                                                                      <div class="col-md-6 col-sm-6 how_text">
                                                                                <div class="how_text_head"><h1>1</h1><h2>Create Sports <br>Profile</h2></div>
                                                                                <div class="how_text_des"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div>
                                                                      </div>
                                                                      
                                                                                          <div class="col-md-6 col-sm-6 how_text">
                                                                                <div class="how_text_head"><h1>2</h1><h2>Create or find <br> Organizations / Teams </h2></div>
                                                                                <div class="how_text_des"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div>
                                                                      </div>
                                                                      
                                                                                        <div class="col-md-6 col-sm-6 how_text">
                                                                                <div class="how_text_head"><h1>3</h1><h2>Schedule <br> Matches</h2></div>
                                                                                <div class="how_text_des"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div>
                                                                      </div>
                                                                      
                                                                                        <div class="col-md-6 col-sm-6 how_text">
                                                                                <div class="how_text_head"><h1>4</h1><h2>Play and Capture <br>result</h2></div>
                                                                                <div class="how_text_des"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div>
                                                                      </div>
                                                                      
                                                                                        <div class="col-md-6 col-sm-6 how_text">
                                                                                <div class="how_text_head"><h1>5</h1><h2>Automatic Team and <br>player statistics</h2></div>
                                                                                <div class="how_text_des"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div>
                                                                      </div>
                                                                      
                                                                      <div class="col-md-6 col-sm-6 how_text">
                                                                                <div class="how_text_head"><h1>6</h1><h2>Share profile with Social sites <br>for visibility</h2></div>
                                                                                <div class="how_text_des"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div>
                                                                      </div>
                                                                    
                                                                    
                                                                    
                                                                </div>-->

                                                                <!--<div class="col-md-12 how_img_dis">
                                
                                                        <img src="home/extra-images/Organizing-Tournaments.png">
                                
                                                </div>-->

                                                                <!--<div class="row">
                                                                
                                                                      <div class="col-md-6 col-sm-6 how_text">
                                                                                <div class="how_text_head"><h1>1</h1><h2>Create Tournaments / Leagues with few <br> easy steps</h2></div>
                                                                                <div class="how_text_des"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div>
                                                                      </div>
                                                                      
                                                                                          <div class="col-md-6 col-sm-6 how_text">
                                                                                <div class="how_text_head"><h1>2</h1><h2>Add teams and schedule matches on 3 different <br> formats</h2></div>
                                                                                <div class="how_text_des"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div>
                                                                      </div>
                                                                      
                                                                                        <div class="col-md-6 col-sm-6 how_text">
                                                                                <div class="how_text_head"><h1>3</h1><h2>Capture results which builds automatic standings</h2></div>
                                                                                <div class="how_text_des"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div>
                                                                      </div>
                                                                      
                                                                                        <div class="col-md-6 col-sm-6 how_text">
                                                                                <div class="how_text_head"><h1>4</h1><h2>Automatic Team and player statistics</h2></div>
                                                                                <div class="how_text_des"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div>
                                                                      </div>
                                                                      
                                                                                        <div class="col-md-6 col-sm-6 how_text" style="clear:both;">
                                                                                <div class="how_text_head"><h1>5</h1><h2>Share tournament info, results, gallery for greater visibility</h2></div>
                                                                                <div class="how_text_des"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div>
                                                                      </div>
                                                                      
                                                                      
                                                                      
                                                                    
                                                                    
                                                                    
                                                                </div>-->



                                                                <!--<div class="row">
                                                                
                                                                                <div class="container">
                                                                        <img src="home/extra-images/how-it-works.png">
                                                                </div>
                                                                
                                                                  <div class="col-md-4 col-sm-6 how_text">
                                                                                                        <div class="how_text_head"><h1>1</h1><h2>Create Sports <br>Profile</h2></div>
                                                                            <div class="how_text_des"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div>
                                                                  </div>
                                                                  
                                                                  <div class="col-md-4 col-sm-6 how_text">
                                                                                                        <div class="how_text_head"><h1>2</h1><h2>Create OR FIND<br>ORGANIZATIONS/TEAMS</h2></div>
                                                                            <div class="how_text_des"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div>                  
                                                                  </div>
                                                                  
                                                                  <div class="col-md-4 col-sm-6 how_text">
                                                                                                        <div class="how_text_head"><h1>3</h1><h2>SCHEDULE<br>MATCHES</h2></div>
                                                                            <div class="how_text_des"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div>
                                                                  </div>
                                                                  
                                                                                  <div class="col-md-4 col-sm-6 how_text">
                                                                                                        <div class="how_text_head"><h1>4</h1><h2>PLAY AND CAPTURE <br>RESULT</h2></div>
                                                                            <div class="how_text_des"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div>
                                                                  </div>
                                                                  
                                                                  <div class="col-md-4 col-sm-6 how_text">
                                                                                                        <div class="how_text_head"><h1>5</h1><h2>AUTOMATIC TEAM & <br>PLAYER STATISTICS</h2></div>
                                                                            <div class="how_text_des"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div>                  
                                                                  </div>
                                                                  
                                                                  <div class="col-md-4 col-sm-6 how_text">
                                                                                                        <div class="how_text_head"><h1>6</h1><h2>SHARE PROFILES WITH <br>SOCIAL SITES</h2></div>
                                                                            <div class="how_text_des"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div>
                                                                  </div>
                                                                  
                                                                </div>-->
                                                        </div>

                                                </div>
                                        </div>
                                </section>
                                <!--// Page Content //-->

                                <section class="kode-pagesection kode-parallax kode-modern-expert-blogger features-bg" style="margin-bottom:0;">

                                        <div class="container">
                                                <div class="row">
                                                        <div class="kode-result-list shape-view col-md-12">
                                                                <div class="heading heading-12">
                                                                        <h2 style="color:#FFFFFF;"><span class="left"></span>Features<span class="right"></span></h2>
                                                                </div>
                                                        </div>
                                                </div>

                                                <div class="row text-center">

                                                        <div class="col-md-4 col-sm-4 col-xs-12 features_text">
                                                                <i class="fa fa-5x"><img src="home/extra-images/player-icon.png" width="60%" height="60%"></i>
                                                                <h3 class="player_color">Player</h3>
                                                                <p>Build individual sports profile (add sports, pictures, specialisation) to join and create teams. One profile for</p>
                                                                <a class="label_more" href="features.html">...</a>
                                                        </div>

                                                        <div class="col-md-4 col-sm-4 col-xs-12 features_text">
                                                                <i class="fa fa-5x"><img src="home/extra-images/team-icon.png" width="60%" height="60%"></i>
                                                                <h3 class="team_color">TEAMS</h3>
                                                                <p>Create Teams and assign players,managers and admins to manage great teams, stay connected</p>
                                                                <a class="label_more" href="features.html">...</a>
                                                        </div>

                                                        <div class="col-md-4 col-sm-4 col-xs-12 features_text">
                                                                <i class="fa fa-5x"><img src="home/extra-images/organization.png" width="60%" height="60%"></i>
                                                                <h3 class="organization_color">Organization Structure</h3>
                                                                <p>Create organization and assign all teams part of the organization for easier management</p>
                                                                <a class="label_more" href="features.html">...</a>
                                                        </div>

                                                </div>

                                                <div class="row text-center">

                                                        <div class="col-md-4 col-sm-4 col-xs-12 features_text">
                                                                <i class="fa fa-5x"><img src="home/extra-images/scores_icon.png" width="60%" height="60%"></i>
                                                                <h3 class="online_score_color">ONLINE SCORING</h3>
                                                                <p>Use scoring system to enter real time and offline scoring for 4 sports (Cricket, Tennis, Badminton, Soccer)</p>
                                                                <a class="label_more" href="features.html">...</a>
                                                        </div>

                                                        <div class="col-md-4 col-sm-4 col-xs-12 features_text">
                                                                <i class="fa fa-5x"><img src="home/extra-images/league.png" width="60%" height="60%"></i>
                                                                <h3 class="league_color">League and tournaments</h3>
                                                                <p>Super easy league builder for organisers. Manage all aspects of league and tournament scheduling </p>
                                                                <a class="label_more" href="features.html">...</a>
                                                        </div>

                                                        <div class="col-md-4 col-sm-4 col-xs-12 features_text">
                                                                <i class="fa fa-5x"><img src="home/extra-images/gallery.png" width="60%" height="60%"></i>
                                                                <h3 class="gallery_color">gallery</h3>
                                                                <p>Create albums and post your personal sporting pictures and team events in a snap. Share your amazing</p>
                                                                <a class="label_more" href="features.html">...</a>
                                                        </div>

                                                </div>

                                                <div class="row text-center">

                                                        <div class="col-md-4 col-sm-4 col-xs-12 features_text">
                                                                <i class="fa fa-5x"><img src="home/extra-images/market-place.png" width="60%" height="60%"></i>
                                                                <h3 class="market_place_color">Market Place</h3>
                                                                <p>Connect with sporting community to buy and sell equipment.</p>
                                                                <a class="label_more" href="features.html">...</a>
                                                        </div>

                                                </div>

                                                <div class="kode-align-btn"><a class="kode-modren-btn thbg-colortwo" href="features.html">View More</a></div>

                                </section>

                                <!--// Page Content //-->

                                <section class="kode-pagesection kode-parallax  kode-modern-expert-blogger sj_white_bg" style="margin-bottom:0;">
                                        <div class="container">
                                                <div class="row">

                                                        <div class="col-md-12">
                                                                <div class="heading heading-12 margin-top-bottom-30">
                                                                        <h2 class="color-white" style="color:#374457;"><span class="left"></span>Tournaments<span class="right"></span></h2>
                                                                </div>

                                                                <div class="kode-blog-list kode-large-blog">
                                                                        <ul class="row">

                                                                                <li class="col-md-4">
                                                                                        <div class="kode-time-zoon">
                                                                                                <time datetime="2008-02-14 20:00">07 <span>may</span></time>
                                                                                                <h5><a href="#">Sed ut perspiciatis unde omnisiste natus error</a></h5>
                                                                                        </div>
                                                                                        <figure><a href="#"><img src="home/extra-images/bloggird-1.jpg" alt=""></a></figure>
                                                                                        <div class="kode-blog-info">
                                                                                                <p>Lorem ipsum dolor sit amet, cons ecte tuer adipiscing elit, sed diam non ummy nibh euismod tinc idunt ut laoreet dolore magna ali quam erat volutpat. Ut veniam, quis..</p>
                                                                                                <a href="#" class="kode-blog-btn kode_blog_btn_link">...</a>
                                                                                                <div class="clearfix"></div>

                                                                                        </div>
                                                                                </li>
                                                                                <li class="col-md-4">
                                                                                        <div class="kode-time-zoon">
                                                                                                <time datetime="2008-02-14 20:00">07 <span>may</span></time>
                                                                                                <h5><a href="#">Sed ut perspiciatis unde omnisiste natus error</a></h5>
                                                                                        </div>
                                                                                        <figure><a href="#"><img src="home/extra-images/bloggird-2.jpg" alt=""></a></figure>
                                                                                        <div class="kode-blog-info">
                                                                                                <p>Lorem ipsum dolor sit amet, cons ecte tuer adipiscing elit, sed diam non ummy nibh euismod tinc idunt ut laoreet dolore magna ali quam erat volutpat. Ut veniam, quis..</p>
                                                                                                <a href="#" class="kode-blog-btn kode_blog_btn_link">...</a>
                                                                                                <div class="clearfix"></div>

                                                                                        </div>
                                                                                </li>
                                                                                <li class="col-md-4">
                                                                                        <div class="kode-time-zoon">
                                                                                                <time datetime="2008-02-14 20:00">07 <span>may</span></time>
                                                                                                <h5><a href="#">Sed ut perspiciatis unde omnisiste natus error</a></h5>
                                                                                        </div>
                                                                                        <figure><a href="#"><img src="home/extra-images/bloggird-3.jpg" alt=""></a></figure>
                                                                                        <div class="kode-blog-info">
                                                                                                <p>Lorem ipsum dolor sit amet, cons ecte tuer adipiscing elit, sed diam non ummy nibh euismod tinc idunt ut laoreet dolore magna ali quam erat volutpat. Ut veniam, quis..</p>
                                                                                                <a href="#" class="kode-blog-btn kode_blog_btn_link">...</a>
                                                                                                <div class="clearfix"></div>

                                                                                        </div>
                                                                                </li>

                                                                        </ul>
                                                                        <div class="kode-align-btn"><a href="#" class="kode-modren-btn thbg-colortwo">View More</a></div>
                                                                </div>
                                                        </div>
                                                </div>

                                        </div>
                                </section>
                                <!--// Page Content //-->



                                <!--// Page Content //-->
                        <!--        <section class="kode-pagesection top_player_section" >
                                  <div class="container">
                                    <div class="row">
                        
                                      <div class="col-md-12">
                                        <div class="kode-section-title"> <h2>Top Player</h2> </div>
                        
                                          <div class="owl-carousel-team owl-theme kode-team-list next-prev-style">
                                            <div class="item">
                                              <figure><a href="#" class="kode-team-thumb"><img src="home/extra-images/player-1.jpg" alt=""></a>
                                                <figcaption>
                                                  <ul class="kode-team-network">
                                                    <li><a href="#" class="fa fa-facebook"></a></li>
                                                    <li><a href="#" class="fa fa-twitter"></a></li>
                                                    <li><a href="#" class="fa fa-linkedin"></a></li>
                                                  </ul>
                                                  <div class="clearfix"></div>
                                                  <h2><a href="#">Sergio Ramos</a></h2>
                                                  <a href="#" class="kode-modren-btn thbg-colortwo">View Detail</a>                        </figcaption>
                                              </figure>
                                            </div>
                                            <div class="item">
                                              <figure><a href="#" class="kode-team-thumb"><img src="home/extra-images/player-2.jpg" alt=""></a>
                                                <figcaption>
                                                  <ul class="kode-team-network">
                                                    <li><a href="#" class="fa fa-facebook"></a></li>
                                                    <li><a href="#" class="fa fa-twitter"></a></li>
                                                    <li><a href="#" class="fa fa-linkedin"></a></li>
                                                  </ul>
                                                  <div class="clearfix"></div>
                                                  <h2><a href="#">Wayne Rooney</a></h2>
                                                  <a href="#" class="kode-modren-btn thbg-colortwo">Vew Detail</a>                        </figcaption>
                                              </figure>
                                            </div>
                                            <div class="item">
                                              <figure><a href="#" class="kode-team-thumb"><img src="home/extra-images/player-3.jpg" alt=""></a>
                                                <figcaption>
                                                  <ul class="kode-team-network">
                                                    <li><a href="#" class="fa fa-facebook"></a></li>
                                                    <li><a href="#" class="fa fa-twitter"></a></li>
                                                    <li><a href="#" class="fa fa-linkedin"></a></li>
                                                  </ul>
                                                  <div class="clearfix"></div>
                                                  <h2><a href="#">Iker Casillas</a></h2>
                                                  <a href="#" class="kode-modren-btn thbg-colortwo">Vew Detail</a>                        </figcaption>
                                              </figure>
                                            </div>
                                            <div class="item">
                                              <figure><a href="#" class="kode-team-thumb"><img src="home/extra-images/player-4.jpg" alt=""></a>
                                                <figcaption>
                                                  <ul class="kode-team-network">
                                                    <li><a href="#" class="fa fa-facebook"></a></li>
                                                    <li><a href="#" class="fa fa-twitter"></a></li>
                                                    <li><a href="#" class="fa fa-linkedin"></a></li>
                                                  </ul>
                                                  <div class="clearfix"></div>
                                                  <h2><a href="#">Sergio Ramos</a></h2>
                                                  <a href="#" class="kode-modren-btn thbg-colortwo">Vew Detail</a>                        </figcaption>
                                              </figure>
                                            </div>
                                          </div>
                        
                                      </div>
                                      
                                    </div>
                                  </div>
                                </section>-->


                                <!--// Page Content //-->
                                <section class="kode-pagesection kode-parallax kode-dark-overlay kode-woo-products-style" style="margin-bottom:0;">
                                        <div class="container">
                                                <div class="row">

                                                        <div class="col-md-12">
                                                                <div class="kode-section-title kode-white"> <h2>Market Place</h2> </div>
                                                                <div class="kode-shop-list">

                                                                        <div class="owl-carousel owl-theme">
                                                                                <div class="item">
                                                                                        <div class="kode-pro-inner">
                                                                                                <figure><a href="#"><img src="home/extra-images/shop-product1.jpg" alt=""></a>
                                                                                                        <figcaption>
                                                                                                                <h4><a href="#">Product Name</a></h4>
                                                                                                                <p class="kode-pro-cat"><a href="#">Categories</a></p>
                                                                                                        </figcaption>
                                                                                                </figure>
                                                                                                <div class="kode-pro-info">
                                                                                                        <a href="#" class="add_to_cart"><i class="fa fa-shopping-cart"></i> Add To Cart</a>
                                                                                                        <span>55$</span>
                                                                                                </div>
                                                                                        </div>
                                                                                </div>
                                                                                <div class="item">
                                                                                        <div class="kode-pro-inner">
                                                                                                <figure><a href="#"><img src="home/extra-images/shop-product2.jpg" alt=""></a>
                                                                                                        <figcaption>
                                                                                                                <h4><a href="#">Product Name</a></h4>
                                                                                                                <p class="kode-pro-cat"><a href="#">Categories</a></p>
                                                                                                        </figcaption>
                                                                                                </figure>
                                                                                                <div class="kode-pro-info">
                                                                                                        <a href="#" class="add_to_cart"><i class="fa fa-shopping-cart"></i> Add To Cart</a>
                                                                                                        <span>55$</span>
                                                                                                </div>
                                                                                        </div>
                                                                                </div>
                                                                                <div class="item">
                                                                                        <div class="kode-pro-inner">
                                                                                                <figure><a href="#"><img src="home/extra-images/shop-product3.jpg" alt=""></a>
                                                                                                        <figcaption>
                                                                                                                <h4><a href="#">Product Name</a></h4>
                                                                                                                <p class="kode-pro-cat"><a href="#">Categories</a></p>
                                                                                                        </figcaption>
                                                                                                </figure>
                                                                                                <div class="kode-pro-info">
                                                                                                        <a href="#" class="add_to_cart"><i class="fa fa-shopping-cart"></i> Add To Cart</a>
                                                                                                        <span>55$</span>
                                                                                                </div>
                                                                                        </div>
                                                                                </div>
                                                                                <div class="item">
                                                                                        <div class="kode-pro-inner">
                                                                                                <figure><a href="#"><img src="home/extra-images/shop-product1.jpg" alt=""></a>
                                                                                                        <figcaption>
                                                                                                                <h4><a href="#">Product Name</a></h4>
                                                                                                                <p class="kode-pro-cat"><a href="#">Categories</a></p>
                                                                                                        </figcaption>
                                                                                                </figure>
                                                                                                <div class="kode-pro-info">
                                                                                                        <a href="#" class="add_to_cart"><i class="fa fa-shopping-cart"></i> Add To Cart</a>
                                                                                                        <span>55$</span>
                                                                                                </div>
                                                                                        </div>
                                                                                </div>
                                                                                <div class="item">
                                                                                        <div class="kode-pro-inner">
                                                                                                <figure><a href="#"><img src="home/extra-images/shop-product3.jpg" alt=""></a>
                                                                                                        <figcaption>
                                                                                                                <h4><a href="#">Product Name</a></h4>
                                                                                                                <p class="kode-pro-cat"><a href="#">Categories</a></p>
                                                                                                        </figcaption>
                                                                                                </figure>
                                                                                                <div class="kode-pro-info">
                                                                                                        <a href="#" class="add_to_cart"><i class="fa fa-shopping-cart"></i> Add To Cart</a>
                                                                                                        <span>55$</span>
                                                                                                </div>
                                                                                        </div>
                                                                                </div>
                                                                        </div>

                                                                </div>

                                                                <div class="kode-align-btn"><a href="features.html" class="kode-modren-btn thbg-colortwo">View More</a></div>

                                                        </div>

                                                </div>

                                        </div>
                                </section>


                                <!--// Page Content //-->


<!--		<section class="kode-pagesection kode-video-section kode-parallax kode-gallery-pretty">
<div class="container">
<h2>Running Tutorial Session</h2>
<a data-gal="prettyphoto" href="http://vimeo.com/7874398"><i class="fa fa-play"></i></a>
<h4>Trainer : Roy Stone</h4>
<p>Source: Youtube, Vimeo</p>
</div>
</section>-->







                        </div>
                        <!--// Main Content //-->

                        <!--// NewsLatter //-->
                        <!--<div class="kode-newslatter kode-bg-color" >
                          <span class="kode-halfbg thbg-color"></span>
                          <div class="container">
                            <div class="row">
                              <div class="col-md-6">
                                <h3>Subscribe Our Monthly Newsletter</h3>
                              </div>
                              <div class="col-md-6">
                                <form>
                                  <input type="text" placeholder="Your E-mail Adress" name="s" required>
                                  <label><input type="submit" value=""></label>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>-->
                        <!--// NewsLatter //-->
                        <footer id="footer1" class="kode-parallax kode-dark-overlay kode-bg-pattern">
                                <!--Footer Medium-->
                                <div class="footer-medium">
                                        <div class="container">
                                                <div class="row">
                                                        <div class="col-md-4">
                                                                <div class="about-widget">
                                                                        <h3>About SportsJun</h3>
                                                                        <ul class="kode-form-list">
                                                                                <li><i class="fa fa-home"></i> <p><strong>Address:</strong> 805 omnis iste natus error.</p></li>
                                                                                <li><i class="fa fa-phone"></i> <p><strong>Phone:</strong> 111 8756 22  777 4456 112</p></li>
                                                                                <li><i class="fa fa-envelope-o"></i> <p><strong>Email:</strong> Info@sportyleague.com</p></li>
                                                                        </ul>
                                                                </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                                <div class="widget links_widget">
                                                                        <h3>Links</h3>
                                                                        <ul>
                                                                                <li><a href="privacy.html">Privacy</a></li>
                                                                                <li><a href="terms-of-conditions.html">Terms and Conditions</a></li>
                                                                                <li><a href="faq.html">FAQ</a></li>
                                                                                <li><a href="contact-us.html">Contact Us</a></li>
                                                                        </ul>
                                                                </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                                <div class="contact-us-widget">
                                                                        <h3>Connect with us</h3>
                                                                        <p>Follow us to stay in the loop on what’s<br>
                                                                                Sed ut perspiciatis unde omnis iste natus<br> error sit Sed ut perspiciatis unde omnis iste<br> natus error sit</p>
                                                                        <ul class="social-links1">
                                                                                <li>
                                                                                        <a href="#" class="tw-bg1"><i class="fa fa-twitter"></i></a>
                                                                                </li>
                                                                                <li>
                                                                                        <a href="#" class="fb-bg1"><i class="fa fa-facebook"></i></a>
                                                                                </li>
                                                                                <li>
                                                                                        <a href="#" class="youtube-bg1"><i class="fa fa-youtube"></i></a>
                                                                                </li>
                                                                                <li>
                                                                                        <a href="#" class="linkedin-bg1"><i class="fa fa-linkedin"></i></a>
                                                                                </li>
                                                                                <li>
                                                                                        <a href="#" class="tw-bg1"><i class="fa fa-instagram"></i></a>
                                                                                </li>

                                                                        </ul>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                                <!--Footer Medium End-->


                        </footer>
                        <!--// Contact Footer //-->

                        <div class="kode-bottom-footer">
                                <div class="container">
                                        <div class="row">
                                                <div class="col-md-6">
                                                        <p>&copy; SportsJun Media & Entertainment Pvt Ltd 2016 All Rights Reserved</p>
                                                </div>
                                                <div class="col-md-6">
                                                        <a href="#" id="kode-topbtn" class="thbg-colortwo"><i class="fa fa-angle-up"></i></a>
                                                </div>
                                        </div>
                                </div>      </div>
                        <div class="clearfix clear"></div>
                </div>
                <!--// Wrapper //-->
                <!-- Modal -->
                <div class="modal fade" id="home-login-modal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                                <div class="modal-content">
                                        <div class="modal-header thbg-color">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Login To Your Account</h4>
                                        </div>
                                        <div class="modal-body">
                                                <form id="home-login-modal-form" class="kode-loginform" onsubmit="SJ.USER.loginValidation(this.id);return false;">
                                                        <p><span>Email address</span> <input type="text" placeholder="Enter your email" name="email" /></p>
                                                        <p><span>Password</span> <input type="password" placeholder="Enter your password" name="password" /></p>
                                                        <!-- p><label><input type="checkbox"><span>Remember Me</span></label></p -->
                                                        <p class="kode-submit">
                                                                <a href="javascript:void(0);" data-dismiss="modal" data-toggle="modal" data-target="#home-forgot-password-modal">Lost Your Password</a>
                                                                <input class="thbg-colortwo" type="submit" value="Sign in">
                                                        </p>
                                                </form>
                                        </div>
                                </div>
                        </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="home-register-modal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                                <div class="modal-content">
                                        <div class="modal-header thbg-color">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Register</h4>
                                        </div>
                                        <div class="modal-body">
                                                <form id="home-register-modal-form" class="kode-loginform" onsubmit="SJ.USER.registerValidation(this.id);return false;">
                                                        <p><span>First Name</span> <input name="firstname" type="text" placeholder="First Name"></p>
                                                        <p><span>Last Name</span> <input name="lastname" type="text" placeholder="Last Name"></p>
                                                        <p><span>Email</span> <input name="email" type="text" placeholder="Email"></p>
                                                        <p><span>Password</span> <input name="password" type="password" placeholder="Password"></p>
                                                        <p><span>Retype Password</span> <input name="password_confirmation" type="password" placeholder="Retype Password"></p>
                                                        <!-- p><label><input type="checkbox"><span>Remember Me</span></label></p -->
                                                        <span class="capcha"> {!!Captcha::img('flat')!!}</span><br />
                                                        <input type="text" name="captcha" class="captcha-input" placeholder="Enter the above captcha">
                                                        <a href="javascript:void(0)" onclick="SJ.USER.refreshCaptcha('home-register-modal-form');" class="signup_capthca"><img src="{{ asset('/images/refresh.png') }}" alt="Refresh Captcha Image" /></a>
                                                        <p class="p_checkbox first"><label><input name="tos" type="checkbox" checked="checked"><span>I agree to the <a href="{{ url('/terms-of-conditions.html') }}" target="_blank">terms and conditions</a> of this site.</span></label></p>
                                                        <p class="p_checkbox last"><label><input name="newsletter" type="checkbox" checked="checked"><span>I wish to receive the weekly bulletin</span></label></p>
                                                        <p class="kode-submit"><a href="javascript:void(0);" data-dismiss="modal" data-toggle="modal" data-target="#home-forgot-password-modal">Lost Your Password</a> <input class="thbg-colortwo" type="submit" value="Sign Up"></p>
                                                </form>
                                        </div>
                                </div>
                        </div>
                </div>
                
                <div class="modal fade" id="home-forgot-password-modal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                                <div class="modal-content">
                                        <div class="modal-header thbg-color">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Lost Your Password</h4>
                                        </div>
                                        <div class="modal-body">
                                                <form id="home-forgot-password-modal-form" class="kode-loginform" onsubmit="SJ.USER.forgotPasswordValidation(this.id);return false;">
                                                        <p><span>Email address</span> <input type="text" placeholder="Enter your email" name="email" /></p>
                                                        <p class="kode-submit">
                                                                <input class="thbg-colortwo" type="submit" value="Submit">
                                                        </p>
                                                </form>
                                        </div>
                                </div>
                        </div>
                </div>
                
                <div class="modal fade" id="home-email-verify-modal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                                <div class="modal-content">
                                        <div class="modal-header thbg-color">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Thank you!</h4>
                                        </div>
                                        <div class="modal-body verify-msg">
                                                The activation link has been sent to your email <span id="verify-email-id"></span><br />
                                                Please check your email and click on the link to activate your account.<br />
                                                <a class="kode-modren-btn thbg-colortwo" href="javascript:void(0);" data-dismiss="modal" data-toggle="modal" data-target="#home-login-modal">Login</a>
                                        </div>
                                </div>
                        </div>
                </div>

                <!-- jQuery (necessary for JavaScript plugins) -->
                <script src="home/js/jquery.js"></script>
                <script src="home/js/sj.global.js"></script>
                <script src="home/js/sj.user.js"></script>
                <script src="home/js/bootstrap.min.js"></script>
                <script src="home/js/jquery.flexslider.js"></script>
                <script src="home/js/owl.carousel.min.js"></script>
                <script src="home/js/jquery.countdown.js"></script>  
                <script src="home/js/waypoints-min.js"></script>
                <script src="home/js/jquery.bxslider.min.js"></script>
                <script src="home/js/bootstrap-progressbar.js"></script>
                <script src="home/js/jquery.accordion.js"></script>
                <script src="home/js/jquery.circlechart.js"></script>
                <script src="home/js/jquery.prettyphoto.js"></script>
                <script src="home/js/kode_pp.js"></script>
                <script src="home/js/functions.js"></script>
        </body>
</html>