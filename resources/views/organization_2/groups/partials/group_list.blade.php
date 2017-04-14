@foreach($groups->chunk(3) as $groupList)
    <div class="row">
        @foreach($groupList as $group)
  
                <div class="col-sm-4 col-md-3">
                    <div class="thumbnail"><a href="#edit_group_{{$group->id}}" data-toggle='modal' class="edit"><i class="fa fa-pencil fa-2x"></i></a><img src="/uploads/org/groups/logo/{{$group->logo }}" alt="">
                        <div class="caption">
                            <a href="{{route('organizationTeamlist',['id'=>$id, 'group'=>$group->id])}}"> <h3>{{$group->name}}</h3> </a>
                            <ul class="card-description">
                                <li><strong>No. of Teams:</strong> {{$group->teams->count()}}</li>
                                <li><strong>Manager:</strong> {{$group->manager->name}}</li>
                            </ul>
                        </div>
                    </div>
                </div>           
       


            @can('createGroup', $organization)
                @include('organization_2.groups.partials.edit_group_modal')
            @endcan
        @endforeach
    </div> {{-- /.row --}}
@endforeach