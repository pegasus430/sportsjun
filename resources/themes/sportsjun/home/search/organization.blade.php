@extends('home.layout')

@section('content')
    <div class="profileWrapp">

        <div class="container">
            <h1>MY PROFILE</h1>

            <div class="profileDetaiWrap">

                <div class="profile_flex">

                    <div class="followingBox">

                        <h2>Following</h2>
                        <h3>0289</h3>

                    </div>

                    <div class="profiledetailWrapp">

                        <div class="profileBox"><img src="images/profille2.png"></div>

                        <h2>TYRION LANNISTER</h2>
                        <h4>BANGALORE,KARNATAKA, INDIA</h4>



                    </div>



                    <div class="followingBox">

                        <h2>Followers</h2>
                        <h3>6589</h3>

                    </div>

                </div>

                <div class="playlist">

                    <div class="playBox">Cricket</div>
                    <div class="playBox">Soccer</div>
                    <div class="playBox">Tennis</div>
                    <div class="playBox">Swimmming</div>
                    <div class="playBox">Badminton</div>



                </div>


            </div>

        </div>

        <div class="SportPlayerWrap">


            <div class="leftBG col-lg-2 col-md-2 cricketbg">
                <div class="hedcric">
                    <img src="images/ico_cric.png">
                    <h3>CRICKET</h3>

                </div>

            </div>

            <div class="spoertSkillLeft col-lg-4 col-md-4">
                <h2>Sport Skill</h2>
                <div class="skill_listing">
                    <div class="pleft"><h3>Batting</h3></div>
                    <div class="pright"><h4>Right</h4></div>
                </div>
                <div class="skill_listing">
                    <div class="pleft"><h3>Bowling</h3></div>
                    <div class="pright"><h4>Right</h4></div>
                </div>
                <div class="skill_listing">
                    <div class="pleft"><h3>Player Ability</h3></div>
                    <div class="pright"><h4>All Rounder</h4></div>
                </div>
                <div class="skill_listing">
                    <div class="pleft"><h3>Bowling Style</h3></div>
                    <div class="pright"><h4>Fast Medium</h4></div>
                </div>
                <div class="skill_listing">
                    <div class="pleft"><h3>Batting Ability</h3></div>
                    <div class="pright"><h4>Middle Order</h4></div>
                </div>



            </div>

            <div class="spoertRight col-lg-6 col-md-6">
                <h2>Player Sport Status</h2>
                <h5>Batting</h5>


                <table width="100%" border="1" style="">
                    <tr class="headingtable">
                        <td>MATCH TYPE	</td>
                        <td>MT</td>
                        <td>IN</td>
                        <td>NOT OUT</td>
                        <td>TOTAL  RUNS</td>
                        <td>50's</td>
                        <td>100's</td>
                        <td>4's</td>
                        <td>6's</td>
                        <td>AVG</td>
                        <td>H.S</td>
                        <td>S.R</td>
                    </tr>
                    <tr class="play_details">
                        <td>T20</td>
                        <td>20</td>
                        <td>18</td>
                        <td>10</td>
                        <td>1500</td>
                        <td>5</td>
                        <td>3</td>
                        <td>56</td>
                        <td>15</td>
                        <td>75</td>
                        <td>125*</td>
                        <td>55.8</td>
                    </tr>

                    <tr class="play_details">
                        <td>ODI</td>
                        <td>20</td>
                        <td>18</td>
                        <td>10</td>
                        <td>1500</td>
                        <td>5</td>
                        <td>3</td>
                        <td>56</td>
                        <td>15</td>
                        <td>75</td>
                        <td>125*</td>
                        <td>55.8</td>
                    </tr>


                </table>

                <h5>BOWLING</h5>

                <table width="100%" border="1" style="">
                    <tr class="headingtable">
                        <td>MATCH TYPE	</td>
                        <td>MT</td>
                        <td>IN</td>
                        <td>NOT OUT</td>
                        <td>TOTAL  RUNS</td>
                        <td>50's</td>
                        <td>100's</td>
                        <td>4's</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="play_details">
                        <td>T20</td>
                        <td>20</td>
                        <td>18</td>
                        <td>10</td>
                        <td>1500</td>
                        <td>5</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                </table>




            </div>

        </div>


        <div class="SportPlayerWrap">


            <div class="leftBG col-lg-2 col-md-2 soccer">
                <div class="hedcric">
                    <img src="images/ico_cric.png">
                    <h3>CRICKET</h3>

                </div>

            </div>

            <div class="spoertSkillLeft col-lg-4 col-md-4">
                <h2>Sport Skill</h2>
                <div class="skill_listing">
                    <div class="pleft"><h3>Batting</h3></div>
                    <div class="pright"><h4>Right</h4></div>
                </div>
                <div class="skill_listing">
                    <div class="pleft"><h3>Bowling</h3></div>
                    <div class="pright"><h4>Right</h4></div>
                </div>
                <div class="skill_listing">
                    <div class="pleft"><h3>Player Ability</h3></div>
                    <div class="pright"><h4>All Rounder</h4></div>
                </div>
                <div class="skill_listing">
                    <div class="pleft"><h3>Bowling Style</h3></div>
                    <div class="pright"><h4>Fast Medium</h4></div>
                </div>
                <div class="skill_listing">
                    <div class="pleft"><h3>Batting Ability</h3></div>
                    <div class="pright"><h4>Middle Order</h4></div>
                </div>



            </div>

            <div class="spoertRight col-lg-6 col-md-6">
                <h2>Player Sport Status</h2>
                <h5>Batting</h5>


                <table width="100%" border="1" style="">
                    <tr class="headingtable">
                        <td>MATCH TYPE	</td>
                        <td>MT</td>
                        <td>IN</td>
                        <td>NOT OUT</td>
                        <td>TOTAL  RUNS</td>
                        <td>50's</td>
                        <td>100's</td>
                        <td>4's</td>
                        <td>6's</td>
                        <td>AVG</td>
                        <td>H.S</td>
                        <td>S.R</td>
                    </tr>
                    <tr class="play_details">
                        <td>T20</td>
                        <td>20</td>
                        <td>18</td>
                        <td>10</td>
                        <td>1500</td>
                        <td>5</td>
                        <td>3</td>
                        <td>56</td>
                        <td>15</td>
                        <td>75</td>
                        <td>125*</td>
                        <td>55.8</td>
                    </tr>

                    <tr class="play_details">
                        <td>ODI</td>
                        <td>20</td>
                        <td>18</td>
                        <td>10</td>
                        <td>1500</td>
                        <td>5</td>
                        <td>3</td>
                        <td>56</td>
                        <td>15</td>
                        <td>75</td>
                        <td>125*</td>
                        <td>55.8</td>
                    </tr>


                </table>

                <h5>BOWLING</h5>

                <table width="100%" border="1" style="">
                    <tr class="headingtable">
                        <td>MATCH TYPE	</td>
                        <td>MT</td>
                        <td>IN</td>
                        <td>NOT OUT</td>
                        <td>TOTAL  RUNS</td>
                        <td>50's</td>
                        <td>100's</td>
                        <td>4's</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="play_details">
                        <td>T20</td>
                        <td>20</td>
                        <td>18</td>
                        <td>10</td>
                        <td>1500</td>
                        <td>5</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                </table>




            </div>

        </div>

        <div class="SportPlayerWrap">


            <div class="leftBG col-lg-2 col-md-2 swimming">
                <div class="hedcric">
                    <img src="images/ico_cric.png">
                    <h3>CRICKET</h3>

                </div>

            </div>

            <div class="spoertSkillLeft col-lg-4 col-md-4">
                <h2>Sport Skill</h2>
                <div class="skill_listing">
                    <div class="pleft"><h3>Batting</h3></div>
                    <div class="pright"><h4>Right</h4></div>
                </div>
                <div class="skill_listing">
                    <div class="pleft"><h3>Bowling</h3></div>
                    <div class="pright"><h4>Right</h4></div>
                </div>
                <div class="skill_listing">
                    <div class="pleft"><h3>Player Ability</h3></div>
                    <div class="pright"><h4>All Rounder</h4></div>
                </div>
                <div class="skill_listing">
                    <div class="pleft"><h3>Bowling Style</h3></div>
                    <div class="pright"><h4>Fast Medium</h4></div>
                </div>
                <div class="skill_listing">
                    <div class="pleft"><h3>Batting Ability</h3></div>
                    <div class="pright"><h4>Middle Order</h4></div>
                </div>



            </div>

            <div class="spoertRight col-lg-6 col-md-6">
                <h2>Player Sport Status</h2>
                <h5>Batting</h5>


                <table width="100%" border="1" style="">
                    <tr class="headingtable">
                        <td>MATCH TYPE	</td>
                        <td>MT</td>
                        <td>IN</td>
                        <td>NOT OUT</td>
                        <td>TOTAL  RUNS</td>
                        <td>50's</td>
                        <td>100's</td>
                        <td>4's</td>
                        <td>6's</td>
                        <td>AVG</td>
                        <td>H.S</td>
                        <td>S.R</td>
                    </tr>
                    <tr class="play_details">
                        <td>T20</td>
                        <td>20</td>
                        <td>18</td>
                        <td>10</td>
                        <td>1500</td>
                        <td>5</td>
                        <td>3</td>
                        <td>56</td>
                        <td>15</td>
                        <td>75</td>
                        <td>125*</td>
                        <td>55.8</td>
                    </tr>

                    <tr class="play_details">
                        <td>ODI</td>
                        <td>20</td>
                        <td>18</td>
                        <td>10</td>
                        <td>1500</td>
                        <td>5</td>
                        <td>3</td>
                        <td>56</td>
                        <td>15</td>
                        <td>75</td>
                        <td>125*</td>
                        <td>55.8</td>
                    </tr>


                </table>

                <h5>BOWLING</h5>

                <table width="100%" border="1" style="">
                    <tr class="headingtable">
                        <td>MATCH TYPE	</td>
                        <td>MT</td>
                        <td>IN</td>
                        <td>NOT OUT</td>
                        <td>TOTAL  RUNS</td>
                        <td>50's</td>
                        <td>100's</td>
                        <td>4's</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="play_details">
                        <td>T20</td>
                        <td>20</td>
                        <td>18</td>
                        <td>10</td>
                        <td>1500</td>
                        <td>5</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                </table>




            </div>

        </div>

        <div class="SportPlayerWrap">


            <div class="leftBG col-lg-2 col-md-2 badminton">
                <div class="hedcric">
                    <img src="images/ico_cric.png">
                    <h3>CRICKET</h3>

                </div>

            </div>

            <div class="spoertSkillLeft col-lg-4 col-md-4">
                <h2>Sport Skill</h2>
                <div class="skill_listing">
                    <div class="pleft"><h3>Batting</h3></div>
                    <div class="pright"><h4>Right</h4></div>
                </div>
                <div class="skill_listing">
                    <div class="pleft"><h3>Bowling</h3></div>
                    <div class="pright"><h4>Right</h4></div>
                </div>
                <div class="skill_listing">
                    <div class="pleft"><h3>Player Ability</h3></div>
                    <div class="pright"><h4>All Rounder</h4></div>
                </div>
                <div class="skill_listing">
                    <div class="pleft"><h3>Bowling Style</h3></div>
                    <div class="pright"><h4>Fast Medium</h4></div>
                </div>
                <div class="skill_listing">
                    <div class="pleft"><h3>Batting Ability</h3></div>
                    <div class="pright"><h4>Middle Order</h4></div>
                </div>



            </div>

            <div class="spoertRight col-lg-6 col-md-6">
                <h2>Player Sport Status</h2>
                <h5>Batting</h5>


                <table width="100%" border="1" style="">
                    <tr class="headingtable">
                        <td>MATCH TYPE	</td>
                        <td>MT</td>
                        <td>IN</td>
                        <td>NOT OUT</td>
                        <td>TOTAL  RUNS</td>
                        <td>50's</td>
                        <td>100's</td>
                        <td>4's</td>
                        <td>6's</td>
                        <td>AVG</td>
                        <td>H.S</td>
                        <td>S.R</td>
                    </tr>
                    <tr class="play_details">
                        <td>T20</td>
                        <td>20</td>
                        <td>18</td>
                        <td>10</td>
                        <td>1500</td>
                        <td>5</td>
                        <td>3</td>
                        <td>56</td>
                        <td>15</td>
                        <td>75</td>
                        <td>125*</td>
                        <td>55.8</td>
                    </tr>

                    <tr class="play_details">
                        <td>ODI</td>
                        <td>20</td>
                        <td>18</td>
                        <td>10</td>
                        <td>1500</td>
                        <td>5</td>
                        <td>3</td>
                        <td>56</td>
                        <td>15</td>
                        <td>75</td>
                        <td>125*</td>
                        <td>55.8</td>
                    </tr>


                </table>

                <h5>BOWLING</h5>

                <table width="100%" border="1" style="">
                    <tr class="headingtable">
                        <td>MATCH TYPE	</td>
                        <td>MT</td>
                        <td>IN</td>
                        <td>NOT OUT</td>
                        <td>TOTAL  RUNS</td>
                        <td>50's</td>
                        <td>100's</td>
                        <td>4's</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="play_details">
                        <td>T20</td>
                        <td>20</td>
                        <td>18</td>
                        <td>10</td>
                        <td>1500</td>
                        <td>5</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                </table>




            </div>

        </div>

    </div>
@endsection