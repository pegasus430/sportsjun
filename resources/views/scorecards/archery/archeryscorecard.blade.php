@extends('layouts.app')
@section('content')
  <style>
    .error{
        color:red;
        font-weight: normal;
    }
    
  </style>


<?php 

    $team_a_name = $user_a_name;
    $team_b_name = $user_b_name;

    $match_data[0]['tournament_id']!=null?$disabled='readonly':$disabled='';
    $match_settings   =   Helper::getMatchSettings($match_data[0]['tournament_id'],$match_data[0]['sports_id']);

    $team_a_id=$match_data[0]['a_id'];
    $team_b_id=$match_data[0]['b_id'];

    $player_a_ids=$match_data[0]['player_a_ids'];
    $player_b_ids=$match_data[0]['player_b_ids'];



    $match_details=json_decode($match_data[0]['match_details']);
    $old_match_details=$match_details;
      if($match_data[0]['game_type']=='rubber'){
          if(count($rubber_details))
            $match_details=json_decode($rubber_details['match_details']);
            isset($match_details->preferences)? $match_details = $match_details: $match_details = $old_match_details;
      }

    isset($match_details->preferences)?$preferences=$match_details->preferences:[];
    

    ${'team_'.$match_data[0]['a_id'].'_score'}='0 sets';
    ${'team_'.$match_data[0]['b_id'].'_score'}='0 sets'; 

    $team_a_info='';
    $team_b_info='';
?>

<style>
   
    input:read-only { 
    background-color: #f4f4f4;

}
.button_add, .button_add:hover, .button_add:active{
   color:green;
  background: none;
  border: 0px #fff inset;
}
.button_remove, .button_remove:hover, .button_add:active{
  color:red;
  background: none;
  border: 0px #fff inset;
}

.main_tour{
    background: #111;
}

td,th{
  height: 60px;
  border: 1px inset black;
}

.border td{
    border: 1px inset #ddd !important;
}

.btn-pink, .btn-pink:active{
    background: #9050ff;
    color: white;
}
.btn-arrow{
    background: #fd9242;
    color: black;
    height:65px;
    border-radius: 50%;
    margin-left: 10px;
    width: 65px;
    padding: 0px !important;
}

.a_s:hover{
  background: #ffdddd;
}
.a_s{
    cursor: pointer;
    font-size: 20px !important;
}


</style>
<div class="col_standard archery_scorecard ">
    <div id="team_vs" class="ac_bg">
      <div class="container">
         

                @if(!is_null($match_data[0]['tournament_id']))
                <div class='row'>
                    <div class='col-xs-12'>
                        <center>
                          <a href="/tournaments/groups/{{$tournamentDetails['id']}}">
                                    <h4>    {{$tournamentDetails['name']}} Tournament </h4>
                                  </a>
                                
                       </center>
                    </div>
                </div>
            @endif

            <div class="row">
              <div class="col-xs-12">
                  <div class="match_loc">
                      {{ date('jS F , Y',strtotime($match_data[0]['match_start_date'])).' - '.date("g:i a", strtotime($match_data[0]['match_start_time'])).(($match_data[0]['facility_name']!='')?' , '.$match_data[0]['facility_name']:'').(($match_data[0]['address']!='')?' , '.$match_data[0]['address']:'') }}
                    </div>
                </div>
            </div>
			<h5 class="scoreboard_title">Archery Scorecard @if($match_data[0]['match_type']!='other')
											<span class='match_type_text'>({{ $match_data[0]['match_type']=='odi'?strtoupper($match_data[0]['match_type']):ucfirst($match_data[0]['match_type'])}} , {{ucfirst($match_data[0]['match_category'] )}})</span>
									@endif</h5>

            <h5 class="scoreboard_title"> No of Athlets : {{$match_obj->archery_players()->count()}} </h5>
        </div>
          @if (session('status'))
          <div class="alert alert-success">{{ session('status') }}</div>
          @endif
    </div>

  <div class="container">
    <div class="row">
      <div class="col-md-12">
    <div class="form-inline">
    @if($match_data[0]['hasSetupSquad'] && $match_data[0]['match_status']!='completed' )
          <br>
       <div id='end_match_button'>
    
           <button type="button" class="btn btn-danger" onclick="end_match_archery()"> End Match</button>
     
        </div>
    @endif
 @if($isValidUser && $isForApprovalExist && ($match_data[0]['winner_id']>0 || $match_data[0]['is_tied']>0 || $match_data[0]['has_result'] == 0))    
      <button style="text-align:center;" type="button" onclick="forApproval();" class=" btn btn-primary">Send Score for Approval</button>
      @endif


	@if($match_data[0]['match_status']=='completed')
	<div class="form-group">
    	<label class="win_head">Winner</label> 
        <h3 class="win_team">{{ ($match_data[0]['a_id']==$match_data[0]['winner_id'])?$user_a_name:$user_b_name }}</h3>
    </div>
	@else

     

	@endif
    <p class="match-status mg"><a href="{{ url('user/album/show').'/match'.'/0'.'/'.$action_id }}"><span class="fa" style="float: left; margin-left: 8px;"><img src=" {{ asset('/images/sc-gallery.png') }}" height="18" width="22"></span> <b>Media Gallery</b></a></p>
        @include('scorecards.share')
        <p class="match-status">@include('scorecards.scorecardstatus')</p>
    </div>



<!-- Setup Squad -->

   <div class="row">
          <div class="col-sm-12">

      @if(!$match_data[0]['hasSetupSquad'] )     


            <div class="clearfix">
            <div class="row">
                <div class="col-sm-12" id='list_rounds'>
                    @include('scorecards.archery.rounds')
                </div>
            </div>

              <!-- Schedule Type is single -->
                @if($match_data[0]['schedule_type']=='player')
                  <div class="row">
                    <div class="col-sm-12">
                       <div class="col-sm-6">
                        <center>    <h4 class="team_fall table_head">  Players</h4> </center>
                             <table class="table table-striped table-bordered">
                               @foreach($players as $player)
                                    <tr>
                                      <td style="height:25px;">
                                          {{$player->player_name}}
                                      </td>
                                    </tr>
                                  @endforeach
                              </table>
                                                 
                        </div>
                      </div>
                    </div>


              <!-- Schedule Type is Team -->

                @else
                  <!-- If schedule type is player, then we need to select the players. -->

              <hr>
            <form id='insert_players'>
              <div class="row">

                   <div class="col-sm-12">
                        <div class="col-sm-6">
                        <center>    <h4 class="team_fall table_head"> {{$team_a_name}} Players</h4> </center>
                             <table class="table table-striped table-bordered">
                               @foreach($players_a as $player)
                                    <tr>
                                      <td style="height:25px;">
                                          {{$player->player_name}}
                                      </td>
                                    </tr>
                                  @endforeach
                              </table>
                          <div id='select_a_players'>
                          </div>                          
                        </div>
                        <div class="col-sm-6">
                         <center> <h4 class="team_extra table_head"> {{$team_b_name}} Players</h4> </center>
                              <table class="table table-striped table-bordered">
                                  @foreach($players_b as $player)
                                    <tr>
                                      <td style="height:25px;">
                                          {{$player->player_name}}
                                      </td>
                                    </tr>
                                  @endforeach
                              </table>
                          <div id='select_b_players'>

                          </div>
                        </div>
                  </div>
              </div>
             

              <div class="row">
                <div class="col-sm-12">
                  <div class="row"> 
                    <center>
                      <div class="col-sm-4">
                          Number of Players
                      </div>
                    
                      <div class="col-sm-4">
                          <input type="number" id="number_of_player" name="number_of_players" class="gui-input"  value="1">
                      </div>
                      <div class="col-sm-4">
                          <button type='button' class="btn btn-primary" onclick="load_players()">Load Players</button> &nbsp;
                          <button type='button' class="btn btn-warning" onclick='insert_players()' id='save_players' style="display:none">Save </button>
                      </div>
                 
                    </center>
                    </div>
                  </div>


               


                  <div style="display:none">
                      <div id='a_players_list'>
                            @foreach($a_players as $player)
                              <option value="{{$player->id}}">{{$player->name}}</option>
                            @endforeach
                      </div>
                      <div id='b_players_list'>
                             @foreach($b_players as $player)
                                <option value="{{$player->id}}">{{$player->name}}</option>
                            @endforeach
                      </div>

                  </div>

                  <input type="hidden" name="match_id" value="{{$match_obj->id}}">


                   </form>
                </div>

                @endif



            <div class="row">
            
              <div class="col-sm-12">
                <br>
                  <hr>
                  <div class="pull-right">
                <a href='javascript:void(0)' class="btn btn-danger" data-toggle='modal' data-target='#new_round_modal'>
                  <i class="fa fa-plus"></i>
                  Add new round
                  </a>
              </div>

               <div> <span color='red'>Note :</span> Please add round to this match to score further</div>

            

              </div>
            </div>

          <!-- New round Modal -->

          <div id='new_round_modal' class="fade modal modal-tiny tossDetail" tabindex="-1" >
               <div class="modal-dialog sj_modal sportsjun-forms">
                 <div class="modal-content">
                    <div class="modal-header text-center">
                                            <button type="button" class="close" data-dismiss="modal">×</button>
                                            <h4>New Round</h4>
                                  </div>
                      <div class="alert alert-danger" id="div_failure1"></div>
                      <div class="alert alert-success" id="div_success1" style="display:none;"></div>
                    <div class="modal-body">
                    <center>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class='section'>
                                    <label class="field label">Distance</label><br>
                                    <input type="number" name="distance" id='distance' class="gui-input">
                                </div>
                            </div>
                        </div>

                         <div class="row">
                            <div class="col-sm-12">
                                <div class='section'>
                                    <label class="field label">Number of Arrows</label>
                                    <br>
                                    <input type="number" name="number_of_arrows" id='number_of_arrows' class="gui-input">
                                </div>
                            </div>
                        </div>   
                      </center>   

                    </div>


                  <div class="modal-footer">
                    <button type='button' class='btn btn-primary' onclick="add_round()" > Add Round</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  </div>
                </div>
              </div>

          </div>

          <!-- End of New round Modal -->

   
<!-- End of Squad Setup -->

  @else 

<!-- Start Scoring -->
  
    <div class="row">

      <div class="col-sm-12">

        <div class="table-responsive">

          <table class="table table-striped table-bordered border" border="1">
            <thead>
              <tr class="thead">
                <th>  </th>
                @foreach($match_obj->archery_rounds as $round)
                <th> {{$round->distance}} Mts </th>
                @endforeach
                <th> Total</th>
              </tr>
            </thead>

            <tbody>
            <?php $p_index=1;?>
              <!-- If Team -->
              @if($match_obj->schedule_type=='team')

                  @foreach($players_a as $player)
                    <tr>
                        <td>{{$player->player_name}}  </td>
                      @foreach($match_obj->archery_rounds as $round)
                        <td class="a_s player_{{$p_index}}_round_{{$round->round_number}} player_{{$player->id}}_round_{{$round->round_number}}" player_id='{{$player->id}}' user_id='{{$player->user_id}}' round_number="{{$round->round_number}}" round_id="{{$round->id}}"> {{$player->{'round_'.$round->round_number} }} </td>


                        <?php $p_index++;?>
                      @endforeach
                        <td class='player_{{$player->id}}_total text-primary' style="font-size:20px">{{$player->total}} </td>
                    </tr>
                  @endforeach

                  @foreach($players_b as $player)
                     <tr>
                          <td>{{$player->player_name}}  </td>
                      @foreach($match_obj->archery_rounds as $round)
                        <td class="a_s player_{{$p_index}}_round_{{$round->round_number}} player_{{$player->id}}_round_{{$round->round_number}}" player_id='{{$player->id}}' user_id='{{$player->user_id}}' round_number="{{$round->round_number}}" round_id="{{$round->id}}"> {{$player->{'round_'.$round->round_number} }} </td>

                        <?php $p_index++;?>
                      @endforeach
                        <td class='player_{{$player->id}}_total text-primary' style="font-size:20px">{{$player->total}}</td>
                    </tr>
                  @endforeach


              @else
                  @foreach($players as $player)
                     <tr>
                          <td>{{$player->player_name}}  </td>
                      @foreach($match_obj->archery_rounds as $round)
                        <td class="a_s player_{{$p_index}}_round_{{$round->round_number}} player_{{$player->id}}_round_{{$round->round_number}}" player_id='{{$player->id}}' user_id='{{$player->user_id}}' round_number="{{$round->round_number}}" round_id="{{$round->id}}"> {{$player->{'round_'.$round->round_number} }} </td>

                        <?php $p_index++;?>
                      @endforeach
                        <td class='player_{{$player->id}}_total text-primary' style="font-size:20px">{{$player->total}}</td>
                    </tr>
                  @endforeach
              @endif
            </tbody>
          </table>

        </div>


        <div id='load_round_details' style="display:none">
            @include('scorecards.archery.round_scoring')
        </div>

      </div>
    </div>


    <input type="hidden" id='match_id' value="{{$match_obj->id}}">
    <input type="hidden" id='selected_user_id'  value=''>
    <input type="hidden" id='selected_round_number' value="">
    <input type="hidden" id='selected_round_id' value="">
    <input type="hidden" id='selected_player_id' value="">
    <input type="hidden" id='selected_arrow_number' value="">


<!-- End Scoring -->


<!-- End Match -->

   
   @endif


  <script type="text/javascript">

    var match_id     = $('#match_id').val();

      function add_round(){
        var distance = $('#distance').val();
        var number_of_arrows = $('#number_of_arrows').val();
        var match_id         = {{$match_data[0]['id']}}
          $.ajax({
              type:'post',
              url:'/match/archery/add_round',
              data:{distance:distance,number_of_arrows:number_of_arrows,match_id:match_id},
              success:function(response){
                  $('#list_rounds').html(response);
                  $('#new_round_modal').modal('hide');
                  $('#distance').val('');
                  $('#number_of_arrows').val('');
              },
              error:function(){

              }
          })
      }


      function start_scoring(){
          $.confirm({
              title:'Alert!',
              content:'Do you want to save and start scoring?',
              confirm:function(){
                  $.ajax({
                      url:'/match/archery/start_scoring',
                      type:'post',
                      data:{ match_id:{{$match_obj->id}} },
                      success:function( ){
                          window.location = window.location;
                      },
                      error:function(){
                          alert('An error occured retry!');
                      }
                  })
              }
          })
      }

        function load_players(){
            var number_of_players = $('#number_of_player').val();       

            var html_a = ''; 
            var html_b = '';
            for(i=1; i<=number_of_players; i++){
                html_a += "<select name='a_player_"+i+"' class='select_players_a form-control' ></select> <br>";
                html_b += "<select name='b_player_"+i+"' class='select_players_b form-control' ></select> <br>";
            }

            $('#select_a_players').html(html_a);
            $('#select_b_players').html(html_b);

            var options_a = $('#a_players_list').html();
            var options_b = $('#b_players_list').html();

            $('.select_players_a').html(options_a);
            $('.select_players_b').html(options_b);

            $('#save_players').show();           
        }

        function insert_players(){
            var data = $('#insert_players').serialize();

            $.ajax({
                url:'/match/archery/insert_players',
                type:'post',
                data:data,
                success:function(){
                    $('#save_players').hide();
                }
            })
        }
  </script>


  <!-- Scoring -->

  <script type="text/javascript">
    
      $('.a_s').click(function(){
          if($(this).hasClass('selected')){
              $(this).removeClass('selected').css({background:'inherit'})
              clear_selected();
              $('#load_round_details').hide();
          }
          else{
              $('.a_s').removeClass('selected').css({background:'inherit'});
              $(this).css({background:'#ff8888'}).addClass('selected');
              $('#selected_arrow_number').val('');

              $('#selected_round_number').val($(this).attr('round_number'))
              $('#selected_user_id').val($(this).attr('user_id'))
              $('#selected_round_id').val($(this).attr('round_id'))
              $('#selected_player_id').val($(this).attr('player_id')) 



              $.ajax({
                  url:'/match/archery/load_arrow',
                  type:'post',
                  data:{round_id:$('#selected_round_id').val(),player_id:$('#selected_player_id').val(),match_id:$('#match_id').val()},
                  success:function(response){
                      $('#load_round_details').html(response);
                      $('#load_round_details').show();
                  }
              }) 
          }

      })


      function btn_arrow_click(that){
           $('.btn-arrow').css({background:'#fd9242',color:'black'});
           $('#selected_arrow_number').val($(that).attr('arrow_number'));
           $(that).css({background:'green',color:'white'})
           $('.btn-pink').css({background:'#9050ff'});
      }

      

      //$('.player_1_round_1').css({background:'#ff8888'}).addClass('selected');

      //initialization

      function init(){

        var that= $('.player_1_round_1');
              $('.a_s').removeClass('selected').css({background:'inherit'});
              $(that).css({background:'#ff8888'}).addClass('selected');
              $('#selected_arrow_number').val('');

              $('#selected_round_number').val($(that).attr('round_number'))
              $('#selected_user_id').val($(that).attr('user_id'))
              $('#selected_round_id').val($(that).attr('round_id'))
              $('#selected_player_id').val($(that).attr('player_id')) 

              $.ajax({
                  url:'/match/archery/load_arrow',
                  type:'post',
                  data:{round_id:$('#selected_round_id').val(),player_id:$('#selected_player_id').val(),match_id:$('#match_id').val()},
                  success:function(response){
                      $('#load_round_details').html(response);
                      $('#load_round_details').show();
                  }
              }) 
        }

      $(document).ready(function(){
          setTimeout(init,1000)
        //init();
      })


      function clear_selected(){
          $('#selected_round_number').val('');
          $('#selected_user_id').val('');
          $('#selected_round_id').val('');
          $('#selected_player_id').val('');
          $('#selected_arrow_number').val('');
      }


      function btn_pink_click(that){
          $('.btn-pink').css({background:'#9050ff'});

            var round_number = $('#selected_round_number').val();
            var user_id      = $('#selected_user_id').val();
            var round_id     = $('#selected_round_id').val();
            var player_id    = $('#selected_player_id').val();
            var arrow_number = $('#selected_arrow_number').val();            
            var value        = $(that).attr('value');

            var attr = $(that);

        if(round_number==''){
            $.alert({
                title:'Alert',
                content:'Please select a player',
              })
          return false;
        }

        if(arrow_number==''){
            $.alert({
                title:'Alert',
                content:'Please select an Arrow',
              })
          return false;
        }

          $.ajax({
              url:'/match/archery/arrow_scoring',
              type:'post',
              data:{match_id:match_id,user_id:user_id,arrow_number:arrow_number,round_number:round_number,round_id:round_id,player_id:player_id,value:value},
              success:function(response){
                  attr.css({background:'green'});

                  for(i=1;i<=10;i++){
                      $('.player_'+player_id+'_round_'+i).html(response['round_'+i]);
                  }

                  $('.player_'+player_id+'_total').html(response.total);

                  $('#arrow_'+arrow_number).html(value);
              },
              error:function(){
                  //attr.css({background:'green'});
              }
          })
          
      }


      function end_match_archery(){

        $.confirm({
            title:"Alert!",
            content:"Are you sure you want to end this match?",
            confirm:function(){
                $.ajax({
                  url:'/match/archery/end_match',
                  type:'post',
                  data:{match_id:match_id},
                  success:function(){
                      window.location = window.location;
                  }
              })
            }
        })

            
      }
  </script>






@stop