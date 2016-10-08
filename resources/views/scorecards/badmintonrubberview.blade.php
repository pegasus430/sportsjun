 <ul class="nav nav-tabs nav-justified">

 <?php
    if($rubber->match_status=='completed'){
      $r_class='completed';
      $r_status= 'Completed';
    }
    else {
        if($match_data[0]['match_status']=='completed'){
            $r_class=$rubber->match_status;
            $r_status = $rubber->match_details==''?'Not played':'';
        }
        else{
            $r_class=$rubber->match_details!=''?'active':'scheduled';
            $r_status=$rubber->match_details!=''?'PLAYING':'Scheduled';
        }
    }
  ?>
  <li class="{{$r_class}}">
      <a>
     <span class="pull-left hidden-xs">{{date('jS F , Y',strtotime($rubber['match_start_date'])).' - '.date("g:i a", strtotime($rubber['match_start_time']))}}</span>
        RUBBER {{$rubber->rubber_number}}   &nbsp; &nbsp; <span style='color:white'> [ {{$rubber->match_category}} , {{$rubber->match_type}} ]</span>
        <span class='pull-right'> {{strtoupper($r_status)}}
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
        
             <th>PLAYERS</th>

             @for($set_index=1; $set_index<=$set; $set_index++)
              <th>SET {{$set_index}}</th>
             @endfor

          </tr>
        </thead>
        <tbody>
             <tr @if(!empty($rubber_a_array['team_id']) && ($rubber_a_array['team_id']==$rubber->winner_id)) class='winner_set' @endif>
           

            <td>  
             @if(!is_null($rubber_a_array['team_id']))<b>{{$rubber_a_array['team_name']}}</b><br>@endif {{$rubber_a_array['player_name_a']}} / {{$rubber_a_array['player_name_b']}}</td>

          @for($set_index=1; $set_index<=$set; $set_index++)
                 
               <td class='a_set{{$set_index}} ' >
                  <span class='remove_button_left button_set_{{$set_index}}'></span>
                      {{$rubber_a_array['set'.$set_index]}}
                  <span class='add_button_left button_set_{{$set_index}}'></span>
               </td>
          @endfor
          
          </tr>

          <tr @if(!empty($rubber_b_array['team_id']) && ($rubber_b_array['team_id']==$rubber->winner_id)) class='winner_set' @endif>
         
            <td>
            @if(!is_null($rubber_b_array['team_id']))<b>{{$rubber_b_array['team_name']}}</b><br>@endif 
            {{$rubber_b_array['player_name_a']}} / {{$rubber_b_array['player_name_b']}}</td>
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


