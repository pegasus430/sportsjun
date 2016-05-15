@extends('home.layout')

@section('content')
<div class="kode-subheader subheader-height">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <h1>FAQ</h1>
            </div>
            <div class="col-md-6">
              <ul class="kode-breadcrumb">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="<?php echo Request::url(); ?>">FAQ</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <!--// Sub Header //-->

      <!--// Main Content //-->
      <div class="kode-content">

        <section class="kode-pagesection kode-padding-bottom-40">
          <div class="container">
            <div class="row">	
			  <div class="col-md-12">
			  <!--<div class="heading heading-12 margin-bottom-10-flat">
					<p>Devoted to</p>
					<h2><span class="left"></span>Frequently Asked Questions<span class="right"></span></h2>
				</div>-->
                <h4>The Basics</h4>
                <div class="kd-accordion">
						
						
						<div class="accordion" id="section1">1. How can I contact you with questions, bugs, support requests, etc?<span class="fa fa-plus"></span></div>
                      <div class="accordion-content">
                          <p>There are couple ways to get in touch with us! You can directly email us at <a href="contact-us.html" class="sj_linkweb">support@sportsjun.com</a> or you can use our feedback form.</p>
                      </div>
					  
                      <div class="accordion" id="section2">2. Why should I use SportsJun?<span class="fa fa-plus"></span></div>
                      <div class="accordion-content">
                          <p>SportsJun believes that every amature sports personal should have access to a platform which can be used to maintain sports performance. It’s a website <strong>for people who play sports</strong>. All of our features are specifically designed for sports activities and every member of the SportsJun community is Sports individual. SportsJun wants every Sports loving individuals from villages, districts, towns and cities to be connected for sports career oppertunities.</p>
                      </div>
                   
                      <div class="accordion" id="section3">3. What browsers does SportsJun support?<span class="fa fa-plus"></span></div>
                      <div class="accordion-content">
                          <p>SportsJun is fully tested and approved for all modern browsers including Firefox , Safari , and Internet Explorer. <strong>We do our best to provide support for older versions of browser users for a limited period, but we do urge users to get to newer versions to take advantage of features newer version browser provides</strong>. We will slowly stop support for SportsJun for older browsers.</p>
                      </div>

                    
                  </div>
                  
                                  <h4>My Account</h4>
                <div class="kd-accordion">
						
						
						<div class="accordion" id="section1">1. How do I sign up for SportsJun?<span class="fa fa-plus"></span></div>
                      <div class="accordion-content">
                          <p>That’s an easy one. Just <a href="javascript:void(0);" data-toggle="modal" data-target="#home-register-modal" class="sj_linkweb">click here</a> and we’ll have you up and running in just a few second.</p>
                      </div>
					  
                      <div class="accordion" id="section2">2. How much does it cost?<span class="fa fa-plus"></span></div>
                      <div class="accordion-content">
                          <p>Most of the features on SportsJun is, and always will be, <strong>100% free</strong>. Few Premium features will be charged in the future.</p>
                      </div>
                   
                      <div class="accordion" id="section3">3. How do I edit my profile’s (User and Sports)?<span class="fa fa-plus"></span></div>
                      <div class="accordion-content">
                          <p>Click the User Image on top right corner, and click on a sub menu named User Profile or Sports Profile. From here, you can change user profile information or Sports profile (selection of sports and skills associated to sports).</p>
                      </div>
                      
					<div class="accordion" id="section4">4. How do I change my email settings?<span class="fa fa-plus"></span></div>
                      <div class="accordion-content">
                          <p>Click the home tab, and in your left sidebar you’ll find a link for <strong>Account Settings</strong>. Click the small arrow next to that link, and you’ll see an option for <strong>Email Preferences</strong>, which is where you can control all of the messages that you would like to receive or not receive. Keep in mind, all messages will still be delivered to your SportsJun inbox. These controls simply allow you to stop receiving emails to your associated email account.</p>
                      </div>
                      
					<div class="accordion" id="section5">5. I forgot my login information. Can you send it to me?<span class="fa fa-plus"></span></div>
                      <div class="accordion-content">
                          <p>Sure. <a href="contact-us.html" class="sj_linkweb">Send us a message</a> with your full name and the email address you used when you signed up. Keeping privacy at utmost importance SportsJun can ask more information before releasing the information.</p>
                      </div>
                      
					<div class="accordion" id="section6">6. How can I delete my account?<span class="fa fa-plus"></span></div>
                      <div class="accordion-content">
                          <p>Click the home tab, and from the left sidebar choose <a class="sj_linkweb">Account Settings</a>. At the bottom of the screen you will see an option to deactivate your account.</p>
                      </div>

                    
                  </div>
                  
				<h4>Friends and Players List</h4>
                
                <div class="kd-accordion">
						
						
						<div class="accordion" id="section1">1. How do I invite my friends and teammates to join SportsJun?<span class="fa fa-plus"></span></div>
                      <div class="accordion-content">
                          <p>You can invite your friends to join SportsJun <a class="sj_linkweb">here</a>. We have tools for you to invite by individual email addresses, or import your contacts from Facebook, Gmail, LinkedIn.</p>
                      </div>
					  
                      <div class="accordion" id="section2">2. How do I find out if my friends are already on SportsJun?<span class="fa fa-plus"></span></div>
                      <div class="accordion-content">
                          <p>Simple… search with email for Player option.</p>
                      </div>
                   
                      <div class="accordion" id="section3">3. What if my invite email are going to SPAM folders?<span class="fa fa-plus"></span></div>
                      <div class="accordion-content">
                          <p>It’s possible that your friends and teammates are receiving the invitation emails, but they are being filtered into their SPAM folders. To help prevent this, your friends and teammates should add the SportsJun admin email address (admin@SportsJun.com) into their email address book. This will indicate to their mail system that the sending account is OK and that this message is not SPAM.</p>
                      </div>

                    
                  </div>
                  
                  
              </div>
			  

            </div>
          </div>
        </section>

      </div>
      <!--// Main Content //-->
@endsection