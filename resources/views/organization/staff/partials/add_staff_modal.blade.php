<div id = "add-staff-modal" class = "modal fade">
    <div class = "modal-dialog sj_modal sportsjun-forms">
        <div class = "modal-content">
            {!! Form::open([
                'route' => ['organization.staff', $id],
                'class'=>'form-horizontal'
            ]) !!}
            <div class = "modal-header">
                <button type = "button" class = "close" data-dismiss = "modal" aria-hidden = "true">×</button>
                <h4 class = "modal-title">{{ trans('message.staff.modal.title') }}
                </h4>
            </div>
            <div class = "modal-body">

                <div class = "form-group">
                    <label for="email" class = "col-md-4 control-label">{{ trans('message.staff.fields.email') }}</label>
                    <div class = "col-md-6">
                        <input type = "text"
                               class = "form-control"
                               name = "email"
                               id = "email"
                               value = ""
                               autocomplete = "off">
                    </div>
                </div>

                <div class = "form-group">
                    <label for="staff_role" class = "col-md-4 control-label">{{ trans('message.staff.fields.role') }}</label>
                    <div class = "col-md-6">
                        {!! Form::select('staff_role', $staffRoles, null, [
                            'class' => 'form-control'
                        ]) !!}
                    </div>
                </div>

            </div>
            <div class = "modal-footer text-center">
                <button type = "submit" id = "savebutton" class = "button btn-primary">Invite</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>