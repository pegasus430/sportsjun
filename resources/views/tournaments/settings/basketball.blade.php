
<div class="row">
	<div class='col-sm-12'>
		<div class='row'>

			<div class="col-sm-6">
				<div class="section">
				<label class="form_label">
					Divide Match's into?
				</label>

				<label class="field select">
					<select name='number_of_sets' class='gui-input' value="{{$settings->number_of_sets}}">
							<option value='2'  {{$settings->number_of_sets==2?'selected':''}}>  Halves </option>
							<option value='4'  {{$settings->number_of_sets==4?'selected':''}} > Quarters </option>
							 
					</select>
					<i class="arrow double"></i>
				</label>

				
				</div>
			</div>

				<div class="section">
				<div class="col-sm-6">
				<label class="form_label">
					{{trans('message.sports_settings.max_substitutes')}}
				</label>

				<label class="field">
						<input type='text' name='maximum_substitutes' value="{{$settings->maximum_substitutes}}" placeholder="e.g 6" class='gui-input'>
				</label>
			</div>
			</div>

		</div>

		<div class='row'>
			<div class="col-sm-6">
				<div class="section">
				<label class="form_label">
					{{trans('message.sports_settings.active_players')}}
				</label>

				<label class="field">
					<input type='text' name='active_players' value="{{$settings->active_players}}" placeholder="e.g 6" class='gui-input'>
				</label>
				</div>
			</div>

			
			<div class="col-sm-6">
				<div class="section">
				<label class="form_label">
					{{trans('message.sports_settings.substitutes')}}
				</label>

				<label class="field">
						<input type='text' name='substitute' value="{{$settings->substitute}}" placeholder="e.g 6" class='gui-input'>
				</label>
			</div>
			</div>

		
		</div>

		@include('tournaments.settings.points')
</div> 	
</div>