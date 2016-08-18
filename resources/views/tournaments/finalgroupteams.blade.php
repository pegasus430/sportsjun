<div id="final_stage_div">
    <div class="sportsjun-forms">
        <div class="action-panel">
            @if($maxRoundNumber>1)
                <div class="sj-alert sj-alert-info">
                    {{ trans('message.tournament.final.knockoutstarted') }}
                </div>
                  <div class="sportsjun-forms">
            <div class="action-panel">
                @if(count($tournamentDetails[0]['final_stage_teams']))
                <table class="table table-striped">
                <thead>
                    <tr></tr>
                </thead>
                <tbody id="addedTeamsTable">
                @if(count($tournamentTeams))
                    @foreach($tournamentTeams as $requestedTeam)
                            <tr class="selected-teams">
                            <td>
                                @if($schedule_type=='team')
                                    {!! Helper::Images($requestedTeam['logo'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border img-responsive lazy','height'=>30,'width'=>30) )!!}
                                @else
                                    {!! Helper::Images($requestedTeam['logo'],config('constants.PHOTO_PATH.USERS_PROFILE'),array('class'=>'img-circle img-border img-responsive lazy','height'=>30,'width'=>30) )!!}
                                @endif
                            </td>
                            <td>{{ $requestedTeam['name'] }}</td>
                            
                        </tr>
                    @endforeach
                 @endif 
                </tbody>
                </table>
            
            
            @else   
                     <div class="sj-alert sj-alert-info">
                    {{ trans('message.tournament.final.nofinalstageteams') }}
                </div>
                  
              @endif
              </div>
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
