@if(count($list) > 0)

  
	    
	@foreach($list as $lis)
	<li class="col-xs-6 col-sm-4 col-md-3"  id="delete_{{ $lis['id'] }}">
			<div class="thumbnail">
        		
	<div  class="ImageWrapper" id="delete_{{ $lis['id'] }}">
	

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
         <div >
			{!! Helper::Images($lis['url'],'sports',array('class'=>'img-responsive','height'=>50,'width'=>50) ) !!}		
			<div class="caption">
              	<span class="lead">By: <a href="{{  url('editsportprofile/'.$name[$lis['user_id']][0]['id']) }}">{{$name[$lis['user_id']][0]['name']}}</a></span>            	<span class="lt-grey">Related to: {{ $lis['imageable_type']}}</span>
					<span class="lt-grey">Created at: {{ $lis['created_at']}}</span>
            </div>
			<a href="#" class="delete"  onclick="deletePhoto({{ $lis['id'] }},'{{$lis['imageable_type']}}');" > <i class="fa fa-remove"></i></a>
		 </div>
@endif
	
	
	
@if( $lis['imageable_type']=="user_photo")
	<div>
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
		<div >
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

		<div>
			{!! Helper::Images($lis['url'],'facility',array('class'=>'img-responsive','height'=>50,'width'=>50) ) !!}		
			<div class="caption">
              	<span class="lead">By: <a href="{{  url('editsportprofile/'.$name[$lis['user_id']][0]['id'])}}">{{$name[$lis['user_id']][0]['name']}}</a></span>            	<span class="lt-grey">Related to: {{ $lis['imageable_type']}}</span>
					<span class="lt-grey">Created at: {{ $lis['created_at']}}</span>
            </div>
            <a href="#" class="delete"  onclick="deletePhoto({{ $lis['id'] }},'{{$lis['imageable_type']}}');" ><i class="fa fa-remove"></i></a>
        </div>	
@endif		

	
@if( $lis['imageable_type']=="gallery_facility")
	<div>
			{!! Helper::Images($lis['url'],'gallery/gallery_facility',array('class'=>'img-responsive','height'=>50,'width'=>50,'id'=> $lis['imageable_id']) ) !!}	
            <div class="caption">
              	<span class="lead">By: <a href="{{  url('editsportprofile/'.$name[$lis['user_id']][0]['id']) }}">{{$name[$lis['user_id']][0]['name']}}</a></span>            	<span class="lt-grey">Related to: {{ $lis['imageable_type']}}</span>
					<span class="lt-grey">Created at: {{ $lis['created_at']}}</span>
            </div>
            <a href="#" class="delete"  onclick="deletePhoto({{ $lis['id'] }},'{{$lis['imageable_type']}}');" ><i class="fa fa-remove"></i></a>
		</div>	
@endif		


@if( $lis['imageable_type']=="form_gallery_facility")
		<div>
			{!! Helper::Images($lis['url'],'form_gallery_facility',array('class'=>'img-responsive','height'=>50,'width'=>50) ) !!}
            <div class="caption">
              	<span class="lead">By: <a href="{{  url('editsportprofile/'.$name[$lis['user_id']][0]['id']) }}">{{$name[$lis['user_id']][0]['name']}}</a></span>            	<span class="lt-grey">Related to: {{ $lis['imageable_type']}}</span>
					<span class="lt-grey">Created at: {{ $lis['created_at']}}</span>
            </div>
            <a href="#" class="delete"  onclick="deletePhoto({{ $lis['id'] }},'{{$lis['imageable_type']}}');" ><i class="fa fa-remove"></i></a>
		</div>	
@endif		


@if( $lis['imageable_type']=="organization")
		<div >
			{!! Helper::Images($lis['url'],'organization',array('class'=>'img-responsive','height'=>50,'width'=>50) ) !!}		
			<div class="caption">
              	<span class="lead">By: <a href="{{  url('editsportprofile/'.$name[$lis['user_id']][0]['id']) }}">{{$name[$lis['user_id']][0]['name']}}</a></span>            	<span class="lt-grey">Related to: {{ $lis['imageable_type']}}</span>
					<span class="lt-grey">Created at: {{ $lis['created_at']}}</span>
            </div>
            <a href="#" class="delete"  onclick="deletePhoto({{ $lis['id'] }},'{{$lis['imageable_type']}}');" > <i class="fa fa-remove"></i></a>
        </div>
@endif		
	
	
@if( $lis['imageable_type']=="gallery_organization")
			<div>
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
 <script type="text/javascript">
            $(document).ready(function() {
	global_record_count = {{$totalcount}}			
    var offset = {{$offset}};
            $("#offset").val(offset);
            if (offset >= global_record_count)
    {
    $("#viewmorediv").remove();
    }

    });
</script>                                
