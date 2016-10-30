<div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Role</th>
            <th>Added On</th>
            <th></th>
        </tr>
        </thead>

        <tbody>
        @foreach($staffList as $staff)
            <tr>
                <td>{{ $staff->name}}</td>
                <td>{{ $staff->roleForOrganization($id)->name }}</td>
                <td>{{ $staff->pivot->created_at->format('M d, Y') }}</td>
                <td>
                    @if (!(isset($is_widget) && $is_widget))
                    <a href="javascript:void(0);"
                       class="btn btn-danger btn-circle btn-sm"
                    >
                        <i class="fa fa-remove"></i>
                    </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table> {{-- /.table --}}
</div> {{-- /.table-responsive --}}
