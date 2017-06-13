        <div class="row">                                
                    <div class=" col-sm-offset-2 col-sm-8">  
                    <div class="sportsjun-forms">          
                                            <form action="/organization/{{$organisation->id}}/settings/payment_settings" method="post">
                                    {!!csrf_field() !!}
            <div class="col-sm-6">                       
                <div class="section">
                    <label class="form_label">PayPal Username</label> 
                    <label for="old_password" class="field prepend-icon">
                         
                        {!! Form::text('paypal_username', $paymentsetting?$paymentsetting->paypal_username:null, array('required','class'=>'gui-input','placeholder'=>'Paypal Username')) !!}
                        
                    <label for="old_password" class="field-icon"><i class="fa fa-user"></i></label>  
                    </label>
                </div>
            </div>

            <div class="col-sm-6">                       
                <div class="section">
                    <label class="form_label">Paypal Password</label> 
                    <label for="" class="field prepend-icon">
                         
                        {!! Form::text('paypal_password', $paymentsetting?$paymentsetting->paypal_password:null, array('required','class'=>'gui-input')) !!}
                     
                    <label for="" class="field-icon"><i class="fa fa-lock"></i></label>  
                    </label>
                </div>
            </div>

            <div class="col-sm-6">                       
                <div class="section">
                    <label class="form_label">Paypal Signature</label> 
                    <label for="" class="field prepend-icon">                         
                        {!! Form::text('paypal_signature', $paymentsetting?$paymentsetting->paypal_signature:null, array('required','class'=>'gui-input','placeholder'=>'Paypal Signature')) !!}
                     
                    <label for="" class="field-icon"><i class="fa fa-lock"></i></label>  
                    </label>
                </div>
            </div>

             <div class="col-sm-6">                       
                <div class="section">
                    <label class="form_label">Paypal Sandbox?</label> 
                    <label for="" class="field  select ">                         
                        {!! Form::select('paypal_sandbox',[0=>'No','1'=>'Yes'], $paymentsetting?$paymentsetting->paypal_sandbox:null, array('required','class'=>'gui-input')) !!}
                     
                   <i class="arrow double"></i> 
                    </label>
                </div>
            </div>

            
            <div class="row">
            <div class="col-sm-12">                       
                <input type="submit" name="" value="Update" class="btn btn-primary">
            </div>
            </div>


                </form>

        </div>

    </div>
</div>