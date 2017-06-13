        <div class="row">                                
                    <div class=" col-sm-offset-3 col-sm-6">  
                    <div class="sportsjun-forms">          
                                            <form action="/organization/{{$organisation->id}}/settings/change_password" method="post">
                                    {!!csrf_field() !!}
                                                <div class="col-sm-12">                       
                <div class="section">
                    <label class="form_label">Old Password</label> 
                    <label for="old_password" class="field prepend-icon">
                         
                        {!! Form::text('old_password', null, array('required','class'=>'gui-input','placeholder'=>'Old password')) !!}
                        
                    <label for="old_password" class="field-icon"><i class="fa fa-user"></i></label>  
                    </label>
                </div>
            </div>

            <div class="col-sm-12">                       
                <div class="section">
                    <label class="form_label">New Password</label> 
                    <label for="new_password" class="field prepend-icon">
                         
                        {!! Form::text('new_password', null, array('required','class'=>'gui-input')) !!}
                     
                    <label for="new_password" class="field-icon"><i class="fa fa-lock"></i></label>  
                    </label>
                </div>
            </div>

            <div class="col-sm-12">                       
                <div class="section">
                    <label class="form_label">Confirm Password</label> 
                    <label for="new_password_2" class="field prepend-icon">
                         
                        {!! Form::text('new_password_2', null, array('required','class'=>'gui-input')) !!}
                       
                    <label for="new_password_2" class="field-icon"><i class="fa fa-lock"></i></label>  
                    </label>
                </div>
            </div>

            <div class="row">
            <div class="col-sm-12">                       
                <input type="submit" name="" value="Update Password" class="btn btn-primary">
            </div>
            </div>


                </form>

        </div>

    </div>
</div>