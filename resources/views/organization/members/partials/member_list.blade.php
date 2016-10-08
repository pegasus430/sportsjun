<table class="table table-bordered">
    <thead class="member-user-thead">
    <tr>
        <th style="width:30%">Name</th>
        <th>Teams</th>
        <th>Sports</th>
        <th>Stats/Notes</th>
    </tr>
    </thead>
    <tbody>
    @foreach($members as $member)
        <tr>
            <td>
                <a href="{{route('showsportprofile',['id'=>$member['id']])}}" class="member-image">
                    {!! Helper::Images( (count($member['user']['photos'])?$member['user']['photos'][0]['url']:''),'user_profile',array('height'=>60,'width'=>60,'class'=>'img-circle img-border ') ) !!}
                </a>
                <span class="member-user-info">
                        <a href="{{route('showsportprofile',['id'=>$member['id']])}}">{{ $member->name }}</a>
                </span>

            </td>
            <td>
                @foreach ($member->userdetails as $teamPlayer)
                        {{object_get($teamPlayer,'team.name')}}@if ($teamPlayer != $member->userdetails->last()), @endif
                @endforeach
            </td>
            <td>
                @foreach ($member->userdetails as $teamPlayer)
                    {{object_get($teamPlayer,'team.sports.sports_name')}}@if ($teamPlayer != $member->userdetails->last()), @endif
                @endforeach
            </td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>