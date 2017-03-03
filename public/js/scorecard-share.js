var shareFacebookLadda;
var shareTwitterLadda;
$(document).ready(function(){
  shareFacebookLadda = Ladda.create( document.querySelector( '.ladda-button.sj-social-ancr-fb' ) );
  shareTwitterLadda = Ladda.create( document.querySelector( '.ladda-button.sj-social-ancr-twt' ) );
});

function postImageToFacebook(token, filename, mimeType, imageData, message) {
    var fd = new FormData();
    fd.append('file', blobToFile(imageData, "image.png"));
    var linkUrl = window.location.href.replace("/match/", "/matchpublic/").replace("http://localhost:8000", "http://sportsjun.com");
    $.ajax({
        url: "/share/facebook",
        data: fd,
        type: 'POST',
        processData: false,
        contentType: false,
        success: function (data) {
            FB.ui({
                method: 'feed',
                picture: window.location.href.substring(0, window.location.href.indexOf("match")) + data,
                link: linkUrl,
                caption: 'Score'
            }, function(response){
            });
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
