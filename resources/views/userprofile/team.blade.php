@extends('layouts.app')
@section('content')

@include ('album._leftmenu')

<div id="content-team" class="col-sm-10">
	<div class="col-sm-12">
	<h3 class="heading">Team Profile</h3>        
	<div class="cstmpanel-tabs">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs nav-justified">
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
                    	<div class="action-panel">
                            <!--<h4>{{ trans('message.users.fields.managedteams') }}</h4>-->
                           <div class="tags">
                            <?php foreach($manageTeamArray as $managed_team){ ?> 
                                    <a href="">{{ $managed_team }}</a>
                                    
                            <?php	} ?>
                            </div>
                        </div>
					</div>
					<div class="tab-pane fade" id="profile">
                    	<div class="action-panel">
						<!--<h4>{{ trans('message.users.fields.joinedteams') }} </h4>-->
                           <div class="tags">
							<?php foreach($joinTeamArray as $joined_team){ ?> 
									<a href="">{{ $joined_team }}</a>
									
						<?php	} ?>
                            </div>
                        </div>
					   
					</div>
					<div class="tab-pane fade" id="messages">
                    	<div class="action-panel">
						<!--<h4>{{ trans('message.users.fields.followingteam') }}</h4>-->
                           <div class="tags">
							<?php foreach($followingTeamArray as $following_team){ ?> 
									<a href="">{{ $following_team }}</a>
									
						<?php	} ?>
                            </div>
                        </div>
					   
					</div>
					
				</div>
			</div>
	</div>
</div>
@endsection
