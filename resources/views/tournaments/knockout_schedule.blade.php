
@if(count($tournamentDetails[0]['final_stage_teams']))
@endif
<!--<div>
    Add Round
</div>


<div>
    <div>Round 1</div>
    <span class="button btn-primary" onclick='addRoundMatchesSchedule({{$tournament_id}},1)'>Add Schedule</span>
</div>

<div id="round_div_1">
</div>    -->
<div class="col-sm-12">
<div class="row group-flex-content">

  @if($isOwner)
          @include ('tournaments.editablegroupfinal') 
        @else
                    @include ('tournaments.viewablegroupfinal') 
        @endif

  </div>
</div>