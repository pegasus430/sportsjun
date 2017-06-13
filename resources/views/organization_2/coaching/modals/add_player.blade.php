<div class="modal fade sessions-modal" id="add-player-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form method="POST" action="/organization/{{$organisation->id}}/coaching/{{$coaching->id}}/add_player" accept-charset="UTF-8" class="form form-horizontal">
				<div class="modal-header">
				{!!csrf_field() !!}
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3>Add Player</h3> </div>
				<div class="modal-body">
					<div class="content row">
						<div class="input-container">
							<input type="text" id="staff_name" required="required" name="name" />
							<label for="Username">Enter Player Name</label>
							<div class="bar"></div>
						</div>
						<div class="input-container">
							<input type="text" id="staff_email" required="" name="email" required="" />
							<label for="Username">Enter Player Email</label>
							<div class="bar"></div>
						</div>
						<div class="input-container select">
							<div>
								<label>Subscription</label>
								<select class="" name="subscription_id">
								 	@foreach($coaching->payment_options as $option)
									<option value="{{$option->id}}">{{$option->package->title}} -  {{$option->price}}</option>
								
									@endforeach
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Add</button>
				</div>
			</form>
		</div>
	</div>
</div>