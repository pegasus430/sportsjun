<div id = "transfer-owner-modal" class = "modal fade in tossDetail">
    <div class = "vertical-alignment-helper">
        <div class = "modal-dialog modal-lg vertical-align-center">
            <div class = "modal-content create-team-model create-album-popup model-align">
                {!! Form::open([
                    'route' => ['team.change_ownership'],
                    'class'=>'form-horizontal',
                    'method'=>'POST'
                ]) !!}
                <input name="teamId" type="hidden" />
                <div class = "modal-header text-center">
                    <button type = "button" class = "close" data-dismiss = "modal" aria-hidden = "true">×</button>
                    <h4>{{ trans('message.team.ownership_change_modal.title') }}
                    </h4>
                </div>
                <div class = "modal-body">
                    <div class = "content">
                        <div class="form-group">
                            <span class = "head">{{ trans('message.team.ownership_change_modal.field_name') }}</span>
                            {!! Form::text('name', null, [
                                   'class' => 'form-control',
                                   'placeholder' => 'Name',
                                   'id' => 'name',
                                   'required'=>''
                               ]) !!}
                        </div>

                        <div class = "form-group">
                            <div>
                                <span class = "head">{{ trans('message.team.ownership_change_modal.field_email') }}</span>

                                {!! Form::text('email', null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Email',
                                    'id' => 'email',
                                    'required'=>''
                                ]) !!}
                            </div>
                        </div>
                    </div> {{-- /.content --}}
                </div> {{-- /.modal-body --}}
                <div class = "modal-footer">
                    <button type = "submit" class = "button btn-primary">Transfer</button>
                </div> {{-- /.modal-footer --}}
                {!! Form::close() !!}
            </div>
        </div> {{-- /.modal-dialog --}}
    </div> {{-- /.vertical-alignment-helper --}}
</div> {{-- /.modal --}}

<script>
    $('#transfer-owner-modal').on('show.bs.modal', function(e) {
        //get data-id attribute of the clicked element
        var teamId = $(e.relatedTarget).data('team-id');
        //populate the textbox
        $(e.currentTarget).find('input[name="teamId"]').val(teamId);
    });
</script>