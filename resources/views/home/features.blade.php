@extends('home.layout')

@section('content')
      <div class="kode-subheader subheader-height">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <h1>SPORTSJUN FEATURES</h1>
            </div>
            <div class="col-md-6">
              <ul class="kode-breadcrumb">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="<?php echo Request::url(); ?>">Features</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <!--// SubHeader //-->

      <!--// Main Content //-->
      <div class="kode-content" style="padding-top:0;  position:relative;">
      
      <section class="features_bg_greay">
      			<div class="container">
                	<div class="row">
                    		<div class="col-md-7"><img class="feature-img" src="/images/home/features/player.png" ></div>
                            <div class="col-md-5 features_txt_dis_new"><h2>Player</h2><p>Build individual sports profile (add sports, pictures, specialisation) to join and create teams. One profile for all your sporting activities. Carry your sporting profile wherever you travel.</p>
                            <ul>
                                <li>Connect with teams</li>
                                <li>Connect with coaches</li>
                                <li>Build your performance statistics</li>
                                <li>Greater visibility to sponsors</li>
                                <li>Search & book facilities right within SJ</li>
                                <li>Search & participate in tournaments</li>
                                <li>Schedule matches with right talent</li>
                                <li>Connect with SJ community to buy/sell old/new equipment</li>
                                <li>Ratings feature to maintain quality of marketplace transactions in SJ</li>
                            </ul>
                            </div>
                    </div>
                </div>
      </section>
      <section class="features_bg_whitee">
      			<div class="container">
                	<div class="row">
                    		<div class="col-md-7"><img class="feature-img" src="/images/home/features/teams.png" ></div>
                            <div class="col-md-5 features_txt_dis_new"><h2>Teams</h2><p>Create Teams and assign players,managers and admins to manage great teams, stay connected with teams in a snap. Schedule your matches and update your scores, build team performance statistics as well as individual performance statistics</p>
                            <ul>
                                <li>Create teams</li>
                                <li>Invite players</li>
                                <li>Schedule matches with right talented teams right with the system</li>
                                <li>Scoring system for 9 sports (<strong>Cricket, Soccer, Hockey, Tennis, Table Tennis, Badminton, Squash, Basketball, Volleyball</strong> and many more to come.)</li>
                                <li>Build  player statistics and team performance statistics automatically</li> 
                                <li>Search and book facilities for your matches</li>
                                <li>search and connect with coaches</li>
                                <li>Get followers</li>
                                <li>Share gallery, statistics and other important information with other networking sites.</li>
                                
                            </ul>
                            </div>
                    </div>
                </div>
      </section>
      <section class="features_bg_greay">
      			<div class="container">
                	<div class="row">
                    		<div class="col-md-7"><img class="feature-img" src="/images/home/features/organization.png" ></div>
                            <div class="col-md-5 features_txt_dis_new"><h2>Organization Structure</h2><p>Create organization and assign all teams part of the organization for easier management.</p>
                            <ul>
                                <li>Create your organization (Schools, Colleges, Academies, Institutions etc) and track all your sports teams under one roof.</li>
                                <li>Hassle free communication with all the teams</li>
                                <li>Appoint managers and coaches to each team.</li>
                                <li>Organize and manage internal and external sports events with ease.</li>
                                <li>Group role up points for multi sports event.</li>
                            </ul>
                            </div>
                    </div>
                </div>
      </section>
      <section class="features_bg_whitee">
      			<div class="container">
                	<div class="row">
                    		<div class="col-md-7"><img class="feature-img" src="/images/home/features/online_scoring.png" ></div>
                            <div class="col-md-5 features_txt_dis_new"><h2>Online Scoring</h2><p>Use scoring system to enter real time and offline scoring (<strong>Cricket, Soccer, Hockey, Tennis, Table Tennis, Badminton, Squash, Basketball, Volleyball</strong> and many more to come) to build your automatic statistics.</p>
                            <ul>
                                <li>Real time online scoring that can be accessed anywhere through web/mobile</li>
                                <li>Generate both team and personal stats.</li>
                                <li>Use our mobile responsive web app to capture the scores to generate stats.</li>
                            </ul>
                            </div>
                    </div>
                </div>
      </section>
      
      
            <section class="features_bg_greay">
      			<div class="container">
                	<div class="row">
                    		<div class="col-md-7"><img class="feature-img" src="/images/home/features/tournaments.png" ></div>
                            <div class="col-md-5 features_txt_dis_new"><h2>League and Tournaments</h2><p>Super easy league builder for organizers. Manage all aspects of league and tournament scheduling and provide real-time schedules, registration, stats and standings on SportsJun website.</p>
                            <ul>
                                <li>Tournament Registration</li>
                                <li>Easy access to teams participating in leagues and tournaments</li>
                                <li>Game Scheduling</li>
                                <li>Tournament Communication</li>
                                <li>Update Scores</li>
                                <li>Share scores & points table in social networking platforms</li>
                            </ul>
                            
                            <table class="kd-table kd-tabletwo">
                    <thead>
                      <tr>
                        <th>Leagues (Group Tourn)</th>
                        <th>Knock-Outs (Bracket Sys)</th>
                        <th>Group + Knock-Out</th></tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Invite Teams</td>
                        <td>Elegant bracket design</td>
                        <td>Create Groups and play knock-outs directly, to complete the tournament sooner.</td>
                      </tr>
                      <tr>
                        <td>Create your leagues</td>
                        <td>Create Knock-out matches without leagues, if needed</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td>Schedule Group matches</td>
                        <td></td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                            
                            </div>
                    </div>
                </div>
      </section>
      
            <section class="features_bg_whitee">
      			<div class="container">
                	<div class="row">
                    		<div class="col-md-7"><img class="feature-img" src="/images/home/features/gallery.png" ></div>
                            <div class="col-md-5 features_txt_dis_new"><h2>Gallery</h2><p>Create albums and post your personal sporting pictures and team events in a snap. Share your amazing sporting gallery memories to the world.</p>
                            <ul>
                                <li>Create album and attach pictures to the album to create event specific memory</li>
                                <li>Post pictures of your recent game and upload right to the specific event.</li>
                                <li>The gallery allows you to store and show pictures.</li>
                                <li>Share pictures with the other networking sites.</li>
                            </ul>
                            </div>
                    </div>
                </div>
      </section>
      
      
                  <section class="features_bg_greay">
      			<div class="container">
                	<div class="row">
                    		<div class="col-md-7"><img class="feature-img" src="/images/home/features/marketplace.png" ></div>
                            <div class="col-md-5 features_txt_dis_new"><h2>Market Place</h2><p>Connect with sporting community to buy and sell equipment.</p>
                            <ul>
<li>Have loads of equipment catching dust – No worries – post an ad in marketplace – users connect with you – Negotiate on a price and sell</li>
<li>Connect with sporting community to buy and sell equipment</li>
<li>Location based search and filters to connect with right sellers and buyers.</li>
<li>Sell your used equipment to the sporting community.</li>
<li>Great place to donate your used sporting equipment to needy sports enthusiastic.</li>

</ul>
                            
                            </div>
                    </div>
                </div>
      </section>
@endsection