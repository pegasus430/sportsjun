<!DOCTYPE html>
 
<?php 
    if(!isset($organisation)){ $organisation = \App\Model\Organization::find(Session::get('organization_id'));}
    $organization = $organisation;
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$organisation->name}}: Sportsjun</title>
    <!-- CSS -->

    <link href="/css/bootstrap.css" rel="stylesheet">
         


        <link href="/org/css/main.css" rel="stylesheet">
            <link href="/org/css/marketplace.css" rel="stylesheet"> 
        <link href="/org/css/font-awesome.min.css" rel="stylesheet">        
     <link href="/org/css/bootstrap.slider.css" rel="stylesheet"> 

       <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>


       <?php $js_version = config('constants.JS_VERSION');$css_version = config('constants.CSS_VERSION'); ?>

    <link href="{{ asset('/js/bootstrap-rating/bootstrap-rating.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/sportsjun.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-datepicker.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-datetimepicker.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/jquery-confirm.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/jquery-ui.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/ladda.min.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/sportsform.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-switch.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/album-popup.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/marketplace-showdetails.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/aftab.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/scorecard.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/teams.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/members.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/green.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/_all.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/sinister.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-select.css') }}?v=<?php echo $css_version;?>" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/css/sidebar-menu.css') }}?v=<?php echo $css_version;?>" />
    <link rel="stylesheet" href="{{ asset('/css/select-multiple.css') }}?v=<?php echo $css_version;?>" />
    <link rel="stylesheet" href="{{ asset('/css/select2.min.css') }}?v=<?php echo $css_version;?>" />
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

 
     @yield('styles')

     <style type="text/css">
            .glyphicon-lg img{
                    border-radius: 50%;
            }
     </style>
     <meta property="fb:app_id" content="{{ env('FACEBOOK_APP_ID') }}" />

       <script src="{{ asset('/js/jquery-2.1.1.min.js') }}?v=<?php echo $js_version;?>"></script>
    <script src="{{ asset('/js/jquery-ui.js') }}?v=<?php echo $js_version;?>"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}?v=<?php echo $js_version;?>"></script>
    <script src="{{ asset('/js/bootstrap-switch.js') }}?v=<?php echo $js_version;?>"></script>
    <script type="text/javascript" src="{{ asset('/js/jquery-form.js')}}?v=<?php echo $js_version;?>"></script>
    <script src="{{ asset('/js/sinister.js') }}?v=<?php echo $js_version;?>" type="text/javascript"></script>
    <script src="{{ asset('/js/sidebar-menu.js') }}?v=<?php echo $js_version;?>"></script>
    <script src="{{ asset('/js/select2/select2.full.min.js') }}?v=<?php echo $js_version;?>"></script>
</head>

<body>

  <div class="wrap">

@if(!Request::has('from_org'))

@if (!(isset($is_widget) && $is_widget) && (Auth::check() && Auth::user()->type!='1'))
        @include('layouts.menu')
    @endif

    
<div class="page-head jumbotron">
        <!-- Hero Panel -->
       <div class="container">
    <div class="row">
        <div class="col-sm-2 mlogo">          
         
            <div class="glyphicon-lg " style="width: 100px; height: 100px;  ">
                <a href="/" >   {!! Helper::makeImageHtml($organisation->logoImage,array('height'=>100,'width'=>100) )!!} </a>
            </div>
             
            <!--<img src="http://placehold.it/110x110/6a737b/ffffff" class="img-circle"> --></div>
        <div class="col-sm-7 mtitle">
            <h1>{{$organisation->name}}</h1>
            <div class="pull-left"> <span><i class="fa fa-map-marker"></i> {{$organisation->location}}</span> <a href="#" class="follow"><i class="fa fa-star-o"></i> Follow Us</a> </div>
        </div>
        <div class="col-sm-3 minfolinks">
        <ul class="nav navbar-nav navbar-right">
                <li><a href="/organization/{{$organisation->id}}"><span class="fa fa-home"></span></a></li>

            @if(Auth::user()->type=='1')
                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="fa fa-user"></span></a>
                    <ul class="dropdown-menu">
                      <!--   <li><a href="#"><span class="fa fa-user-circle-o"></span> User Profile</a></li>
                        <li><a href="#"><span class="fa fa-trophy"></span> Sports Profile</a></li> -->
                        <li><a href="/organization/{{$organisation->id}}/settings"><span class="fa fa-gear"></span> Settings</a></li>
                        <li><a href="/organization/{{$organisation->id}}/settings#change-password"><span class="fa fa-lock"></span> Change password</a></li>
                        <li><a href="/auth/logout"><span class="fa fa-power-off"></span> Logout</a></li>
                    </ul>
                </li>
            @endif

            @if(Auth::user()->type!='1')
             
                  <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="fa fa-shopping-cart"></span><span class="cart-bubble cart_total">{{Cart::getContent()->count()}}</span></a>
                    <ul class="dropdown-menu" >
                  
                 @if(Cart::getContent()->count())
                    @foreach(Cart::getContent() as $ca)
                        <li ><a href="/cart/list"> {{str_limit($ca->name,15)}} <span class='pull-right'>X {{$ca->quantity}}</span> </a> </li>
                    @endforeach    
                 @else
                    <li> Cart is empty!</li>
                 @endif
                      
                    </ul>
                </li>
            @endif
                <!--
                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-plus"></span> Create New</a>
    <ul class="dropdown-menu">
        <li><a href="#"><span class="fa fa-trophy"></span> Tournament</a></li>
        <li><a href="#"><span class="fa fa-id-card-o"></span> Coaching Session</a></li>
    </ul>
</li>
-->
            </ul>
        </div>
    </div>
</div> 
        <!-- Header -->
      <header>

          <nav class='container'>
        <div class="scroll row nav-icons">
            <ul class="col-md-12" id="nav">                 
                <li class='nav-item li_info'>
                    <a href="/organization/{{$organisation->id}}/info"><img src="/org/images/icons/icon-info.png" alt="" width="16" height="16"> Info</a>
                </li>
                <li class='nav-item li_staff'>
                    <a href="/organization/{{$organisation->id}}/staff"><img src="/org/images/icons/icon-staff.png" alt="" width="16" height="16"> Staff</a>
                </li>
                <li class='nav-item li_team' >
                    <a href="/organization/{{$organisation->id}}/groups"><img src="/org/images/icons/icon-group.png" alt="" width="16" height="16"> Team</a>
                </li>
                <li class='nav-item'>
                    <a href="/organization/{{$organisation->id}}/players"><img src="/org/images/icons/icon-players.png" alt="" width="16" height="16"> Players</a>
                </li>
                <li class='nav-item'>
                    <a href="/organizationTournaments/{{$organisation->id}}"><img src="/org/images/icons/icon-tournament.png" alt="" width="16" height="16"> Tournaments</a>
                </li>
                <li class='nav-item'>
                    <a href="/organization/{{$organisation->id}}/schedules"><img src="/org/images/icons/icon-schedule.png" alt="" width="16" height="16"> Schedule</a>
                </li>
                <li class='nav-item'>
                    <a href="/organization/{{$organisation->id}}/coaching"><img src="/org/images/icons/icon-coach.png" alt="" width="16" height="16"> Coaching</a>
                </li>
                <li class='nav-item'>
                    <a href="/organization/{{$organisation->id}}/marketplace"><img src="/org/images/icons/icon-marketplace.png" alt="" width="16" height="16"> marketplace</a>
                </li>
                <li class='nav-item'>
                    <a href="/user/album/organization?id=0&team_id={{$organisation->id}}"><img src="/org/images/icons/icon-gallery.png" alt="" width="16" height="16"> Gallery</a>
                </li>
                  <li class='nav-item'>
                    <a href="/organization/{{$organisation->id}}/polls"><i class="fa fa-bar-chart"></i> Polls</a>
                </li>
                 <li class='nav-item'>
                    <a href="/organization/{{$organisation->id}}/news"><i class="fa fa-bar-chart"></i> News</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
    </div>

@endif

    <div class="container">
            <div class="alert alert-danger" style="display: none;">

            </div>

            <div class="alert alert-success" style="display: none;">

            </div>

            @if($errors->has())
                <div class="alert  alert-danger"> 
                          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
               @foreach ($errors->all() as $error)
                  <div>{{ $error }}</div>
              @endforeach
                </div>
            @endif

            @if(Session::has('message'))
                  <div class="alert  alert-success"> 
                          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{Session::get('message')}}
                </div>
            @endif

             @if(Session::has('error'))
                  <div class="alert  alert-danger"> 
                          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{Session::get('error')}}
                </div>
            @endif
    </div>
    <!-- Page Head -->
    
    @yield('content')


</div>


    <!-- Footer -->

    <div class="clearfix">
   <footer class="">
    <hr>
    <div class="col-md-12">
        <div class="social-icons-wrapper text-center"> <a href="#" target="_self" class="google-plus"><i class="fa fa-google-plus"></i></a> <a href="#" target="_self" class="twitter"><i class="fa fa-twitter"></i></a> <a href="#" target="_self" class="facebook"><i class="fa fa-facebook"></i></a> <a href="#" target="_self" class="linkedin"><i class="fa fa-linkedin"></i></a> </div>
        <div class="text-center copyright"> Powered by
            <br> <img src="/org/images/sportsjun-logo.png" height=40 alt="" /></div>
    </div>
    <div class="clearfix"></div>
    <hr> </footer>



    <script type="text/javascript">
            var is_organization=true;
            var base_url = '';
    </script>

    <div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : "{{env('FACEBOOK_APP_ID')}}",   // App ID from the app dashboard
      status     : true,                                 // Check Facebook Login status
      xfbml      : true                                  // Look for social plugins on the page
    });

    // Additional initialization code such as adding Event Listeners goes here
  };

  // Load the SDK asynchronously
  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/all.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
    @include ('layouts.footer_scripts')



        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> 

        <script type="text/javascript" src='/org/js/scripts.js'></script>
    <script>

        // Page Active
        jQuery(function () {
            var page = location.pathname.split('/').pop();
            $('#nav li a[href="' + page + '"]').addClass('active')
        });
    </script>

    <script type="text/javascript">
    var contenteditable_val =''

         $('[contenteditable="true"]').focus(function(){
                contenteditable_val = $(this).html();
                console.log(contenteditable_val);
         })

        $('[contenteditable="true"]').blur(function(){

        if(contenteditable_val!=$(this).html()){
            data={
                value:$(this).html(),
                model:$(this).attr('model'),
                field:$(this).attr('field')
            }
           that= $(this);
                $.ajax({
                    url:'/organization/{{$organisation->id}}/update_fields',
                    data:data,
                    success:function(){
                        show_alert('success', 'Updated')
                    },
                    error:function(){
                        show_alert('danger', 'Failed to Update');
                        $(that).html(contenteditable_val);
                        console.log(contenteditable_val)
                    }
                })
            }
              
        })

        function show_alert(type, message){
            $('.alert-'+type).show().html(message)
            window.setTimeout(function() {
                $('.alert-'+type).hide().html('');
            }, 3000);
        }
    </script>


<script type="text/javascript">


        function add_to_cart(id,item){
          
            $.ajax({
                url:'/cart/add_to_cart',
                data:{id:id},
                success:function(response){
                    toastr.success('Added to Cart',item)
                    $('.cart_total').html(response.lenght)
                },
                error:function(){
                    toastr.error('Sorry an Error Occured!',item)
                }

            })
        }
</script>


  @yield('end_scripts')




<script type="text/javascript">
        $.ajax();
</script>

   


</body>

</html>