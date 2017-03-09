<div class = "modal fade in tossDetail" id = "create_group">
    <div class = "vertical-alignment-helper">
        <div class = "modal-dialog modal-lg vertical-align-center">
            <div class = "modal-content create-team-model create-album-popup model-align">
               <div class = "modal-header text-center">
                    <button type = "button" class = "close" data-dismiss = "modal">×</button>
                    <h4>{{$parent_tournament->tournament_parent_name}}</h4>
                </div> {{-- /.modal-header --}}
                <div class = "modal-body">
                    <div class = "content">
                        <div id = "group_name_input" class = "form-group @if ($errors->has('name')) has-error @endif">
                            <div>
                                <span class = "head">GROUP NAME</span>
                                {!! Form::text('name', null, [
                                    'class' => 'form-control',
                                    'id' => 'group_name',
                                    'placeholder' => 'Wnter your group name',
                                ]) !!}
                                @if ($errors->has('name'))
                                    @foreach($errors->get('name') as $message)
                                        <p class = "help-block text-danger">{{ $message }}</p>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        
                        <div id = "group_manager_select" class = "form-group @if ($errors->has('manager_id')) has-error @endif">
                            <div>
                                <span class = "head">GROUP MANAGER</span>
                                {!! Form::select('manager_id', $staffList, null, [
                                    'class' => 'form-control',
                                    'id' => 'group_manager'
                                ]) !!}
                                @if ($errors->has('manager_id'))
                                    @foreach($errors->get('manager_id') as $message)
                                        <p class = "help-block">{{ $message }}</p>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        
                        <div id = "group_logo_input" class = "form-group @if ($errors->has('logo')) has-error @endif">
                            <div>
                                <span class = "head">GROUP LOGO</span>
                                {!! Form::file('logo', [
                                    'class' => 'form-control',
                                    'id' => 'group_logo',
                                    'accept' => 'image/*'
                                ]) !!}
                                @if ($errors->has('logo'))
                                    @foreach($errors->get('logo') as $message)
                                        <p class = "help-block">{{ $message }}</p>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div> {{-- /.content --}}
                </div> {{-- /.modal-body --}}
                <div class = "modal-footer">
                    <button type = "submit" class = "button btn-primary" onclick = "">Create</button>
                </div> {{-- /.modal-footer --}}
                {!! Form::close() !!}
            </div> {{-- /.modal-content --}}
        </div> {{-- /.modal-dialog --}}
    </div> {{-- /.vertical-alignment-helper --}}
</div> {{-- /.modal --}}
