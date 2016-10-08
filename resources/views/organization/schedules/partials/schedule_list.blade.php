<table class="table table-bordered">
    <thead>
    <tr>
        <th style="width:30%">Date</th>
        <th>Type</th>
        <th>Event</th>
        <th>Location</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($schedules as $item)
        <tr>
            <td>
                <span>{{$item->match_start_date}}</span><br/>
                <span class="schedule-time">{{$item->match_start_time}}</span>
            </td>
            <td>{{$item->match_type}}</td>
            <td>{{$item->tournament->name}}</td>
            <td>{{$item->address}}</td>
        </tr>
    @endforeach
    </tbody>
</table>