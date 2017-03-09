<div id = "add-staff-modal" class = "modal fade"   tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                                    <input type="text" id="staff_name" required="required" name="name" />
                                    <label for="Username">Enter Your Staff Name</label>
                                    <div class="bar"></div>
                                </div>
                                <div class="input-container">
                                    <input type="text" id="staff_email" required="" name="email" />
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
        </div> {{-- /.modal-dialog --}}
    </div> {{-- /.vertical-alignment-helper --}}
</div> {{-- /.modal --}}