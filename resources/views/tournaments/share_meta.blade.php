<?php

$t_url=url("/viewpublic/gettournamentdetails/{$tournamentInfo[0]['id']}");
$t_text="{$tournamentInfo[0]['name']} is a match tournament with {$tournamentInfo[0]['prize_money']} worth. {$tournamentInfo[0]['name']} starts from {$tournamentInfo[0]['start_date']} to {$tournamentInfo[0]['end_date']} at {$tournamentInfo[0]['location']}  {$tournamentInfo[0]['description']}";
$t_title="Tournament Details for {$tournamentInfo[0]['name']} - {$tournamentInfo[0]['prize_money']} Price";
$t_img=url("/uploads/tournaments/".!empty($left_menu_data['logo'])?$left_menu_data['logo']:'');
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

