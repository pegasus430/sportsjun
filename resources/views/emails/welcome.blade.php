{!! $view_data['header'] !!}
Thanks for joining SportsJun.com, you have successfully completed your registration.<br/>
Before you can start you'll need to activate your account. To activate your account click on the below link:<br/>
<a href="{!! url('/confirmemail', ['code'=>$view_data['verification_key']]) !!}">Activate My Account</a>
{!! $view_data['footer'] !!}