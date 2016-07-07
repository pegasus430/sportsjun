<?php 

$t_url=url("/viewpublic/gettournamentdetails/{$tournamentInfo[0]['id']}");
$t_text="{$tournamentInfo[0]['name']} is a match tournament with {$tournamentInfo[0]['prize_money']} worth. {$tournamentInfo[0]['name']} starts from {$tournamentInfo[0]['start_date']} to {$tournamentInfo[0]['end_date']} at {$tournamentInfo[0]['location']}  {$tournamentInfo[0]['description']}";

$t_title="Tournament Details for {$tournamentInfo[0]['name']}";

$fb_url = 'https://www.facebook.com/dialog/share?app_id=' . env('FACEBOOK_APP_ID') . '&amp;display=popup&amp;href=' .$t_url. '&amp;redirect_uri=' . url('js_close');
$tw_url = 'https://twitter.com/intent/tweet?url=' . $t_url. '&amp;text=' . $t_text . '&amp;title=' . $t_title . '&amp;via=sj_sportsjun';
$gp_url = 'https://plus.google.com/share?url=' . $t_url;
?>

<div class="ssk-group col-md-10 col-md-offset-1 " >
        
        <a class="ssk ssk-facebook f_b " href="#" href="javascript:void(0);" onclick="SJ.GLOBAL.share('{{$fb_url}}', 'sjfb');" ><i class="fa fa-facebook"></i>Share</a>
        
        <a class="ssk ssk-twitter tw_r" href="#" href="javascript:void(0);" onclick="SJ.GLOBAL.share('{{$tw_url}}', 'sjtw');" ><i class="fa fa-twitter"></i>Tweet</a>
        
        <a class="ssk ssk-google-plus gp_l" href="#" href="javascript:void(0);" onclick="SJ.GLOBAL.share('{{$gp_url}}', 'sjgp');"><i class="fa fa-google-plus"></i>Share</a>
        
             <hr class="im_hr">
    </div>
