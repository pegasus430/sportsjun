@extends('layouts.organisation')

@section('content')



		<div class="container">
		<div class="container">
			<div class="row">
			<form action="/organization/{{$organisation->id}}/polls/{{$poll->id}}/update" class="form create-form clearfix" method="post">
				<div class="col-sm-4">

					<div class="bg-white pd-30 clearfix">
						<h5 class="text-left"><b>Poll results</b></h5>
						<ul class="list-group">
							<li class="list-group-item">
								<input type="radio" name="poll_results" value="result" {{$organisation->poll_settings && $organisation->poll_settings->poll_result=='result'?'checked':''}}> Show result to voters.</li>
							<li class="list-group-item">
								<input type="radio" name="poll_results" value="percentage" {{$organisation->poll_settings && $organisation->poll_settings->poll_result=='percentage'?'checked':''}} > Only show percentage.</li>
							<li class="list-group-item">
								<input type="radio" name="poll_results" value="hide" {{$organisation->poll_settings && $organisation->poll_settings->poll_result=='hide'?'checked':''}}> Hide all results.</li>
						</ul>
						<h5 class="text-left"><b>Block repeat voters</b></h5>
						<ul class="list-group">
							<li class="list-group-item">
								<input type="radio" name="block_votes" value="disabled" {{$organisation->poll_settings && $organisation->poll_settings->block_votes=='disabled'?'checked':''}}> Don't block repeat voters.</li>
							<li class="list-group-item">
								<input type="radio" name="block_votes" value="cookie" {{$organisation->poll_settings && $organisation->poll_settings->block_votes=='cookie'?'checked':''}}> Block by cookie (recommended).</li>
							<li class="list-group-item">
								<input type="radio" name="block_votes" value="all" {{$organisation->poll_settings && $organisation->poll_settings->block_votes=='all'?'checked':''}}> Block by cookie and IP address.</li>
						</ul>
						<para>Note: Block by cookie and IP address can be problematic for some voters.</para>
					</div>
				</div>
				<div class="col-sm-8">
					<div class="wg wg-dk-grey no-shadow no-margin">
						<div class="wg-wrap clearfix">
							<h4 class="no-margin pull-left"><i class="fa fa-bar-chart"></i> Create Poll</h4></div>
					</div>
					<div class="wg no-margin wg-white">
						<p>&nbsp;</p>
						
							<div class="input-container one-col">
								<input type="text" id="poll_questions" required="required" name="question" value="{{$poll->title}}">
								<label for="poll_questions">Question <span class="req">&#42;</span></label>
								<div class="bar"></div>
							</div>
			<div class="sportsjun-forms pd-30">        

        <div class="col-sm-6">
            <div class="section">
                <label class="form_label">{{  trans('message.tournament.fields.startdate') }} <span  class='required'>*</span></label>         
                <label class='field' >
                    <div class="input-group date" id='startdate'>
                        {!! Form::text('start_date', $poll->start_date, array('class'=>'gui-input date','placeholder'=>trans('message.tournament.fields.startdate'))) !!}
                        <span class="input-group-addon">
        	                <span class="glyphicon glyphicon-calendar"></span>
    	                </span>
                    </div>
                    @if ($errors->has('start_date')) <p class="help-block">{{ $errors->first('start_date') }}</p> @endif
                </label>
            
            </div>
        </div>
       
         <div class="col-sm-6">
                <div class="section">
                    <label class="form_label">{{  trans('message.tournament.fields.enddate') }}  <span  class='required'>*</span></label>		
                        <label class='field'>
                        	<div class='input-group date' id='enddate'>
                                {!! Form::text('end_date', $poll->end_date, array('class'=>'gui-input date','placeholder'=>trans('message.tournament.fields.enddate'))) !!}
                                <span class="input-group-addon">
    	                            <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                        	</div>
                        @if ($errors->has('end_date')) <p class="help-block">{{ $errors->first('end_date') }}</p> @endif
                        </label>
                        
                </div>
         </div>
    						 					 
    </div>		
							{!! csrf_field() !!}
							<p>&nbsp;</p>
							<div class="pia_">
								<p class="lead">Answers</p>
								<ul class="options_list">
								  @foreach($poll->options as $key=>$option)
									<li>
										<input type="text" value='{{$option->title}}' name="option_{{$key+1}}" required="">
										<input type="hidden" name="option_old_{{$key+1}}" value="{{$option->id}}">
									</li>
								  @endforeach
									<li class="li_{{isset($key)?$key+2:1}}">
										<input type="text" placeholder="Undecided" name="option_{{$key+2}}"> <span><a href="#" class="del_option" del_id='{{$key+2}}' >X</a></span></li>


								</ul>

								<input type="hidden" name="i" value="{{isset($key)?$key+2:1}}" id='option_index'>
								<button type="button" class="btn btn-secondary btn-xs btn-add_option"><i class="fa fa-plus"></i> Add another</button>
							</div>
							<div class="text-center">
								<button type="submit" class="btn btn-primary">Update</button>
							</div>
						</form>
						<p>&nbsp;</p>
					</div>
				</div>
			</div>


        </div>

  @stop


@section('end_scripts')
<script type="text/javascript">
var i = $('#option_index').val();
		$('.btn-add_option').click(function(){
			$('.options_list').append("<li class='li_"+i+"'><input type='text' name='option_"+i+"'><span><a href='javascript:void(0)' class='del_option' del_id='"+i+"' >X</a></span></li>");
			i++;

			$('.del_option').click(function(){
				$(this).parents('li').remove();
			})

			$('#option_index').val(i);
		});

		$('.del_option').click(function(){
			$(this).parents('li').remove();
		})

		 $(".date").datepicker();
        $(".date").datepicker();



        $(function() {
	$.validator.addMethod("greater_startdate", function(value, element) {
		 var startDate = $('[name="start_date"]').val();
		  var endDate = $('[name="end_date"]').val();
		  var startDate = startDate.split('/').reverse().join('-');
		var endDate = endDate.split('/').reverse().join('-');										
		return Date.parse(endDate) >= Date.parse(startDate);
	}, "End Date must be equal to or after Start Date");
	$('[name="end_date"]').rules("add", "greater_startdate");
		})
</script>


@stop

