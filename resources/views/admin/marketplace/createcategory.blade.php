@extends('admin.layouts.app')
@section('content')
@include ('admin.marketplace._leftmenu')
<div id="page-wrapper">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
			
				
			
                <div class="panel-heading">{{ trans('message.marketplace.fields.categoryheading') }}</div>
				                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
                <div class="panel-body">
				
				<?php if((isset($category_array[0]['name']))) {
						$form = Form::open(array('url' => 'admin/updatecategory'));
					} else {
						$form = Form::open(array('url' => 'admin/insertcategory'));
					}?>
				
					
					<?php echo $form; ?>
					 <input type="hidden" name="_token" value="{{ csrf_token() }}">
					 
					 
					<div class="form-group">
                            <label class="col-md-4 control-label">{{ trans('message.marketplace.fields.categoryname') }}</label>
                            <div class="col-md-6">
							<input type="text" class="form-control" name="category" value="<?php echo (isset($category_array[0]['name']))?$category_array[0]['name']:''?>">
							<input type="hidden" class="form-control" name="category_id" value="<?php echo (isset($category_array[0]['id']))?$category_array[0]['id']:''?>">
							
					 @if ($errors->has('category')) <p class="help-block">{{ $errors->first('category') }}</p> @endif
					
					
                            </div>
                    </div>
		
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