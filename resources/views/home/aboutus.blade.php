@extends('home.layout')

@section('content')
        <div class="kode-subheader subheader-height">
                <div class="container">
                  <div class="row">
                    <div class="col-md-6">
                      <h1>About SportsJun</h1>
                    </div>
                    <div class="col-md-6">
                      <ul class="kode-breadcrumb">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="<?php echo Request::url(); ?>">About SportsJun</a></li>
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
            <div class="row">

                <div class="col-md-12">
                  <div class="kode-editor">
                      <div>
                      <h2>SportsJun History</h2>
                      <p class="about_content">
                      	SportsJun evolved after looking into amateur talent which never gets published or captured in any of the platforms for future reference. SportsJun wants to create a difference in lives of individuals by giving much needed platform to create a Sports profile which can be linked to any number of sports. In addition to Sports Profile, platform gives ability to organize and manage teams and sports. It&#39;s a tool for building performance stats of players and teams by organizing sports teams online, for planning games and events, and for managing and promoting leagues. It&#39;s for people who are passionate about playing sports and staying active. With busy lives of parents, coaches, owners and other individuals who cannot attend all games SportsJun plans to connect with real time performance tracking system to track the performance of near and dear’s right from their work places. Individuals, Schools, Colleges, Academies, Corporates, Government organization all can use the platform.</p>
                      <p>We want to make a change in lives of all deserved Sports individuals.</p>
                      </div>
                      
                      <h2>Our Vision</h2>
                      <p>SportsJun's vision is to provide an integrated system to Sporting community to build the world’s biggest organized sporting community which would create value to every single service involved in sports.</p>
                      
                      <h2>Our Values</h2>
                      
                      <div class="row text-center">
                      
                            
					  <div class="col-md-12 col-sm-6 how_text text-left">
                                <div class="how_text_head"><h1>1</h1><h2>SportsJun believes in <br>Happiness/Passion</h2></div>
                                <div class="how_text_des"><p>At SportsJun, we believe that Passion for what we do and happiness in the workplace lead to more productive employees, fewer workplace issues, and a higher quality of work. </p></div>
                      </div>
                      
                      <div class="col-md-12 col-sm-6 how_text text-left">
                                <div class="how_text_head"><h1>2</h1><h2>SportsJun believes in <br>Innovation/Creativity</h2></div>
                                <div class="how_text_des"><p>SportsJun encourages Innovation within organization.</p></div>
                      </div>
                      
                      <div class="col-md-12 col-sm-6 how_text text-left" style="">
                                <div class="how_text_head"><h1>3</h1><h2>SportsJun believes in <br>Endurance Race not a Sprint</h2></div>
                                <div class="how_text_des"><p>Strive to maintain a balanced life because you are running a marathon when building a company. Learn something new everyday. Believe in long and sustainable growth. Remember Tortoise and Rabbit race always.</p></div>
                      </div>
                            
                      
                      </div>
                      
                    </div>
                  </div>

              </div>
            </div>
        </section>
        <!--// Page Content //--> 
        </div>
        <div class="kd-divider divider4"><span></span></div>
@endsection