@extends('layouts.login')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><center><h3>Reset Password</h3></center></div>
                <div class="panel-body">
                    {{-- @if (count($errors) > 0) --}}
                    <!--						<div class="alert alert-danger">
                                                                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                                                            <ul>
                                                                                    @foreach ($errors->all() as $error)
                                                                                            <li>{{ $error }}</li>
                                                                                    @endforeach
                                                                            </ul>
                                                                    </div>-->
                    {{-- @endif --}}

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="token" value="{{ $token }}">
						<input type="hidden" class="form-control" name="email" value="{{ $email }}">

                       <!-- <div class="form-group">
                            <label class="col-md-4 control-label">E-Mail Address&nbsp;<span  class='required'>*</span></label>
                            <div class="col-md-6">
                                <input type="hidden" class="form-control" name="email" value="{{ $email }}">
                                @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                            </div>
                        </div> -->

                        <div class="form-group">
                            <label class="col-md-4 control-label">Password&nbsp;<span  class='required'  style="color:#a94442;">*</span></label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">
                                @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Confirm Password&nbsp;<span  class='required'  style="color:#a94442;">*</span></label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation">
                                @if ($errors->has('password_confirmation')) <p class="help-block">{{ $errors->first('password_confirmation') }}</p> @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="button btn-primary">
                                    Reset Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
