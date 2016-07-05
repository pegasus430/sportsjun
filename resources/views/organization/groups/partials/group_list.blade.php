@foreach($groups->chunk(3) as $groupList)
    <div class = "row">
        @foreach($groupList as $group)
            <div class = "col-xs-4">
                <div class = "single_group">
                    <img src = "{{ "/uploads/org/groups/logo/$group->logo" }}"
                         class = "img-responsive img-rounded center-block"
                         alt = ""
                         style = "height: 150px">
                    <div class = "single_group_info">
                        <div class = "single_group_name">
                            {{ $group->name }}
                        </div>
                        <div class = "single_group_fields">
                            <ul>
                                <li>
                                    <label>No of teams:</label>
                                    <span>{{ $group->teams->count() }}</span>
                                </li>
                                <li>
                                    <label>Manager:</label>
                                    <span>{{ $group->manager->name ? $group->manager->name . ' - ' : '' }} {{ $group->manager->email }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div> {{-- /.row --}}
@endforeach