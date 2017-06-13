@extends('layouts.organisation')

@section('content')

<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Create News</h2>
					<div class="create-btn-link">
						<a href="/organization/{{$organisation->id}}/news/manage" class="wg-cnlink">Manage News</a>
					</div>
				</div>
			</div>

<div class="container-fluid col-sm-12">
	<div class="sportsjun-forms sportsjun-container wrap-1">
<div class="form-header header-primary"><h4><i class="fa fa-pencil-square"></i>Add News</h4></div>

					<form action="/organization/{{$organisation->id}}/news/add" method="post" class="form form-horizontal">
					<div class="form-body">
						{!! csrf_field() !!}
						<div class="row">	
<div class="col-sm-12">					
<div class="section">
    	<label class="form_label">Title<span  class='required'>*</span> </label>
	<label class="field prepend-icon">
		{!! Form::text('title', null, array('required','class'=>'gui-input','placeholder'=> 'News title')) !!}
		@if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
       
	</label>
</div>
</div>	

<div class="col-sm-12" 	>
							
@include ('common.upload')
@include ('common.uploadfield', ['uploadLimit' => '1','field'=>'photos','fieldname'=>'Choose  Image'])
</div>
						
							<div class="col-sm-12">
<div class="section">
    	<label class="form_label">Category </label>
	<label class="field select">

	{!! Form::select('category_id',Helper::getAllSports()->lists('sports_name','id'),null, array('class'=>'gui-input','id'=>'team')) !!}
	@if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
     <i class="arrow double"></i>      
	</label>
</div>

							<div class="section summernote_wrapper">
	<label class="form_label">Description </label>
		   {!! Form::textarea('details', null, array('id'=>'match_report','class'=>'summernote','style'=>'resize:none','rows'=>3)) !!}
		   @if ($errors->has('about')) <p class="help-block">{{ $errors->first('about') }}</p> @endif
	
	</label>

	</div>



				<center>
								<button type="submit" class="btn btn-primary">Create</button>
							</center>
						</div>
					</form>
					</div>
				</div>
			</div>
		</div>

@stop