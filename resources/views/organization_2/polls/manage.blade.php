
		<div class="container">
			<div class="row">
			<form action="/organization/{{$organisation->id}}/polls/add" class="form create-form clearfix" method="post">
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

			<div class="clearfix" style="height: 40px"></div>
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
												{{$option->title}} ({{$option->percentage()}}%) |
											@endforeach</td>
										<td class="text-center">
											
											
                                                <a href="/organization/{{$organisation->id}}/polls/{{$poll->id}}/edit" data-toggle="tooltip" data-placement="top" title="Edit!" class="label label-primary label-a-primary" poll-id='{{$poll->id}}'><i class="fa fa-pencil"></i></a>
                                                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Delete!" class="label label-danger label-a-danger del" poll-id='{{$poll->id}}'><i class="fa fa-remove"></i></a>
                                              	<span style='{{$poll->status?"display:none":''}}' id='published-{{$poll->id}}'>
                                              		  <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Publish!" class="label label-success label-a-success toggle" type="un-published"  poll-id='{{$poll->id}}'><i class="fa fa-eye"></i></a>
                                              	</span>
                                              
                                                <span style='{{!$poll->status?"display:none":''}}' id='un-published-{{$poll->id}}' >
                                                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Un-publish!" class="label label-danger label-a-danger toggle" type="published"  poll-id='{{$poll->id}}'><i class="fa fa-eye-slash"></i></a>
                                                </span>
                                            </td>
										
									</tr>
								    @endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

        </div>
      
