
@foreach($photo_array as $photoalbum)
@foreach( $photoalbum as $album)
<div class="item" id="image-{{$album['id']}}">
		@if($album['imageable_type']=='gallery_user' || $album['imageable_type']=='gallery_team' || $album['imageable_type']=='gallery_tournaments' || $album['imageable_type']=='gallery_facility' || $album['imageable_type']=='gallery_organization' || $album['imageable_type']=='gallery_match')
		{!! Helper::Images($album['url'],'gallery/'.$album['imageable_type'],array('class'=>'img-center','height'=>500,'width'=>500,'id'=>$action_id ) )!!}
		@else                        
		{!! Helper::Images($album['url'],'user_profile',array('class'=>'img-thumbnail','height'=>500,'width'=>500) )!!} 
		@endif       
</div>
@endforeach
@endforeach