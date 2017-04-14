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
                                <li class="active"> <a href="#tournament_detail" data-toggle="tab">
								TOURNAMENT DETAILS </a> </li>
                                <li class=""> <a href="#tournament_events" data-toggle="tab" aria-expanded="false">
								TOURNAMENT EVENTS </a> </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tournament_detail">
                                    <div class="sportsjun-forms">
                                {!! Form::open(['route' => 'tournaments.store','class'=>'form-horizontal','method' => 'POST','id' => 'parent-tournaments']) !!}   
                                   <div class="form-body">
                                         @include ('organization_2.tournament._parentform', ['submitButtonText' => 'Create'])
                                         <input type="hidden" name="isParent" id="isParent" value="yes">
                                </div>      
                                 <div class="form-footer">
                                  <button type="submit" class="button btn-primary">Create</button>

                                </div>              
                                    {!! Form::close() !!}
                                    {!! JsValidator::formRequest('App\Http\Requests\CreateTournamentRequest', '#parent-tournaments'); !!}
                                </div>

                                </div>
                                <div class="tab-pane" id="tournament_events">
                                    <div class="text-center">Firsly, add tournament details, and then you'll be able to add tournament events.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@stop