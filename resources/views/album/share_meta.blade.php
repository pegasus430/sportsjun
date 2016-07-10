<?php

switch ($action) {
	case 'tournaments':
		$sharingKit=Helper::getTournamentDetails($action_id);
		break;
	case 'team':
		$sharingKit=Helper::getTeamDetails($action_id);
		break;
	case 'organization':
		$sharingKit=Helper::getOrganisationDetails($action_id);
		break;
	case 'user':
		$sharingKit=Helper::getUserDetails($action_id);
		break;
	default:
		$sharingKit=(object)['gallery_sharing'=>'','logo'=>'', 'name'=>''];
		break;
}
$data_url=url("/viewpublic/user/album/show/$action/$action_id");
$data_text=$sharingKit->gallery_sharing;
$data_title="Photo Album of $sharingKit->name";
$data_image=url("/uploads/$action/$sharingKit->logo");

?>

<meta property="og:url"           content="{{$data_url}}"/>
<meta property="og:type"          content="website" />
<meta property="og:title"         content="<?php echo $data_title ?>" />
<meta property="og:description"   content="<?php echo $data_text?>" />

@if(isset($photo_array))
	@foreach($photo_array as $album)
		<?php $a_image=url("uploads/gallery/gallery_$action/$action_id/{$album['url']}");?>
		<meta property="og:image"   content="{{$a_image}}"}} />
	@endforeach
@endif
	<meta property="og:image"   content="{{$data_image}}" />
	<meta property="og:image"         content="{{ asset('/images/sj_facebook_share.jpg') }}" />


