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
		default:
			$sharingKit=(object)['sharingString'=>'','logo'=>'', 'name'=>''];
		break;
	}
  $data_url='';
  $data_text=$sharingKit->sharingString;
  $data_title="Photo Album of $action $sharingKit->name";
  $data_image=url("/uploads/gallery_$action/$action_id/$sharingKit->logo");
 ?>                  
<meta property="og:url"           content="<?php ?>" />
<meta property="og:type"          content="website" />
<meta property="og:title"         content="<?php echo $data_title; ?>" />
<meta property="og:description"   content="<?php echo $data_text; ?>" />

 @foreach($photo_array as $album) 
 	<?php $photo_url=$album['url'] ?>
<meta property="og:image"         content="{{ asset('/uploads/gallery/{$action}/{$action_id}/$photo_url') }}" />
 @endforeach
