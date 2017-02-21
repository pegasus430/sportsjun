@extends('layouts.organisation')

@section('content')

  <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Organization Details</h2></div>
        </div>
        <div class="row">
            <div class="col-md-offset-2 col-md-8 bg-grey">
                <table class="table">
                    <tbody>
                        <tr>
                            <th>Organization Name</th>
                            <td contenteditable="true" model='organisation' field='name'>{{$organisation->name}}</td>
                        </tr>
                        <tr>
                            <th>Contact Number</th>
                            <td contenteditable="true" model='organisation' field='contact_number'>{{$organisation->contact_number}}</td>
                        </tr>
                        <tr>
                            <th>POC (Point of Contact) Name</th>
                            <td contenteditable="true" model='organisation' field='contact_name'> {{$organisation->contact_name}}</td>
                        </tr>
                        <tr>
                            <th>E-Mail Address</th>
                            <td contenteditable="true" model='organisation' field='email'>{{$organisation->email}}</td>
                        </tr>
                        <tr>
                            <th>Organization Type</th>
                            <td contenteditable="true" model='organisation' field='organization_type'>{{$organisation->organization_type}}</td>
                        </tr>
                        <tr>
                            <th>Facebook</th>
                            <td contenteditable="true" model='organisation' field='social_facebook'>{{$organisation->social_facebook}}</td>
                        </tr>
                        <tr>
                            <th>Website URL</th>
                            <td contenteditable="true" model='organisation' field='website_url'> {{$organisation->website_url}}</td>
                        </tr>
                        <tr>
                            <th>About</th>
                            <td contenteditable="true" model='organisation' field='about'>{{$organisation->about}}</td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td contenteditable="true" model='organisation' field='location'>{{$organisation->location}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@stop


@section('end_scripts')
	<script type="text/javascript">
		$('.li_info').addClass('active');
	</script>

@stop