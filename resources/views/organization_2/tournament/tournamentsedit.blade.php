@extends('layouts.organisation')

@section('content')


        <div class="container">
            <div class="row">
                <div class="col-sm-4 bg-white pdtb-30">
                    <h5 class="text-center"><b>Steps to create tournament</b></h5>
                    <ol class="steps-list text-left">
                        <li>Firstly, fill up the <strong>Tournament Details</strong> section.</li>
                        <li><strong>Click Update</strong></li>
                        <li>Navigate to <strong>Tournament Events</strong> tab.</li>
                        <li><strong>Add</strong> single/multiple tournament events to a single tournament.</li>
                        <li><strong>Teams / Players</strong> will respond to your request to join.</li>
                        <li><strong>Accept / Reject</strong> to enroll the Teams / Players into the tournament.</li>
                    </ol>
                </div>
                <div class="col-sm-8">
                    <div class="wg wg-dk-grey no-shadow no-margin">
                        <div class="wg-wrap clearfix">
                            <h4 class="no-margin pull-left"><i class="fa fa-pencil-square"></i> Edit Tournament / League Details</h4></div>
                    </div>
                    <div class="wg no-margin tabbable-panel create_tab_form">
                        <div class="tabbable-line">
                            <ul class="nav nav-tabs ">
                                <li class="active"> <a href="#tournament_details" data-toggle="tab">
								TOURNAMENT DETAILS </a> </li>
                                <li class=""> <a href="#tournament_events" data-toggle="tab" aria-expanded="false">
								TOURNAMENT EVENTS </a> </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tournament_details">
                                    <br>
                                    <form action="/tournaments/{{$tournament->id}}/update" class="form create-form clearfix" method="post" enctype="multipart/form-data">
                                        <div class="input-container one-col">
                                            <input type="text" id="tournament_name" required="required" name="name" value="{{$tournament->name}}">
                                            <label for="tournament_name">Tournament Name <span class="req">&#42;</span></label>
                                            <div class="bar"></div>
                                        </div>
                                        {!! csrf_field() !!}
                                        <div class="input-container one-col file nomgbtm">
                                            <label>Group Logo</label>
                                            <input type="file" id="staff_email"  name="logo"> </div>
                                        
                                        <div class="input-container two-col">
                                            <input type="text" id="contact_number" required="required" name="contact_number" value="{{$tournament->contact_number}}">
                                            <label for="contact_number">Contact Number <span class="req">&#42;</span></label>
                                            <div class="bar"></div>
                                     </div>
                                        <input type="hidden" name="organization_id" value="{{$organisation->id}}">
                                        <input type="hidden" name="isParent" value="yes">
                                        <input type="hidden" name="from_organization" value="yes">

                                         <div class="input-container two-col">
                                            <input type="text" id="alternate_number" name="alternate_contact_number" value="{{$tournament->alternate_contact_number}}">
                                            <label for="alternate_number">Alternate Number</label>
                                            <div class="bar"></div>
                                        </div>
                                        <div class="input-container two-col select">
                                           
                                            <label for="manager_name">Manager Name <span class="req">&#42;</span></label>
                                            <select name="manager_id">
                                            	@foreach($organisation->staff as $staff)
                                            		<option value="{{$staff->id}}" {{$staff->id==$tournament->manager_id?'selected':''}}> {{$staff->name}}</option>
                                            	@endforeach
                                            </select>
                                            <div class="bar"></div>
                                        </div>
                                        <div class="input-container two-col">
                                            <input type="text" id="email" required="required" name="email" value="{{$tournament->email}}">
                                            <label for="email">Email <span class="req">&#42;</span></label>
                                            <div class="bar"></div>
                                        </div>
                                        <div class="input-container select one-col">
                                            <label>Description</label>
                                            <div>
                                                <textarea class="textarea" style="resize:none" rows="3" name="description" cols="50" maxlength="250" >{{$tournament->description}}</textarea>
                                                <div class="characterLeft"></div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="tournament_events">
                                 
                                  <a href="#" data-toggle="modal" data-target="#subtournament" class="add-tour"><i class="fa fa-plus"></i> Add Tournament Event</a>

                                    @if(!empty($subTournamentArray) && count($subTournamentArray)>0)
                                        @include('organization_2.tournament.viewsubtournaments')

                                   
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('tournaments.create')  
@stop