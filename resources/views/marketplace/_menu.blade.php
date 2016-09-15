<div class="col-md-2 col-sm-2 sidebar_bg">
	<div class="sportsjun-forms">
    <div class="mp_left sportsjun-forms">
    
<!--     <form role="form">
  <div class="form-group">
    <label for="email">Email address:</label>
    <input type="email" class="form-control" id="email">
  </div>
  <div class="form-group">
    <label for="pwd">Password:</label>
    <input type="password" class="form-control" id="pwd">
  </div>
  <div class="checkbox">
    <label><input type="checkbox"> Remember me</label>
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
</form>-->

  
                <div class="section">
                 <!-- <label class="field prepend-icon">
                  <input type="text" class="gui-input search" placeholder="I'm Looking for">
                  <span class="field-icon"><i class="fa fa-search"></i></span>  
                  </label>-->

<!--                                <label class="field prepend-icon">
                                    <input type="search" placeholder="I'm Looking for" class="gui-input" id="s" name="s">
                                    <span class="field-icon"><i class="fa fa-search"></i></span>  
                                </label>-->

                </div>
                 <form action="{{ url('/marketplace') }}"> 
	


<!-- <div class="form-group">
			<div>

				  <label >{!! Form::radio('all','all',null,array('class'=>'form-control','id'=>"all",'class'=>"radio1")) !!}All</label>
				   <label >{!! Form::radio('all','myitems',null,array('class'=>'form-control','id'=>"myitems",'class'=>"radio1")) !!}My items</label>

		 </div>
 </div>-->
 
 
 <?php /* <div class="form-group">
    <h3>State</h3>
    <div class="section">
    <label class="field select">
        {!! Form::select('state_id',$states,null, array('id'=>'state_id','class'=>'gui-input','onchange'=>'displayStates(this.value)')) !!}
        @if ($errors->has('state_id')) <p class="help-block">{{ $errors->first('state_id') }}</p> @endif
        <i class="arrow double"></i>
        </label>
    </div>
   </div>

<div class="form-group">
    <h3>City</h3>
    <div class="section">
    <label class="field select">
        {!! Form::select('city_id',$cities,null, array('id'=>'city_id','class'=>'gui-input','id'=>'city_id')) !!}
        @if ($errors->has('city_id')) <p class="help-block">{{ $errors->first('city_id') }}</p> @endif
        <i class="arrow double"></i>
    </label>
    </div>
</div> */ ?>

                 <input type="hidden" name="max" id="max" value=<?php echo $max; ?>>				
                  <h3>CATEGORIES</h3>             
                  <div class="checklist list" id="id1" >
          <ul>
          <!--  <li> <input    id="selecctall" type="checkbox" value="all" class="allcheckbox">&nbsp;<span></span>All</a></li> -->
            @foreach( $marketPlaceCategories as $categories)                                      
                          <li><input class="checkbox1" name="categorytype[]" type="checkbox" autocomplete="off" value='{{$categories['id'] }}' <?php if(isset($request_params) && $request_params['category'] == $categories['id']){?> checked="checked"<?php }?>>&nbsp;{{$categories['name'] }}</a></li>
                      @endforeach 
                   </ul>
          
                  </div>
          <h3>ITEM TYPE</h3>
                  <div class="checklist filters">
				  <ul>
                      <li> <input class="checkbox2" name="itemtype[]" type="checkbox" autocomplete="off" value='new'>&nbsp;<span></span>new</li>
                      <li> <input class="checkbox2" name="itemtype[]" type="checkbox" autocomplete="off" value='used'>&nbsp;<span></span>used</li>
				  </ul>
                  </div>
		 @if($page!='marketplace')
		  <h3>ITEM Status</h3>
	         <div class="checklist filters">
				  <ul>
				   <li> <input class="checkbox3" name="itemstatus[]" type="checkbox" autocomplete="off" value='available'>&nbsp;<span></span>Available</li>
                     <li> <input class="checkbox3" name="itemstatus[]" type="checkbox" autocomplete="off" value='sold out'>&nbsp;<span></span>Sold Out</li>
				 </ul>
				 	</div>
        @endif
	
           <div class="form-group">
	          <h3 for="amount">Price range:</h3>
	          <input type="text" id="amount" readonly  class="gui-input">
         </div>
		
        <div class="form-group slide">	
            
                <div id="slider-range"></div>
</div>

            

			 <input type="button" value="Go"  onclick="marketplace(this);"  id="go" class="button btn-primary" />
        <span class="clear_filter btn-link btn-secondary-link" onclick=" location.reload();" style="display: inline-block;padding-top: 3px;border: none;">Clear</span> 
                     </button>
         </form >                    
	</div>
    </div>
</div>