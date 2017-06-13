
<div class="modal fade sessions-modal" id="coach-assesment-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form method="POST" action="" accept-charset="UTF-8" class="form form-horizontal">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3>Coach Assesment</h3> </div>
				<div class="modal-body">
					<div class="content">
						<div class="col-xs-6">
							<input type="checkbox" id="player_availablity" />
							<label for="player_availablity">Player absent for this session</label>
						</div>
						<div class="col-xs-6">
							<div class="date">
								<div class="input-group input-append date" id="">
									<input type="text" class="form-control" name="date" /> <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						<hr>
						<div class="col-sm-6">
							<select class="form-control" name="staff_role">
								<option value="1">Batting Session</option>
								<option value="2">Bowling Session</option>
							</select>
							<p>&nbsp;</p>
							<select class="form-control" name="staff_role">
								<option value="1">Front Foot Defence</option>
								<option value="2">Back Foot Defence</option>
							</select>
						</div>
						<div class="col-sm-6 text-center">
							<div class="player-rating">7</div>
							<p>Player skill rating</p>
						</div>
						<div class="clearfix"></div>
						<hr>
						<div class="col-md-12">
							<div class="stepwizard">
								<div class="stepwizard-row">
									<div class="stepwizard-step">
										<button type="button" class="btn btn-default btn-circle">1</button>
									</div>
									<div class="stepwizard-step">
										<button type="button" class="btn btn-default btn-circle">2</button>
									</div>
									<div class="stepwizard-step">
										<button type="button" class="btn btn-default btn-circle">3</button>
									</div>
									<div class="stepwizard-step">
										<button type="button" class="btn btn-default btn-circle">4</button>
									</div>
									<div class="stepwizard-step">
										<button type="button" class="btn btn-default btn-circle">5</button>
									</div>
									<div class="stepwizard-step">
										<button type="button" class="btn btn-default btn-circle">6</button>
									</div>
									<div class="stepwizard-step">
										<button type="button" class="btn btn-primary btn-circle" disabled="disabled">7</button>
									</div>
									<div class="stepwizard-step">
										<button type="button" class="btn btn-default btn-circle">8</button>
									</div>
									<div class="stepwizard-step">
										<button type="button" class="btn btn-default btn-circle">9</button>
									</div>
									<div class="stepwizard-step">
										<button type="button" class="btn btn-default btn-circle">10</button>
									</div>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						<hr>
						<div class="col-sm-6">
							<div id="dropzone" class="dropzone">
								<form action="/upload" class="dropzone needsclick" id="media-upload">
									<div class="dz-message needsclick"> Drop files here or click to upload.</div>
								</form>
							</div>
						</div>
						<div class="col-sm-6">
							<textarea name="" id="" cols="30" rows="10" placeholder="Coach Comment"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer" style="position: static;">
					<button type="submit" class="btn btn-primary">Done</button>
				</div>
			</form>
		</div>
	</div>
</div>