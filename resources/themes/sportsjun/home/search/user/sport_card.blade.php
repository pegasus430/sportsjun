<div class="SportPlayerWrap">
    <div class="leftBG col-lg-2 col-md-2 cricketbg">
        <div class="hedcric">
            <img src="images/ico_cric.png">
            <h3>{{$sport->sport_name}}</h3>
        </div>
    </div>
    <?php
    $skillset = [
            'Batting' => '',
            'Bowling' => '',
            'Player Ability' => '',
            'Bowling Style' => '',
            'Batting Ability' => ''
    ];
    ?>

    <div class="spoertSkillLeft col-lg-4 col-md-4">
        <h2>Sport Skill</h2>
        @foreach ($skillset as $key=>$value)
            <div class="skill_listing">
                <div class="pleft"><h3>{{$key}}</h3></div>
                <div class="pright"><h4>{{$value}}</h4></div>
            </div>
        @endforeach
    </div>

    <div class="spoertRight col-lg-6 col-md-6">
        <h2>Player Sport Status</h2>
        @foreach ($skillset as $skill=>$position)
            <h5>{{ $skill }}</h5>

            <table width="100%" border="1" style="">
                <tr class="headingtable">
                    <td>MATCH TYPE</td>
                    <td>MT</td>
                    <td>IN</td>
                    <td>NOT OUT</td>
                    <td>TOTAL RUNS</td>
                    <td>50's</td>
                    <td>100's</td>
                    <td>4's</td>
                    <td>6's</td>
                    <td>AVG</td>
                    <td>H.S</td>
                    <td>S.R</td>
                </tr>
                @if (isset($play_details))
                    @foreach ($play_details as $play_detail_data)
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
                    @endforeach
                @endif
            </table>
        @endforeach

    </div>

</div>