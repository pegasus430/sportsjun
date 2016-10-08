<div class="col_left">
	<div class="mp_left">
    
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
<ul class="nav nav-pills">
<!--  <li><a class="sidemenu_2" href="{{ url('/marketplace/myitems') }}">My Items</a></li>
-->
     <li class="active"><a href="{{ url('/marketplace/create') }}">create</a></li>
  </ul>
<ul class="nav nav-pills">
<!--  <li><a class="sidemenu_2" href="{{ url('/marketplace/myitems') }}">My Items</a></li>
-->  <li class="active"><a href="{{ url('/marketplace/myitems') }}">My Items</a></li>
    
  </ul>
  
                <div class="form-group">
                  <input type="text" class="form-control search" placeholder="I'm Looking for">
                </div>
                 <form action="{{ url('/marketplaceSearch') }}"> 
	


 <div class="form-group">
         <label class="col-md-4 control-label"  ></label>
			<div>
			   <!-- <input type="radio" name="verified" value="1">&nbsp;Yes
				  <input type="radio" name="verified" value="0">&nbsp;No-->
				  <label >{!! Form::radio('all','all',null,array('class'=>'form-control','id'=>"all",'class'=>"radio1")) !!}All</label>
				   <label >{!! Form::radio('all','myitems',null,array('class'=>'form-control','id'=>"myitems",'class'=>"radio1")) !!}My items</label>

		 </div>
 </div>
 
 
 <div class="form-group">
    <label>State</label>
    <div >
        {!! Form::select('state_id',$states,null, array('id'=>'state_id','class'=>'form-control','onchange'=>'displayStates(this.value)')) !!}
        @if ($errors->has('state_id')) <p class="help-block">{{ $errors->first('state_id') }}</p> @endif
    </div>
   </div>

<div class="form-group">
    <label>City</label>
    <div >
        {!! Form::select('city_id',$cities,null, array('id'=>'city_id','class'=>'form-control','id'=>'city_id')) !!}
        @if ($errors->has('city_id')) <p class="help-block">{{ $errors->first('city_id') }}</p> @endif
    </div>
</div>

                 <input type="hidden" name="max" id="max" value=<?php echo $max; ?>>				
                  <h6>CATEGORIES</h6>             
                  <div class="checklist list" id="id1" >
          <ul>
           <li> <input    id="selecctall" type="checkbox" value="all">&nbsp;<span></span>All</a></li>
            @foreach( $marketPlaceCategories as $categories)                                      
                          <li><input class="checkbox1" name="categorytype[]" type="checkbox" value='{{$categories['id'] }}' ></span>&nbsp;{{$categories['name'] }}</a></li>
                      @endforeach 
                   </ul>
          
                  </div>
          <h6>FILTERS</h6>
                  <div class="checklist filters">
				  <ul>
                      <li> <input class="checkbox2" name="itemtype[]" type="checkbox" value='new'>&nbsp;<span></span>new</li>
                     <li> <input class="checkbox2" name="itemtype[]" type="checkbox" value='used'>&nbsp;<span></span>used</li>
					 </ul>
                  </div>

           <div class="form-group">
	          <label for="amount">Price range:</label>
	          <input type="text" name="amount" id="amount" readonly  class="form-control">
         </div>

<div id="slider-range"></div></br>


          <!--  <input type="button" value="Go"  onclick="marketplace(this);"  id="go" class="button btn-primary">-->
		               
                             <input type="submit" value="Go" onclick="Go(this);" id="go" class="button btn-primary">
							   <button type="reset" value="Clear" class="button btn-primary">Reset</button>
                     </button>
<input type="hidden" name="limit" id="limit" value="{{config('constants.LIMIT')}}"/>
@if(isset($offset))
<input type="hidden"  name="offset" id="offset" value="{{$offset}}"/>
@else
<input type="hidden"  name="offset" id="offset" value="0"/>
@endif
<input type="hidden" name="marketplace" id="marketplace" value="{{$page}}"/>
         </form >                    
	</div>
</div>

 <script>

	$(function() {
		 var max=$('#max').val();
		// var min=$('#min').val();
		$( "#slider-range" ).slider({
			range: true,
			min: 0,
			max: max,
			values: [ 0, max ],
			slide: function( event, ui ) {
				$( "#amount" ).val(ui.values[ 0 ] + "-" + ui.values[ 1 ] );
			}
		});
		$( "#amount" ).val($( "#slider-range" ).slider( "values", 0 ) +
			"-" + $( "#slider-range" ).slider( "values", 1 ) );

	});

	</script>
		<script>
$(document).ready(function() {
	
	
		//marketplace(this);	
			
    $('#selecctall').click(function(event) {  
	
        if(this.checked) { 
            $('.checkbox1').each(function() { 
                this.checked = true; 				
            });
        }else{
            $('.checkbox1').each(function() {
                this.checked = false;          
				
            });        
        }
    });
	
	 $('.checkbox1').on('click',function(){
        if($('.checkbox1:checked').length == $('.checkbox1').length){
            $('#selecctall').prop('checked',true);
        }else{
            $('#selecctall').prop('checked',false);
        }
    });

   
});
</script>

 