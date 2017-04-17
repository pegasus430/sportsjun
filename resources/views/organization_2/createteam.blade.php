@extends('layouts.organisation')

@section('content')


        <div class="container">
            <div class="row">
                <div class="col-sm-4 bg-white pdtb-30">
                    <h5 class="text-center"><b>Steps to create tournament</b></h5>
                    <ol class="steps-list text-left">
                                  <span class="steps_to_follow">Steps to create team:</span>
                                <li><span class="bold">Fill</span> the create team form.</li>
                                <li>Click <span class="bold">create.</span></li>
                                <li><span class="bold">Add players</span> using Add Player / Invite Player wizard.</li>
                                <li>Make a player the <span class="bold">Manager / Captain</span> of the team using <span class="glyphicon glyphicon-option-vertical"></span> button.</li>
                    </ol>
                </div>
                <div class="col-sm-8">
                    <div class="wg wg-dk-grey no-shadow no-margin">
                        <div class="wg-wrap clearfix">
                            <h4 class="no-margin pull-left"><i class="fa fa-pencil-square"></i> Create Team</h4></div>
                    </div>
                    <div class="wg no-margin tabbable-panel create_tab_form">
                        <div class="tabbable-line">
                            <ul class="nav nav-tabs ">
                     
                                @include('organization_2.teams.createteam')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@stop