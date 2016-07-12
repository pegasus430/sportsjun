<?php

$t_url=url("/viewpublic/team/members/$team_id");
$t_text="$team_name is sport team at $location.Click here to see the complete team details";
$t_title="View Members of $team_name";
$t_img=url($photo_path);
?>
<meta property="og:url"           content="{{$t_url}}" />
<meta property="og:type"          content="website" />
<meta property="og:title"         content="<?php echo $t_title ?>" />
<meta property="og:description"   content="<?php echo $t_text ?>" />
<meta property="og:image"         content="{{$t_img }}" />
<meta property="og:image"         content="{{ asset('/images/sj_facebook_share.jpg') }}" />

<meta name="twitter:card" content="photo" />
<meta name="twitter:site" content="@sj_sportsjun" />
<meta name="twitter:description" content="{{$t_text}}" />
<meta name="twitter:image" content="{{$t_img }}" />
<meta name="twitter:url" content="{{$t_url}}" />
<meta name="twitter:title" content="{{$t_title}}" />
