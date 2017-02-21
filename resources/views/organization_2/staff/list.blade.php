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
        <div class="row">

              @if(!$staffList->isEmpty())
     				@include('organization.staff.partials.staff_list')
              @else
                       
            <div class="col-md-offset-2 col-md-8 bg-grey text-center pdtb-30">
                <div>Please add staff to assign as group managers, admin etc.</div>
                <br>
                <h3><b>Steps to follow</b></h3>
                <ol class="steps-list text-left">
                    <li> Click <strong>Invite Staff</strong></li>
                    <li>Enter <strong>Email ID</strong></li>
                    <li>Select <strong>Role</strong></li>
                    <li>Click <strong>Invite</strong></li>
                </ol>
            </div>
            <div class="col-md-offset-2 col-md-8">
                <div id="staff_foot_info">*Additional staff members can be invited using email</div>
            </div>

            @endif
        </div>
    </div>
    <div class="modal fade" id="add-staff-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
              {!! Form::open([
                    'route' => ['organization.staff', $id],
                    'class'=>'form form-horizontal'
                ]) !!}

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3>Invite Staff</h3> </div>
                    <div class="modal-body">
                        <div class="content">
                            <div class="input-container">
                                {!! Form::text('name', null, [
                                   'class' => '',
                                   'placeholder' => '',
                                   'id' => 'staff_name','required'
                               ]) !!}
                                <label for="Username">Enter Your Staff Name</label>
                                <div class="bar"></div>
                            </div>
                            <div class="input-container">
                                 {!! Form::text('email', null, [
                                    'class' => '',
                                    'placeholder' => '',
                                    'id' => 'staff_email','required'
                                ]) !!}
                                <label for="Username">Enter Your Staff Email (optional)</label>
                                <div class="bar"></div>
                            </div>
                            <div class="input-container select">
                                <div>
                                    <label>Staff Role</label>
                                    {!! Form::select('staff_role', $staffRoles, null, [
                                    'class' => ''
                                ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Invite</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@stop