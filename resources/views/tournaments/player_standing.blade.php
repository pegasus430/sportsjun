@extends(Auth::user() ? 'layouts.app' : 'home.layout')
@section('content')
@include ('tournaments._leftmenu')
<div id="content-team" class="col-sm-10 group_stage">
    <div class="col-sm-12 group-stage sportsjun-forms">
           
            @include('tournaments.player_stats.'.$sport_name)
    </div>
    
</div>

<script type="text/javascript">
$(function() {  
    
   
        $('.sidemenu_5').addClass('active')
       
</script>
@endsection
