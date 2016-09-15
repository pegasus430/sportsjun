@extends('admin.layouts.app')
@section('content')
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>jQuery UI Autocomplete - Default functionality</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
 


 </head>
 
  @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif

  {!! Form::open(array('url' => '', 'files'=> true)) !!}
<div class="ui-widget">
  <label for="tags">Add Player: </label>
  <input id="user">
  <input id="response" name="response">
</div>
 <meta name="_token" content="<?php echo csrf_token(); ?>" />
<button type="button" name="add_player" id="add_player" onclick="addPlayer();" class="button btn-primary">
    Add Player
</button>
  {!!Form::close()!!}
 
 <script>
  $(function() {
            $("#user").autocomplete({
                source: 'source',
                minLength: 3,
                select: function( event, ui ) {
                    $('#response').val(ui.item.id);
                }
            });
  });
  function addPlayer()
  {
	var token = "<?php echo csrf_token();?>"; 
	var response =  $('#response').val();
	$.ajax({
			  url: 'addplayer',
			  type: "post",
			  dataType: 'JSON',
			  data: {'_token':token,'response':response},
			  success: function(data){
				  //alert(data.success);
				 //console.log(data);
			  }
		}); 
  }
		
 </script>
	@endsection