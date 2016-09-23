@foreach($groups->chunk(3) as $groupList)
    <div class="row">
        @foreach($groupList as $group)
            <div class="col-xs-4">
                <div class="single_group">
                    <img src="{{ "/uploads/org/groups/logo/$group->logo" }}"
                         class="img-responsive img-rounded center-block"
                         alt=""
                         style="height: 150px"/>
                    <div class="single_group_info">
                        <div class="single_group_name">
                            <a href="{{route('organizationTeamlist',['id'=>$id, 'group'=>$group->id])}}">{{ $group->name }}</a>

                            @can('createGroup', $organization)
                                <span class='pull-right '><a href='javascript:void(0)' class="red"
                                                             data-toggle="modal"
                                                             data-target="#edit_group_{{$group->id}}"><i
                                                class='fa fa-edit'></i> Edit</a></span>
                            @endcan
                        </div>
                        <div class="single_group_fields">
                            <ul>
                                <li>
                                    <label>No of teams:</label>
                                    <span>{{ $group->teams->count() }}</span>
                                </li>
                                <li>
                                    <label>Manager:</label>
                                    <span>{{ $group->manager->name }} </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @can('createGroup', $organization)
                @include('organization.groups.partials.edit_group_modal')
            @endcan
        @endforeach
    </div> {{-- /.row --}}
@endforeach