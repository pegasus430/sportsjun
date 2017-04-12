@extends('layouts.organisation')

@section('content')

    <div class="container">

            <div class="row">
                <div class="col-md-12">
                    <h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Tournaments</h2>
                          <div class="create-btn-link"><a href="/organization/{{$organisation->id}}/new_tournament" class="wg-cnlink" >Create New Tournament</a></div>
                    </div>
            </div>
            <div class="row">
        <div class="col-md-8">
    @foreach($parent_tournaments as $parent_tournament)

            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading-{{$parent_tournament->id}}">
                        <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{$parent_tournament->id}}" aria-expanded="true" aria-controls="collapseOne">
                            {{$parent_tournament->name}}
                        </a>
                      </h4>             
                 </div>

        <div id="collapse-{{$parent_tournament->id}}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                             <div class="row">
                <div class="col-md-12">
                      <span class="pull-right">
                        <br><button class="btn btn-danger" href="javascript:void(0);" data-toggle="modal" data-target="#overall_standing_{{$parent_tournament->id}}">Overall Standing</button></span>
                </div>
               </div>
                 
                            @foreach($parent_tournament->tournaments as $lis)
                                <div class="panel-body">

                                    <div class="search_thumbnail right-caption">
                                        <div class="col-md-2 col-sm-3 col-xs-12 text-center">
                                            <div class="">
                                                {!! Helper::Images( $lis->logo ? $lis->logo : $parent_tournament->logo ,'tournaments',array('height'=>90,'width'=>90,'class'=>'img-circle img-border img-scale-down img-responsive') )!!}
                                            </div>
                                            <!--                                    <img data-original="http://www.sportsjun.com/uploads/tournaments/jlXE2OrNeNlDqciAAbAT.png" src="http://www.sportsjun.com/uploads/tournaments/jlXE2OrNeNlDqciAAbAT.png" title="" onerror="this.onerror=null;this.src=&quot;http://www.sportsjun.com/images/default-profile-pic.jpg&quot;" height="90" width="90" class="img-circle img-border img-scale-down img-responsive lazy" style="display: block;"> --></div>
                                        <div class="col-md-10 col-sm-9 col-xs-12">
                                            <div class="t_tltle">
                                                <h4><a href="/gettournamentdetails/{{$lis->id}}" id="touname_{{$lis->id}}"><i class="fa fa-link"></i> {{$lis->name}}</a></h4>
                                                <p class="label label-default">{{ Helper::displayDate($lis->start_date,1) }} to {{ Helper::displayDate($lis->end_date,1) }}</p>
                                            </div>
                                            <hr>
                                            <div class="clearfix"></div>
                                            <ul class="t_tags">
                                                <li> Status: <span class="green">{{$lis->status}}</span> </li>
                                                <li> Sport: <span class="green">{{$lis->sport->sports_name}}</span> </li>
                                                <li> Tournament Type: <span class="green">{{$lis->type}}</span> </li>
                                            </ul>
                                              @if(isset($lis->winnerName))
                                            <p>Winner: <strong>{{$lis->winnerName}}</strong></p>
                                             @endif
                                            <p class="text-lite">{{$lis->address}}</p>
                                              @if($lis->description)
                                                <p class="lt-grey">{{$lis->description}}</p>
                                                @endif
                                            <div class="action-bar">
                                                <button class="btn btn-secondary" href="javascript:void(0);" data-toggle="modal" data-target="#event_points-{{$lis->id}}">Event Points </button> &nbsp; &nbsp;
                                                <button class="btn btn-primary" href="javascript:void(0);" data-toggle="modal" data-target="#register">Register For This Event </button>
                                            </div>
                                        </div>
                                    </div>
                                 </div>

                                   @include('organization_2.tournament.event_points')

                                @endforeach
                               
                            </div>
                    </div>

                    
                        

                @endforeach
                    
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>

@if(isset($parent_tournament))
   
   @include('organization_2.overall_standing')

@endif

           
@stop