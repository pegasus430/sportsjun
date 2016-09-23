<!DOCTYPE html>
<html lang="en">

<head>
<style>

    #header { position: fixed; left: 0px; top: -150px; right: 0px; height: 150px; text-align: center; }
    #footer { position: fixed; left: 0px; bottom: -150px; right: 0px; height: 150px; text-align: center; }
    .pagenum:before {
        content: counter(page);
    }
    @page { margin: 180px 50px; }
</style>

</head>

<body  id='pdf'>

<div id="footer">
    <p> Powered by Sportsjun.com</p>
    <br>    <img class="img-responsive" src='images/SportsJun_Final_Transparent.png' height="30px" width="180px">
</div>
@yield('content')


</body>
</html>