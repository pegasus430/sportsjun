@extends('layouts.app')
@section('content')
<a  href="{{ url('/admin/viewsports') }}">Sports List</a> &nbsp&nbsp
<a  href="{{ url('/admin/createoption') }}">Create Options & Questions</a>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
			
				
			
                <div class="panel-heading">{{ trans('message.sports.fields.sportheading') }}</div>
				                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
                <div class="panel-body">
				
					<?php if((isset($sports_array[0]['sports_name']))) {
						$form = Form::open(array('url' => '/admin/editsport','files' => true));
					} else {
						$form = Form::open(array('url' => '/admin/insertsport','files' => true));
					}?>
					<?php echo $form; ?>
					 <input type="hidden" name="_token" value="{{ csrf_token() }}">
					 
					 
					<div class="form-group">
                            <label class="col-md-4 control-label">{{ trans('message.sports.fields.sportsname') }}</label>
                            <div class="col-md-6">
							<input type="text" class="form-control" name="sportsname" value="<?php echo (isset($sports_array[0]['sports_name']))?$sports_array[0]['sports_name']:''?>">
							<input type="hidden" value="<?php echo (isset($sports_array[0]['id']))?$sports_array[0]['id']:''?>" name="sports_id">
                               					<?php //echo Form::text('sportsname');?>
					 @if ($errors->has('sportsname')) <p class="help-block">{{ $errors->first('sportsname') }}</p> @endif
					
					
                            </div>
                    </div>

					@if(isset($sport_image))
						@include('common.editphoto',['photos'=>$sport_image->photos,'type'=>'sports'])
					@endif
					
					@include ('common.upload')
					@include ('common.uploadfield', ['uploadLimit' => '1','field'=>'logo'])
						
						                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="button btn-primary">
                                  <?php //echo Form::submit('Save');?>
								  Save
                                </button>
                            </div>
                        </div>
<?php echo Form::close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection