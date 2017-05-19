@extends('layouts.organisation')

@section('content')


        <div class="container">
            <div class="row">              
                <div class="col-sm-12">
                    <div class="wg wg-dk-grey no-shadow no-margin">
                        <div class="wg-wrap clearfix">
                            <h4 class="no-margin pull-left"><i class="fa fa-pencil-square"></i> Settings</h4></div>
                    </div>
                    <div class="wg no-margin tabbable-panel create_tab_form">
                        <div class="tabbable-line">
                            <ul class="nav nav-tabs ">
                                <li class="active"> <a href="#info" data-toggle="tab">
                                INFO</a> </li>
                                <li class=""> <a href="#change_password" data-toggle="tab">
								CHANGE PASSWORD</a> </li>
                                <li class=""> <a href="#bank_settings" data-toggle="tab" aria-expanded="false">
								BANK SETTINGS </a> </li>
                            </ul>
                            <div class="tab-content">
                                 <div class="tab-pane active" id="info">
                                    <div class="sportsjun-forms sportsjun-container wrap-2">
                                 {!! Form::model($organization,(array('route' => array('organization.update',$id),'class'=>'form-horizontal','method' => 'put','id' => 'organization-form'))) !!}
             <div class="form-body">             
                        @include ('organization._form', ['submitButtonText' => 'Update'])
                        </div>
                         <div class="form-footer">
                  <button type="submit" class="button btn-primary">Update</button>

                </div>      
                    {!! Form::close() !!}
                {!! JsValidator::formRequest('App\Http\Requests\CreateOrganizatonRequest', '#organization-form'); !!}

                                </div>
                            </div>


                                <div class="tab-pane " id="change_password">
                <div class="row">                                
                    <div class=" col-sm-offset-3 col-sm-6">  
                    <div class="sportsjun-forms">          
                                            <form action="/organization/{{$organisation->id}}/settings/change_password" method="post">
                                    {!!csrf_field() !!}
                                                <div class="col-sm-12">                       
                <div class="section">
                    <label class="form_label">Old Password</label> 
                    <label for="account_holder_name" class="field prepend-icon">
                         
                        {!! Form::text('old_password', null, array('required','class'=>'gui-input','placeholder'=>'Old password')) !!}
                        
                    <label for="account_holder_name" class="field-icon"><i class="fa fa-user"></i></label>  
                    </label>
                </div>
            </div>

            <div class="col-sm-12">                       
                <div class="section">
                    <label class="form_label">New Password</label> 
                    <label for="account_holder_name" class="field prepend-icon">
                         
                        {!! Form::text('new_password', null, array('required','class'=>'gui-input')) !!}
                     
                    <label for="account_holder_name" class="field-icon"><i class="fa fa-lock"></i></label>  
                    </label>
                </div>
            </div>

            <div class="col-sm-12">                       
                <div class="section">
                    <label class="form_label">Confirm Password</label> 
                    <label for="account_holder_name" class="field prepend-icon">
                         
                        {!! Form::text('new_password_2', null, array('required','class'=>'gui-input')) !!}
                       
                    <label for="account_holder_name" class="field-icon"><i class="fa fa-lock"></i></label>  
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
</div>
                                <div class="tab-pane" id="bank_settings">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
@stop