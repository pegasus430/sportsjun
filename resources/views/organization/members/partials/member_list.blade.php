@foreach($members as $member)
    <?php
        $teams = $member->userdetails->reject(function ($name) {
            return empty($name['team']);
        })->lists('team')->unique('name');
    ?>

    <tr>
        <td>
            @if (!(isset($is_widget) && $is_widget))
            <a href="{{route('showsportprofile',['id'=>$member['id']])}}" class="member-image">
                {!! Helper::Images( (count($member['user']['photos'])?$member['user']['photos'][0]['url']:''),'user_profile',array('height'=>60,'width'=>60,'class'=>'img-circle img-border ') ) !!}
            </a>
            @else
                <span class="member-image">
                    {!! Helper::Images( (count($member['user']['photos'])?$member['user']['photos'][0]['url']:''),'user_profile',array('height'=>60,'width'=>60,'class'=>'img-circle img-border ') ) !!}
                </span>
            @endif
            <span class="member-user-info">
                @if (!(isset($is_widget) && $is_widget))
                        <a href="{{route('showsportprofile',['id'=>$member['id']])}}" class="member-user-info-name">{{ $member->name }}</a>
                @else
                    <span class="member-user-info-name">{{ $member->name }}</span>
                @endif
            </span>

        </td>
        <td>
            <?php $teamNames = $teams->lists('name'); ?>
            @foreach ($teamNames as $teamName)
                {{$teamName}}@if ($teamName != $teamNames->last()), @endif
            @endforeach
        </td>
        <td>
            <?php
                $sports = array_unique($teams->lists('sports.sports_name')->toArray());
            ?>
            @foreach ($sports as $sport)
                {{$sport }}@if ($sport != last($sports)), @endif
            @endforeach
        </td>
        <td>
            {{ \App\Model\Rating::overralRate($member['id'],\App\Model\Rating::$RATE_USER) }}
        </td>
        <td></td>
    </tr>
@endforeach
<tr>
    <td colspan="5">
        @if ($members->hasMorePages())
            <div id="viewmorediv">
                <a id="viewmorebutton" class="view_tageline_mkt" data-replace="tr"
                   @if (!(isset($is_widget) && $is_widget))
                        data-url="{{route('organization.members.list',['id'=>$id,'page'=>$members->currentPage()+1,'filter-team'=>$filter_team])}}"
                   @else
                        data-url="{{route('widget.organization.members',['id'=>$id,'page'=>$members->currentPage()+1,'filter-team'=>$filter_team])}}"
                   @endif

                   onclick="return DataTableLoadMore(this);"
                >
                    <span class="market_place"><i
                                class="fa fa-arrow-down"></i> <label>{{ trans('message.view_more') }}</label></span>
                </a>
            </div>
        @endif
    </td>
</tr>
