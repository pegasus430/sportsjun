 <ul class="nav nav-tabs nav-justified">
  <li class="{{$rubber->rubber_number==$active_rubber?'active':$rubber->match_status}}">
      <a>
     <span class="pull-left hidden-xs">{{date('jS F , Y',strtotime($rubber['match_start_date'])).' - '.date("g:i a", strtotime($rubber['match_start_time']))}}</span>
        RUBBER {{$rubber->rubber_number}}   &nbsp; &nbsp; <span style='color:white'> [ {{$rubber->match_category}} , {{$rubber->match_type}} ]</span>
        <span class='pull-right'>{{strtoupper($rubber->rubber_number==$active_rubber?'PLAYING':$rubber->match_status)}}
        </span>
        </a>
</ul>

{!!$rubber->rubber_number==$active_rubber?"<br>":''!!}


    <div class="row">
    <div class="col-sm-12">
   <div class='table-responsive'>

    @if(isset($rubber_a_array['team_id']))
      <table class='table table-striped table-bordered'>
        <thead>
          <tr class='team_fall team_title_head'>
           @if(!is_null($rubber_a_array['team_id']))  <th><b>TEAMS</b></th> @endif
             <th>PLAYERS</th>

             @for($set_index=1; $set_index<=$set; $set_index++)
              <th>SET {{$set_index}}</th>
             @endfor

          </tr>
        </thead>
        <tbody>
             <tr>
             <!-- Show teams if schedule type is team -->
            @if(!is_null($rubber_a_array['team_id']))<td><a href="/team/members/{{$rubber_a_array['team_id']}}">{{$rubber_a_array['team_name']}}</a></td>@endif

            <td><b>{{$rubber_a_array['player_name_a']}} / {{$rubber_a_array['player_name_b']}}</b></td>

          @for($set_index=1; $set_index<=$set; $set_index++)
                 
               <td class='a_set{{$set_index}} ' >
                  <span class='remove_button_left button_set_{{$set_index}}'></span>
                      {{$rubber_a_array['set'.$set_index]}}
                  <span class='add_button_left button_set_{{$set_index}}'></span>
               </td>
          @endfor
          
          </tr>

          <tr>
          <!-- Show teams if schedule type is team -->
          @if(!is_null($rubber_b_array['team_id']))<td><a href="/team/members/{{$rubber_a_array['team_id']}}">{{$rubber_b_array['team_name']}}</a></td>@endif

            <td><b>{{$rubber_b_array['player_name_a']}} / {{$rubber_b_array['player_name_b']}}</b></td>
            @for($set_index=1; $set_index<=$set; $set_index++)
                  
               <td class='b_set{{$set_index}}  '>
                   <span class='remove_button_right button_set_{{$set_index}}'></span>
                      {{$rubber_b_array['set'.$set_index]}}
                   <span class='add_button_right button_set_{{$set_index}}'></span>
                </td>
          @endfor
        </tr>

        </tbody>
        </tbody>
      </table>

        @endif
    </div>


