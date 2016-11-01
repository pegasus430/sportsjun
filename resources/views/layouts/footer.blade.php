<script>
    var data_token = "{{csrf_token()}}";
</script>

<?php $js_version = config('constants.JS_VERSION');$css_version = config('constants.CSS_VERSION'); ?>
<script src="{{ asset('/js/jquery-ui.min.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ asset('/js/bootstrap.min.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ asset('/js/moment.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ asset('/js/bootstrap-rating/bootstrap-rating.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ asset('/js/bootstrap-datepicker.min.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ asset('/js/bootstrap-datetimepicker.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ asset('/js/jquery.blockUI_2.64.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ asset('/js/metisMenu.min.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ asset('/js/jquery-confirm.js') }}?v=<?php echo $js_version;?>"></script>

<script src="{{ asset('/js/cities.js') }}?v=<?php echo $js_version;?>"></script>

<script src="{{ asset('/js/sinister.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ asset('/js/icheck.min.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ asset('/js/jquery.lazyload.min.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ asset('/js/jQuery.browser.mobile.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ asset('/js/bootstrap-select.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ asset('/js/jquery.select-multiple.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ asset('/js/jquery.touch.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ asset('/js/sportsjun.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ asset('/js/sj.global.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ asset('/js/sj.team.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ asset('/js/sj.tournament.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ asset('/js/sj.scorecard.js') }}?v=<?php echo $js_version;?>"></script>

<link rel="stylesheet" href="{{ asset('/js/summernote/dist/summernote.css') }}?v=<?php echo $css_version;?>">
<script src="{{ asset('/js/summernote/dist/summernote.min.js') }}?v=<?php echo $js_version;?>"></script>
<script>
var resizeId;
$(function () {
    setNavigation();
});

function setNavigation() {
    var path = window.location.pathname;
    path = path.replace(/\/$/, "");
    path = decodeURIComponent(path);

    $("#main li a").each(function () {
        var href = $(this).attr('href');
        //if (path.substring(0, href.length) === href) {
        if (window.location.href.indexOf(href) > -1) {
            $(this).parent().addClass('active');
        }
    });
}
$(document).ready(function(){
  jQuery("img.lazy").lazyload({
        effect: "fadeIn",
        effectTime: 1000,
        appear: function(ele,settings){
          $(ele).attr("src",$(ele).attr("data-original"));
        }
    });
  $('[data-toggle="tooltip"]').tooltip();
    setHeight();
    $(window).resize(function() {
        //setHeight();
        clearTimeout(resizeId);
    resizeId = setTimeout(setHeight, 500);
    });
    setTimeout('hideMessg()',3000);
    $('[data-toggle="tooltip"]').tooltip();
    $('.multiselect').selectMultiple();
    @if(isset(Auth::user()->id) && Auth::user()->profile_updated==0)
        $('a:not("#logout"),.btn').click(function(e){e.preventDefault();});//Disable clicks if the profile is not updated.
    @endif
    
    
    // Code to append the hash tag to browser while navigating through tabs
        var sportProfileUri = '{{Route::getCurrentRoute()->getUri()}}';
        if(sportProfileUri!='editsportprofile/{userId}' && sportProfileUri!='match/scorecard/edit/{match_id}') {
            var hash = window.location.hash;
            hash && $('ul.nav a[href="' + hash + '"]').tab('show');

            $('.nav-tabs a').click(function (e) {
            $(this).tab('show');
            var scrollmem = $('body').scrollTop() || $('html').scrollTop();
            window.location.hash = this.hash;
            //$('html,body').scrollTop(scrollmem);
            });
        }
});

function setHeight() {
    /*windowHeight = $(window).innerHeight();
    $('.rightsidebar').css('min-height', windowHeight);
    $('.leftsidebar').css('min-height', windowHeight);*/
    if(!jQuery.browser.mobile){
        var heightToSet = $(document).height() - $('.navbar-default').height()-$('.sportsjun_actions').height();        
        if($(window).height() > heightToSet){
            heightToSet = $(window).height()- $('.navbar-default').height()-$('.sportsjun_actions').height();
        }
        $('#content').height(heightToSet);
        $('#content-team').height(heightToSet);
        $('.container-fluid').height(heightToSet);        
    }
}

function hideMessg() {
    if($(".alert").is(":visible"))
      $('.alert').fadeOut('slow','swing');
}

$('.topmenu_{{ $top or 0 }}').addClass('active');
$('.sidemenu_{{ $left or 0}}').addClass('active');
@if(!isset($left) or $left == 0)
$('#page-wrapper').css('margin','0px 0px 0px 0px');
@endif
@if(Auth::check())
    $.fn.timer = function() {
        $.ajax({
            type: 'GET',
            url: "{{ route('auth.check') }}",
            global: false,
            dataType: 'json',
            success: function(data) {
                data.status == false ? $('#session-expired').modal({
                    show: true,
                    backdrop: 'static'
                }) : $('#session-expired').modal('hide');
            }
        });
    };

    window.setInterval(function() {
        $('#timedOutSession').timer();
    }, 1000000);
@endif
window.onload = function() {
    $('.selectpicker').selectpicker();
};
</script>
<!-- Session Expired or not checking -->
<!-- http://stackoverflow.com/questions/29089191/laravel-5-checking-if-the-user-session-has-expired -->
<div class="modal fade" id="session-expired">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Session Expired!</h4>
            </div>
            <div class="modal-body">
                <p>Your session has expired, please login to continue.</p>
            </div>
            <div class="modal-footer">
                <a href="{{url('/?open_popup=login')}}" class="btn btn-danger" style="inline-table">Login</a>
            </div>
        </div>
    </div>
</div>
<!-- <script src="{{ asset('/js/jquery.ui.touch-punch.min.js') }}"></script>
<script src="{{ asset('/js/facescroll.js') }}"></script>
<script type="text/javascript">
    jQuery(function(){ // on page DOM load
        //$('.table-responsive').alternateScroll();
        $('.customeScroll').alternateScroll();
    })
</script>-->