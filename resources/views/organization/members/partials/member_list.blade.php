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
                        <a href="javascript:void(0)" class="member-user-info-name" onclick="openNav({{$member->id}})">{{ $member->name }}</a>
                @else
                    <span class="member-user-info-name" >{{ $member->name }}</span>
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
            @if (\Auth::user())
                    <input type="hidden" class="rating b-rating" value="{{$member->rate}}" data-filled="fa fa-star s-rating" data-empty="fa fa-star-o s-rating"
                           data-target_id="{{$member->id}}" data-type="user"
                    />
            @endif
        </td>
        <td></td>
    </tr>

     <div id="myNav{{$member->id}}" class="overlay"> <a href="javascript:void(0)" class="closebtn" onclick="closeNav({{$member->id}})">&times;</a>
                                
                                            <div class="overlay-content"> 
                                                         <iframe src="/editsportprofile/{{$member->id}}?from_org=true" width="100%"; height="1000px">
                                             
                                                          </iframe>


                                            </div>
                                        </div>
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

                   onclick="return DataTableLoadMore(this,function() {InitRatings();})"
                >
                    <span class="market_place"><i
                                class="fa fa-arrow-down"></i> <label>{{ trans('message.view_more') }}</label></span>
                </a>
            </div>
        @endif
    </td>
</tr>
