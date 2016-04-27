{!! $view_data['header'] !!}
Hi {{ $view_data['user_name'] or 'User' }} <br>
Here's some great news! You've been added as a user on SportsJun!<br>
Please <a href="{!! url('auth/login') !!}"> click here</a> to login with the credentials given below:<br>
<b>Email:</b> {{ $view_data['email']  }}<br>
<b>Password:</b> {{ $view_data['password']  }}<br>
We welcome you to the SportsJun Online Fraternity!<br>

Cheers!<br>
{!! $view_data['footer'] !!}






