@extends(Auth::user() ? (Helper::check_if_org_template_enabled()?'layouts.organisation':'layouts.app') : 'home.layout')
@section('content')
@include ('tournaments._leftmenu')


<div id="content-team" class="col-sm-10 group_stage">

    <div class="col-sm-12 group-stage sportsjun-forms">
    	<?php $tournamentDetails =$tournamentInfo->toArray();?>
    	@include('tournaments.share_groups')
                <h4><b>PLAYER STANDING</b></h4>
                <div class="row">
                	      <div class="pull-right">
							<a href="/download/player_standing?tournament_id={{$tournamentInfo[0]['id']}}" class="btn-danger btn" name="match_schedule_tournament_201"><i class="fa fa-download"></i> Download Standing </a>
			</div>
				</div>

               
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
