@extends('layouts.app')
@section('content')
    @include ('teams.orgleftmenu')
    <div class = "col-lg-10 col-md-10 col-sm-12 staff">
        <div class = "col-md-12">
            <div class = "panel">
                <h4 class = "panel-heading">Staff</h4>
                <div class = "panel-body">
                    @if (session('status'))
                        <div class = "alert alert-success">
                            {{ session('status') }}
                        </div>
                    @elseif(session('alert'))
                        <div class = "alert alert-danger">
                            {{ session('alert') }}
                        </div>
                    @endif

                    @if($staffList->isEmpty())
                        @include('organization.staff.partials.add_staff_instruction')
                    @else
                        @include('organization.staff.partials.staff_list')
                    @endif
                </div> {{-- /.panel-body --}}
                <div id="staff_foot_info">Additional staff members can be invited using email</div>
                @include('organization.staff.partials.invite_button')
            </div> {{-- /.panel --}}
        </div> {{-- /.col-md-12 --}}
    </div> {{-- /.staff --}}
    @include('organization.staff.partials.add_staff_modal')
@endsection
