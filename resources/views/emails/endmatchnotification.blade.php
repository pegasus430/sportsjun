
{!! $view_data['header'] !!}
Hi {{ $view_data['user_name'] or 'User' }} <br>
Your {{$view_data['match_type']}} game played on {{date('jS F , Y',strtotime($view_data['match_date']))}} between <b>{{$view_data['team_a_name']}}</b> vs <b>{{$view_data['team_b_name']}}</b> score has been updated and is available to view<br>

<br>
ScoreCard Link :  <a href="{!! url('/matchpublic/scorecard/view/'.$view_data['match_id']) !!}"> click here</a>
<br><br>
Personal Stats View : <a href="{!! url('/matchpublic/scorecard/view/'.$view_data['match_id']) !!}">Personal stats</a>
<br>

<br>
You can also view other team scores, team stats and personal stats of players by <a href="http://www.sportsjun.com">loggin to Sportsjun</a>


Regards!<br>
{!! $view_data['footer'] !!}






