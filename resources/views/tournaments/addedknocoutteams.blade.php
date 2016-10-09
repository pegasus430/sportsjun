    @foreach($tournamentTeams as $requestedTeam)
    <tr id='selected_team_{{$tournamentId}}_{{$requestedTeam['id']}}' class="selected-teams">
        <td class="col-sm-4">
            <p>
            @if($scheduleType=='team')
                {!! Helper::Images($requestedTeam['url'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('height'=>30,'width'=>30) )!!}
            @else    
                {!! Helper::Images($requestedTeam['url'],config('constants.PHOTO_PATH.USERS_PROFILE'),array('height'=>30,'width'=>30) )!!}
            @endif
            </p>
        </td>
        <td>{{ $requestedTeam['name'] }}</td>
        <td><a href="#" class="btn btn-danger btn-circle btn-sm" onclick="deleteFinalStageTeam({{$requestedTeam['id']}})"><i class="fa fa-remove"></i></a></td>
    </tr>	
    @endforeach	
                        
                 