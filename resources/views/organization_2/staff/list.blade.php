@extends('layouts.organisation')

@section('content')

    <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Staff</h2>
                    <div class="create-btn-link">
                        <a href="" class="wg-cnlink" data-toggle="modal" data-target="#add-staff-modal"> <i class="fa fa-plus"></i> &nbsp; Invite Staff</a>
                    </div>
                </div>
            </div>

 @if($staffList->isEmpty())
                        @include('organization_2.staff.partials.add_staff_instruction')
@else
    @include('organization_2.staff.partials.staff_list')
@endif

    </div>


    @include('organization_2.staff.partials.add_staff_modal')
@stop