@extends('layouts.app')
@section('content')
@include ('search._usermenu')
<?php
$auth_user_id = \Auth::user() ? \Auth::user()->id : false;
?>
<div id="content" class="col-sm-10">
		<div class="col-sm-9 viewmoreclass tournament_profile" id="searchresultsDiv"> 
		<div class="search_header_msg">Search results for {{$sports_array[$sport_by]}} players @if($search_city)in {{$search_city}}  @endif @if($search_by_name)  with name "{{$search_by_name}}" @endif </div>	
			@if(count($result) > 0)
			<?php $i = 0;?>
			@foreach($result as $lis)
			<?php if($i%2 == 0){ $alt_class = '';}else{ $alt_class = 'bg_white';}?>
			<div class="teams_search_display row main_tour <?php echo $alt_class;?>">	
				<div class="search_thumbnail right-caption">
                	
                    <div class="col-md-2 col-sm-3 col-xs-12 text-center">
                    	{!! Helper::Images( $lis['logo'] ,config('constants.PHOTO_PATH.USERS_PROFILE'),array('height'=>90,'width'=>90,'class'=>'img-circle img-border img-scale-down img-responsive') )!!}
					</div>
					<div class="col-md-10 col-sm-9 col-xs-12">
                    	<div class="t_tltle">
                        	<div class="pull-left"><a href="{{ url('/editsportprofile').'/'.$lis['user_id'] }}" id="{{'uname_'.$lis['user_id']}}">{{ $lis['name'] }}</a></div>
							<p class="search_location t_by">{{ $lis['location'] }}</p>
                        </div>
                        <ul class="t_tags">
                        	<li>Sports:
                            	<?php $sport_ids = explode(",", trim($lis['following_sports'],","));
							?>													   
                                <span class="green">
                                    <?php $sport_names = ''; ?>
                                    @foreach($sport_ids as $key=>$val)
                                    <?php 
                                    if(isset($sports_array[$val]))							
                                        $sport_names .= ", ".$sports_array[$val];					   
                                    ?>
                                    @endforeach													   
                                    <?php $sport_names = trim($sport_names,",");?>
                                    {{$sport_names}}
                                </span>
                            </li>
                        </ul>
                        <div class="sj_actions_new">
                            <?php if (strpos($lis['allowed_sports'], ','.$sport_by.',') !== false) {?>
                                <div class="sb_join_p_main" id="{{$lis['user_id']}}" val="TEAM_TO_PLAYER"><a href="#" class="sj_add_but"><span><i class="fa fa-plus"></i>Add To Team</span></a></div>
                            <?php } ?>                            
                            <div class="follow_unfollow_player" id="follow_unfollow_player_{{$lis['user_id']}}" uid="{{$lis['user_id']}}" val="PLAYER" flag="{{ in_array($lis['user_id'],$follow_array)?0:1 }}"><a href="#" id="follow_unfollow_player_a_{{$lis['user_id']}}" class="{{ in_array($lis['user_id'],$follow_array)?'sj_unfollow':'sj_follow' }}"><span id="follow_unfollow_player_span_{{$lis['user_id']}}"><i class="{{ in_array($lis['user_id'],$follow_array)?'fa fa-remove':'fa fa-check' }}"></i>{{ in_array($lis['user_id'],$follow_array)?'Unfollow':'Follow' }}</span></a></div>
                            @if ($auth_user_id)
                                <?php $user_rating = \App\Model\Rating::activeUserRate($lis['user_id'],\App\Model\Rating::$RATE_USER); ?>
                                @if ($user_rating)
                                        <input type="hidden" class="rating b-rating" value="{{$user_rating}}" data-readonly data-filled="fa fa-star s-rating" data-empty="fa fa-star-o s-rating"/>
                                @else
                                     <!--   <a href="#" class="sj_follow">Rate a Player</a> -->
                                        <input type="hidden" class="rating b-rating"
                                                data-filled="fa fa-star s-rating" data-empty="fa fa-star-o s-rating"
                                                data-target_id="{{$lis['user_id']}}" data-type="user"
                                        />
                                @endif
                            @endif
                        </div>                       
					</div>                      	
				</div>
			</div>
			<?php $i++;?>
			@endforeach
			@else
			<div class="sj-alert sj-alert-info">No Records</div>
			@endif
		</div>
        
            @if($totalcount>count($result))
    <div  id="viewmorediv" style="text-align: center; margin-bottom:10px;" class="col-md-12"> 
    <!--<a id="viewmorediv" class="view_tageline_mkt">
    <span class="market_place" id="viewmorebutton"><i class="fa fa-arrow-down"></i> <label>{{ trans('message.view_more') }}</label></span>
    </a>-->
    <a  id="viewmorebutton" class="view_tageline_mkt"><span class="market_place"><i class="fa fa-arrow-down"></i> <label>{{ trans('message.view_more') }}</label></span></a>
    </div>
    @endif
        
		<div class="col-sm-3 search_filters_div col-xs-12" id="sidebar-right">
			<div id="suggested_teams"></div>
			<div id="suggested_tournaments"></div>
		</div>

</div>
<input type="hidden" name="limit" id="limit" value="{{$limit}}"/>
<input type="hidden" name="offset" id="offset" value="{{$offset}}"/>
<input type="hidden" name="sport_by" id="sport_by" value="{{$sport_by}}"/>
<input type="hidden" name="city_by" id="city_by" value="{{$city_by}}"/>
<input type="hidden" name="category_by" id="category_by" value="{{$category_by}}"/>
<input type="hidden" name="search_by_name" id="search_by_name" value="{{$search_by_name}}"/>
<input type="hidden" name="search_city" id="search_city" value="{{$search_city}}"/>

@include ('widgets.teamspopup')
<script type="text/javascript">	
$(window).ready(function(){
	suggestedWidget("teams", '', $("#sport").val(), "player_to_team",$("#search_city_id").val());
	suggestedWidget("tournaments", '', $("#sport").val(), "player_to_tournament",$("#search_city_id").val());
});
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#offset").val({{$offset}});
        if ($("#viewmorediv").length) {
            $('#viewmorebutton').on("click", function(e) {
        		var page='user';
        		var urls =	'{{URL('search/viewMore')}}';
				var sport_by=$("#sport_by").val();
				var city_by=$("#city_by").val();
				var category_by=$("#category_by").val();
				var search_by_name=$("#search_by_name").val();
				var search_city = $("#search_city").val();
            var params = { limit:{{$limit}}, offset:$("#offset").val(), page:page,sport:sport_by,search_city_id:city_by,category:category_by,search_by:search_by_name,search_city:search_city  };
                    viewMore(params,urls);
            });
         global_record_count = {{$totalcount}}
        }

        $('input.b-rating').rating().on('change', function () {
            var target_id = $(this).data('target_id');
            var type = $(this).data('type');

            $.post('/ajax/setrating',
                    {
                        'to_id':target_id,
                        'type': type
                    },
                    function (){

                    }
            );

            //Set rating here
        });
    });

     $(document.body).on('click', '.sb_join_p_main' ,function(){
        var sport_id = $("#sport").val();
        var val = $(this).attr('val');
        var id = $(this).attr('id');
        var title = $("#uname_"+id).html();
        var jsflag = '';
        generateteamsdiv(sport_id,val,id,title,jsflag);       
     });  
</script>
@endsection
