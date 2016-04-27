<div id="final_stage_div">
    <div class="sportsjun-forms">
        <div class="action-panel">
            @if($maxRoundNumber>1)
                <div class="sj-alert sj-alert-info">
                    {{ trans('message.tournament.final.knockoutstarted') }}
                </div>
            @else
                @if(count($tournamentTeams))
                <div class="section">
                    <label for="firstname" class="field prepend-icon">
                            <!--<input type="text" placeholder="Number Of Teams Qualifying For The Final Stage" value="" id="final_stage_teams">-->
                            {!! Form::select('final_stage_teams[]',$tournamentTeams,$selectetdFinalStageTeams, array('multiple'=>true,'class'=>'multiselect','id'=>'final_stage_teams','placeholder'=>trans('message.tournament.group.numberofteamsqualify'))) !!}
                    </label>
                </div>
                <button type="button" class="button btn-primary" onclick="finalStageTeams('group')">Submit </button>
                @else
                <div class="sj-alert sj-alert-info">
                    {{ trans('message.tournament.final.addfinalteams') }}
                </div>
                @endif
            @endif
        </div>
    </div>
</div>
