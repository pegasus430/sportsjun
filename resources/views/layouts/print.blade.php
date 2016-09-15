<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{csrf_token()}}" />
    <title>SportsJun - Connecting Sports People</title>
<!--<link href="{{ asset('/css/app.css') }}" rel="stylesheet">-->
    <?php $js_version = config('constants.JS_VERSION');$css_version = config('constants.CSS_VERSION'); ?>
    <link href="{{ asset('/css/bootstrap.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/sportsjun.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-datepicker.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-datetimepicker.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/jquery-confirm.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/jquery-ui.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/sportsform.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-switch.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/album-popup.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/marketplace-showdetails.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/aftab.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/scorecard.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/teams.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/green.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/_all.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/sinister.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-select.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <script src="{{ asset('/js/jquery-2.1.1.min.js') }}?v=<?php echo $js_version;?>"></script>
<script src="{{ asset('/js/jquery-ui.js') }}?v=<?php echo $js_version;?>"></script>
<script type="text/javascript" src="/js/jspdf.js">  </script>
</head>

<body  id='pdf'>
  <div class="app-wrap">
     <div class="sports_wrapper">
        <div class="row">
    @yield('content')
        </div>
    </div>
  </div>



<div class="clearfix"></div>
<div class="hr"></div>
<hr>
<center> 
<p> Powered by Sportsjun.com</p>
<br>    <img class="img-responsive" src='/images/SportsJun_Final_Transparent.png' height="30px" width="180px">
</center>

</div>
</body>



<script>
$(document).ready(function(){    
    
        var printDoc = new jsPDF('landscape');
        printDoc.fromHTML($('#pdf')[0], 15, 10, {'width': 1800}, function(){
             printDoc.save('dsdf.pdf');
         });
       
   
})

</script>