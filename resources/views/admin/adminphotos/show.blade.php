@extends('admin.layouts.app')

@section('content')


<?php //echo $html;?>

<h1>Photos</h1>
<div id="marketplaceid" class="gallery-pg">         			 
	
<ul class="row list-unstyled market_gallery clearfix viewmoreclass">

@if(count($list) > 0)

  
	    
	@foreach($list as $lis)
	<li class="col-xs-6 col-sm-4 col-md-3" id="delete_{{ $lis['id'] }}">
			<div class="thumbnail">
        		
	<div  class="ImageWrapper" >
	

@if( $lis['imageable_type']=="marketplace")
	<div>
			{!! Helper::Images($lis['url'],'marketplace',array('class'=>'img-responsive','height'=>50,'width'=>50) ) !!}	
            <div class="caption">
              	<span class="lead">By: <a href="{{ url('editsportprofile/'.$name[$lis['user_id']][0]['id']) }}">{{$name[$lis['user_id']][0]['name']}}</a></span>            	<span class="lt-grey">Related to: {{ $lis['imageable_type']}}</span>
				<span class="lt-grey">Created at: {{ $lis['created_at']}}</span>
            </div>                		 
            <a href="#" class="delete"  onclick="deletePhoto({{ $lis['id'] }},'{{$lis['imageable_type']}}');" > <i class="fa fa-remove"></i></a>
		</div>	
@endif	
				


@if( $lis['imageable_type']=="sports")
         <div>
			{!! Helper::Images($lis['url'],'sports',array('class'=>'img-responsive','height'=>50,'width'=>50) ) !!}		
			<div class="caption">
              	<span class="lead">By: <a href="{{  url('editsportprofile/'.$name[$lis['user_id']][0]['id']) }}">{{$name[$lis['user_id']][0]['name']}}</a></span>            	<span class="lt-grey">Related to: {{ $lis['imageable_type']}}</span>
					<span class="lt-grey">Created at: {{ $lis['created_at']}}</span>
            </div>
			<a href="#" class="delete"  onclick="deletePhoto({{ $lis['id'] }},'{{$lis['imageable_type']}}');" > <i class="fa fa-remove"></i></a>
		 </div>
@endif
	
	
	
@if( $lis['imageable_type']=="user_photo")
	<div >
			{!! Helper::Images($lis['url'],'user_profile',array('class'=>'img-responsive','height'=>50,'width'=>50) ) !!}		
            <div class="caption">
              	<span class="lead">By: <a href="{{ url('editsportprofile/'.$name[$lis['user_id']][0]['id']) }}">{{$name[$lis['user_id']][0]['name']}}</a></span>            	<span class="lt-grey">Related to:{{ $lis['imageable_type']}}</span>
					<span class="lt-grey">Created at: {{ $lis['created_at']}}</span>
            </div>
            <a href="#" class="delete"  onclick="deletePhoto({{ $lis['id'] }},'{{$lis['imageable_type']}}');" > <i class="fa fa-remove"></i></a>
	</div>
@endif
	
	
	
@if( $lis['imageable_type']=="gallery_user")
	<div>
			{!! Helper::Images($lis['url'],'gallery/gallery_user',array('class'=>'img-responsive','height'=>50,'width'=>50,'id'=> $lis['imageable_id'] ) ) !!}					
            <div class="caption">
              	<span class="lead">By: <a href="{{  url('editsportprofile/'.$name[$lis['user_id']][0]['id']) }}">{{$name[$lis['user_id']][0]['name']}}</a></span>            	<span class="lt-grey">Related to: {{ $lis['imageable_type']}}</span>
					<span class="lt-grey">Created at: {{ $lis['created_at']}}</span>
            </div>
            <a href="#" class="delete"  onclick="deletePhoto({{ $lis['id'] }},'{{$lis['imageable_type']}}');" ><i class="fa fa-remove"></i></a>
	</div>
@endif						

	

@if( $lis['imageable_type']=="teams")
		<div>
			{!! Helper::Images($lis['url'],'teams',array('class'=>'img-responsive','height'=>50,'width'=>50) ) !!}		
			<div class="caption">
              	<span class="lead">By: <a href="{{ url('editsportprofile/'.$name[$lis['user_id']][0]['id']) }}">{{$name[$lis['user_id']][0]['name']}}</a></span>            	<span class="lt-grey">Related to: {{ $lis['imageable_type']}}</span>
					<span class="lt-grey">Created at: {{ $lis['created_at']}}</span>
            </div>
            <a href="#" class="delete"  onclick="deletePhoto({{ $lis['id'] }},'{{$lis['imageable_type']}}');" ><i class="fa fa-remove"></i></a>
		</div>	
@endif		


@if( $lis['imageable_type']=="gallery_team")
		<div >
			{!! Helper::Images($lis['url'],'gallery/gallery_team',array('class'=>'img-responsive','height'=>50,'width'=>50,'id'=> $lis['imageable_id']) ) !!}
            <div class="caption">
              	<span class="lead">By: <a href="{{ url('editsportprofile/'.$name[$lis['user_id']][0]['id']) }}">{{$name[$lis['user_id']][0]['name']}}</a></span>            	<span class="lt-grey">Related to: {{ $lis['imageable_type']}}</span>
					<span class="lt-grey">Created at: {{ $lis['created_at']}}</span>
            </div>
            <a href="#" class="delete"  onclick="deletePhoto({{ $lis['id'] }},'{{$lis['imageable_type']}}');" ><i class="fa fa-remove"></i></a>
		</div>	
@endif		

	
@if( $lis['imageable_type']=="tournaments")
		<div>
			{!! Helper::Images($lis['url'],'tournaments',array('class'=>'img-responsive','height'=>50,'width'=>50) ) !!}	
            <div class="caption">
              	<span class="lead">By: <a href="{{ url('editsportprofile/'.$name[$lis['user_id']][0]['id']) }}">{{$name[$lis['user_id']][0]['name']}}</a></span>            	<span class="lt-grey">Related to: {{ $lis['imageable_type']}}</span>
					<span class="lt-grey">Created at: {{ $lis['created_at']}}</span>
            </div>
            <a href="#" class="delete"  onclick="deletePhoto({{ $lis['id'] }},'{{$lis['imageable_type']}}');" ><i class="fa fa-remove"></i></a>
		</div>
@endif		

	
			
@if( $lis['imageable_type']=="gallery_tournaments")
<div >
			{!! Helper::Images($lis['url'],'gallery/gallery_tournaments',array('class'=>'img-responsive','height'=>50,'width'=>50,'id'=> $lis['imageable_id']) ) !!}	
            <div class="caption">
              	<span class="lead">By: <a href="{{  url('editsportprofile/'.$name[$lis['user_id']][0]['id']) }}">{{$name[$lis['user_id']][0]['name']}}</a></span>            	<span class="lt-grey">Related to: {{ $lis['imageable_type']}}</span>
					<span class="lt-grey">Created at: {{ $lis['created_at']}}</span>
            </div>
            <a href="#" class="delete"  onclick="deletePhoto({{ $lis['id'] }},'{{$lis['imageable_type']}}');" ><i class="fa fa-remove"></i></a>
		</div>	
@endif		

@if( $lis['imageable_type']=="form_gallery_tournaments")

	<div>
			{!! Helper::Images($lis['url'],'form_gallery_tournaments',array('class'=>'img-responsive','height'=>50,'width'=>50) ) !!}	
			<div class="caption">
              	<span class="lead">By: <a href="{{  url('editsportprofile/'.$name[$lis['user_id']][0]['id']) }}">{{$name[$lis['user_id']][0]['name']}}</a></span>            	<span class="lt-grey">Related to: {{ $lis['imageable_type']}}</span>
					<span class="lt-grey">Created at: {{ $lis['created_at']}}</span>
            </div>
            <a href="#" class="delete"  onclick="deletePhoto({{ $lis['id'] }},'{{$lis['imageable_type']}}');" ><i class="fa fa-remove"></i></a>
		</div>	
@endif		


@if( $lis['imageable_type']=="facility")

		<div >
			{!! Helper::Images($lis['url'],'facility',array('class'=>'img-responsive','height'=>50,'width'=>50) ) !!}		
			<div class="caption">
              	<span class="lead">By: <a href="{{  url('editsportprofile/'.$name[$lis['user_id']][0]['id'])}}">{{$name[$lis['user_id']][0]['name']}}</a></span>            	<span class="lt-grey">Related to: {{ $lis['imageable_type']}}</span>
					<span class="lt-grey">Created at: {{ $lis['created_at']}}</span>
            </div>
            <a href="#" class="delete"  onclick="deletePhoto({{ $lis['id'] }},'{{$lis['imageable_type']}}');" ><i class="fa fa-remove"></i></a>
        </div>	
@endif		

	
@if( $lis['imageable_type']=="gallery_facility")
	<div >
			{!! Helper::Images($lis['url'],'gallery/gallery_facility',array('class'=>'img-responsive','height'=>50,'width'=>50,'id'=> $lis['imageable_id']) ) !!}	
            <div class="caption">
              	<span class="lead">By: <a href="{{  url('editsportprofile/'.$name[$lis['user_id']][0]['id']) }}">{{$name[$lis['user_id']][0]['name']}}</a></span>            	<span class="lt-grey">Related to: {{ $lis['imageable_type']}}</span>
					<span class="lt-grey">Created at: {{ $lis['created_at']}}</span>
            </div>
            <a href="#" class="delete"  onclick="deletePhoto({{ $lis['id'] }},'{{$lis['imageable_type']}}');" ><i class="fa fa-remove"></i></a>
		</div>	
@endif		


@if( $lis['imageable_type']=="form_gallery_facility")
		<div >
			{!! Helper::Images($lis['url'],'form_gallery_facility',array('class'=>'img-responsive','height'=>50,'width'=>50) ) !!}
            <div class="caption">
              	<span class="lead">By: <a href="{{  url('editsportprofile/'.$name[$lis['user_id']][0]['id']) }}">{{$name[$lis['user_id']][0]['name']}}</a></span>            	<span class="lt-grey">Related to: {{ $lis['imageable_type']}}</span>
					<span class="lt-grey">Created at: {{ $lis['created_at']}}</span>
            </div>
            <a href="#" class="delete"  onclick="deletePhoto({{ $lis['id'] }},'{{$lis['imageable_type']}}');" ><i class="fa fa-remove"></i></a>
		</div>	
@endif		


@if( $lis['imageable_type']=="organization")
		<div>
			{!! Helper::Images($lis['url'],'organization',array('class'=>'img-responsive','height'=>50,'width'=>50) ) !!}		
			<div class="caption">
              	<span class="lead">By: <a href="{{  url('editsportprofile/'.$name[$lis['user_id']][0]['id']) }}">{{$name[$lis['user_id']][0]['name']}}</a></span>            	<span class="lt-grey">Related to: {{ $lis['imageable_type']}}</span>
					<span class="lt-grey">Created at: {{ $lis['created_at']}}</span>
            </div>
            <a href="#" class="delete"  onclick="deletePhoto({{ $lis['id'] }},'{{$lis['imageable_type']}}');" > <i class="fa fa-remove"></i></a>
        </div>
@endif		
	
	
@if( $lis['imageable_type']=="gallery_organization")
			<div >
			{!! Helper::Images($lis['url'],'gallery/gallery_organization',array('class'=>'img-responsive','height'=>50,'width'=>50,'id'=> $lis['imageable_id']) ) !!}
            <div class="caption">
              	<span class="lead">By: <a href="{{ url('editsportprofile/'.$name[$lis['user_id']][0]['id']) }}">{{$name[$lis['user_id']][0]['name']}}</a></span>            	<span class="lt-grey">Related to: {{ $lis['imageable_type']}}</span>
					<span class="lt-grey">Created at: {{ $lis['created_at']}}</span>
            </div>
            <a href="#" class="delete"  onclick="deletePhoto({{ $lis['id'] }},'{{$lis['imageable_type']}}');" ><i class="fa fa-remove"></i></a>
	</div>	
@endif		 


@if( $lis['imageable_type']=="form_gallery_organization")
		<div >
			{!! Helper::Images($lis['url'],'form_gallery_organization',array('class'=>'img-responsive','height'=>50,'width'=>50) ) !!}		
			<div class="caption">
              	<span class="lead">By: <a href="{{  url('editsportprofile/'.$name[$lis['user_id']][0]['id'])}}">{{$name[$lis['user_id']][0]['name']}}</a></span>            	<span class="lt-grey">Related to: {{ $lis['imageable_type']}}</span>
					<span class="lt-grey">Created at: {{ $lis['created_at']}}</span>
            </div>
            <a href="#" class="delete"  onclick="deletePhoto({{ $lis['id'] }},'{{$lis['imageable_type']}}');" ><i class="fa fa-remove"></i></a>
        </div>	
@endif		


@if( $lis['imageable_type']=="gallery_match")
		<div>
			{!! Helper::Images($lis['url'],'gallery/gallery_match',array('class'=>'img-responsive','height'=>50,'width'=>50,'id'=> $lis['imageable_id']) ) !!}
            <div class="caption">
              	<span class="lead">By: <a href="{{  url('editsportprofile/'.$name[$lis['user_id']][0]['id']) }}">{{$name[$lis['user_id']][0]['name']}}</a></span>            	<span class="lt-grey">Related to: {{ $lis['imageable_type']}}</span>
					<span class="lt-grey">Created at: {{ $lis['created_at']}}</span>
            </div>
            <a href="#" class="delete"  onclick="deletePhoto({{ $lis['id'] }},'{{$lis['imageable_type']}}');" ><i class="fa fa-remove"></i></a>
	</div>
@endif	


	
</div>	

</div>
</li>
@endforeach
	@endif	
</ul>
</div>





@if($totalcount>count($list)) 
<a class="view_tageline_mkt" id="viewmorediv">
	<span id="viewmorebutton" class="market_place"><i class="fa fa-arrow-down"></i> <label>{{ trans('message.view_more') }}</label></span>
</a>
@endif

<input type="hidden" name="limit" id="limit" value="{{$limit}}"/>
<input type="hidden" name="offset" id="offset" value="{{$offset}}"/>




<script type="text/javascript">
            $(document).ready(function() {
			
    $("#offset").val({{$offset}});
            if ($("#viewmorediv").length) {
    $('#viewmorebutton').on("click", function(e) {
	
		var urls =	'{{URL('/viewMorePhotos')}}';
    var params = { limit:{{$limit}}, offset:$("#offset").val()};
            viewMore(params,urls);
    });
            global_record_count = {{$totalcount}}
    }

    });
</script>
<script >

	  function deletePhoto(id,imagetype)
        {
	
            var id =id;
			var imagetype =imagetype;
			 var token = "{{csrf_token()}}";
			 $.confirm({
			title: 'Confirmation',
			content: "Are you sure you want to delete?",
				confirm: function() {
						$.ajax({
							url: "{{ url('/deletephoto') }}",
							type: "POST",
						 //  dataType: 'JSON',
							data: {id:id, imagetype:imagetype,'_token': token},
							success: function(data) {
								

								if(data.msg=="success")
								  {
									  $("#delete_"+id).remove();
									  $.alert({
														title: "Alert!",
														content: "Photo deleted successfully."
											});
								  }

							}


						});
				}
			 });
			
			
		}
		
		
</script>


@endsection