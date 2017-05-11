@extends('layouts.organisation')

@section('content')

<div class="container">
			<div class="row">
				<div class="col-md-9">
					<div class="sr-table">
						<div class="table-responsive">
							<table class="table" style="margin-top: 0;">
								<thead>
									<tr>
										<th>NEWS</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<div class="news-item news-type-news-list">
												<div class="news-item-wrapper">
													<div class="news-thumbnail">
														<a href="news-details.php"> <img src="images/news-thumb.jpg" alt="" title=""> </a>
													</div>
													<div class="news-content">
														<div class="news-name"> <span>Cricket</span> </div>
														<div class="news-title">
															<h3><a href="news-details.php"><span>IPL 2017: Delhi Daredevils End Losing Streak, Stun Sunrisers Hyderabad By 6 Wickets</span></a></h3> </div>
														<div class="news-description">
															<p>After beating Sunrisers Hyderabad by 6 wickets, Delhi Daredevils keep their chances alive to qualify for the IPL Play-offs.</p>
														</div>
														<div class="news-meta">
															<div class="news-meta-news"> <span>03 May 2017</span> </div>
														</div>
													</div>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="news-item news-type-news-list">
												<div class="news-item-wrapper">
													<div class="news-thumbnail">
														<a href="news-details.php"> <img src="images/news-thumb.jpg" alt="" title=""> </a>
													</div>
													<div class="news-content">
														<div class="news-name"> <span>Cricket</span> </div>
														<div class="news-title">
															<h3><a href="news-details.php"><span>IPL 2017: Delhi Daredevils End Losing Streak, Stun Sunrisers Hyderabad By 6 Wickets</span></a></h3> </div>
														<div class="news-description">
															<p>After beating Sunrisers Hyderabad by 6 wickets, Delhi Daredevils keep their chances alive to qualify for the IPL Play-offs.</p>
														</div>
														<div class="news-meta">
															<div class="news-meta-news"> <span>03 May 2017</span> </div>
														</div>
													</div>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				
			@include('organization_2._sidebar')
			</div>
		</div>

@stop