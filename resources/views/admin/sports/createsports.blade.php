@extends('admin.layouts.app')
@section('content')
@include ('admin.sports._leftmenu')
<div id="page-wrapper">
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
				
					<?php $readonly=''; if((isset($sports_array[0]['sports_name']))) {
						$readonly='';
						if($sports_array[0]['id']==1 || $sports_array[0]['id']==2 || $sports_array[0]['id']==3 || $sports_array[0]['id']==4)
						{
							$readonly='readonly';
						}
						$form = Form::open(array('url' => '/admin/editsport','files' => true));
					} else {
						$form = Form::open(array('url' => '/admin/insertsport','files' => true));
						$readonly='';
					}?>
					<?php echo $form; ?>
					 <input type="hidden" name="_token" value="{{ csrf_token() }}">
					 
					 
					<div class="form-group">
                            <label class="col-md-4 control-label">{{ trans('message.sports.fields.sportsname') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="sportsname" <?php echo $readonly;?> value="<?php echo (isset($sports_array[0]['sports_name']))?$sports_array[0]['sports_name']:''?>">
							<input type="hidden" value="<?php echo (isset($sports_array[0]['id']))?$sports_array[0]['id']:''?>" name="sports_id">
                               					<?php //echo Form::text('sportsname');?>
					 @if ($errors->has('sportsname')) <p class="help-block " style="color:#a94442;">{{ $errors->first('sportsname') }}</p> @endif
					
					
                            </div>
                    </div>
					<?php  //echo $errors->first('sports_type') ; echo $errors->first('sportsname');?>
					 <div class="form-group">
                            <label class="col-md-4 control-label">Sports Type</label>
                            <div class="col-md-6">								
								<select name="sports_type" class="form-control">
								
									<?php foreach($sports_type as $key=>$value){?>
										<option value="<?php echo $key;?>" <?php if(isset($sports_array[0]['sports_type']) && $sports_array[0]['sports_type']==$key) { echo 'selected';}?>><?php echo $value;?></option>
									<?php } ?>
									
								</select>
							@if ($errors->has('sports_type')) <p class="help-block" style="color:#a94442;">{{ $errors->first('sports_type') }}</p> @endif
                            </div>
                     </div>
<br><br>
				
					
					@include ('common.upload')
					@include ('common.uploadfield', ['uploadLimit' => '1','field'=>'logo'])
					@if(isset($sport_image))
						@include('common.editphoto',['photos'=>$sport_image->photos,'type'=>'sports'])
					@endif
						
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
</div>
@endsection