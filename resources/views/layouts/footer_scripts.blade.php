<script>
    var data_token = CSRF_TOKEN = "{{csrf_token()}}";
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