@extends('layouts.organisation')

@section('content')
  <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Create News</h2>
					<div class="create-btn-link">
							<a href="/organization/{{$organisation->id}}/news/create" class="wg-cnlink" > <i class="fa fa-plus"></i> &nbsp; Create News</a>
					</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="search-reasult">
                                       <div class="sr-table">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox"></th>
                                            <th>Thumbnail</th>
                                            <th>News Title</th>
                                            <th>Category</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	@foreach($news as $nw)
                                        <tr class="record">
                                           <td><input type="checkbox"></td>
                                            <td width="50">
                                               
                                                <img src="{{$nw->image_url}}" class="img-responsive" alt="">
                                               
                                            </td>
                                            <td> <a href="/organization/{{$organisation->id}}/news/{{$nw->id}}" class="player-name">
                                                  {{$nw->title}}
                                                </a></td>
                                            <td>{{$nw->category?$nw->category->sports_name:''}}</td>
                                            <td>
                                                <a href="/organization/{{$organisation->id}}/news/{{$nw->id}}/edit" data-toggle="tooltip" data-placement="top" title="Edit!" class="label label-primary label-a-primary" news-id='{{$nw->id}}'><i class="fa fa-pencil"></i></a>
                                                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Delete!" class="label label-danger label-a-danger del" news-id='{{$nw->id}}'><i class="fa fa-remove"></i></a>
                                              	<span style='{{$nw->status?"display:none":''}}' id='published-{{$nw->id}}'>
                                              		  <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Published!" class="label label-success label-a-success toggle" type="un-published"  news-id='{{$nw->id}}'><i class="fa fa-eye"></i></a>
                                              	</span>
                                              
                                                <span style='{{!$nw->status?"display:none":''}}' id='un-published-{{$nw->id}}' >
                                                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Un-published!" class="label label-danger label-a-danger toggle" type="published"  news-id='{{$nw->id}}'><i class="fa fa-eye-slash"></i></a>
                                                </span>
                                            </td>
                                        </tr>
                                       @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@stop


@section('end_scripts')

	<script type="text/javascript">
		$('.toggle').click(function(){
			var type = $(this).attr('type'); 
			var id = $(this).attr('news-id'); 
			var url = '/organization/{{$organisation->id}}/news/'+id+'/toggle';
			var that = $(this);

			$.ajax({
				url:url,
				success:function(){
					$(that).parents('span').hide();
					$('#'+type+'-'+id).show();
				}
			})
		})

		$('.del').click(function(){

            if(confirm('are you sure you want to delete this post?')){
                var type = $(this).attr('type'); 
            var id = $(this).attr('news-id'); 
            var url = '/organization/{{$organisation->id}}/news/'+id+'/delete';
            var that = $(this);

            $.ajax({
                url:url,
                success:function(){
                    $(that).parents('.record').hide('slow');
                    
                }
            })
   
            }
		})
	</script>
@stop