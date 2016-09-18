<div class="row">
    <div class="section col-sm-6">
    	<label class="form_label">Select Category <span  class='required'>*</span></label>
        <label class="field select">
             {!! Form::select('marketplace_category_id', $marketPlaceCategories, null,array('required','class'=>'gui-input','placeholder'=> trans('message.marketplace.fields.category'))) !!}              
                @if ($errors->has('marketplace_category_id')) <p class="help-block">{{ $errors->first('marketplace_category_id') }}</p> @endif
            <i class="arrow double"></i>                    
        </label>  
    </div><!-- end section -->
    
    <div class="section col-sm-6">
    	<label class="form_label">Item Name <span  class='required'>*</span></label>
        <label for="item" class="field prepend-icon">
             {!! Form::text('item', null, array('required','class'=>'gui-input','placeholder'=>trans('message.marketplace.fields.item'))) !!}
            @if ($errors->has('item')) <p class="help-block">{{ $errors->first('item') }}</p> @endif
        </label>
    </div> 
</div>

<div class="section">
	<label class="form_label">Item Description</label>
	<label for="item" class="field prepend-icon">
		   {!! Form::textarea('item_description', null, array('class'=>'gui-textarea','placeholder'=>trans('message.marketplace.fields.itemdescription'),'style'=>'resize:none','rows'=>3)) !!}
        @if ($errors->has('item_description')) <p class="help-block">{{ $errors->first('item_description') }}</p> @endif
		
	</label>
</div> 


<div class="row">
    <div class="section col-sm-6">
    	<label class="form_label">Base Price <span  class='required'>*</span></label>
        <label for="item" class="field prepend-icon">
                {!! Form::text('base_price', null, array('required','class'=>'gui-input','placeholder'=>trans('message.marketplace.fields.baseprice'))) !!}
            @if ($errors->has('base_price')) <p class="help-block">{{ $errors->first('base_price') }}</p> @endif
        </label>
    </div> 
    
    <div class="section col-sm-6">
    	<label class="form_label">Actual Price <span  class='required'>*</span></label>
        <label for="item" class="field prepend-icon">
               {!! Form::text('actual_price', null, array('class'=>'gui-input','placeholder'=>trans('message.marketplace.fields.actualprice'))) !!}
            @if ($errors->has('actual_price')) <p class="help-block">{{ $errors->first('actual_price') }}</p> @endif
        </label>
    </div> 
</div>

<div class="row">
    <div class="section col-sm-6">
        <label class="form_label">Item Type</label>
        <label class="field select">
              {!! Form::select('item_type', $enum,null,array('class'=>'gui-input','placeholder'=>trans('message.marketplace.fields.type')),Input::old('item_type')) !!}
            @if ($errors->has('item_type')) <p class="help-block">{{ $errors->first('item_type') }}</p> @endif
            <i class="arrow double"></i>                    
        </label>  
    </div>
</div><!-- end section -->
@include ('common.upload')
<label class="form_label">{{  trans('message.marketplace.fields.gallery') }} </label>
@include ('common.uploadfield', ['uploadLimit' => 'null','field'=>'photos'])

<div class="spacer-b30">
			<div class="tagline"><span>Contact Details</span></div><!-- .tagline -->
		</div>

@include ('common.address', ['mandatory' => ''])
@if(isset($marketplace))
@include('common.editphoto',['photos'=>$marketplace->photos,'type'=>'marketplace'])
@endif


