
<div class="row">
	<div class='col-sm-12'>
		<div class='row'>

			<div class="col-sm-6">
				<div class="section">
				<label class="form_label">
					{{trans('message.sports_settings.number_of_sets')}}
				</label>

				<label class="field select">
					<select name='number_of_sets' class='gui-input' value="{{$settings->number_of_sets}}">
							<option value='1'  {{$settings->number_of_sets==1?'selected':''}}>  1 </option>
							<option value='3'  {{$settings->number_of_sets==3?'selected':''}} >  3 </option>
							<option value='5'  {{$settings->number_of_sets==5?'selected':''}}>  5 </option> 
							<option value='7'  {{$settings->number_of_sets==7?'selected':''}}>  7 </option> 
					</select>
					<i class="arrow double"></i>
				</label>

				
				</div>
			</div>


			<div class="col-sm-6">
				<div class="section">
				<label class="form_label">
					{{trans('message.sports_settings.score_to_win')}}
				</label>
				<label class="field">
					<input type='text' name='score_to_win' value="{{$settings->score_to_win}}">
				</label>
			</div>
			</div>
		</div>

		<div class='row'>
			<div class="col-sm-6">
				<div class="section">
				<label class="form_label">
					{{trans('message.sports_settings.end_point')}}
				</label>

				<label class="field">
					<input type='text' name='end_point' value="{{$settings->end_point}}" placeholder="e.g 29" class='gui-input'>
				</label>
				</div>
			</div>

			<div class="section">
				<div class="col-sm-6">
				<label class="form_label">
					{{trans('message.sports_settings.enable_two_points')}}
				</label>

				<label class="field">
					<input type='checkbox' name='enable_two_points' {{isset($settings->enable_two_points)?'checked':''}} class='gui-input'>
				</label>
			</div>
			</div>
		</div>
</div> 	
</div>