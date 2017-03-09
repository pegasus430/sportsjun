@foreach($groups->chunk(3) as $groupList)
    <div class="row">
        @foreach($groupList as $group)
                <div class="row">
                <div class="col-sm-4 col-md-3">
                    <div class="thumbnail"><img src="/uploads/org/groups/logo/{{$group->logo }}" alt="">
                        <div class="caption">
                            <a href="{{route('organizationTeamlist',['id'=>$id, 'group'=>$group->id])}}"> <h3>HYDERABAD NIZAMS</h3> </a>
                            <ul class="card-description">
                                <li><strong>No. of Teams:</strong> 9</li>
                                <li><strong>Manager:</strong> Ravi Kiran J</li>
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