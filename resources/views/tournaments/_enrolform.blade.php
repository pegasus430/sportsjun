<div class="form_enrol{{$formType}}">
    <div class="row">
        <div class="col-sm-12">
            <div class="section">
                <h3>{{ trans('message.tournament.fields.reg_reg_heading') }}</h3>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="section">
                <label class="form_label">{{  trans('message.tournament.fields.reg_opening_date') }} <span  class='required'>*</span></label>         
                <label class='field' >
                    <div class="input-group date" id='reg_opening_date'>
                        {!! Form::text('reg_opening_date', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.reg_opening_date'))) !!}
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                    @if ($errors->has('reg_opening_date')) <p class="help-block">{{ $errors->first('reg_opening_date') }}</p> @endif
                </label>
            
            </div>
        </div>
        <div class="col-sm-3">
            <div class="section">
                <label class="form_label">{{  trans('message.tournament.fields.reg_opening_time') }}</label>         
                <label class='field' >
                    <div class="input-group date" id="reg_opening_time">
                        {!! Form::text('reg_opening_time', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.reg_opening_time'))) !!}
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                    @if ($errors->has('reg_opening_time')) <p class="help-block">{{ $errors->first('reg_opening_time') }}</p> @endif
                </label>
            
            </div>
        </div>
        <div class="col-sm-3">
            <div class="section">
                <label class="form_label">{{  trans('message.tournament.fields.reg_closing_date') }} <span  class='required'>*</span></label>         
                <label class='field' >
                    <div class="input-group date" id='reg_closing_date'>
                        {!! Form::text('reg_closing_date', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.reg_closing_date'))) !!}
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                    @if ($errors->has('reg_closing_date')) <p class="help-block">{{ $errors->first('reg_closing_date') }}</p> @endif
                </label>
            
            </div>
        </div>
        <div class="col-sm-3">
            <div class="section">
                <label class="form_label">{{  trans('message.tournament.fields.reg_closing_time') }}</label>         
                <label class='field' >
                    <div class="input-group date" id='reg_closing_time'>
                        {!! Form::text('reg_closing_time', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.reg_closing_time'))) !!}
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                    @if ($errors->has('reg_closing_time')) <p class="help-block">{{ $errors->first('reg_closing_time') }}</p> @endif
                </label>
            
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">                       
            <div class="section">
                <label class="form_label">{{  trans('message.tournament.fields.total_enrollment') }}</label> 
                <label for="total_enrollment" class="field prepend-icon">
                     
                    {!! Form::text('total_enrollment', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.total_enrollment'))) !!}
                    @if ($errors->has('total_enrollment')) <p class="help-block">{{ $errors->first('total_enrollment') }}</p> @endif
                <label for="total_enrollment" class="field-icon"><i class="fa fa-users"></i></label>  
                </label>
            </div>
        </div>
        <div class="col-sm-4">                       
            <div class="section">
                <label class="form_label">{{  trans('message.tournament.fields.min_enrollment') }}</label> 
                <label for="min_enrollment" class="field prepend-icon">
                     
                    {!! Form::text('min_enrollment', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.min_enrollment'))) !!}
                    @if ($errors->has('min_enrollment')) <p class="help-block">{{ $errors->first('min_enrollment') }}</p> @endif
                <label for="min_enrollment" class="field-icon"><i class="fa fa-users"></i></label>  
                </label>
            </div>
        </div>
        <div class="col-sm-4">                       
            <div class="section">
                <label class="form_label">{{  trans('message.tournament.fields.max_enrollment') }}</label> 
                <label for="max_enrollment" class="field prepend-icon">
                     
                    {!! Form::text('max_enrollment', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.max_enrollment'))) !!}
                    @if ($errors->has('max_enrollment')) <p class="help-block">{{ $errors->first('max_enrollment') }}</p> @endif
                <label for="max_enrollment" class="field-icon"><i class="fa fa-users"></i></label>  
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">                       
            <div class="section">
                <label class="form_label">{{  trans('message.tournament.fields.price_per_enrolment') }}</label> 
                <label for="price_per_enrolment" class="field prepend-icon">
                     
                    {!! Form::text('online_enrollment_fee', $online_enrollment_fee, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.price_per_enrolment'))) !!}
                    @if ($errors->has('price_per_enrolment')) <p class="help-block">{{ $errors->first('price_per_enrolment') }}</p> @endif
                <label for="price_per_enrolment" class="field-icon"><i class="fa fa-inr"></i></label>  
                </label>
            </div>
        </div>
        <div class="col-sm-6 sold_check" style="display: none;">                       
            <div class="section">
                <label class="form_label"><br/></label> 
                <label class = "option block">
                    {!! Form::checkbox('is_sold_out', 1, 0, ['id' => 'is_sold_out', 'class'=>'gui-input']) !!}
                    <span class = "checkbox"></span>{{ trans('message.tournament.fields.is_sold_out') }}
                    @if ($errors->has('is_sold_out'))
                        <p class = "help-block">{{ $errors->first('is_sold_out') }}</p> @endif
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="section">
                <label class="form_label">{{  trans('message.tournament.fields.terms_conditions') }} </label> 
                    <label for="comment" class="field prepend-icon">
                    {!! Form::textarea('terms_conditions', null, array('class'=>'gui-textarea','placeholder'=>trans('message.tournament.fields.terms_conditions'),'style'=>'resize:none','rows'=>3)) !!}
                    @if ($errors->has('terms_conditions')) <p class="help-block">{{ $errors->first('terms_conditions') }}</p> @endif
                    <label for="comment" class="field-icon"><i class="fa fa-comments"></i></label>
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="section">
                
                    <label for="comment" class="field prepend-icon">
                    {!! Form::checkbox('agree', 'yes',false, array('required','class'=>'gui-checkbox', 'id'=>'disclaimer_agree')) !!}
                    <span class="agree_conditions">{{$disclaimer_content}}</span>
                    <p class="help-block" id="agree_conditions-val">Please agree our Terms and Conditions</p> 
                    
                </label>
            </div>
        </div>
    </div>






</div>
<div class="payment_details{{$formType}}">
    <div class="row">
        @if($vendorBankAccounts !== null)
            @foreach($vendorBankAccounts as $vendorBankAccount)
                <div class="col-sm-6">
                    <div class="account-holder-box">
                        {!! Form::radio('vendor_bank_account_id', $vendorBankAccount->id, null, ['id' => 'foo_$vendorBankAccount->id', 'class'=>'gui-input']) !!}
                        <label for="{{$vendorBankAccount->id}}">
                            <div>{{$vendorBankAccount->account_holder_name}}</div>
                            <div>{{$vendorBankAccount->account_number}}</div>
                            <div>{{$vendorBankAccount->bank_name}}</div>
                            <div>{{$vendorBankAccount->branch}}</div>
                            <div>{{$vendorBankAccount->ifsc}}</div>
                        </label>
                        
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <div class="add_account_div{{$formType}}"><i style="font-size:40px;cursor: pointer;" class="fa fa-plus-circle"></i> <br/> Add another account</div>
        </div>
    </div>
    <div class="payment_form{{$formType}}">
        <div class="row">
            <div class="col-sm-6">                       
                <div class="section">
                    <label class="form_label">{{  trans('message.tournament.fields.account_holder_name') }}</label> 
                    <label for="account_holder_name" class="field prepend-icon">
                         
                        {!! Form::text('account_holder_name', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.account_holder_name'))) !!}
                        @if ($errors->has('account_holder_name')) <p class="help-block">{{ $errors->first('account_holder_name') }}</p> @endif
                    <label for="account_holder_name" class="field-icon"><i class="fa fa-user"></i></label>  
                    </label>
                </div>
            </div>
            <div class="col-sm-6">                       
                <div class="section">
                    <label class="form_label">{{  trans('message.tournament.fields.account_number') }}</label> 
                    <label for="account_number" class="field prepend-icon">
                         
                        {!! Form::text('account_number', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.account_number'))) !!}
                        @if ($errors->has('account_number')) <p class="help-block">{{ $errors->first('account_number') }}</p> @endif
                    <label for="account_number" class="field-icon"><i class="fa fa-credit-card"></i></label>  
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">                       
                <div class="section">
                    <label class="form_label">{{  trans('message.tournament.fields.bank_name') }}</label> 
                    <label for="bank_name" class="field prepend-icon">
                         
                        {!! Form::text('bank_name', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.bank_name'))) !!}
                        @if ($errors->has('bank_name')) <p class="help-block">{{ $errors->first('bank_name') }}</p> @endif
                    <label for="bank_name" class="field-icon"><i class="fa fa-university"></i></label>  
                    </label>
                </div>
            </div>
            <div class="col-sm-6">                       
                <div class="section">
                    <label class="form_label">{{  trans('message.tournament.fields.branch') }}</label> 
                    <label for="branch" class="field prepend-icon">
                         
                        {!! Form::text('branch', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.branch'))) !!}
                        @if ($errors->has('branch')) <p class="help-block">{{ $errors->first('branch') }}</p> @endif
                    <label for="branch" class="field-icon"><i class="fa fa-briefcase"></i></label>  
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">                       
                <div class="section">
                    <label class="form_label">{{  trans('message.tournament.fields.ifsc') }}</label> 
                    <label for="ifsc" class="field prepend-icon">
                         
                        {!! Form::text('ifsc', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.ifsc'))) !!}
                        @if ($errors->has('ifsc')) <p class="help-block">{{ $errors->first('ifsc') }}</p> @endif
                    <label for="ifsc" class="field-icon"><i class="fa fa-university"></i></label>  
                    </label>
                </div>
            </div>
            
        </div>
        <div class="row">
            <div class="col-sm-12">                       
                <div class="section">
                     @include ('common.upload')
                    <label class="form_label">{{  trans('message.tournament.fields.file_upload') }} </label>
                    @include ('common.uploadfield', ['uploadLimit' => 'null','field'=>'file_upload'])
                </div> 
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                {{  trans('message.tournament.bank_form_warning') }}
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .account-holder-box{
        border-radius: 5px;
        background-color: #fcde7e;
        padding: 10px;
    }
    .account-holder-box .iradio_square-green{
        float: right;
    }
</style>