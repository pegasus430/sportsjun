@extends('admin.layouts.app')
@section('content')
<div class="container">
<div id="searchFilterDiv" class="sf_div">
    <form class="row">
	
        <div class="col-xs-12">
		{!! Form::select('sportsId',$sportArray,$selectedSport,['id' => 'sportsId','class' => 'input-sm selectpicker']) !!}

        {!! Form::selectMonth('month',$currentMonth, ['id' => 'currentMonth','class' => 'input-sm selectpicker']) !!}
        {!! Form::selectYear('year', config('constants.YEAR.START_YEAR'), config('constants.YEAR.END_YEAR'),$currentYear,['id' => 'currentYear','class' => 'input-sm selectpicker']) !!}
		
        <span id="submitButtonDiv"> 
            <input type="button" value="GO" onclick="listView();" class="btn" />
        </span> 
        </div>
  
    </form>
</div>
<hr>
<div class="clearfix"></div>
<div id="fc_calendar"></div>
</div>
<script type="text/javascript">
    function listView() {
        var month = $("#currentMonth").val();
        var year = $("#currentYear").val();
        var sportsId = $("#sportsId").val();
        if (month && year) {
            $.ajax({
                           url: base_url + '/admin/getlistviewevents',
                               type: 'GET',
                               data: {month: month, year: year, sportsId: sportsId, token: CSRF_TOKEN},
                               dataType: 'html',
                success: function(response) {
                    $("#fc_calendar").html(response);
                           }
            });
        } else {
            return false;
        }
    }
	$(document).ready(function() {
        listView();
    });
</script>
@endsection