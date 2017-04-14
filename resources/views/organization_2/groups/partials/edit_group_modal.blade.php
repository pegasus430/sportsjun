<div class = "modal fade in " id = "edit_group_{{$group->id}}">
  <div class="modal-dialog" role="document">
            <div class="modal-content">
     
                {{-- Create Group Form --}}
                {!! Form::open([
                    'route' => ['organization.groups.update', $group->id],
                    'method'=>'put',
                    'class'=>'form form-horizontal',
                    'files' => true
                ]) !!}
                <div class = "modal-header text-center">
                    <button type = "button" class = "close" data-dismiss = "modal">×</button>
                    <h4>EDIT TEAM GROUP DETAILS</h4>
                </div> {{-- /.modal-header --}}
                <div class = "modal-body">
                    <div class = "content">
                                 <div class="input-container">
                                                    {!! Form::text('name', $group->name, [
                                    'class' => '',
                                    'id' => 'group_name',
                                    'placeholder' => '',
                                ]) !!}
                           
                                   <label for="Username">Enter Your Group Name</label>
                
                                      <div class="bar"></div>
                            </div>
                            <input type="hidden" name="group_id" value="{{$group->id}}">
                     
                            <div class="input-container select">
                                <label>Group Manager</label>
                                {!! Form::select('manager_id', $staffList, [$group->manager_id=>$group->manager->email], [
                                    'class' => '',
                                    'id' => 'group_manager'
                                ]) !!}
                               
                            </div>
                      
                            <div>
                                    <div class="input-container file">
                                    <label>Group Logo</label>
                                    <input type="file" id="staff_email"  name="logo" /> </div>
                             
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
