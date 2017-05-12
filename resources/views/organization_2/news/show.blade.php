@extends('layouts.organisation')

@section('content')

<div class="container">
			<div class="row">
				<div class="col-md-9">
					<div class="sr-table">
						<div class="table-responsive">
							<table class="table" style="margin-top: 0;">
								<tbody>
									<tr>
										<td>
											<div class="news-item news-type-news-list">
												<div class="news-item-wrapper details">
													<div class="news-thumbnail"> <img src="{{$news->image_url}}" alt="" title="" class="img-responsive"> </div>
													<div class="news-content">
														<div class="news-name"> <span>{{$news->category->sports_name}}</span> </div>
														<div class="news-meta">
															<div class="news-meta-news"> <span>{{date('jS M, Y',strtotime($news->created_at))}}</span> </div>
														</div>
														<div class="news-title">
															<h3>{{$news->title}}</h3> </div>
														<div class="news-description">
															{!!$news->details!!}
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

			@include('organization_2._sidebar');

@stop