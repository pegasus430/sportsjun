<div id = "add-staff-modal" class = "modal fade in tossDetail">
    <div class = "vertical-alignment-helper">
        <div class = "modal-dialog modal-lg vertical-align-center">
            <div class = "modal-content create-team-model create-album-popup model-align">
                {!! Form::open([
                    'route' => ['organization.staff', $id],
                    'class'=>'form-horizontal'
                ]) !!}
                <div class = "modal-header text-center">
                    <button type = "button" class = "close" data-dismiss = "modal" aria-hidden = "true">×</button>
                    <h4>{{ trans('message.staff.modal.title') }}
                    </h4>
                </div>
                <div class = "modal-body">
                    <div class = "content">


                        <div class = "form-group">
                            <div>
                                <span class = "head">{{ trans('message.staff.fields.email') }}</span>

                                {!! Form::text('email', null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Your Staff Email',
                                    'id' => 'staff_email'
                                ]) !!}
                            </div>
                        </div>

                        <div class = "form-group">
                            <div>
                                <span class = "head">{{ trans('message.staff.fields.role') }}</span>
                                {!! Form::select('staff_role', $staffRoles, null, [
                                    'class' => 'form-control'
                                ]) !!}
                            </div>
                        </div>
                    </div> {{-- /.content --}}
                </div> {{-- /.modal-body --}}
                <div class = "modal-footer">
                    <button type = "submit" class = "button btn-primary">Invite</button>
                </div> {{-- /.modal-footer --}}
                {!! Form::close() !!}
            </div>
        </div> {{-- /.modal-dialog --}}
    </div> {{-- /.vertical-alignment-helper --}}
</div> {{-- /.modal --}}