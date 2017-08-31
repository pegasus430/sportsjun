@if(count($sportsPlayerStatistics) > 0)
    <?php

        $stats = [];
        $stats['Level'] = 0;
        $stats['K'] = 0;
        $stats['D'] = 0;
        $stats['A'] = 0;
        $stats['Gold'] = 0;
        $stats['GPM'] = 0;
        $stats['Magic DMG'] = 0;
        $stats['Physical DMG'] = 0;

        foreach($sportsPlayerStatistics as $match)
        {
            $stats['Level'] += $match['FinalLevel'];
            $stats['K'] += $match['Kills'];
            $stats['D'] += $match['Deaths'];
            $stats['A'] += $match['Assists'];
            $stats['Gold'] += $match['GoldEarned'];
            $stats['GPM'] += $match['GoldPerMinute'];
            $stats['Magic DMG'] += $match['MagicalDamage'];
            $stats['Physical DMG'] += $match['PhysicalDamage'];
        }


        $stats['Level'] =  $stats['Level'] / count($sportsPlayerStatistics);
        $stats['K'] =$stats['K'] / count($sportsPlayerStatistics);
        $stats['D'] = $stats['D'] / count($sportsPlayerStatistics);
        $stats['A'] = $stats['A'] / count($sportsPlayerStatistics);
        $stats['Gold'] = $stats['Gold'] / count($sportsPlayerStatistics);
        $stats['GPM'] = $stats['GPM'] / count($sportsPlayerStatistics);
        $stats['Magic DMG'] = $stats['Magic DMG'] / count($sportsPlayerStatistics);
        $stats['Physical DMG'] = $stats['Physical DMG'] / count($sportsPlayerStatistics);
    ?>
    <div class='row'>
        <div class='col-sm-12'>
            <div class='table-responsive'>
                <table class='table table-striped table-bordered'>
                    <thead>
                    <tr class='team_fall team_title_head'>
                        <?php $is_odd = false ?>
                        @foreach($stats as $key=>$val)
                            @if($is_odd)
                                <th bgcolor="#84cd93">{{ $key }}</th>
                            @else
                                <th bgcolor="#fff" style="color: #84cd93;" >{{ $key }}</th>
                            @endif
                            <?php $is_odd = !$is_odd; ?>
                        @endforeach

                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach($stats as $key=>$val)
                                <td>
                                    {{ $val }}
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <span>Statistic is calculated based on average for all games.</span>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-sm-12">
            <span>No game statistic registered.</span>
        </div>
    </div>
@endif
