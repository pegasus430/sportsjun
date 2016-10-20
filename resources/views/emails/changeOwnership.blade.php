<?php
$user = $view_data['user'];
$team = $view_data['team'];
?>
{!! $view_data['header'] !!}
<h1>Hi {{$user->name}}</h1>
<p>Congratulation for becoming new owner of {{ $team->name }}.
    You can now start inviting / Adding new players into your team using Add Player / Invite Player feature in Team
    management.</p>
<p>Please <a href="{{ url('/') }}">click here</a> to login with the credentials given below:</p>
<p><b>Email</b>: {{ $user->email }}</p>

<p>Cheers!</p>
{!! $view_data['footer'] !!}