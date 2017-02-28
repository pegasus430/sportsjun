   <div class="modal fade" id="create_group" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {{-- Create Group Form --}}
                {!! Form::open([
                    'route' => ['organization.groups.store', $id],
                    'files' => true,
                    'class' =>'form form-horizontal'
                ]) !!}
                <div class = "modal-header text-center">
                    <button type = "button" class = "close" data-dismiss = "modal">×</button>
                    <h4>TEAM GROUP DETAILS</h4>
                </div> {{-- /.modal-header --}}

                <div class="modal-body">
                        <div class="content">
                            <div class="input-container">
                                {!! Form::text('name', null, [
                                    'class' => 'input-container',
                                    'id' => 'group_name',
                                    'placeholder' => '',
                                    'required'
                                ]) !!}
                                @if ($errors->has('name'))
                                    @foreach($errors->get('name') as $message)
                                        <p class = "help-block text-danger">{{ $message }}</p>
                                    @endforeach
                                @endif
                                <label for="Username">Enter Your Group Name</label>
                                <div class="bar"></div>
                            </div>
                            <div class="input-container select">
                                <div>
                                    <label>Group Manager</label>
                                 {!! Form::select('manager_id', $staffList, null, [
                                    'class' => 'select',
                                    'id' => 'group_manager',
                                    'required'
                                ]) !!}
                                @if ($errors->has('manager_id'))
                                    @foreach($errors->get('manager_id') as $message)
                                        <p class = "help-block">{{ $message }}</p>
                                    @endforeach
                                @endif
                                </div>
                            </div>
                            <div class="input-container file">
                                <label>Group Logo</label>
                                  {!! Form::file('logo', [
                                    'class' => '',
                                    'id' => 'group_logo',
                                    'required',
                                    'accept' => 'image/*'
                                ]) !!}
                                @if ($errors->has('logo'))
                                    @foreach($errors->get('logo') as $message)
                                        <p class = "help-block">{{ $message }}</p>
                                    @endforeach
                                @endif
                        </div>
                    </div>
                <div class = "modal-footer">
                    <button type = "submit" class = "btn btn-primary" onclick = "">Create</button>
                </div> {{-- /.modal-footer --}}
                {!! Form::close() !!}
            </div> {{-- /.modal-content --}}
        </div> {{-- /.modal-dialog --}}
  
</div> {{-- /.modal --}}

@if($errors->has('name') || $errors->has('manager_id') || $errors->has('logo'))
    <script>
        $(function () {
            $('#create_group').modal('show');
        });
    </script>
@endif
