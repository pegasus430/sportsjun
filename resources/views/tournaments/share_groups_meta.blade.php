<?php 

$t_url=url("/viewpublicgettournamentdetails/{$tournamentDetails[0]['id']}");
$t_text="{$tournamentDetails[0]['name']} is a match tournament with {$tournamentDetails[0]['prize_money']} worth. {$tournamentDetails[0]['name']} starts from {$tournamentDetails[0]['start_date']} to {$tournamentDetails[0]['end_date']} at {$tournamentDetails[0]['location']}  {$tournamentDetails[0]['description']}";
$t_title="Tournament Group Details for {$tournamentDetails[0]['name']}";
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
<meta property="twitter:image"         content="{{ asset('/images/sj_facebook_share.jpg') }}" />
