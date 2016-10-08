@extends('home.layout')
@section('content')
<div class="kode-subheader subheader-height">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <h1>Contact Us</h1>
            </div>
            <div class="col-md-6">
              <ul class="kode-breadcrumb">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="<?php echo Request::url(); ?>">Contact Us</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <!--// SubHeader //-->

      <!--// Main Content //-->
      <div class="kode-content">

        <!--// Page Content //-->
        <section class="kode-pagesection">
          <div class="container">
            <div class="row">

                <div class="kode-pagecontent col-md-12">
                  <div class="kode-map">
                    <div class="map-canvas" id="map-canvas"></div>
                  </div>

                  <div class="contactus-page">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="kode-simple-form">  					
						 <form method="post" class="comments-form" id="contactform">
                            <ul>
								<li><input type="text" id="name" name="name" class="required" placeholder="Name *"></li>
								<li><input type="text" id="email" name="email" class="required email" placeholder="Email *"></li>
								<li><input type="text" name="address" id="address" placeholder="Address:"></li>
								<li><textarea name="message" id="message" placeholder="add your message"></textarea></li>
								<li>
									<label for="verify">Are you human?</label>
									<iframe src="" height="29" width="80" scrolling="no" frameborder="0" marginheight="0" marginwidth="0" class="capcha_image_frame" name="capcha_image_frame"></iframe>
									<input class="verify" type="text" id="verify" name="verify" />
								</li>
								<li><input class="thbg-color" type="submit" value="send"></li>
                            </ul>
                          </form>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="kode-section-title" style="margin-bottom:10px;"> <h2>Contact Info</h2> </div>
                        <div class="kode-forminfo" style="padding:0;">
                          <p></p>
                          <ul class="kode-form-list" style="margin-bottom:20px;">
                            <li><i class="fa fa-home"></i> <p><strong>Address:</strong> Gachibowli, Hyderabad, Telangana, India 500031.</p></li>
                            <li><i class="fa fa-envelope-o"></i> <p><strong>Email:</strong> contact@sportsjun.com</p></li>
                          </ul>
                          <h3>Find Us On</h3>
                          <ul class="kode-team-network">
                            <li><a target="_blank" href="https://www.facebook.com/SJ.SportsJun" class="fa fa-facebook"></a></li>
                            <li><a target="_blank" href="https://twitter.com/sj_sportsjun" class="fa fa-twitter"></a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>

              </div>
            </div>
        </section>
        <!--// Page Content //-->

      </div>
      <!--// Main Content //-->
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyDn58PtJ191MRs6IVm_O13Ktkgt2Tj7AcU"></script>
@endsection