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
										<th width="200" style="text-align: right;">
											<div class="create-btn-link">
												<a href="/organization/{{$organisation->id}}/news/create" class="wg-cnlink" style="top: -24px;"> <i class="fa fa-plus"></i> &nbsp; Create News</a>
											</div>
										</th>
									</tr>
								</thead>
								<tbody>
							@foreach($news as $nw)
									<tr>
										<td colspan="2">
											<div class="news-item news-type-news-list">
												<div class="news-item-wrapper">
													<div class="news-thumbnail">
														<a href="/organization/{{$organisation->id}}/news/{{$nw->id}}"> <img src="{{$nw->image_url}}" alt="" title=""> </a>
													</div>
													<div class="news-content">
														<div class="news-name"> <span>{{$nw->category->sports_name}}</span> </div>
														<div class="news-title">
															<h3><a href="/organization/{{$organisation->id}}/news/{{$nw->id}}"><span>{{$nw->title}}</span></a></h3> </div>
														<div class="news-description">
															<p>{{$nw->details}}</p>
														</div>
														<div class="news-meta">
															<div class="news-meta-news"> <span>{{date('jS M, Y', strtotime($nw->created_at))}}</span> </div>
														</div>
													</div>
												</div>
											</div>
										</td>
								</tr>
								@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
				
			@include('organization_2._sidebar')
			</div>
		</div>

@stop