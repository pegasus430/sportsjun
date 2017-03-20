<div class = "modal fade in " id = "create_group">
      <div class="modal-dialog" role="document">
            <div class="modal-content">
          
                {{-- Create Group Form --}}
                {!! Form::open([
                    'route' => ['organization.groups.store', $id],
                    'files' => true
                ]) !!}
                <div class = "modal-header text-center">
                    <button type = "button" class = "close" data-dismiss = "modal">×</button>
                    <h4>TEAM GROUP DETAILS</h4>
                </div> {{-- /.modal-header --}}
                <div class = "modal-body">
                    <div class = "content">                       
                        
                                 <div class="input-container">
                                   <label for="Username">Enter Your Group Name</label>
                                {!! Form::text('name', null, [
                                    'class' => '',
                                    'id' => 'group_name',
                                    'placeholder' => 'Wnter your group name',
                                ]) !!}
                                @if ($errors->has('name'))
                                    @foreach($errors->get('name') as $message)
                                        <p class = "help-block text-danger">{{ $message }}</p>
                                    @endforeach
                                @endif
                                      <div class="bar"></div>
                            </div>
                        </div>
                        
                        <div id = "group_manager_select" class = "form-group @if ($errors->has('manager_id')) has-error @endif">
                            <div>
                                <span class = "head">GROUP MANAGER</span>
                                {!! Form::select('manager_id', $staffList, null, [
                                    'class' => '',
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
                                    'class' => '',
                                    'id' => 'group_logo',
                                    'accept' => 'image/*'
                                ]) !!}
                                @if ($errors->has('logo'))
                                    @foreach($errors->get('logo') as $message)
                                        <p class = "help-block">{{ $message }}</p>
                                    @endforeach
                                @endif
                  
                    </div> {{-- /.content --}}
                </div> {{-- /.modal-body --}}
                <div class = "modal-footer">
                    <button type = "submit" class = "btn btn-primary" onclick = "">Create</button>
                </div> {{-- /.modal-footer --}}
                {!! Form::close() !!}
            </div> {{-- /.modal-content --}}
        </div> {{-- /.modal-dialog --}}
    </div> {{-- /.vertical-alignment-helper --}}
</div> {{-- /.modal --}}

@if($errors->has('name') || $errors->has('manager_id') || $errors->has('logo'))
    <script>
        $(function () {
            $('#create_group').modal('show');
        });
    </script>
@endif
