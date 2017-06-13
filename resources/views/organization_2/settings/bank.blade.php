
                                    <div class="text-center">
                                            <div class="payment_details">
    <div class="row">
        @if($bank_accounts !== null)
            @foreach($bank_accounts as $bank_account)
                <div class="col-sm-6">
                    <div class="account-holder-box existing_accounts">
                        {!! Form::radio('vendor_bank_account_id', $bank_account->id, null, ['id' => 'foo_$bank_account->id', 'class'=>'gui-input']) !!}
                        <label for="{{$bank_account->id}}">
                            <div>{{$bank_account->account_holder_name}}</div>
                            <div>{{$bank_account->account_number}}</div>
                            <div>{{$bank_account->bank_name}}</div>
                            <div>{{$bank_account->branch}}</div>
                            <div>{{$bank_account->ifsc}}</div>
                        </label>
                        
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <div class="add_account_div" data-toggle='modal' data-target='#add_bank_account_form'><i style="font-size:40px;cursor: pointer;" class="fa fa-plus-circle"></i> <br/> Add another account</div>
        </div>
    </div>
    <div class="payment_form modal " id="add_bank_account_form" style="">
          <div class="modal-dialog" role="document">
            <div class="modal-content">   
                <div class=" sportsjun-forms">
                <div class="form-body">
                    <form action="/organization/{{$organisation->id}}/settings/add_bank">
                    {!!csrf_field() !!}  

        <div class="row">
            <div class="col-sm-6">                       
                <div class="section">
                    <label class="form_label">{{  trans('message.tournament.fields.account_holder_name') }}</label> 
                    <label for="account_holder_name" class="field prepend-icon">
                         
                        {!! Form::text('account_holder_name', null, array('required','class'=>'gui-input','placeholder'=>trans('message.tournament.fields.account_holder_name'))) !!}
                        <p class="help-block validation_msg" id="account_name_validator" style="display:none">Please enter your name</p>
                        @if ($errors->has('account_holder_name')) <p class="help-block">{{ $errors->first('account_holder_name') }}</p> @endif
                    <label for="account_holder_name" class="field-icon"><i class="fa fa-user"></i></label>  
                    </label>
                </div>
            </div>
            <div class="col-sm-6">                       
                <div class="section">
                    <label class="form_label">{{  trans('message.tournament.fields.account_number') }}</label> 
                    <label for="account_number" class="field prepend-icon">
                         
                        {!! Form::text('account_number', null, array('required','class'=>'gui-input','placeholder'=>trans('message.tournament.fields.account_number'))) !!}
                        <p class="help-block validation_msg" id="account_number_validator" style="display:none">Please enter a valid account number</p>
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
                         
                        {!! Form::text('bank_name', null, array('required','class'=>'gui-input','placeholder'=>trans('message.tournament.fields.bank_name'))) !!}
                        <p class="help-block validation_msg" id="account_bankname_validator" style="display:none">Please enter your bank name</p>
                        @if ($errors->has('bank_name')) <p class="help-block">{{ $errors->first('bank_name') }}</p> @endif
                    <label for="bank_name" class="field-icon"><i class="fa fa-university"></i></label>  
                    </label>
                </div>
            </div>
            <div class="col-sm-6">                       
                <div class="section">
                    <label class="form_label">{{  trans('message.tournament.fields.branch') }}</label> 
                    <label for="branch" class="field prepend-icon">
                         
                        {!! Form::text('branch', null, array('required','class'=>'gui-input','placeholder'=>trans('message.tournament.fields.branch'))) !!}
                        <p class="help-block validation_msg" id="account_branch_validator" style="display:none">Please enter your  branch</p>
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
                         
                        {!! Form::text('ifsc', null, array('required','class'=>'gui-input','placeholder'=>trans('message.tournament.fields.ifsc'))) !!}
                        <p class="help-block validation_msg" id="account_ifsc_validator" style="display:none">Please enter your  IFSC</p>
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

             <div class="row">
            <div class="col-sm-12">                       
                <input type="submit" name="" value="Add Pament Method" class="btn btn-primary">
            </div>
            </div>
         
    </div>
    </form>
    </div>
    <div class="row">
            <div class="col-sm-12 validation_msg bank_account_validation"" style="display:none;">
               please select/add account to make transactions
            </div>
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
                                    </div>
                                </div>
                            </div>
                       