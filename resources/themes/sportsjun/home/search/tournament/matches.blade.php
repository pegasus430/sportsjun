<div class="groupstageWrapp">
    <h2>MATCHES</h2>
    <table width="100%" border="1" class="test">
        <tbody>
        <tr class="headingMAT matchesN">
            <td>Date</td>
            <td>Time</td>
            <td>Venue</td>
            <td>Team 1</td>
            <td>Team 2</td>
            <td>Team Won</td>
        </tr>
        @foreach ($tournament->matches as $match)
            <tr class="playList matchList">
                <td>{{ $match->match_start_date }}</td>
                <td>{{ $match->match_start_time }}</td>
                <td>{{ $match->address }}</td>
                <td>{{ object_get($match->sideA,'name') }}</td>
                <td>{{ object_get($match->sideB,'name') }}</td>
                <td>{{ $match->winner }} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>