<style>
.ui-widget-content{z-index:9999;}
.ui-autocomplete {
    position: absolute;
}
/*.alert{display: none;}*/
#container_main_my_team,#container_main_opp_team {
    display: block; 
    position:relative
} 
</style>

<!-- Modal -->
{!! Form::open(['route' => 'main_addschedule','class'=>'form-horizontal','method' => 'POST','id' => 'frm_main_add_schedule']) !!} 
<div class="modal fade"  id="mainmatchschedule" role="dialog">
  <div class="modal-dialog sj_modal sportsjun-forms">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ trans('message.schedule.fields.schedulematch') }}</h4>
      </div>
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
@endif
        <div class="alert alert-success" id="div_success" style="display:none;"></div>
      <div class="alert alert-danger" id="div_failure" style="display:none;"></div>
      <div class="modal-body">
        <div class="sportsjun-forms sportsjun-container wrap-2 sportsjun-forms-modal">
          <div class="form-body">
          <div class="row">
          <div class="col-sm-12">
                <div class="section">
                <label class="form_label">{{   trans('message.team.fields.sports') }}<span  class='required'>*</span> </label>              
                  <label class="field select">
                    <select id="main_sports_id" name="main_sports_id">
                      <option value=''>Select Sport</option>
                    </select>        
                    @if ($errors->has('main_sports_id')) <p class="help-block">{{ $errors->first('main_sports_id') }}</p> @endif
                    <i class="arrow double"></i> 
                  </label>
                </div>
            </div>
			<div class="col-sm-12">
            <div class="section" id="hid_schedule_type">
              <label class="form_label">{{   trans('message.schedule.fields.scheduletype') }}<span  class='required'>*</span> </label>              
              <label class="field select">
                    <select id="main_scheduletype" name="main_scheduletype">
                      <option value="team">Team</option>
                      <option value="player">Player</option>
                    </select>        
                @if ($errors->has('main_scheduletype')) <p class="help-block">{{ $errors->first('main_scheduletype') }}</p> @endif
                <i class="arrow double"></i>
              </label>
            </div>
      </div>            
		</div>

      <!-- Start Archery Module -->

      <div id='archery' style="display:none">
        <div class="row">
            <div class="col-sm-12">
                <div class="section">
                     <label class="form_label">{{   trans('message.schedule.fields.number_of_players') }}<span  class='required'>*</span> </label>             
                    <label class="field">
                    <input id="number_of_players" name="number_of_players" class="gui-input" type="number" onchange="load_number_of_players_html(this)">
                    </label>                             
            
                </div>
            </div> 
        </div>

        <div class="row" id='list_number_of_players'>
            
        </div>

        </div>

      <!-- Stop Archery Module -->

		<div class="row non-archery">
        	<div class="col-sm-6">
                <div class="section">
                  <label class="form_label">{{   trans('message.schedule.fields.myteam') }} <span  class='required'>*</span> </label>              
                  <label class="field select">
                        <select id="main_myteam" name="main_myteam">
                          <option value=''>Select My Team</option>
                        </select>        
                    @if ($errors->has('main_myteam')) <p class="help-block">{{ $errors->first('main_myteam') }}</p> @endif
                    <i class="arrow double"></i>
                    {!! Form::hidden('main_my_team_id', null, array('id' => 'main_my_team_id')) !!}
                  </label>              
                </div>
            </div>
			<div class="col-sm-6">
                <div class="section">
                  <label class="form_label">{{   trans('message.schedule.fields.opponentteam') }}<span  class='required'>*</span> </label>              
                  <label for="main_oppteam" class="field prepend-icon">
                    {!! Form::text('main_oppteam',null, array('required','class'=>'gui-input','placeholder'=>trans('message.schedule.fields.opponentteam'),'id'=>'main_oppteam','autocomplete'=>'off')) !!}
                    <div id="container_main_opp_team"></div>
                    {!! Form::hidden('main_opp_team_id', '', array('id' => 'main_opp_team_id')) !!}
                    {!! Form::hidden('main_schedule_id', null , array('id' => 'main_schedule_id')) !!}
                    @if ($errors->has('main_oppteam')) <p class="help-block">{{ $errors->first('main_oppteam') }}</p> @endif
                    <label for="main_oppteam" class="field-icon"><i class="fa fa-user"></i></label>
                  </label>
                </div>
            </div>
        </div>
            
		<div class="row">
            <div class="col-sm-6">
                <div class="section">
                  <label class="form_label">{{   trans('message.schedule.fields.playertype') }}<span  class='required'>*</span> </label>                
                  <label class="field select">
                    <select id="main_player_type" name="main_player_type">
                      <option value=''>Select Player Type</option>
                    </select>       
                    @if ($errors->has('main_player_type')) <p class="help-block">{{ $errors->first('main_player_type') }}</p> @endif
                    <i class="arrow double"></i> 
                  </label>
                </div>
            </div>
            
            <div class="col-sm-6">
            <div class="section">
              <label class="form_label">{{   trans('message.schedule.fields.matchtype') }}<span  class='required'>*</span> </label>
              <label class="field select">
                <select id="main_match_type" name="main_match_type">
                  <option value=''>Select Match Type</option>
                </select>
                @if ($errors->has('main_match_type')) <p class="help-block">{{ $errors->first('main_match_type') }}</p> @endif
                <i class="arrow double"></i> 
              </label>
            </div>
            </div>
        </div>               

		<div class="row">
			<div class="col-sm-6">
                <div class="section">
                  <label class="form_label">{{   trans('message.schedule.fields.start_date') }}<span  class='required'>*</span> </label>              
                  <label for="main_match_start_date" class="field prepend-icon">
                    <div class='input-group date' id='main_matchStartDate'>
                      {!! Form::text('main_match_start_date',null, array('required','class'=>'gui-input','placeholder'=>trans('message.schedule.fields.start_date'),'id'=>'main_match_start_date')) !!}
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                      @if ($errors->has('main_match_start_date')) <p class="help-block">{{ $errors->first('main_match_start_date') }}</p> @endif
                    </div>
                  </label>
                </div>
            </div>
                       
			<div class="col-sm-6">                
            <div class="section">
              <label class="form_label">{{   trans('message.schedule.fields.start_time') }}</label>
              <label for="main_match_start_time" class="field prepend-icon">
                <div class='input-group date' id='main_matchStartTime'>
                  {!! Form::text('main_match_start_time',null, array('class'=>'gui-input','placeholder'=>trans('message.schedule.fields.start_time'),'id'=>'main_match_start_time')) !!}
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                  @if ($errors->has('main_match_start_time')) <p class="help-block">{{ $errors->first('main_match_start_time') }}</p> @endif
                </div>
              </label>
            </div>
            </div>
        </div>
        <div class="row">
        <div class="col-sm-12">
            <div class="section">
              <label class="form_label">{{   trans('message.schedule.fields.venue') }}<span  class='required'>*</span> </label>               
              <label for="main_venue" class="field prepend-icon">
                {!! Form::text('main_venue',null, array('required','class'=>'gui-input','placeholder'=>trans('message.schedule.fields.venue'),'id'=>'main_venue')) !!}
                {!! Form::hidden('main_facility_id', '', array('id' => 'main_facility_id')) !!}
                {!! Form::hidden('main_is_edit', '', array('id' => 'main_is_edit')) !!}
                @if ($errors->has('main_venue')) <p class="help-block">{{ $errors->first('main_venue') }}</p> @endif
                <label for="main_venue" class="field-icon"><i class="fa fa-user"></i></label>
              </label>
            </div>
        </div>            
        </div>

            @include ('common.address',['mandatory'=>'','countries'=>array(),'states'=>array(),'cities'=>array(),])

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" name="main_save_schedule" id="main_save_schedule" class="button btn-primary">Schedule</button>
        <button type="button" class="button btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
    
  </div>
</div>
{!! Form::close() !!}
{!! JsValidator::formRequest('App\Http\Requests\AddMainSchedulesRequest', '#frm_main_add_schedule'); !!}

<script type="text/javascript">

  $('#mainmatchschedule').on('show.bs.modal', function (e) {
	  	  $(".details1").empty();
    $("#hid_schedule_type").hide();
  })
  $(document).ready(function() {
      $("#main_save_schedule").show();
      $("#main_matchStartDate").datetimepicker({ format: '{{ config("constants.DATE_FORMAT.JQUERY_DATE_FORMAT") }}' });
      $('#main_matchStartTime').datetimepicker({ format: '{{ config("constants.DATE_FORMAT.JQUERY_TIME_FORMAT") }}' });

      //for getting states
      @if(isset(Auth::user()->id) && Auth::user()->profile_updated!=0)
        $.get(base_url+"/schedule/getcountries", function(data, status){
          country_html_str = "<option value=''>Select Country</option>";
          state_html_str = "<option value=''>Select State</option>";
          city_html_str = "<option value=''>Select City</option>";
          // console.log(data);
          if(status == 'success' && data.countries.length > 0)
          {
            $.each(data.countries, function( index, value ){
              country_html_str += "<option value='"+value.id+"'>"+value.country_name+"</option>";
            });
          }
          $("#country_id").html(country_html_str);
          $("#state_id").html(state_html_str);
          $("#city_id").html(city_html_str);
        });

        //for getting sports
        $.get(base_url+"/sport/getsports", function(data, status){
          html_str = "<option value=''>Select Sport</option>";
          // console.log(data.sports);
          if(status == 'success' && data.sports.length > 0)
          {
            $.each(data.sports, function( index, value ){
              html_str += "<option value='"+value.id+"' type='"+value.sports_type+"'>"+value.sports_name+"</option>";
            });
          }
          $("#main_sports_id").html(html_str);
        });
      @endif
      $("#hid_schedule_type").hide();

    });

    //schedule type on chanage
    $('#main_scheduletype').change(function(){
      $('#main_myteam').val('');
      $('#main_my_team_id').val('');
      $("#main_oppteam").val('');
      $("#main_opp_team_id").val('');
      if($(this).val()==='player')
      {
          //for autocomplete my team or player   
          // $('#main_myteam').attr("placeholder", "My Player");
          $('#main_oppteam').attr("placeholder", "Opponent Player");
          var user_id = '{{isset(Auth::user()->id)?Auth::user()->id:0}}';
          var user_name = '{{isset(Auth::user()->name)?Auth::user()->name:''}}';
          $('#main_myteam').val(user_name);
          $('#main_my_team_id').val(user_id);
          buildmyteamdiv('player');
          // $('#main_myteam').attr('readonly', true);
          $("#main_player_type option[value='mixed']").remove();
          $("#main_match_type option[value='doubles']").remove();
          $("#main_match_type option[value='mixed']").remove();

          //archery 
            var selected_sport_id = $('#main_sports_id').val();

            if(selected_sport_id==18){
                $('#archery').show();
                $('.non-archery').hide();
            }

          //stop archery
      }
      else
      {
        $('#main_myteam').attr("placeholder", "My Team");
        $('#main_oppteam').attr("placeholder", "Opponent Team");
        $('#main_myteam').attr('readonly', false);
        buildmyteamdiv('team');
        var selected_sport = $("#main_sports_id option:selected").text();
        selected_sport = selected_sport.toUpperCase();
        buildmatchtypediv(selected_sport);

              $('#archery').hide();
              $('.non-archery').show();
      }
    });
    if($('#main_scheduletype').val()==='team')
    {

    /*//for autocomplete my team or player    
      $("#main_myteam").autocomplete({
        source: function(request, response) {
              $.ajax({
                  url: base_url+"/myteamdetails",
                  dataType:'json',
                  data: {
                      team_id:($.isNumeric($("#main_oppteam").val())?$("#main_oppteam").val():0),
                      sport_id:$("#main_sports_id").val(),
                      scheduled_type:$('#main_scheduletype').val(),
                      term: request.term
                  },
                success: function (data){
                    response(data);
                 }
              });
          },
        minLength: 3,
        change: function(event,ui) { 
        if (ui.item==null || ui.item==undefined) 
        { 
          $("#main_myteam").val('');
          $("#main_myteam").focus(); 
        } 
        },
        select: function(event, ui) {
          $('#main_my_team_id').val(ui.item.id);
        },
        appendTo: "#container_main_my_team"
      });*/
      $("#main_myteam").change(function(){
        $("#main_my_team_id").val($("#main_myteam").val());
      });

      //for autocomplete opponent team or player    
      $("#main_oppteam").autocomplete({
        source: function(request, response) {
                var main_scheduletype = '';
                if ($('#main_scheduletype').val() != null)
                {
                        main_scheduletype = $('#main_scheduletype').val();
                }
                else if (typeof SJ.TEAM.scheduleType !== 'undefined')
                {
                        main_scheduletype = SJ.TEAM.scheduleType;
                }
              $.ajax({
                  url: base_url+"/oppositeteamdetails",
                  dataType:'json',
                  data: {
                      team_id:$("#main_my_team_id").val(),
                      sport_id:$("#main_sports_id").val(),
                      scheduled_type:main_scheduletype,
                      term: request.term
                  },
                success: function (data){
                    response(data);
                 }
              });
          },     
        minLength: 3,
        change: function(event,ui) { 
          if (ui.item==null || ui.item==undefined) 
          { 
            $("#main_oppteam").val('');
            $("#main_oppteam").focus(); 
          } 
        },      
        select: function(event, ui) {
          $('#main_opp_team_id').val(ui.item.id);
        },
        appendTo: "#container_main_opp_team"
      }); 
    }
    else
    {
      //for autocomplete opponent team or player    
      $("#main_oppteam").autocomplete({
        source: function(request, response) {
                var main_scheduletype = '';
                if ($('#main_scheduletype').val() != null)
                {
                        main_scheduletype = $('#main_scheduletype').val();
                }
                else if (typeof SJ.TEAM.scheduleType !== 'undefined')
                {
                        main_scheduletype = SJ.TEAM.scheduleType;
                }
              $.ajax({
                  url: base_url+"/oppositeteamdetails",
                  dataType:'json',
                  data: {
                      team_id:$("#main_my_team_id").val(),
                      sport_id:$("#main_sports_id").val(),
                      scheduled_type:main_scheduletype,
                      term: request.term
                  },
                success: function (data){
                    response(data);
                 }
              });
          },     
        minLength: 3,
        change: function(event,ui) { 
          if (ui.item==null || ui.item==undefined) 
          { 
            $("#main_oppteam").val('');
            $("#main_oppteam").focus(); 
          } 
        },      
        select: function(event, ui) {
          $('#main_opp_team_id').val(ui.item.id);
        },
        appendTo: "#container_main_opp_team"
      });      
    }
    //for autocomplete facilities
    /*$("#main_venue").autocomplete({
      source: base_url+"/facilities",
      minLength: 3,   
      select: function(event, ui) {
      $('#main_facility_id').val(ui.item.id);
      }
    });*/

    //hide schedule type
    $("#main_sports_id").change(function(){
      $('#main_myteam').val('');
      $('#main_my_team_id').val('');
      $("#main_oppteam").val('');
      $("#main_opp_team_id").val('');
      $('#main_myteam').attr('readonly', false);
      $('#main_myteam').attr("placeholder", "My Team");
      $('#main_oppteam').attr("placeholder", "Opponent Team");
      var selected_sport = $("#main_sports_id option:selected").text();
      selected_sport = selected_sport.toUpperCase();
      var sports_type = $('#main_sports_id option:selected').attr('type');
      if(sports_type == 'both')
      {
        $("#hid_schedule_type").show();
        $("#main_scheduletype").val('team');
      }
      else
      {
        if(sports_type = 'team')
        {
          $("#main_scheduletype").val('team');
        }
        else
        {
          $("#main_scheduletype").val('player');
        }
        $("#hid_schedule_type").hide(); 
      }
      if($("#main_sports_id").val()!='') {
            buildmyteamdiv('');
            buildmatchtypediv(selected_sport);
       }

       // Archery Module



       // stop Archery Module
      
    });
    function buildmyteamdiv(flag)
    {
      var my_team_html = '';
      if(flag === 'player')
      {
         my_team_html = "<option value=''>Select My Player</option>";
        var user_id = '{{isset(Auth::user()->id)?Auth::user()->id:0}}';
        var user_name = '{{isset(Auth::user()->name)?Auth::user()->name:''}}';
        my_team_html += "<option value='"+user_id+"' selected>"+user_name+"</option>";
        $("#main_myteam").html(my_team_html);
      }
      else
      {
        $.post(base_url+"/schedule/getmymanagingteams",{'sport_id':$("#main_sports_id").val()},function(response,status){
           my_team_html = "<option value=''>Select My Team</option>";
          if(status == 'success')
          {            
            if(response.managingteams)
            {
              $.each(response.managingteams, function(key, value) {
                my_team_html += "<option value='"+value.id+"'>"+value.name+"</option>";
              });
            }
             
          }
          $("#main_myteam").html(my_team_html);
        });        
      }
      
    }
    function buildmatchtypediv(selected_sport)
    {
     //get match type and schedules
      $.get(base_url+"/schedule/getmatchandplayertypes",{'sport_name':selected_sport},function(response,status){
        var match_type_html = "<option value=''>Select Match Type</option>";
        var player_type_html = "<option value=''>Select Player Type</option>";
        if(status == 'success')
        {
          if(response.matchTypes)
          {
             $.each(response.matchTypes, function(key, value) {
              match_type_html += "<option value='"+key+"'>"+value+"</option>";
             });
          }
          if(response.playerTypes)
          {
             $.each(response.playerTypes, function(key, value) {
              player_type_html += "<option value='"+key+"'>"+value+"</option>";
             });          
          }
        }
        $("#main_match_type").html(match_type_html);
        $("#main_player_type").html(player_type_html);
      });      
    }
  
  //save match schedule
  $("#main_save_schedule").click(function(){
  if($('#frm_main_add_schedule').valid()) //if form is valid
    {
        $("#main_save_schedule").before("<div id='loader'></div>");
            $("#loader").html("<img src="+base_url+"/images/loaderwhite_21X21.gif>");
            $("#main_save_schedule").hide();
      $("#frm_main_add_schedule").ajaxSubmit({
        url: base_url + '/main_addschedule', 
        type: 'get',
        dataType:'json',
        success:function(data){
          if(data.success)
          {
            // console.log(data.success);
            $("#div_success").text(data.success);
            $("#div_success").show();
          }
          else
          {
            // console.log(data.success);
            $("#div_failure").text(data.failure);
            $("#div_failure").show();
            $("#loader").remove();
            $("#main_save_schedule").show();
          }
          $('.modal .modal-body').animate({scrollTop:0},500);
          //on success reload the page
          window.setTimeout(function(){location.reload()},1000)

        },
        error: function ( xhr, status, error) {
          //on error get the errors and display
          $("#loader").remove();
          $("#main_save_schedule").show(); 
          var data=xhr.responseText;
          var parsed_data = JSON.parse(data);
          $.each(parsed_data, function(key, value) {
            if(key ===  'main_match_start_date')//if error thrown is for date picker
            {
              $("#"+key).parent().parent().parent().addClass('has-error');  
              $("#"+key).parent().parent().append(getErrorHtml(value, key, '_'+key));
            }
            else //if other errors
            {
              $("#"+key).parent().parent().addClass('has-error'); 
              $("#"+key).parent().append(getErrorHtml(value, key, '_'+key));
            }
            
          });
        }
      }); 
       }else{
      return true;
    }
  });

  //function to build span on error
  function getErrorHtml(formErrors , id, flag )
  {
    var o = '<span id="'+id+'-error" class="help-block error-help-block" >';
    o += formErrors;
    o += '</span>';
    return o;
  }

</script>


<!-- Archery Module -->

<script type="text/javascript">
      function load_number_of_players_html(that){
          var number_of_players = $(that).val();
          var html ='';
          for(i=1; i<=number_of_players; i++){

                html += "<div class='col-sm-6'> <div class='section'>";
                  html += "<label class='form_label'>Player " + i +"<span  class='required'>*</span> </label>  <label class='field'><input type='text' class='gui-input select_player' type_id='"+i+"' id='player_"+i+"' name='player_"+i+"' >";                   
                  html +="</label></div><input type='hidden' name='player_id_"+i+"' id='player_id_"+i+"'>   </div>";
           
          }
             $('#list_number_of_players').html(html);
             init_players();

             //populate the first player
            var user_id = '{{isset(Auth::user()->id)?Auth::user()->id:0}}';
            var user_name = '{{isset(Auth::user()->name)?Auth::user()->name:''}}';      
            $('#player_1').val(user_name);
            $('#player_id_1').val(user_id);
      }


    function init_players(){
       $(".select_player").autocomplete({
            source:  function(request, response) {
                    var main_scheduletype = '';
                    if ($('#main_scheduletype').val() != null)
                    {
                            main_scheduletype = $('#main_scheduletype').val();
                    }
                    else if (typeof SJ.TEAM.scheduleType !== 'undefined')
                    {
                            main_scheduletype = SJ.TEAM.scheduleType;
                    }
                  $.ajax({
                      url: base_url+"/oppositeteamdetails",
                      dataType:'json',
                      data: {
                          team_id:$("#main_my_team_id").val(),
                          sport_id:$("#main_sports_id").val(),
                          scheduled_type:main_scheduletype,
                          term: request.term
                      },
                    success: function (data){
                        response(data);
                     }
                  });
              },
            minLength: 3,
            select: function(event, ui) {

              var type_id = $(this).attr('type_id');

              console.log($(this));

                $('#player_'+type_id).val(ui.item.id);
                //('#team_name').val(ui.item.value);
            }
        });
      }
</script>
