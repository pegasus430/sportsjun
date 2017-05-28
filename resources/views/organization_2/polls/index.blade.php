@extends('layouts.organisation')

@section('content')



		<div class="container">

		@if(!$is_owner)
			@include('organization_2.polls.vote')

		@else
			@include('organization_2.polls.manage')
		@endif

        </div>

@stop


@section('end_scripts')


<script type="text/javascript">
var i =4;
		$('.btn-add_option').click(function(){
			$('.options_list').append("<li class='li_"+i+"'><input type='text' name='option_"+i+"'><span><a href='javascript:void(0)' class='del_option' del_id='"+i+"' >X</a></span></li>");
			i++;

			$('.del_option').click(function(){
				$(this).parents('li').remove();
			})

			$('#option_index').val(i);
		});

		$('.del_option').click(function(){
			$(this).parents('li').remove();
		})

		 $(".date").datepicker();
        $(".date").datepicker();



        $(function() {
	$.validator.addMethod("greater_startdate", function(value, element) {
		 var startDate = $('[name="start_date"]').val();
		  var endDate = $('[name="end_date"]').val();
		  var startDate = startDate.split('/').reverse().join('-');
		var endDate = endDate.split('/').reverse().join('-');										
		return Date.parse(endDate) >= Date.parse(startDate);
	}, "End Date must be equal to or after Start Date");
	$('[name="end_date"]').rules("add", "greater_startdate");
		})
</script>

@stop