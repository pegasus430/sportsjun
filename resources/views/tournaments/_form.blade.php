<?php
$disable = '';
if(!empty($matchScheduleCount) && $matchScheduleCount>0)
	$disable = 'disabled';
?>




<div class="main_tour_form{{$formType}}">
    <div class="row">

        <div class="col-sm-12">
            <div class="section">
                <label class="form_label">TOURNAMENT - EVENT NAME <span  class='required'>*</span></label>
            <label class="field prepend-icon">
            {!! Form::text('name', $tournament->name, array('required','class'=>'gui-input','placeholder'=> trans('message.tournament.fields.name') )) !!}
            @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
            <label for="firstname" class="field-icon"><i class="fa fa-trophy"></i></label>
            </label>
           </div>
        </div>
    </div>
    @include ('common.upload')
    <label class="form_label">{{  trans('message.tournament.fields.gallery') }} </label>
    @include ('common.uploadfield', ['uploadLimit' => 'null','field'=>'gallery'])
    @if(isset($tournament))
    @include('common.editphoto',['photos'=>$tournament->photo,'type'=>'form_gallery_tournaments'])
    @endif


    <div class="row">
    <div class="col-sm-6">
        <div class="section">
                <label class="form_label">{{  trans('message.tournament.fields.type') }} <span  class='required'>*</span></label>
            <label class="field select">
            {!! Form::select('type', $enum, null,array('class'=>'gui-input','id'=>'type','onchange'=>'getGroups();',$disable)) !!}
            @if ($errors->has('type')) <p class="help-block">{{ $errors->first('sports') }}</p> @endif
            <i class="arrow double"></i>
            </label>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="section">
            <label class="form_label">{{  trans('message.tournament.fields.sports') }} <span  class='required'>*</span></label>
        <label class="field select">

         {!! Form::select('sports_id', $sports, null,array('id'=>'sports_id','class'=>'gui-input','onchange'=>'sportsChange(this.value)',$disable)) !!}
        @if ($errors->has('sports_id')) <p class="help-block">{{ $errors->first('sports') }}</p> @endif
         <i class="arrow double"></i>
        </label>
        </div>
    </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="section show_hide_div" id="show_hide_div" style="display:none;">
            <label class="form_label">{{  trans('message.tournament.fields.schedule_type') }} <span  class='required'>*</span></label>
                <label class="field select">
                 {!! Form::select('schedule_type', $schedule_type_enum, null,array('id'=>'schedule_type','class'=>'gui-input',$disable)) !!}

                 <i class="arrow double"></i>
                </label>
            </div>
        </div>


        <div class="col-sm-6">
            <div class="section show_hide_div show_hide_game_type"  style="display:none;">
            <label class="form_label">{{  trans('message.tournament.fields.game_type') }} <span  class='required'>*</span></label>
                <label class="field select">
                 {!! Form::select('game_type', $game_type_enum, null,array('id'=>'game_type','class'=>'gui-input',$disable)) !!}

                 <i class="arrow double"></i>
                </label>
            </div>
        </div>

    </div>

    <div class="row">
         <div class="col-sm-12">
            <div class="section show_hide_rubber "  style="display:none;">
            <label class="form_label">{{  trans('message.tournament.fields.rubber_number') }} <span  class='required'>*</span></label>
                <label class="field select">
                 {!! Form::select('number_of_rubber', [3=>3, 5=>5,7=>7, 9=>9], null,array('id'=>'rubber_number','class'=>'gui-input',$disable)) !!}

                 <i class="arrow double"></i>
                </label>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-6">
            <div class="section">
    			<label class="form_label">{{  trans('message.tournament.fields.player_type') }} <span  class='required'>*</span></label>
                  <label class="field select">
                   <!-- <select id="main_player_typee" name="player_type">
                      <option value=''>Select Player Type</option>
                    </select>  -->
    			{!! Form::select('player_type', $player_types, null,array('id'=>'main_player_typee','class'=>'gui-input', $disable)) !!}
                    @if ($errors->has('player_type')) <p class="help-block">{{ $errors->first('player_type') }}</p> @endif
                    <i class="arrow double"></i>
                  </label>
            </div>
        </div>

         <div class="col-sm-6">
               <div class="section">
    		   <label class="form_label">{{  trans('message.tournament.fields.match_type') }} <span  class='required'>*</span></label>
                  <label class="field select">
                   <!-- <select id="main_match_typee" name="match_type">
                      <option value=''>Select Match Type</option>
                    </select>-->
    				{!! Form::select('match_type', $match_types, null,array('id'=>'main_match_typee','class'=>'gui-input', $disable)) !!}
                    @if ($errors->has('match_type')) <p class="help-block">{{ $errors->first('match_type') }}</p> @endif
                    <i class="arrow double"></i>
                  </label>
                </div>
         </div>

    </div>

    <div class="row">


        <div class="col-sm-6">
            <div class="section">
                <label class="form_label">{{  trans('message.tournament.fields.startdate') }} <span  class='required'>*</span></label>
                <label class='field' >
                    <div class="input-group date" id='startdate'>
                        {!! Form::text('start_date', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.startdate'))) !!}
                        <span class="input-group-addon">
        	                <span class="glyphicon glyphicon-calendar"></span>
    	                </span>
                    </div>
                    @if ($errors->has('start_date')) <p class="help-block">{{ $errors->first('start_date') }}</p> @endif
                </label>

            </div>
        </div>

         <div class="col-sm-6">
                <div class="section">
                    <label class="form_label">{{  trans('message.tournament.fields.enddate') }}  <span  class='required'>*</span></label>
                        <label class='field'>
                        	<div class='input-group date' id='enddate'>
                                {!! Form::text('end_date', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.enddate'))) !!}
                                <span class="input-group-addon">
    	                            <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                        	</div>
                        @if ($errors->has('end_date')) <p class="help-block">{{ $errors->first('end_date') }}</p> @endif
                        </label>

                </div>
         </div>

    </div>


    <div class="row">
    <div class="col-sm-6">
        <div class="section">
            <label class="form_label">{{  trans('message.tournament.fields.contactnumber') }}  <span  class='required'>*</span></label>
                <label for="mobile_phone" class="field prepend-icon">

                        {!! Form::text('contact_number', null, array('class'=>'gui-input phone-group', 'placeholder'=>trans('message.tournament.fields.contactnumber'))) !!}
                        @if ($errors->has('contact_number')) <p class="help-block">{{ $errors->first('contact_number') }}</p> @endif
                <label for="mobile_phone" class="field-icon"><i class="fa fa-mobile-phone"></i></label>
                </label>
        </div>
    </div>

    <div class="col-sm-6">
    <div class="section">
        <label class="form_label">{{  trans('message.tournament.fields.altcontactnumber') }} </label>
    		<label for="mobile_phone" class="field prepend-icon">

    				{!! Form::text('alternate_contact_number', null, array('class'=>'gui-input phone-group', 'placeholder'=>trans('message.tournament.fields.altcontactnumber'))) !!}
    				@if ($errors->has('alternate_contact_number')) <p class="help-block">{{ $errors->first('alternate_contact_number') }}</p> @endif
    		<label for="mobile_phone" class="field-icon"><i class="fa fa-mobile-phone"></i></label>
    		</label>
    </div>
    </div>

    </div>

    <div class="row">
    <div class="col-sm-6">
    <div class="section">
        <label class="form_label">{{  trans('message.tournament.fields.contactpersonname') }}<span  class='required'>*</span></label>
    	<label class="field prepend-icon">
    	@if(!empty($manager_name))
    		{!! Form::text('manager_id', $manager_name, array('required','class'=>'gui-input','placeholder'=> trans('message.tournament.fields.contactpersonname'),'id'=>'manager_id' )) !!}
    	@else
    		{!! Form::text('manager_id', null, array('required','class'=>'gui-input','placeholder'=> trans('message.tournament.fields.contactpersonname'),'id'=>'manager_id' )) !!}
    	@endif
    	@if ($errors->has('manager_id')) <p class="help-block">{{ $errors->first('manager_id') }}</p> @endif
    	<label for="firstname" class="field-icon"><i class="fa fa-user"></i></label>
    	</label>
    	<input type="hidden" id="user_id" name="user_id" value="">

    </div>
    </div>

    <div class="col-sm-6">
    <div class="section">
        <label class="form_label">{{  trans('message.tournament.fields.email') }}  <span  class='required'>*</span></label>
    	<label for="useremail" class="field prepend-icon">

    		{!! Form::text('email', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.email'))) !!}
    		@if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
    	<label for="useremail" class="field-icon"><i class="fa fa-envelope"></i></label>
    	</label>
    </div>
    </div>
    </div><!-- end section -->


    	 @include ('common.address', ['mandatory' => 'mandatory'])
    <div class="row" id="hide_groups">
    <div class="col-sm-6">
        <div class="section">
            <label class="form_label">{{  trans('message.tournament.fields.groups') }}  <span  class='required'>*</span></label>
                <label class="field prepend-icon">

                @if($type=='create')

                {!! Form::text('groups_number', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.groups'))) !!}

                @endif
                @if($type=='edit')
                {!! Form::text('groups_number', $tournamentGroupCount, array('class'=>'gui-input','placeholder'=>'groups_number','readonly'=>'true')) !!}

                @endif
                @if ($errors->has('groups_number')) <p class="help-block">{{ $errors->first('groups_number') }}</p> @endif
                <label for="groups_number" class="field-icon"><i class="fa fa-group"></i></label>

                </label>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="section">
            <label class="form_label">{{  trans('message.tournament.fields.noofteams') }} <span  class='required'>*</span></label>
            <label class="field prepend-icon">

                 {!! Form::text('groups_teams', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.noofteams'))) !!}
                    @if ($errors->has('groups_teams')) <p class="help-block">{{ $errors->first('groups_teams') }}</p> @endif
                                <label for="noofteams" class="field-icon"><i class="fa fa-group"></i></label>

             </label>
        </div>
    </div>
    </div> 
    <!--<div class="row">
    <div class="section col-sm-6">
        <label class="form_label">{{  trans('message.tournament.fields.facility') }} </label>
    	<label class="field prepend-icon">
    	{!! Form::text('facility_name', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.facility'),'id'=>'facility')) !!}
    	{!! Form::hidden('facility_response', null, array('class'=>'gui-input','placeholder'=>'Group','id'=>'response')) !!}
    	{!! Form::hidden('facility_response_name', null, array('class'=>'gui-input','placeholder'=>'Group','id'=>'response_name')) !!}
    	@if ($errors->has('facility_id')) <p class="help-block">{{ $errors->first('facility_id') }}</p> @endif
    	</label>
    </div>-->
    <div class="row">
    <div class="col-sm-4">
        <div class="section">
            <label class="form_label">{{  trans('message.tournament.fields.pointstowinningteam') }} </label>
            <label class="field prepend-icon">
            {!! Form::text('points_win', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.pointstowinningteam'),$disable)) !!}
            @if ($errors->has('points_win')) <p class="help-block">{{ $errors->first('points_win') }}</p> @endif
                                <label for="pointstowinningteam" class="field-icon"><i class="fa fa-thumbs-up"></i></label>
            </label>
        </div>
    </div>
    <div class="col-sm-4">
    <div class="section">
        <label class="form_label">{{  trans('message.tournament.fields.Pointstolosingteam') }} </label>
    	<label class="field prepend-icon">
    	{!! Form::text('points_loose', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.Pointstolosingteam'),$disable)) !!}
    	@if ($errors->has('points_loose')) <p class="help-block">{{ $errors->first('points_loose') }}</p> @endif
                                    <label for="Pointstolosingteam" class="field-icon"><i class="fa fa-thumbs-down"></i></label>
    	</label>
    </div>
    </div>
    <div class="col-sm-4">
    <div class="section">
        <label class="form_label">{{  trans('message.tournament.fields.Pointsfortie') }} </label>
        <label class="field prepend-icon">
        {!! Form::text('points_tie', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.Pointsfortie'),$disable)) !!}
        @if ($errors->has('points_tie')) <p class="help-block">{{ $errors->first('points_tie') }}</p> @endif
                                    <label for="Pointstolosingteam" class="field-icon"><i class="fa fa-thumbs-down"></i></label>
        </label>
    </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="section">
            <label class="form_label">{{  trans('message.tournament.fields.pricemoney') }} </label>
            <label class="field prepend-icon">
             {!! Form::text('prize_money', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.pricemoney'))) !!}
                @if ($errors->has('prize_money')) <p class="help-block">{{ $errors->first('prize_money') }}</p> @endif
                <label for="pricemoney" class="field-icon"><i class="fa fa-inr"></i></label>
            </label>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="section">
            <label class="form_label">{{  trans('message.tournament.fields.Enrollmenttype') }} </label>
            <label class="field prepend-icon">
            {!! Form::select('enrollment_type', $enrollment_type, null,array('id'=>'enrollment_type','class'=>'form-control')) !!}
            @if ($errors->has('enrollment_type')) <p class="help-block">{{ $errors->first('enrollment_type') }}</p> @endif
            <label for="Pointstolosingteam" class="field-icon"><i class="fa fa-thumbs-down"></i></label>
            </label>
        </div>
    </div>
</div>
<div class="row enroltype{{$formType}}">
    <div class="col-sm-6">
        <div class="section">
            <label class="form_label">{{  trans('message.tournament.fields.enrollmentfee') }} </label>
            <label class="field prepend-icon">
             {!! Form::text('enrollment_fee', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.enrollmentfee'))) !!}
                @if ($errors->has('enrollment_fee')) <p class="help-block">{{ $errors->first('enrollment_fee') }}</p> @endif
                            <label for="enrollmentfee" class="field-icon"><i class="fa fa-inr"></i></label>

              </label>
        </div>
    </div>
</div>
    <!--<div class="section col-sm-6">
        <label class="form_label">{{  trans('message.tournament.fields.status') }} <span  class='required'>*</span> </label>
    	<label class="field prepend-icon">
    	{!! Form::text('status', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.status'))) !!}
    	@if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
    	</label>
    </div>-->


    <div class="section">
       <label class="form_label">{{  trans('message.tournament.fields.description') }} </label>
    		<label for="comment" class="field prepend-icon">
    		{!! Form::textarea('description', null, array('class'=>'gui-textarea','placeholder'=>trans('message.tournament.fields.description'),'style'=>'resize:none','rows'=>3)) !!}
    		@if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
            <label for="comment" class="field-icon"><i class="fa fa-comments"></i></label>
    		</label>
    </div>
</div>
<script type="text/javascript">
function update()
{

    if($('#edit-tournaments').valid())
    {
        $("#type").removeAttr("disabled");
	    $("#sports_id").removeAttr("disabled");
	    $("#schedule_type").removeAttr("disabled");
	    $("#main_player_typee").removeAttr("disabled");
	    $("#main_match_typee").removeAttr("disabled");
    }

}

$(function () {
	   $("#manager_id").autocomplete({
			source: "{{ url('tournaments/getUsers') }}",
			minLength: 3,
			response: function(event, ui) {
				if (!ui.content.length) {
					var noResult = { value:"",label:"No results found" };
					ui.content.push(noResult);
					$("[name='manager_id']").val('');
				} else {
				   // $("#response").empty();
				}
			},
			select: function( event, ui ) {
				$('#user_id').val(ui.item.id);
			}
		});
});

    $(function () {
		   $("#facility").autocomplete({
                source: "{{ url('/getfacilitieslist') }}",
                minLength: 3,
                select: function( event, ui ) {
                    $('#response').val(ui.item.id);
					$('#response_name').val(ui.item.value);
                }
            });
    $("#startdate").datepicker();
    $("#enddate").datepicker();

    });
	function sportsChange(sport_id)
	{
		var sport_name = $( "#sports_id option:selected" ).text();
		if(sport_name.toLowerCase()=='tennis' || sport_name.toLowerCase()=='table tennis' || sport_name.toLowerCase()=='badminton' || sport_name.toLowerCase()=='squash')
		{
			$('#show_hide_div').show();
            $('.show_hide_div').show();
			$("#schedule_type").val('team');
		}
		else
		{
			$('#show_hide_div').hide();
            $('.show_hide_div').hide();
            $('.show_hide_rubber').hide();
		}

        if(sport_name.toLowerCase()=='smite')
        {
            $('#show_hide_div').show();
            $('.show_hide_div').show();
            $('.show_hide_game_type').hide();
            $("#schedule_type").val('team');
        }
        else
        {
            $('#show_hide_div').hide();
            $('.show_hide_div').hide();
            $('.show_hide_rubber').hide();
        }

        if(sport_name.toLowerCase()=='archery'){
                $('#show_hide_div').show();
                $("#schedule_type").val('team');
        }



		var selected_sport = $("#sports_id option:selected").text();
		selected_sport = selected_sport.toUpperCase();
		buildmatchtypedivs(selected_sport);
	}
	function buildmatchtypedivs(selected_sport, div_type)
    {
     //get match type and schedules
      $.get(base_url+"/schedule/getmatchandplayertypes",{'sport_name':selected_sport,'from_tournament':'no'},function(response,status){
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
        $("#main_match_typee").html(match_type_html);
        $("#main_player_typee").html(player_type_html);
      });
    }

    function buildmatchtypedivsOnly(selected_sport)
    {
     //get match type and schedules
      $.get(base_url+"/schedule/getmatchandplayertypes",{'sport_name':selected_sport,'from_tournament':'no'},function(response,status){
        var match_type_html = "<option value=''>Select Match Type</option>";

        if(status == 'success')
        {
          if(response.matchTypes)
          {
             $.each(response.matchTypes, function(key, value) {
              match_type_html += "<option value='"+key+"'>"+value+"</option>";
             });
          }

        }
        $("#main_match_typee").html(match_type_html);

      });
    }
	  //schedule type on chanage
    $('#schedule_type').change(function(){

      if($(this).val()==='individual')
      {
          // $('#main_myteam').attr('readonly', true);
          $("#main_player_typee option[value='mixed']").remove();
          $("#main_match_typee option[value='doubles']").remove();
          $("#main_match_typee option[value='mixed']").remove();
      }
      else
      {
        var selected_sport = $("#sports_id option:selected").text();
        selected_sport = selected_sport.toUpperCase();
        buildmatchtypedivs(selected_sport);
      }
    });
	var ttype = $('#type').val();
	if(ttype=='knockout' || ttype=='doubleknockout')
	{
		//$('#hide_groups').hide();
		$('[name=groups_number]').val(1).attr('readonly',true);
		$('[name=groups_teams]').val(32);
	}else
	{
        $('[name=groups_number]').removeAttr('readonly');
		$('#hide_groups').show();
	}
	function getGroups()
	{
		var type = $('#type').val();
		if(type=='knockout' || type=='doubleknockout')
		{
			     //$('#hide_groups').hide();
        		$('[name=groups_number]').val(1).attr('readonly',true);
                $('[name=groups_teams]').val(32);
		}
        else
		{
            $('[name=groups_number]').removeAttr('readonly');
			$('#hide_groups').show();
		}
	}


    $('#game_type').change(function(){
            if($(this).val()=='rubber'){
                $('.show_hide_rubber').show();
                $('#rubber_number').val(5);
            }
            else{
                $('.show_hide_rubber').hide();
                 $('#rubber_number').val(1);
            }
    })

    $("#schedule_type").change(function(){
            if($(this).val()=='individual'){
                 $('#rubber_number').val(1);
                 $('#game_type').val('normal');
                 $('.show_hide_rubber').hide();
                 $('.show_hide_game_type').hide()
            }
            else{
                 $('#rubber_number').val(3);
                 $('#game_type').val('normal');
                 $('.show_hide_show').hide();
                 $('.show_hide_game_type').show()
            }
    })

    $("#main_player_typee").change(function(){
            if($(this).val()=='mixed'){
                 $('#main_match_typee').val('doubles');
                 $('#main_match_typee').attr('readonly', true);
            }
            else{
               $('#main_match_typee').removeAttr('readonly');

            }
    })

    $("#main_player_typee").change(function(){

      if($(this).val()==='mixed')
      {
          // $('#main_myteam').attr('readonly', true);
          $("#main_match_typee option[value='singles']").remove();
          $("#main_match_typee option[value='any']").remove();
      }
      else
      {
        var selected_sport = $("#sports_id option:selected").text();
        selected_sport = selected_sport.toUpperCase();
        buildmatchtypedivsOnly(selected_sport);
      }
    });


    $(document).ready(function(){
        var sport_name = $( "#sports_id option:selected" ).text();
        if(sport_name.toLowerCase()=='tennis' || sport_name.toLowerCase()=='table tennis' || sport_name.toLowerCase()=='badminton' || sport_name.toLowerCase()=='squash')
        {
            $('#show_hide_div').show();
            $('.show_hide_div').show();
            $("#schedule_type").val('team');
        }else
        {
            $('#show_hide_div').hide();
            $('.show_hide_div').hide();
            $('.show_hide_rubber').hide();
        }



            // if($("#schedule_type").val()=='individual'){
            //      $('#rubber_number').val(1);
            //      $('#game_type').val('normal');
            //      $('.show_hide_rubber').hide();
            //      $('.show_hide_game_type').hide()
            // }
            // else{
            //      $('#rubber_number').val(3);
            //      $('#game_type').val('normal');
            //      $('.show_hide_show').hide();
            //      $('.show_hide_game_type').show()
            // }


             if($('#game_type').val()=='rubber'){
                $('.show_hide_rubber').show();

            }
            else{
                $('.show_hide_rubber').hide();

            }

    })




/* TODO: check this is needed
$('#country_id').change(function(){
     var c_id=$('#country_id').val();
       $.ajax({
        type: "POST",
        url: 'paymentgateways/availability',
        data: { 'c_id': c_id},
        success: function(msg) {
           if(msg==0) {
             $("#enrollment_type option[value='online']").remove();
           } else {
              $('#enrollment_type').append($('<option>', {
                value: 'online',
                text: 'ONLINE PAYMENT'
              }));

           }
        }
    })

});

*/




</script>
