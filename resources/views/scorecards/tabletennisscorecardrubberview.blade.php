<ul class="nav nav-tabs nav-justified">
  <li class="{{$rubber->rubber_number==$active_rubber?'active':$rubber->match_status}}">
      <a>
     <span class="pull-left hidden-xs">{{date('jS F , Y',strtotime($rubber['match_start_date'])).' - '.date("g:i a", strtotime($rubber['match_start_time']))}}</span>
        RUBBER {{$rubber->rubber_number}}   &nbsp; &nbsp; <span style='color:white'> [ {{$rubber->match_category}} , {{$rubber->match_type}} ]</span>
        <span class='pull-right'>{{strtoupper($rubber->rubber_number==$active_rubber?'PLAYING':$rubber->match_status)}}
        </span>
        </a>
</ul>

{!!$rubber->rubber_number==$active_rubber?"<br>":''!!}


    <div class="row">
    <div class="col-sm-12">

    <div class="table-responsive">
    	<table class="table table-striped">
        <thead class="thead">
    		<tr id="sets">
    			  <th>{{ trans('message.scorecard.tennis_fields.team') }}</th>
				  @if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set1']!='' || $rubber_b_array['set1']!='' ))
    				<th>{{ trans('message.scorecard.tennis_fields.set1') }}</th>
				@endif
				@if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set2']!='' || $rubber_b_array['set2']!=''))
    				<th>{{ trans('message.scorecard.tennis_fields.set2') }}</th>
				@endif
				@if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set3']!='' || $rubber_b_array['set3']!=''))
    				<th>{{ trans('message.scorecard.tennis_fields.set3') }}</th>
				@endif
				@if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set4']!='' || $rubber_b_array['set4']!=''))
    				<th>{{ trans('message.scorecard.tennis_fields.set4') }}</th>
				@endif
				@if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set5']!='' || $rubber_b_array['set5']!=''))
    				<th>{{ trans('message.scorecard.tennis_fields.set5') }}</th>
				@endif
    		</tr>
      </thead>
    		<tbody>
    			<tr id="set_a">
    				<td>
              @if($user_a_logo['url']!='')
             <!--   <img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/'.$upload_folder.'/'.$user_a_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
		  {!! Helper::Images($user_a_logo['url'],$upload_folder,array('class'=>'fa fa-user fa-2x','height'=>36,'width'=>36) )!!}	
                @else
                <!--<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
			 {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'fa fa-user  fa-2x','height'=>36,'width'=>36) )!!}	
              @endif
              {{ $user_a_name }}
    				</td>
					@if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set1']!='' || $rubber_b_array['set1']!=''))
    				<td>{{(!(empty($rubber_a_array['set1'])))?$rubber_a_array['set1']:''}}</td>
					@endif
					@if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set2']!='' || $rubber_b_array['set2']!=''))
    				<td>{{(!(empty($rubber_a_array['set2'])))?$rubber_a_array['set2']:''}}</td>
					@endif
					@if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set3']!='' || $rubber_b_array['set3']!=''))
    				<td>{{(!(empty($rubber_a_array['set3'])))?$rubber_a_array['set3']:''}}</td>
					@endif
					@if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set4']!='' || $rubber_b_array['set4']!=''))
    				<td>{{(!(empty($rubber_a_array['set4'])))?$rubber_a_array['set4']:''}}</td>
					@endif
					@if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set5']!='' || $rubber_b_array['set5']!=''))
    				<td>{{(!(empty($rubber_a_array['set5'])))?$rubber_a_array['set5']:''}}</td>    
					@endif
					</tr>
    			<tr id="set_b">
    				<td>
              @if($user_b_logo['url']!='')
                <!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/'.$upload_folder.'/'.$user_b_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
			 {!! Helper::Images($user_b_logo['url'],$upload_folder,array('class'=>'fa fa-user fa-2x','height'=>36,'width'=>36) )!!}	
                @else
                <!--<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
			 {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'fa fa-user fa-2x','height'=>36,'width'=>36) )!!}	
              @endif
              {{ $user_b_name }}
    				</td>
					@if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set1']!='' || $rubber_b_array['set1']!=''))
    					<td>{{(!(empty($rubber_b_array['set1'])))?$rubber_b_array['set1']:''}}</td>
					@endif
					@if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set2']!='' || $rubber_b_array['set2']!=''))
    					<td>{{(!(empty($rubber_b_array['set2'])))?$rubber_b_array['set2']:''}}</td>
					@endif
					@if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set3']!='' || $rubber_b_array['set3']!=''))
    					<td>{{(!(empty($rubber_b_array['set3'])))?$rubber_b_array['set3']:''}}</td>
					@endif
					@if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set4']!='' || $rubber_b_array['set4']!=''))
    					<td>{{(!(empty($rubber_b_array['set4'])))?$rubber_b_array['set4']:''}}</td>
					@endif
					@if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set5']!='' || $rubber_b_array['set5']!=''))
    					<td>{{(!(empty($rubber_b_array['set5'])))?$rubber_b_array['set5']:''}}</td>
					@endif
            </tr>
    		</tbody>
    	</table>
    </div>

	

 