@extends(Auth::user() ? 'layouts.app' : 'home.layout')
@section('content')
@include ('tournaments._leftmenu')
<div id="content-team" class="col-sm-10 group_stage">
    <div class="col-sm-12 group-stage sportsjun-forms">
    	<?php $tournamentDetails =$tournamentInfo->toArray();?>
    	@include('tournaments.share_groups')
                <h4><b>PLAYER STANDING</b></h4>
            @include('tournaments.player_stats.'.$sport_name)
    </div>
    
</div>

</div>
<div class='clearfix'>

<script type="text/javascript">
$(function() {  
    
   
        $('.sidemenu_5').addClass('active')
       
</script>
@endsection
