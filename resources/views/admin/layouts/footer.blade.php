<script src="{{ asset('/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/js/moment.js') }}"></script>
<script src="{{ asset('/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('/js/bootstrap-datetimepicker.js') }}"></script>
<script src="{{ asset('/js/jquery.blockUI_2.64.js') }}"></script>
<script src="{{ asset('/js/metisMenu.min.js') }}"></script>
<script src="{{ asset('/js/jquery-confirm.js') }}"></script>
<script src="{{ asset('/js/icheck.min.js') }}"></script>
<script src="{{ asset('/js/cities.js') }}"></script>
<script src="{{ asset('/js/sportsjun.js') }}"></script>
<script src="{{ asset('/js/jQuery.browser.mobile.js') }}"></script>
<script src="{{ asset('/js/bootstrap-select.js') }}"></script>


<!-- <script src="{{ asset('/js/marketplace.js') }}"></script> -->
<script type="text/javascript">
    
function hideMessg() {
    if($(".alert").is(":visible"))
      $('.alert').fadeOut('slow','swing');
}

$(document).ready(function() {
    $('.selectpicker').selectpicker();
    function setHeight() {
        windowHeight = $(window).innerHeight();
        $('.rightsidebar').css('min-height', windowHeight);
        $('.leftsidebar').css('min-height', windowHeight);

    }
    setHeight();
    $(window).resize(function() {
        setHeight();
    });
    setTimeout('hideMessg()',3000);
});
function setHeight() {
        /*windowHeight = $(window).innerHeight();
        $('.rightsidebar').css('min-height', windowHeight);
        $('.leftsidebar').css('min-height', windowHeight);*/

        var heightToSet = $(document).height() - $('.navbar-default').height()-$('.sportsjun_actions').height();        
        if($(window).height() > heightToSet){
            heightToSet = $(window).height()- $('.navbar-default').height()-$('.sportsjun_actions').height();
        }
        $('#content').height(heightToSet);
        $('#content-team').height(heightToSet);
        $('.container-fluid').height(heightToSet);        
    }
$('.topmenu_{{ $top or 0 }}').addClass('active');
$('.sidemenu_{{ $left or 0}}').addClass('active_tab');



</script>