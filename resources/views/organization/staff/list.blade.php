@extends('layouts.app')
@section('content')
    @include ('teams.orgleftmenu')
    <div id = "content" class = "col-sm-10" style = "height: 986px;">
        <div class = "col-sm-9 organization-staff-list">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @elseif(session('alert'))
                <div class="alert alert-danger">
                    {{ session('alert') }}
                </div>
            @endif

            @if($staffList->isEmpty())
                @include('organization.staff.partials.add_staff_instruction')
            @else
                @include('organization.staff.partials.staff_list')
            @endif
        </div>
    </div>

    @include('organization.staff.partials.add_staff_modal')
@endsection
