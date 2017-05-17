@extends('layouts.organisation')

@section('content')



		<div class="container">
			<div class="row">
				<div class="col-sm-4">
					<div class="bg-white pd-30 clearfix">
						<h5 class="text-left"><b>Poll results</b></h5>
						<ul class="list-group">
							<li class="list-group-item">
								<input type="radio" name="poll_results" value="result"> Show result to voters.</li>
							<li class="list-group-item">
								<input type="radio" name="poll_results" value="percentage"> Only show percentage.</li>
							<li class="list-group-item">
								<input type="radio" name="poll_results" value="hide"> Hide all results.</li>
						</ul>
						<h5 class="text-left"><b>Block repeat voters</b></h5>
						<ul class="list-group">
							<li class="list-group-item">
								<input type="radio" name="block_votes" value="disabled"> Don't block repeat voters.</li>
							<li class="list-group-item">
								<input type="radio" name="block_votes" value="cookie"> Block by cookie (recommended).</li>
							<li class="list-group-item">
								<input type="radio" name="block_votes" value="all"> Block by cookie and IP address.</li>
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
						<form action="/organization/{{$organisation->id}}/polls/add" class="form create-form clearfix" method="post">
							<div class="input-container one-col">
								<input type="text" id="poll_questions" required="required" name="question">
								<label for="poll_questions">Question <span class="req">&#42;</span></label>
								<div class="bar"></div>
							</div>
			<div class="sportsjun-forms pd-30">        

        <div class="col-sm-6">
            <div class="section">
                <label class="form_label">{{  trans('message.tournament.fields.startdate') }} <span  class='required'>*</span></label>         
                <label class='field' >
                    <div class="input-group date" id='startdate'>
                        {!! Form::text('start_date', null, array('class'=>'gui-input date','placeholder'=>trans('message.tournament.fields.startdate'))) !!}
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
                                {!! Form::text('end_date', null, array('class'=>'gui-input date','placeholder'=>trans('message.tournament.fields.enddate'))) !!}
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
									<li>
										<input type="text" placeholder="Yes" name="option_1" required="">
									</li>
									<li>
										<input type="text" placeholder="No" name="option_2" required="">
									</li>
									<li class="li_3">
										<input type="text" placeholder="Undecided" name="option_3"> <span><a href="#" class="del_option" del_id='3' >X</a></span></li>


								</ul>

								<input type="hidden" name="i" value="3" id='option_index'>
								<button type="submit" class="btn btn-secondary btn-xs btn-add_option"><i class="fa fa-plus"></i> Add another</button>
							</div>
							<div class="text-center">
								<button type="submit" class="btn btn-primary">Pubish</button>
							</div>
						</form>
						<p>&nbsp;</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="sr-table">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>
											<input type="checkbox">
										</th>
										<th>Question</th>
										<th class="text-center">Answer</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach($polls as $poll)
									<tr class="record">
										<td>
											<input type="checkbox"> </td>
										<td>{{$poll->title}}</td>
										<td class="text-center">
											@foreach($poll->options as $option)
												{{$option->title}} |
											@endforeach</td>
										<td class="text-center"><a href="#">Active</a> | <a href="#">Deactive</a> | <a href="#" class="del">Delete</a></td>
									</tr>
								    @endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

        </div>

@stop


@section('end_scripts')


<script type="text/javascript">
var i =4;
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