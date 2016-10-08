@extends('home.layout')

@section('content')
      <div class="kode-subheader subheader-height">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <h1>Sports</h1>
            </div>
            <div class="col-md-6">
              <ul class="kode-breadcrumb">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="<?php echo Request::url(); ?>">Sports</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <!--// SubHeader //-->

      <!--// Main Content //-->
      <div class="kode-content">

        <!--// Page Content //-->
        <section class="kode-pagesection margin-bottom-40">
          <div class="container">
            <div class="row text-center">

                <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/American-football.png') }}" height="60" width="60"></div>
                  <h4>American football</h4>
                </div>
                
                
				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/Badminton.png') }}" height="60" width="60" alt="Badminton"></div>
                  <h4>Badminton</h4>
                  <span class="scorecard_avila">Scorecard Available</span>
                </div>
                
				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/Basebal.png') }}" height="60" width="60" alt="Baseball"></div>
                  <h4>Baseball</h4>
                </div>                
                
				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/Basketball.png') }}" height="60" width="60" alt="Basketball"></div>
                  <h4>Basketball</h4>
                  <span class="scorecard_avila">Scorecard Available</span>
                </div>                
                
				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/boxing.png') }}" height="60" width="60" alt="Boxing"></div>
                  <h4>Boxing</h4>
                </div>
                
				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/cricket.png') }}" height="60" width="60" alt="Cricket"></div>
                  <h4>Cricket</h4>
                  <span class="scorecard_avila">Scorecard Available</span>
                </div>      
                
				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/Cycling.png') }}" height="60" width="60" alt="Cycling"></div>
                  <h4>Cycling</h4>
                </div> 
                
				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/Field-hockey.png') }}" height="60" width="60" alt="Field hockey"></div>
                  <h4>Field hockey</h4>
                  <span class="scorecard_avila">Scorecard Available</span>
                </div>
                
				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/Golf.png') }}" height="60" width="60" alt="Golf"></div>
                  <h4>Golf</h4>
                </div>
                
				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/Karate.png') }}" height="60" width="60" alt="Karate"></div>
                  <h4>Karate</h4>
                </div>
                
				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/Lacrosse.png') }}" height="60" width="60" alt="Lacrosse"></div>
                  <h4>Lacrosse</h4>
                </div>   
                
				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/Racquetball.png') }}" height="60" width="60" alt="Racquetball"></div>
                  <h4>Racquetball</h4>
                </div>  
                
				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/Rugby.png') }}" height="60" width="60" alt="Rugby"></div>
                  <h4>Rugby</h4>
                </div>  
                
				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/Running.png') }}" height="60" width="60" alt="Running"></div>
                  <h4>Running</h4>
                </div>   
                
				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/Soccer.png') }}" height="60" width="60" alt="Soccer"></div>
                  <h4>Soccer</h4>
					<span class="scorecard_avila">Scorecard Available</span>
                </div>   

				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/Squash.png') }}" height="60" width="60" alt="Squash"></div>
                  <h4>Squash</h4>
                  <span class="scorecard_avila">Scorecard Available</span>
                </div>   
                
				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/Swimming-icon.png') }}" height="60" width="60" alt="Swimming"></div>
                  <h4>Swimming</h4>
                </div> 
                
				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/Table-Tennis.png') }}" height="60" width="60" alt="Table Tennis"></div>
                  <h4>Table Tennis</h4>
				  <span class="scorecard_avila">Scorecard Available</span>
                </div>
                
				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/Tennis.png') }}" height="60" width="60" alt="Tennis"></div>
                  <h4>Tennis</h4>
				  <span class="scorecard_avila">Scorecard Available</span>
                </div>
                               
				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/Volleyball.png') }}" height="60" width="60" alt="Volleyball"></div>
                  <h4>Volleyball</h4>
                  <span class="scorecard_avila">Scorecard Available</span>
                </div>
                                
				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/Weightlifting.png') }}" height="60" width="60" alt="Weightlifting"></div>
                  <h4>Weightlifting</h4>
                </div>
                
				<div class="col-lg-2 col-md-3 col-sm-6 col-xs-6 sports_list">
                  <div class="sport_icon_bg"><img src="{{ asset('/home/extra-images/Wrestling.png') }}" height="60" width="60" alt="Wrestling"></div>
                  <h4>Wrestling</h4>
                </div>        

                                          
                
              </div>
            </div>
        </section>
        <!--// Page Content //-->

        <!--// Page Content //-->
        <!--<section class="kode-pagesection kode-team-info-bg padding-top-40 padding-bottom-30">
          <div class="container">
            <div class="row">

                <div class="col-md-12">
                  
				  <div class="heading heading-12 kode-white margin-top30-bottom-80">
					<p>Devoted to</p>
					<h2><span class="left"></span>team achievement<span class="right"></span></h2>
				</div>
                  <div class="kode-team-timeline">
                    <span class="timeline-circle"></span>
                    <ul>
                      <li>
                        <span class="kode-timezoon">2004</span>
                        <div class="timeline-inner">
                          <h2>intercontinental cup</h2>
                          <p>Voluptate illum dolore ita ipsum, quid deserunt singulis, labore admodum ita multos malis ea nam nam tamen fore amet. Vidisse quid incurreret ut ut possumus transferrem</p>
                        </div>
                      </li>
                      <li>
                        <div class="timeline-inner">
                          <h2>FEDERATION cup</h2>
                          <p>Voluptate illum dolore ita ipsum, quid deserunt singulis, labore admodum ita multos malis ea nam nam tamen fore amet. Vidisse quid incurreret ut ut possumus transferrem</p>
                        </div>
                      </li>
                      <li>
                        <span class="kode-timezoon">2005</span>
                        <div class="timeline-inner">
                          <h2>intercontinental cup</h2>
                          <p>Voluptate illum dolore ita ipsum, quid deserunt singulis, labore admodum ita multos malis ea nam nam tamen fore amet. Vidisse quid incurreret ut ut possumus transferrem</p>
                        </div>
                      </li>
                      <li>
                        <div class="timeline-inner">
                          <h2>FEDERATION cup</h2>
                          <p>Voluptate illum dolore ita ipsum, quid deserunt singulis, labore admodum ita multos malis ea nam nam tamen fore amet. Vidisse quid incurreret ut ut possumus transferrem</p>
                        </div>
                      </li>
                      <li>
                        <span class="kode-timezoon">2013</span>
                        <div class="timeline-inner">
                          <h2>intercontinental cup</h2>
                          <p>Voluptate illum dolore ita ipsum, quid deserunt singulis, labore admodum ita multos malis ea nam nam tamen fore amet. Vidisse quid incurreret ut ut possumus transferrem</p>
                        </div>
                      </li>
                      <li>
                        <div class="timeline-inner">
                          <h2>FEDERATION cup</h2>
                          <p>Voluptate illum dolore ita ipsum, quid deserunt singulis, labore admodum ita multos malis ea nam nam tamen fore amet. Vidisse quid incurreret ut ut possumus transferrem</p>
                        </div>
                      </li>
                    </ul>
                    <span class="timeline-circle bottom-circle"></span>
                  </div>
                </div>

              </div>
            </div>
        </section>-->
        </div>
@endsection