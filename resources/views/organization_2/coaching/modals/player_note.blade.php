
<div class="modal fade sessions-modal" id="player-coaching-note-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form method="POST" action="" accept-charset="UTF-8" class="form form-horizontal">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3>Player Coaching Note</h3> </div>
				<div class="modal-body">
					<div class="content">
						<div class="col-sm-8">
							<ul class="skills-list list-inline text-center">
								<li>
									<p><strong>Batting Skill</strong></p>
									<div class="player-rating pr-circle pr-select">9</div>
								</li>
								<li>
									<p><strong>Bowling Skill</strong></p>
									<div class="player-rating pr-circle">7</div>
								</li>
								<li>
									<p><strong>Fielding Skill</strong></p>
									<div class="player-rating pr-circle">8</div>
								</li>
							</ul>
							<div class="list_actions">
								<a class="scroll-left"></a>
								<ul>
									<li>
										<div class="player-rating pr-circle">1</div>
										<p><strong>orange</strong></p>
									</li>
									<li>
										<div class="player-rating pr-circle">2</div>
										<p><strong>orange</strong></p>
									</li>
									<li>
										<div class="player-rating pr-circle">3</div>
										<p><strong>orange</strong></p>
									</li>
									<li>
										<div class="player-rating pr-circle">4</div>
										<p><strong>orange</strong></p>
									</li>
									<li>
										<div class="player-rating pr-circle">5</div>
										<p><strong>orange</strong></p>
									</li>
									<li>
										<div class="player-rating pr-circle">6</div>
										<p><strong>orange</strong></p>
									</li>
									<li>
										<div class="player-rating pr-circle">7</div>
										<p><strong>orange</strong></p>
									</li>
									<li>
										<div class="player-rating pr-circle">8</div>
										<p><strong>orange</strong></p>
									</li>
								</ul>
								<a class="scroll-right"></a>
							</div>
							<script>
								jQuery(function () {
									var scrolled = 0;
									$(".scroll-left").on("click", function () {
										scrolled = scrolled - 300;
										$("ul").animate({
											scrollLeft: scrolled
										});
									});
									$(".scroll-right").on("click", function () {
										scrolled = scrolled + 300;
										$("ul").animate({
											scrollLeft: scrolled
										});
									});
								});
							</script>
						</div>
						<div class="col-sm-4 text-center">
							<div class="player-rating">9</div>
							<p>Player skill rating</p>
						</div>
						<div class="clearfix"></div>
						<hr>
						<div class="col-md-12">
							<div class="sm-date">23rd Dec, 2017 <span class="sm-action">Square cut</span></div>
							<div class="sm-para">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
						</div>
						<div class="col-md-12">
							<div class="sm-date">23rd Dec, 2017</div>
							<div class="sm-para">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
						</div>
						<div class="col-md-12">
							<div class="sm-date">23rd Dec, 2017 <span class="sm-action">Front foot</span></div>
							<div class="sm-para">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>