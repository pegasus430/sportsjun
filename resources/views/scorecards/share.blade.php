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
                                        <button onclick="shareTeamVSOnFacebook();" class="ladda-button sj-social-ancr sj-social-ancr-fb" data-style="slide-left" rel="noreferrer" style="margin: 0; padding: 2px;">
                                          <span>
                                            <span class="sj-ico sj-fb-share" style="float: left;"></span>
                                            <span class="sj-font-12" style="top: 2px;">Share</span>
                                          </span>
                                        </button>
                                </td>
                                <td class="sj-social-td">
                                        <button onclick="shareTeamVSOnTweeter();" class="ladda-button sj-social-ancr sj-social-ancr-twt" data-style="slide-left" rel="noreferrer" style="margin: 0; padding: 2px;">
                                          <span>
                                            <span class="sj-ico sj-twt-share" style="float: left;"></span>
                                            <span class="sj-font-12" style="top: 2px;">Tweet</span>
                                          </span>
                                        </button>
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



<script type="text/javascript" src="{{ asset('/js/html2canvas.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/spin.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/ladda.js') }}"></script>
<script type="text/javascript">
    var caption = '<?php echo $tournamentDetails['name'] ?>';
var shareFacebookLadda = Ladda.create( document.querySelector( '.sj-social-ancr-fb' ) );
var shareTwitterLadda = Ladda.create( document.querySelector( '.sj-social-ancr-twt' ) );

function postImageToFacebook(token, filename, mimeType, imageData, message) {
  var fd = new FormData();
  fd.append('file', blobToFile(imageData, "image.png"));
  $.ajax({
      url: "/share/facebook",
      data: fd,
      type: 'POST',
      processData: false,
      contentType: false,
      success: function (data) {
        var linkUrl = window.location.href.replace("match", "matchpublic").replace('edit','view').replace("http://localhost:8000", "http://sportsjun.com");
        console.log("picture:", window.location.href.substring(0, window.location.href.indexOf("match")) + data);
        console.log("link:", linkUrl);
        // Create facebook post using image
        FB.ui({
          method: 'feed',
          picture: window.location.href.substring(0, window.location.href.indexOf("match")) + data,
          link: linkUrl,
          caption: 'Score'
        }, function(response){console.log(response)});
        shareFacebookLadda.stop();
      },
      error: function (shr, status, data) {
        shareFacebookLadda.stop();
      }
  });
}

function shareTeamVSOnFacebook() {
  shareFacebookLadda.start();
  html2canvas($("#team_vs"), {
    onrendered: function(canvas) {
        canvas.toBlob(function(blob) {
          FB.getLoginStatus(function (response) {
              if (response.status === "connected") {
                  postImageToFacebook(response.authResponse.accessToken, "Canvas to Facebook/Twitter", "image/png", blob, window.location.href);
              } else if (response.status === "not_authorized") {
                  FB.login(function (response) {
                      postImageToFacebook(response.authResponse.accessToken, "Canvas to Facebook/Twitter", "image/png", blob, window.location.href);
                  });
              } else {
                  FB.login(function (response) {
                      postImageToFacebook(response.authResponse.accessToken, "Canvas to Facebook/Twitter", "image/png", blob, window.location.href);
                  });
              }
          });
        });
    }
  });
}

function blobToFile(theBlob, fileName){
    //A Blob() is almost a File() - it's just missing the two properties below which we will add
    theBlob.lastModifiedDate = new Date();
    theBlob.name = fileName;
    return theBlob;
}

function shareTeamVSOnTweeter() {
  shareTwitterLadda.start();
  html2canvas($("#team_vs"), {
    onrendered: function(canvas) {
        canvas.toBlob(function(blob) {
          var fd = new FormData();
          fd.append('file', blobToFile(blob, "image.png"));
          $.ajax({
              url: "/share/twitter",
              data: fd,
              type: 'POST',
              processData: false,
              contentType: false,
              success: function (data) {
                shareTwitterLadda.stop();
              },
              error: function (shr, status, data) {
                shareTwitterLadda.stop();
              }
          });
        });
    }
  });
}

//Send Approve
function scoreCardStatus(status)
{
    var msg = ' Reject ';
    if(status=='approved')
        var msg = ' Approve ';
    $.confirm({
    title: 'Confirmation',
    content: 'Are You Sure You Want To '+msg+' This ScoreCard?',
    confirm: function() {
        match_id = $('#match_id').val();
        rej_note = $('#rej_note').val();
        $.ajax({
            url: base_url+'/match/scoreCardStatus',
            type: "post",
            data: {'scorecard_status': status,'match_id':match_id,'rej_note':rej_note,'sport_name':'Cricket'},
            success: function(data) {
                if(data.status == 'success') {
                    window.location.href = base_url+'/match/scorecard/edit/'+match_id;
                }
            }
        });
    },
    cancel: function() {
            // nothing to do
        }
    });

}
</script>
