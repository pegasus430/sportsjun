<?php

$teamD=Helper::getTeamDetails($team_id);
$t_url=url("/viewpublic/team/members/$team_id");
$t_title="Team Members for $team_name";
$t_text="$teamD->sharingString";

$fb_url = 'https://www.facebook.com/dialog/share?app_id=' . env('FACEBOOK_APP_ID') . '&amp;display=popup&amp;href=' .$t_url. '&amp;redirect_uri=' . url('js_close');
$tw_url = 'https://twitter.com/intent/tweet?url=' . $t_url. '&amp;text=' . $t_text . '&amp;title=' . $t_title . '&amp;via=sj_sportsjun';
$gp_url = 'https://plus.google.com/share?url=' . $t_url;

  $data_image=url('/uploads/'.$photo_path); 

?>

<div class="row">
    <br>
    <div class="ssk-group col-sm-6 col-sm-offset-6" >


        <div class="">
            <table class="sj-social">
                <tbody>
                <tr>
                    <td class="sj-social-td">
                        <a href="javascript:void(0);" onclick="SJ.GLOBAL.shareFacebook('{{$t_url}}','{{$t_title}}','{{$data_image}}', '{{$t_text}}');" class="sj-social-ancr sj-social-ancr-fb" rel="noreferrer">
                            <span class="sj-ico sj-fb-share "></span>
                            <span class="sj-font-12">Share</span>
                        </a>
                    </td>
                    <td class="sj-social-td">
                        <a href="javascript:void(0);" onclick="SJ.GLOBAL.share('{{$tw_url}}', 'sjtw');" class="sj-social-ancr sj-social-ancr-twt" rel="noreferrer">
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

    </div>
</div>