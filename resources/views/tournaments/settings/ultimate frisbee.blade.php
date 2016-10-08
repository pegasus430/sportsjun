
<div class="row">
	<div class='col-sm-12'>
		

		<div class='row'>

	@if(isset($settings->number_of_halves))
			<div class="col-sm-6">
				<div class="section">
				<label class="form_label">
					Number of Halves
				</label>

				<label class="field">
					<input type='text' name='number_of_halves' value="{{$settings->number_of_halves}}" placeholder="e.g 6" class='gui-input'>
				</label>
				</div>
			</div>		

			<div class="col-sm-6">
				<div class="section">
				<label class="form_label">
					Maximum fouls
				</label>

				<label class="field">
					<input type='text' name='max_fouls' value="{{$settings->max_fouls}}" placeholder="e.g 6" class='gui-input'>
				</label>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="section">
				<label class="form_label">
					Walkover Points
				</label>

				<label class="field">
					<input type='text' name='walk_over_points' value="{{$settings->walk_over_points}}" placeholder="e.g 6" class='gui-input'>
				</label>
				</div>
			</div>	
    @endif

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

					
		
			</div>

			@include('tournaments.settings.points')
		</div>

</div>