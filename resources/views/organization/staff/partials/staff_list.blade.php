<?php $is_widget = (isset($is_widget) && $is_widget) ? $is_widget : false; ?>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Role</th>
            <th>Added On</th>
            @if($is_owner)
            <th></th>
            @endif
        </tr>
        </thead>

        <tbody>
        @foreach($staffList as $staff)
            <tr>
                <td>{{ $staff->name}}</td>
                <td>{{ $staff->roleForOrganization($id)->name }}</td>
                <td>{{ $staff->pivot->created_at->format('M d, Y') }}</td>
                @if($is_owner)
                <td>
                    @if (!(isset($is_widget) && $is_widget))
                    <a href="javascript:void(0);"
                       class="btn btn-danger btn-circle btn-sm"
                    >
                        <i class="fa fa-remove"></i>
                    </a>
                    @endif
                </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table> {{-- /.table --}}
</div> {{-- /.table-responsive --}}
