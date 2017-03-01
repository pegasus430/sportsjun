<?php

// $user_a_name is used in tennis scorecard view
$team_a_name = isset($user_a_name) ? $user_a_name : $team_a_name;
$team_b_name = isset($user_b_name) ? $user_b_name : $team_b_name;
$team_share_title       = $sportsDetails[0]['sports_name'] . ' Scorecard for ' . $team_a_name . ' Vs ' . $team_b_name;

$match_data[0]['facility_name'] = trim($match_data[0]['facility_name']);
$match_data[0]['address']       = trim($match_data[0]['address']);

$team_share_desc        = $sportsDetails[0]['sports_name'] . ' Scorecard for ' . $team_a_name . ' Vs ' . $team_b_name . ' played at ' . ((!empty($match_data[0]['facility_name'])) ? $match_data[0]['facility_name']:'') . ((!empty($match_data[0]['address'])) ? ((!empty($match_data[0]['facility_name'])) ? ', ':'') . $match_data[0]['address'] : '') . ' on ' . date('jS F, Y', strtotime($match_data[0]['match_start_date']));
if (isset($tournamentDetails['tournament_parent_name']) && !empty($tournamentDetails['tournament_parent_name']))
{
        $team_share_desc = $tournamentDetails['tournament_parent_name'] . ': ' . $team_share_desc;
}
$tw_team_share_desc = strlen($team_share_desc) > 60 ? substr($team_share_desc,0,60)."..." : $team_share_desc;
$tw_team_share_desc_encoded = urlencode($tw_team_share_desc);

$fb_url = 'https://www.facebook.com/dialog/share?app_id=' . env('FACEBOOK_APP_ID') . '&amp;display=popup&amp;href=' . url('matchpublic/scorecard/view',$match_data[0]['id']) . '&amp;redirect_uri=' . url('js_close');
$tw_url = 'https://twitter.com/intent/tweet?url=' . url('matchpublic/scorecard/view',$match_data[0]['id']) . '&amp;text=' . $tw_team_share_desc_encoded . '&amp;via=sj_sportsjun';
$gp_url = 'https://plus.google.com/share?url=' . url('matchpublic/scorecard/view',$match_data[0]['id']);
?>
<div class="share-scorecard pull-left">
        <table class="sj-social">
                <tbody>
                        <tr>
                                <td class="sj-social-td">
                                        <button onclick="shareTeamVSOnFacebook();" class="sj-social-ancr sj-social-ancr-fb ladda-button" data-style="slide-left" rel="noreferrer" style="margin: 0; padding: 0;">
                                          <span>
                                            <span class="sj-ico sj-fb-share" style="float: left;"></span>
                                            <span class="sj-font-12" style="top: 0;">Share</span>
                                          </span>
                                        </button>
                                </td>
                                <td class="sj-social-td">
                                        <a href="javascript:void(0);" onclick="shareTeamVSOnTweeter();', 'sjtw');" class="sj-social-ancr sj-social-ancr-twt" rel="noreferrer">
                                                <span class="sj-ico sj-twt-share"></span>
                                                <span class="sj-font-12">Tweet</span>
                                        </a>
                                </td>
                                <td class="sj-social-td">
                                        <a href="javascript:void(0);" onclick="SJ.GLOBAL.share('{{$gp_url}}', 'sjgp');" class="sj-social-ancr sj-social-ancr-gplus" rel="noreferrer">
                                                <span class="sj-ico sj-gplus-share"></span>
                                                <span class="sj-font-12">Share</span>
                                        </a>
                                </td>
                        </tr>
                </tbody>
        </table>
</div>
