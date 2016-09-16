@extends(Auth::user() ? 'layouts.app' : 'home.layout')
@section('content')
@include ('tournaments._leftmenu')
<div id="content-team" class="col-sm-10 group_stage">


<!-- Display rubber modal -->

<div id='displayrubber'> </div>



    <div class="col-sm-12 group-stage sportsjun-forms">
    @include('tournaments.share_groups')
        @if($dispViewFlag=='group')
        @if($tournament_type=='league' || $tournament_type=='multistage')
        <div id="group_stage">
        <!-- /.panel-heading -->
            @if(count($tournamentDetails[0]['final_stage_teams']))
                    @include ('tournaments.viewablegroup')
            @else
				@if($isOwner)
					@include ('tournaments.editablegroup') 
				@else
                    @include ('tournaments.viewablegroup') 
				@endif
            @endif
        </div>
        @endif
        @endif
        
        @if($dispViewFlag=='final')
            @if($tournament_type=='knockout' || $tournament_type=='multistage')



                 <div id="final_stage">
                 	<div class="group_no"><h4 class="stage_head">FINAL STAGE</h4></div>
                    <div class="cstmpanel-tabs">
                        <ul class="nav nav-tabs nav-justified">
                        	<li class="active"><a href="#final_stage_teams" data-toggle="tab" aria-expanded="true">Final Stage Teams</a></li>
                            <li class=""><a href="#tournament_bracket" data-toggle="tab" aria-expanded="false">Tournament Bracket</a></li>
                            <li class=""><a href="#tournament_knockout_schedule" data-toggle="tab" aria-expanded="false">Match Schedules</a></li>
                        </ul>
                        <div  class="tab-content clearfix">
                            <div id="final_stage_teams" class="tab-pane fade active in">
                                @if($isOwner)
                                    @if($tournament_type=='knockout')
                                        @include ('tournaments.finalknocoutteams')
                                    @else
                                        @include ('tournaments.finalgroupteams')
                                    @endif
                                @else
                                    @include ('tournaments.viewablefinalteams')
                                @endif
                            </div>
                            <div id="tournament_bracket" class="tab-pane fade" >
                                @include ('tournaments.final',[$tournamentDetails])
                            </div>
                             <div id="tournament_knockout_schedule" class="tab-pane fade" >
                                @include ('tournaments.knockout_schedule',[$tournamentDetails])
                            </div>
                            
                        </div>
                    </div>

                </div>
            @endif
        @endif
    </div>
    
</div>
</div>
</div>
<div class='clearfix'>

@include ('tournaments.addtournamentschedule',[])

<script type="text/javascript">
$(function() {  
    @if($tournament_type=='knockout')
        $('.nav-tabs a[href="#final_stage"]').tab("show");
    @endif    
    @if(count($tournamentDetails[0]['final_stage_teams']))
            $('.nav-tabs a[href="#tournament_bracket"]').tab("show");
    @endif    
    @if($dispViewFlag=='group')
        $('.sidemenu_3').addClass('active')
    @else
        $('.sidemenu_4').addClass('active')
    @endif
	
	$('.sidemenu_1').removeClass('active')
    
        // Code to append the hash tag to browser while navigating through tabs
        var hash = window.location.hash;
        hash && $('ul.nav a[href="' + hash + '"]').tab('show');

        $('.nav-tabs a').click(function (e) {
        $(this).tab('show');
        var scrollmem = $('body').scrollTop() || $('html').scrollTop();
        window.location.hash = this.hash;
        //$('html,body').scrollTop(scrollmem);
        });
});    
</script>

<?php $settings = Helper::getTournamentDetails($tournamentDetails[0]['id'])->settings; ?>
@if(isset($settings) && $settings->has_setup_details==0)
    <script>
    $(document).ready(function(){
         getTournamentSettings({{$tournamentDetails[0]['id']}});
        $('#settings').modal('show');
    })
   
    </script>    
@endif


@endsection
