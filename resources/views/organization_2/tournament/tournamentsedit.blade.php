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

                             {!! Form::model( $tournament,(array('route' => array('tournaments.update',$id),'class'=>'form-horizontal','method' => 'put','id' => 'my-tournaments'))) !!}  

                                <form action="/tournaments/{{$tournament->id}}/update" class="form create-form clearfix" method="post" enctype="multipart/form-data" id='my-tournaments'>

                 <div class="form-body">
                        @include ('organization_2.tournament._parentform', ['submitButtonText' => 'Update'])
                        <input type="hidden" name="isParent" id="isParent" value="yes">
                        </div>  
                 <div class="form-footer">
                  <button type="submit" class="button btn-primary">Update</button>

                </div>  
                {!! Form::close() !!}
                    {!! JsValidator::formRequest('App\Http\Requests\CreateTournamentRequest', '#my-tournaments'); !!}
          
                            </div>
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