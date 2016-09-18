<?php

	$sp='Sports played :';
			$sharingKit=Helper::getUserDetails($userId);		
		 foreach($sports as $sport){
		 	$sp.=$sport->sports_name. ', ';
		 }
  $data_url=url("/viewpublic/editsportprofile/$userId");
  $data_text="$sharingKit->name is $sharingKit->gender sport player. $sharingKit->name leaves at $sharingKit->location. $sp . Click here to view his complete sport profile.";
  $data_title="$sharingKit->name's Sport Profile";
  $data_image=url("/uploads/user_profile/$sharingKit->logo");

?>

<meta property="og:url"           content="{{$data_url}}"/>
<meta property="og:type"          content="website" />
<meta property="og:title"         content="<?php echo $data_title ?>" />
<meta property="og:description"   content="<?php echo $data_text?>" />

@if(isset($sharingKit->providers) && count($sharingKit->providers)>0 )
	@foreach($sharingKit->providers as $sp)
<meta property="og:image"   content="{{$sp->avatar}}" />
<meta property="twitter:image"         content="{{$sp->avatar}}" />
	@endforeach
@endif


<meta property="og:image"   content="{{$data_image}}" />
<meta property="og:image"         content="{{ asset('/images/sj_facebook_share.jpg') }}" />

<meta name="twitter:card" content="photo" />
<meta name="twitter:site" content="@sj_sportsjun" />
<meta name="twitter:description" content="{{$data_text}}" />
<meta name="twitter:url" content="{{$data_url}}" />
<meta name="twitter:title" content="{{$data_title}}" />
<meta name="twitter:image" content="{{$data_image }}" />

