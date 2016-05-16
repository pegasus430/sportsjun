<?php 

// $user_a_name is used in tennis scorecard view
$team_a_name = isset($user_a_name) ? $user_a_name : $team_a_name;
$team_b_name = isset($user_b_name) ? $user_b_name : $team_b_name;
$team_share_title       = $sportsDetails[0]['sports_name'] . ' Scorecard for ' . $team_a_name . ' Vs ' . $team_b_name;                                                
$team_share_desc        = $sportsDetails[0]['sports_name'] . ' Scorecard for ' . $team_a_name . ' Vs ' . $team_b_name . ' played at ' . (($match_data[0]['facility_name']!='') ? ' , '.$match_data[0]['facility_name']:'').(($match_data[0]['address']!='')?' , '.$match_data[0]['address']:'') . ' on ' . date('jS F , Y', strtotime($match_data[0]['match_start_date']));
if (isset($tournamentDetails['tournament_parent_name']) && !empty($tournamentDetails['tournament_parent_name']))
{
        $team_share_desc = $tournamentDetails['tournament_parent_name'] . ': ' . $team_share_desc;
}
$team_share_desc_encoded = urlencode($team_share_desc);
?>                      
<meta property="og:url"           content="<?php echo url('matchpublic/scorecard/view',$match_data[0]['id']) ?>" />
<meta property="og:type"          content="website" />
<meta property="og:title"         content="<?php echo $team_share_title ?>" />
<meta property="og:description"   content="<?php echo $team_share_desc ?>" />
<meta property="og:image"         content="http://www.sportsjun.com/home/extra-images/slide1.jpg" />