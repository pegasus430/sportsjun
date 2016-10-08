
{!! $view_data['header'] !!}
Hi {{ $view_data['user_name'] or 'User' }} <br>
Here's some great news!<b> {{ $view_data['organization_name'] }} </b>has added you to SportsJun! as {{ $view_data['role_name'] }} in their organization.<br>
Please <a href="{!! url('?open_popup=login') !!}"> click here</a> to login with the credentials given below:<br>
<b>Email:</b> {{ $view_data['email']  }}<br>
<b>Password:</b> {{ $view_data['password']  }}<br>
We welcome you to the SportsJun Online Fraternity!<br>

Cheers!<br>
{!! $view_data['footer'] !!}






