<div class='row'>
	<fieldset style="border:inset 1px #eee; border-radius:5px">
		<div class="col-sm-12">
		<center><label><h4>Final Points </h4></label> </center>
		<p><span class="winner"><i>Optional : This feature is for organizers who assigns points to W, L, 3rd and 4th position</i></span>
		</div>
		<div class="clearfix">
			<div class="col-sm-3">
				<div class="section">
				<label class="form_label">
					Winner
				</label>

				<label class="field">
					<input type='text' name='p_1' value="{{$tournament->p_1}}" placeholder="100" class='gui-input' {{$readonly}}>
				</label>
				</div>
			</div>

			<div class="col-sm-3">
			<div class="section">
				
				<label class="form_label">
					Runner
				</label>

				<label class="field">
				<input type='text' name='p_2' value="{{$tournament->p_2}}" placeholder="100" class='gui-input' {{$readonly}}>
				</label>
			</div>
			</div>

			<div class="col-sm-3">
			<div class="section">
				
				<label class="form_label">
					3rd Position
				</label>

				<label class="field">
				<input type='text' name='p_3' value="{{$tournament->p_3}}" placeholder="100" class='gui-input' {{$readonly}}>
				</label>
			</div>
			</div>

			<div class="col-sm-3">
			<div class="section">
				
				<label class="form_label">
					4th Position
				</label>

				<label class="field">
				<input type='text' name='p_4' value="{{$tournament->p_4}}" placeholder="100" class='gui-input' 
				{{$readonly}}>
				</label>
			</div>
			</div>
		</fieldset>
</div>
