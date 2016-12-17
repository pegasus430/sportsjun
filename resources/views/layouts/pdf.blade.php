<!DOCTYPE html>
<html lang="en">

<head>
<style>

    #header { position: fixed; left: 0px; top: -100px; right: 0px; height: 70px; text-align: center; }
    #footer { position: fixed; left: 0px; bottom: -100px; right: 0px; height: 70px; text-align: center; }
    .pagenum:before {
        content: counter(page);
    }
    .small{
        font-size:0.7em;
    }
    @page { margin: 100px 50px; }
</style>

</head>

<body  id='pdf'>

<div id="footer">
    <p> Powered by Sportsjun.com <img class="img-responsive" src='images/SportsJun_Final_Transparent.png' height="30px" width="180px"></p>

</div>
@yield('content')


</body>
</html>