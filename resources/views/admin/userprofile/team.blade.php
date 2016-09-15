@extends('admin.layouts.app')
@section('content')

@include ('album._leftmenu')

<div class="col_middle">

	<div class="panel panel-default">
			<div class="panel-heading">
				Team Profile
			</div>
                        <!-- /.panel-heading -->
			<div class="panel-body">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs">
					<li class="active"><a href="#home" data-toggle="tab" aria-expanded="true">{{ trans('message.users.fields.managedteams') }} ( {{ count($manageTeamArray) }} )</a>
					</li>
					<li class=""><a href="#profile" data-toggle="tab" aria-expanded="false">{{ trans('message.users.fields.joinedteams') }} ( {{ count($joinTeamArray) }} )</a>
					</li>
					<li class=""><a href="#messages" data-toggle="tab" aria-expanded="false">{{ trans('message.users.fields.followingteam') }}  ( {{ count($followingTeamArray) }} )</a>
					</li>
				   
				</ul>
				<!-- Tab panes -->
				<div class="tab-content">
					<div class="tab-pane fade active in" id="home">
						<h4>{{ trans('message.users.fields.managedteams') }}</h4>
					   <table class="table">
						<tbody>
						<tr>
						<?php foreach($manageTeamArray as $managed_team){ ?> 
								<td>{{ $managed_team }}</td>
								
						<?php	} ?>
						</tr>	
							
							</tbody>
						</table>
					</div>
					<div class="tab-pane fade" id="profile">
						<h4>{{ trans('message.users.fields.joinedteams') }} </h4>
						<table class="table">
							<tbody>
							<tr>
							<?php foreach($joinTeamArray as $joined_team){ ?> 
									<td>{{ $joined_team }}</td>
									
						<?php	} ?>
							</tr>	
								
							</tbody>
						</table>
					   
					</div>
					<div class="tab-pane fade" id="messages">
						<h4>{{ trans('message.users.fields.followingteam') }}</h4>
						<table class="table">
							<tbody>
							<tr>
							<?php foreach($followingTeamArray as $following_team){ ?> 
									<td>{{ $following_team }}</td>
									
						<?php	} ?>
							</tr>	
								
								</tbody>
						</table>
					</div>
					
				</div>
			</div>
                        <!-- /.panel-body -->
	</div>
</div>
@endsection
