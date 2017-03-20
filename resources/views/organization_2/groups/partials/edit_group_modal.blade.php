<div class = "modal fade in " id = "edit_group_{{$group->id}}">
  <div class="modal-dialog" role="document">
            <div class="modal-content">
     
                {{-- Create Group Form --}}
                {!! Form::open([
                    'route' => ['organization.groups.update', $group->id],
                    'method'=>'put',
                    'files' => true
                ]) !!}
                <div class = "modal-header text-center">
                    <button type = "button" class = "close" data-dismiss = "modal">×</button>
                    <h4>EDIT TEAM GROUP DETAILS</h4>
                </div> {{-- /.modal-header --}}
                <div class = "modal-body">
                    <div class = "content">
                         <div id = "group_name_input" class = "form-group @if ($errors->has('name')) has-error @endif">
                            <div>
                                <span class = "head">GROUP NAME</span>
                                {!! Form::text('name',$group->name, [
                                    'class' => '',
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
                                {!! Form::select('manager_id', $staffList, [$group->manager_id=>$group->manager->email], [
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
                        <input type="hidden" name="group_id" value="{{$group->id}}">
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
                      </div>
                    </div> {{-- /.content --}}
                </div> {{-- /.modal-body --}}
                <div class = "modal-footer">
                    <button type = "submit" class = "btn btn-primary" onclick = "">Update</button>
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
