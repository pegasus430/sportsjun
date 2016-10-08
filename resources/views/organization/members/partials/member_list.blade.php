<table class="table table-bordered">
    <thead>
    <tr>
        <th style="width:30%">Name</th>
        <th>Teams</th>
        <th>Stats/Notes</th>
    </tr>
    </thead>
    <tbody>
    @foreach($members as $member)
        <tr>
            <td>
                <a href="{{route('showsportprofile',['id'=>$member['id']])}}" class="member-image">
                    {!! Helper::Images( (count($member['user']['photos'])?$member['user']['photos'][0]['url']:''),'user_profile',array('height'=>60 ) ) !!}
                </a>
                <span class="member-user-info">
                        <a href="{{route('showsportprofile',['id'=>$member['id']])}}">{{ $member->name }}</a>
                </span>

            </td>
            <td>
                @foreach ($member->userdetails as $teamPlayer)
                    <a href="{{route('team/members',['team_id'=>object_get($teamPlayer,'team.id')])}}">{{object_get($teamPlayer,'team.name')}}</a>
                @endforeach

            </td>

            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>