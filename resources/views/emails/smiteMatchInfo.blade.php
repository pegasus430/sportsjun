{!! $view_data['header'] !!}
Hi! {{ $view_data['name'] }}<br>
Your match has just started! <br>

<b>{{ $view_data['first_participant'] }}</b> vs. <b>{{ $view_data['second_participant'] }}</b><br>
<b>{{ $view_data['first_participant'] }} </b>has to create a private lobby.<br>

Please use following credentials for lobby name and password:<br>
<b>Lobby name:</b> {{ $view_data['lobby_name']  }}<br>
<b>Password:</b> {{ $view_data['lobby_password']  }}<br>

Good luck and have fun!<br>

Cheers!<br>
{!! $view_data['footer'] !!}
