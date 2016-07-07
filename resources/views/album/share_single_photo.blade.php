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
  $data_title="Photo Album of $action $sharingKit->name - Add a new photo";
  $data_image=url("/uploads/gallery_$action/$action_id/$sharingKit->logo");


$t_url=url("/viewpublic/user/album/show/$action/$action_id");
$t_text="$data_text";

$t_title="$data_title";
$photo_url=url("uploads/gallery/gallery_$action/$action_id/{$album['url']}");

$fb_url = 'https://www.facebook.com/dialog/share?app_id=' . env('FACEBOOK_APP_ID') .'&amp;image=' . $photo_url .'&amp;display=popup&amp;href=' .$t_url. '&amp;redirect_uri=' . url('js_close');
$tw_url = 'https://twitter.com/intent/tweet?url=' . $t_url. '&amp;text=' . $t_text . '&amp;title=' . $t_title . '&amp;via=sj_sportsjun';
$gp_url = 'https://plus.google.com/share?url=' . $t_url;
?>

<ul class="ssk-group col-md-10 col-md-offset-1 dropdown-menu ">
	
							<a class="ssk ssk-facebook f_b " href="#" role="presentation"><i class="fa fa-facebook"></i></a>
							<a class="ssk ssk-twitter tw_r" href="#" role="presentation"><i class="fa fa-twitter"></i></a>
							<a class="ssk ssk-google-plus gp_l" href="#" role="presentation" ><i class="fa fa-google-plus"></i></a>
        						</ul>
