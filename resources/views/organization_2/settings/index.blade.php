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
                                <li class=""> <a href="#payment_settings" data-toggle="tab" aria-expanded="false">
                                PAYPAL</a> </li>
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
                                        @include('organization_2.settings.password')
                                </div>
                                <div class="tab-pane" id="bank_settings">
                                        @include('organization_2.settings.bank')


                             
                                <div class="tab-pane" id='payment_settings'>
                                      @include('organization_2.settings.paypal')
                                </div>
            </div>
        </div>
        </div>
         </div>
                    </div>
@stop