<table class="table table-bordered">
    <thead>
    <tr>
        <th style="width:30%">Name</th>
        <th>Team</th>
        <th>Stats/Notes</th>
    </tr>
    </thead>
    <tbody>
    @foreach($members as $member)
        <tr>
            <td>
                {!! Helper::Images( (count($member['user']['photos'])?$member['user']['photos'][0]['url']:''),'user_profile',array('height'=>60,'width'=>60,'style'=>'float:left' ) ) !!}
                {{ $member->name }}</td>
            <td>{{ $member->teams }}</td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>