
<div class="row">
	<div class='col-sm-12'>
		

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
		
			</div>

			@include('tournaments.settings.points')
		</div>

</div>