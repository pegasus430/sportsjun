<?php
use \App\Model\Sport;

?>
<div class="container">
    <h2>Final Stage</h2>
    @if(count($tournament->final_stage_teams_list))
        <div class="row">
            @foreach ($tournament->final_stage_teams_list as $team)
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <img src="{{ $team->logoImage }}" class="img img-responsive" style="max-width:100px;"/>
                    <span>{{ $team->name }}</span>
                </div>
            @endforeach
        </div>
    @else
        <div class="sj-alert sj-alert-info">
            {{ trans('message.tournament.final.nofinalstageteams') }}
        </div>
    @endif


@include('home.search.tournament.final_stage_brackets')

</div>