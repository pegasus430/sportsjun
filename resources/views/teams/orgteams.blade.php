 @extends('layouts.app')
 @section('content') 
 @include ('teams.orgleftmenu')
<div id="content" class="col-sm-10" style="height: 986px;">
    <div class="col-sm-9 tournament_profile">
       
        @if(count($teams)) 
			 <div class="group_no clearfix">
            <h4 class="stage_head">Teams</h4>
            </div>
			@foreach($teams as $t)
        <div class="t_details" style="min-height: inherit;">
        <div class="row main_tour">
            <div id="searchresultsDiv">
                <div class="col-sm-2 text-center">

                    {!! Helper::Images((!empty($t->logo)?$t->logo:''),'teams',array('class'=>'img-circle img-border img-scale-down img-responsive','height'=>90,'width'=>90) )!!}

                </div>
                <div class="col-sm-10">
                    <div class="t_tltle">
                        <div class="pull-left">
                            <a href="{{ url('/team/members').'/'.(!empty($t->id)?$t->id:0) }}">{{ !empty($t->teamname)?$t->teamname:'' }}</a>
                            <p class="t_by">By <a target="_blank" href="{{ url('/editsportprofile/'.(!empty($t->team_owner_id)?$t->team_owner_id:0))}}">{{  !empty($t->name)?$t->name:'' }}</a></p>
                        </div>
                        @if(isset($userId) && ($userId == $t->team_owner_id))
                        <div class="pull-right ed-btn">
                            <a href="{{ url('/team/edit/'.(!empty($t->id)?$t->id:0))}}" class="edit"><i class="fa fa-pencil"></i></a>
                            
                            
                            <a href="{{ url('/team/deleteteam/'.(!empty($t->id)?$t->id:0)).'/'.(empty($t->isactive)?'a':'d')}}" class="delete" title="{{empty($t->isactive)?'Activate':'Deactivate'}}" data-toggle="tooltip" data-placement="top">
                            {!! empty($t->isactive)?"<i class='fa fa-check'></i>":"<i class='fa fa-ban'></i>" !!}</a>                            
                        </div>
                        @endif
                    </div>

                    <div class="clearfix"></div>
                    <p class="lt-grey">{{ !empty($t->description)?$t->description:'' }}</p>
                </div>

            </div>
        </div>
        </div>
        @endforeach 
		@else
		<div class="sj-alert sj-alert-info sj-alert-sm">No teams found. Please add team(s) to your organization.</div>
		
		@endif
    </div>
</div>
@endsection