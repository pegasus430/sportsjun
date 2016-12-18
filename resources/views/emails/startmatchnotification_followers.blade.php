
{!! $view_data['header'] !!}
Hi {{ $view_data['user_name'] or 'User' }} <br>
<br>
The {{$view_data['sports_name']}} match played on {{date('jS F , Y',strtotime($view_data['match_date']))}} between <b>{{$view_data['team_a_name']}}</b> vs <b>{{$view_data['team_b_name']}}</b> score  will start soon!<br>

<br>
ScoreCard Link :  <a href="{!! url('/matchpublic/scorecard/view/'.$view_data['match_id']) !!}"> click here</a>
<br><br>

<br>

<br>
You can also view other team scores, team stats and personal stats of players by <a href="http://www.sportsjun.com">login to Sportsjun</a>


Regards!<br>
{!! $view_data['footer'] !!}






