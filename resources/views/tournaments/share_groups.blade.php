<?php 

$t_url=url("/viewpublic/tournaments/groups/{$tournamentDetails[0]['id']}/group");
$t_text="{$tournamentDetails[0]['name']} is a match tournament with {$tournamentDetails[0]['prize_money']} worth. {$tournamentDetails[0]['name']} starts from {$tournamentDetails[0]['start_date']} to {$tournamentDetails[0]['end_date']} at {$tournamentDetails[0]['location']}  {$tournamentDetails[0]['description']}";

$t_title="Tournament Details for {$tournamentDetails[0]['name']}";

$fb_url = 'https://www.facebook.com/dialog/share?app_id=' . env('FACEBOOK_APP_ID') . '&amp;display=popup&amp;href=' .$t_url. '&amp;redirect_uri=' . url('js_close');
$tw_url = 'https://twitter.com/intent/tweet?url=' . $t_url. '&amp;text=' . $t_text . '&amp;title=' . $t_title . '&amp;via=sj_sportsjun';
$gp_url = 'https://plus.google.com/share?url=' . $t_url;
?>

<div class="row">
<br>
<div class="ssk-group col-sm-6 col-sm-offset-6" >
        
        <a class="f_b"  href="javascript:void(0);" onclick="SJ.GLOBAL.share('{{$fb_url}}', 'sjfb');" ><i class="fa fa-facebook"></i>Share</a>
        
        <a class="tw_r"  href="javascript:void(0);" onclick="SJ.GLOBAL.share('{{$tw_url}}', 'sjtw');" ><i class="fa fa-twitter"></i>Tweet</a>
        
        <a class="gp_l"  href="javascript:void(0);" onclick="SJ.GLOBAL.share('{{$gp_url}}', 'sjgp');"><i class="fa fa-google-plus"></i>Share</a>
        
    </div>
</div>
