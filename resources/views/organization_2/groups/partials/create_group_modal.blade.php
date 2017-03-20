<div class = "modal fade in " id = "create_group">
      <div class="modal-dialog" role="document">
            <div class="modal-content">
           
                {{-- Create Group Form --}}
                {!! Form::open([
                    'route' => ['organization.groups.store', $id],
                    'files' => true,
                    'class'=>'form form-horizontal'
                ]) !!}
                <div class = "modal-header text-center">
                    <button type = "button" class = "close" data-dismiss = "modal">×</button>
                    <h4>TEAM GROUP DETAILS</h4>
                </div> {{-- /.modal-header --}}
                <div class = "modal-body">
                    <div class = "content">                       
                        
                                 <div class="input-container">
                                                    {!! Form::text('name', null, [
                                    'class' => '',
                                    'id' => 'group_name',
                                    'placeholder' => '',
                                ]) !!}
                           
                                   <label for="Username">Enter Your Group Name</label>
                
                                      <div class="bar"></div>
                            </div>
                     
                            <div class="input-container select">
                                <label>Group Manager</label>
                                {!! Form::select('manager_id', $staffList, null, [
                                    'class' => '',
                                    'id' => 'group_manager'
                                ]) !!}
                               
                            </div>
                      
                            <div>
                                    <div class="input-container file">
                                    <label>Group Logo</label>
                                    <input type="file" id="staff_email" required="" name="logo" /> </div>
                             
                  
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
