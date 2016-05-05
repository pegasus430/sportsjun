<!DOCTYPE html>
<html lang="en">
        <head>
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <title>Sportsjun - Connecting Sports Community</title>                
                <?php   
                        $js_version     = config('constants.JS_VERSION');
                        $css_version    = config('constants.CSS_VERSION');
                ?>
                <!-- Css Files -->
                <link href="{{ asset('/home/css/bootstrap.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
                <link href="{{ asset('/home/css/font-awesome.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
                <link href="{{ asset('/home/css/themetypo.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
                <link href="{{ asset('/home/css/style.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
                <link href="{{ asset('/home/css/widget.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
                <link href="{{ asset('/home/css/color.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
                <link href="{{ asset('/home/css/flexslider.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
                <link href="{{ asset('/home/css/owl.carousel.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
                <link href="{{ asset('/home/css/jquery.bxslider.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">    
                <link href="{{ asset('/home/css/prettyphoto.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
                <link href="{{ asset('/home/css/responsive.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
                
                <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('/images/favicon/apple-icon-57x57.png') }}">
                <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('/images/favicon/apple-icon-60x60.png') }}">
                <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('/images/favicon/apple-icon-72x72.png') }}">
                <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/images/favicon/apple-icon-76x76.png') }}">
                <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('/images/favicon/apple-icon-114x114.png')}}">
                <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('/images/favicon/apple-icon-120x120.png')}}">
                <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('/images/favicon/apple-icon-144x144.png')}}">
                <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('/images/favicon/apple-icon-152x152.png')}}">
                <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/images/favicon/apple-icon-180x180.png')}}">
                <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('/images/favicon/android-icon-192x192.png')}}">
                <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/images/favicon/favicon-32x32.png')}}">
                <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('/images/favicon/favicon-96x96.png')}}">
                <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/images/favicon/favicon-16x16.png')}}">
                <link rel="manifest" href="/manifest.json">
                <meta name="msapplication-TileColor" content="#ffffff">
                <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
                <meta name="theme-color" content="#ffffff">
    
                <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
                <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
                <!--[if lt IE 9]>
                  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
                <![endif]-->
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
                                                                        <!-- li><a href="#"><i class="fa fa-user"></i> My Account</a></li -->
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
                                                                <li><a href="/">Home</a></li>
                                                                <li><a href="sports.html">Sports</a></li>
                                                                <li><a href="features.html">Features</a></li>
                                                        </ul>
                                                </div>
                                                <!--NAVIGATION END--> 
                                                <!--LOGO START-->	
                                                <div class="logo">
                                                        <a href="/" class="logo"><img src="home/images/sportsjun.png"  alt=""></a>
                                                </div>
                                                <!--LOGO END-->	
                                                <!--NAVIGATION START-->
                                                <div class="kode-navigation">
                                                        <ul>
                                                                <li><a href="/#howitworks">Players / Teams</a></li>
                                                                <li><a href="/#tournaments">Tournaments</a></li>
                                                                <li><a href="/#marketplace">Market Place</a></li>
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
                                                                        <li><a href="/">Home</a></li>
                                                                        <li><a href="sports.html">Sports</a></li>
                                                                        <li><a href="features.html">Features</a></li>
                                                                        <li><a href="/#howitworks">Players / Teams</a></li>
                                                                        <li><a href="/#tournaments">Tournaments</a></li>
                                                                        <li><a href="/#marketplace">Market Place</a></li>
                                                                        <li><a href="/#aboutus">About Us</a></li>
                                                                        <li><a href="contact-us.html">Contact Us</a></li>
                                                                </ul>
                                                        </div>
                                                </nav>
                                        </div>
                                </div>
                        </header>
@yield('content')
                        <footer id="footer1" class="kode-parallax kode-dark-overlay kode-bg-pattern">
                                <!--Footer Medium-->
                                <div class="footer-medium">
                                        <div class="container">
                                                <div class="row">
                                                        <div id="aboutus" class="col-md-4">
                                                                <div class="about-widget">
                                                                        <h3>About SportsJun</h3>
                                                                        <ul class="kode-form-list">
                                                                                <li><i class="fa fa-home"></i> <p><strong>Address:</strong> Gachibowli, Hyderabad, Telangana, India 500031.</p></li>
                                                                                <li><i class="fa fa-envelope-o"></i> <p><strong>Email:</strong> contact@sportsjun.com</p></li>
                                                                        </ul>
                                                                </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                                <div class="widget links_widget">
                                                                        <h3>Links</h3>
                                                                        <ul>
                                                                                <li><a href="privacy.html">Privacy</a></li>
                                                                                <li><a href="terms-and-conditions.html">Terms and Conditions</a></li>
                                                                                <li><a href="faq.html">FAQ</a></li>
                                                                                <li><a href="contact-us.html">Contact Us</a></li>
                                                                        </ul>
                                                                </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                                <div class="contact-us-widget">
                                                                        <h3>Connect with us</h3>
                                                                        <p>Follow us to stay updated and connected – using your favorite social media.<br></p>
                                                                        <ul class="social-links1">
                                                                                <li>
                                                                                        <a target="_blank" href="https://twitter.com/sj_sportsjun" class="tw-bg1"><i class="fa fa-twitter"></i></a>
                                                                                </li>
                                                                                <li>
                                                                                        <a target="_blank" href="https://www.facebook.com/SJ.SportsJun" class="fb-bg1"><i class="fa fa-facebook"></i></a>
                                                                                </li>
                                                                                <!-- li>
                                                                                        <a target="_blank" href="#" class="youtube-bg1"><i class="fa fa-youtube"></i></a>
                                                                                </li>
                                                                                <li>
                                                                                        <a target="_blank" href="#" class="linkedin-bg1"><i class="fa fa-linkedin"></i></a>
                                                                                </li>
                                                                                <li>
                                                                                        <a target="_blank" href="#" class="tw-bg1"><i class="fa fa-instagram"></i></a>
                                                                                </li -->

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
                                </div>
                        </div>
                        <div class="clearfix clear"></div>
                </div>
                <!--// Wrapper //-->
                
                <!-- Modal -->
                <div class="modal fade" id="home-login-modal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                                <div class="modal-content">
                                        <div class="modal-header thbg-color">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Login</h4>
                                                <span class="modal-title-right-msg">Don't have an account? 
                                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#home-register-modal">Register here</a>
                                                </span>
                                        </div>
                                        <div class="modal-body">
                                                <form id="home-login-modal-form" class="kode-loginform" onsubmit="SJ.USER.loginValidation(this.id);return false;">
                                                        <p><span>Email address</span> <input type="text" placeholder="Enter your email" name="email" /></p>
                                                        <p><span>Password</span> <input type="password" placeholder="Enter your password" name="password" /></p>
                                                        <!-- p><label><input type="checkbox"><span>Remember Me</span></label></p -->
                                                        <p class="kode-submit">
                                                                <input class="thbg-colortwo btn-home-login" type="submit" value="Login">
                                                        </p>
                                                        <p>
                                                                <span class="btn-home-forgot">
                                                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#home-forgot-password-modal">Forgot Password?</a>
                                                                </span>
                                                        </p>
                                                        <div class="tagline"><span>OR</span></div>
                                                        <div class="col-md-6 social_but">
                                                           <a class="btn btn-block btn-social btn-facebook" href="{{ route('social.login', ['facebook']) }}">
                                                               <span class="fa fa-facebook"></span> Facebook
                                                           </a>
                                                        </div>
                                                        <div class="col-md-6 social_but">
                                                            <a class="btn btn-block btn-social btn-twitter" href="{{ route('social.login', ['twitter']) }}">
                                                                <span class="fa fa-twitter"></span> Twitter
                                                            </a>
                                                        </div>
                                                        <div class="col-md-6 social_but">
                                                           <a class="btn btn-block btn-social btn-google" href="{{ route('social.login', ['google']) }}">
                                                               <span class="fa fa-google-plus"></span> Google
                                                           </a>
                                                        </div>
                                                        <div class="col-md-6 social_but">
                                                            <a class="btn btn-block btn-social btn-linkedin" href="{{ route('social.login', ['linkedin']) }}">
                                                                <span class="fa fa-linkedin"></span> LinkedIn
                                                            </a>
                                                        </div>
                                                </form>
                                        </div>
                                </div>
                        </div>
                </div>
                <!-- Modal -->
                
                <!-- Modal -->
                <div class="modal fade" id="home-register-modal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                                <div class="modal-content">
                                        <div class="modal-header thbg-color">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Register</h4>
                                        </div>
                                        <div class="modal-body">
                                                <div id="register-btn-fb">
                                                        <a class="btn-register-fb" href="{{ route('social.login', ['facebook']) }}"></a>
                                                </div>
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
                                                        <p class="p_checkbox first"><label><input name="tos" type="checkbox" checked="checked"><span>I agree to the <a href="{{ url('/terms-and-conditions.html') }}" target="_blank">terms and conditions</a> of this site.</span></label></p>
                                                        <p class="p_checkbox last"><label><input name="newsletter" type="checkbox" checked="checked"><span>I wish to receive the weekly bulletin</span></label></p>
                                                        <p class="kode-submit"><a href="javascript:void(0);" data-toggle="modal" data-target="#home-forgot-password-modal">Lost Your Password</a> <input class="thbg-colortwo" type="submit" value="Sign Up"></p>
                                                </form>
                                        </div>
                                </div>
                        </div>
                </div>
                <!-- Modal -->
                
                <!-- Modal -->
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
                <!-- Modal -->
                
                <!-- Modal -->
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
                                                <a class="kode-modren-btn thbg-colortwo" href="javascript:void(0);" data-toggle="modal" data-target="#home-login-modal">Login</a>
                                        </div>
                                </div>
                        </div>
                </div>
                <!-- Modal -->
                
                <!-- jQuery (necessary for JavaScript plugins) -->
                <script src="{{ asset('/home/js/jquery.js') }}?v=<?php echo $js_version;?>"></script>
                <script src="{{ asset('/home/js/sj.global.js') }}?v=<?php echo $js_version;?>"></script>
                <script src="{{ asset('/home/js/sj.user.js') }}?v=<?php echo $js_version;?>"></script>
                <script src="{{ asset('/home/js/bootstrap.min.js') }}?v=<?php echo $js_version;?>"></script>
                <script src="{{ asset('/home/js/jquery.flexslider.js') }}?v=<?php echo $js_version;?>"></script>
                <script src="{{ asset('/home/js/owl.carousel.min.js') }}?v=<?php echo $js_version;?>"></script>
                <script src="{{ asset('/home/js/jquery.countdown.js') }}?v=<?php echo $js_version;?>"></script>  
                <script src="{{ asset('/home/js/waypoints-min.js') }}?v=<?php echo $js_version;?>"></script>
                <script src="{{ asset('/home/js/jquery.bxslider.min.js') }}?v=<?php echo $js_version;?>"></script>
                <script src="{{ asset('/home/js/bootstrap-progressbar.js') }}?v=<?php echo $js_version;?>"></script>
                <script src="{{ asset('/home/js/jquery.accordion.js') }}?v=<?php echo $js_version;?>"></script>
                <script src="{{ asset('/home/js/jquery.circlechart.js') }}?v=<?php echo $js_version;?>"></script>
                <script src="{{ asset('/home/js/jquery.prettyphoto.js') }}?v=<?php echo $js_version;?>"></script>
                <script src="{{ asset('/home/js/kode_pp.js') }}?v=<?php echo $js_version;?>"></script>
                <script src="{{ asset('/home/js/functions.js') }}?v=<?php echo $js_version;?>"></script>
        </body>
</html>