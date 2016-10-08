@extends('admin.layouts.app')
@section('content')
@include ('admin.sports._leftmenu')
<div id="page-wrapper">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ trans('message.sports.fields.optionheading') }}</div>
				
				  @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
				
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/saveoptions') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">{{ trans('message.sports.fields.game') }}</label>
                            <div class="col-md-6">
								<?php if(isset($sports_name)) {?>
									<select name="sports_id" class="form-control">
										<option value="<?php echo $sports_name[0]->id;?>"><?php echo $sports_name[0]->sports_name?></option>
									</select>
								<?php } else {?>
								<select name="sports_id" class="form-control">
									<option value="-1">Select a game</option>
									<?php foreach($sports_array as $key=>$value){?>
										<option value="<?php echo $key;?>"><?php echo $value;?></option>
									<?php } ?>
									@if ($errors->has('sports_id')) <p class="help-block">{{ $errors->first('sports_id') }}</p> @endif
								</select>
								<?php }?>
                            </div>
                        </div>
						
					<?php if(isset($sports_name) && isset($sports_array)) { 
					
					?>
					<input type="hidden" name="delete_options" id="delete_options" value="">
					<input type="hidden" name="delete_questions" id="delete_questions" value="">
					<div id="question_div">
					<a style="float:right;" href="javascript:void(0)" name="new_question" id="new_question" title="Add More Question"  onclick="addQuestion(<?=$m=1?>);">{{ trans('message.sports.fields.addnewquestion') }}</a>
						<?php foreach($sports_array as $sport) {
							$val=1;
							?>
						<div id="idfordelete_<?=$sport->id;?>">
						
                        <div class="form-group">
                            <label class="col-md-4 control-label">{{ trans('message.sports.fields.question') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control question" value="<?php echo $sport->sports_question;?>" name="questions[<?=$sport->id;?>]" id="question_id_1" tt="1">
                                @if ($errors->has('question')) <p class="help-block">{{ $errors->first('question') }}</p> @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">{{ trans('message.sports.fields.questiontype') }}</label>
                            <div class="col-md-6">
                                <label><input type="radio" name="radio_buttons[<?=$sport->id;?>]" value="check_box" id="check_box_1" tt="1" <?php echo ($sport->sports_element=="check_box")?'checked="checked"':''?>>{{ trans('message.sports.fields.checkbox') }}</label>
								<label><input type="radio" name="radio_buttons[<?=$sport->id;?>]" value="radio_button" id="radio_button_1" tt="1" <?php echo ($sport->sports_element=='radio_button')?'checked="checked"':''?>>{{ trans('message.sports.fields.radiobox') }}</label>
								 @if ($errors->has('radio_button')) <p class="help-block">{{ $errors->first('radio_button') }}</p> @endif
                            </div>
                        </div>
						<?php foreach($sport->options as $option) { ?>
                        <div class="form-group myfields" id='myfields_<?=$sport->id;?>' >
                            <label class="col-md-4 control-label" id="lable_<?=$val;?>_<?=$option->id;?>">{{ trans('message.sports.fields.options') }}</label>
							
                            <div class="col-md-6" id="option_typess_<?=$val;?>_<?=$option->id?>">
                                <input type="text" class="form-control" name="option_types[<?=$option->id;?>]" value="<?php echo $option->options;?>" id="option_type_<?=$option->id;?>" tt="1">
								 @if ($errors->has('option_type')) <p class="help-block">{{ $errors->first('option_type') }}</p> @endif
								 <?php if($val==1){?>
								<a href="javascript:void(0)" name="add_percent" id="add_percent" title="Add More Options"  onclick="addFieldForEdit(<?=$sport->id;?>,<?=$option->id;?>);">(+)</a>
								 <?php } else if($val>1) { ?>
									 <a href="javascript:void(0)" name="add_percent" id="add_percent" option_id="<?=$option->id;?>" title="Add More Options" class="deleteForEdit_<?=$option->id;?>" onclick="removeFieldForEdit(<?=$option->id;?>,<?=$val;?>,<?=$option->id;?>);">(X)</a>
								<?php } ?>
                            </div>
                        </div>
						<?php 
						$val++;
						} ?>
						<a href="javascript:void(0)" style="float:right;" question_id="<?=$sport->id;?>" title="Delete Question" class="deleteForQuestion_<?=$sport->id;?>" onclick="deleteQuestion(<?=$sport->id;?>);">(Delete)</a><br/>
						</div>
					
						<?php 
						
						} ?>
						</div>
						
					<?php } else {
						?>	
					<div id="question_div">
						<a style="float:right;" href="javascript:void(0)" name="new_question" id="new_question" title="Add More Question"  onclick="addQuestion(<?=$m=1?>);"> {{ trans('message.sports.fields.addnewquestion') }}</a>
                        <div class="form-group">
                            <label class="col-md-4 control-label">{{ trans('message.sports.fields.question') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control question" name="question[1]" id="question_id_1" tt="1">
                                @if ($errors->has('question')) <p class="help-block">{{ $errors->first('question') }}</p> @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">{{ trans('message.sports.fields.questiontype') }}</label>
                            <div class="col-md-6">
                                <label><input type="radio" name="radio_button[1]"value="check_box" id="check_box_1" tt="1">{{ trans('message.sports.fields.checkbox') }}</label>
								<label><input type="radio" name="radio_button[1]" value="radio_button" id="radio_button_1" tt="1">{{ trans('message.sports.fields.radiobox') }}</label>
								 @if ($errors->has('radio_button')) <p class="help-block">{{ $errors->first('radio_button') }}</p> @endif
                            </div>
                        </div>

                        <div class="form-group myfields" id='myfields' >
                            <label class="col-md-4 control-label" id="lable_1">{{ trans('message.sports.fields.options') }}</label>
                            <div class="col-md-6" id="option_typess_1">
                                <input type="text" class="form-control" name="option_type[1][]" id="option_type_1" tt="1">
								
								 @if ($errors->has('option_type')) <p class="help-block">{{ $errors->first('option_type') }}</p> @endif
								<a href="javascript:void(0)" name="add_percent" id="add_percent" title="Add More Options"  onclick="addField(<?=$m=1?>);">(+)</a>
                               
                            </div>
                        </div>
					</div>
					<?php } ?>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="button btn-primary">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
 <input type="hidden" name="i" value="2" id="i">
 <input type="hidden" name="j" value="2" id="j">
 <input type="hidden" name="k" value="2" id="k">
 <input type="hidden" name="l" value="2" id="l">
 <input type="hidden" name="question_count" value="1" id="question_count">
@endsection
<script type="text/javascript"> 
function addField(i){
	
	var i=$('#i').val();
	var newContent =  " <label class='col-md-4 control-label' id='lable_"+i+"'>{{ trans('message.sports.fields.options') }}</label>"+
						"<div class='col-md-6' id='option_typess_"+i+"'>"+
                                "<input type='text' class='form-control' name='option_type[1][]' id='option_type_"+i+"'  tt='"+i+"'>"+
								"<a href='javascript:void(0)'  class='remove' onclick='removeField("+i+");' title='Delete'>(X)</a>"+
                            "</div>";
							$("#myfields").append(newContent); 
							 
		i++;
	  $('#i').val(i);
}
var count=1;
function addFieldForEdit(i,option_id){
	
	var l=$('#l').val();
	var newContent =  " <label class='col-md-4 control-label' id='lable_"+l+"_"+i+"'>{{ trans('message.sports.fields.options') }}</label>"+
						"<div class='col-md-6' id='option_typess_"+l+"_"+i+"'>"+
                                "<input type='text' class='form-control' name='new_option_types["+i+"]' id='option_type_"+i+"'  tt='"+i+"'>"+
								"<a href='javascript:void(0)'  class='deleteForEdit_"+option_id+"' option_id="+option_id+" onclick='removeFieldForEdit("+i+","+l+","+option_id+");' title='Delete'>(X)</a>"+
                            "</div>";
							$("#myfields_"+i).append(newContent); 
							 
	l++;
	  $('#l').val(l);
}
function addQuestion(j){
		count++;
	$('#question_count').val(count);
	qn_val = $('#question_count').val();
	var val=1;
	var j=$('#j').val();
	var k=$('#k').val();
	var newContent = "<div class='questiond_"+qn_val+"'>"+
	"<div class='form-group'>"+
                            "<label class='col-md-4 control-label'>{{ trans('message.sports.fields.question') }}</label>"+
                           "<div class='col-md-6'>"+
                                "<input type='text' class='form-control' name='question["+j+"]' id='question_id_"+j+"' tt='"+j+"'>"+
                               
                            "</div>"+
                       "</div>" +
					   " <div class='form-group'>"+
                            "<label class='col-md-4 control-label'>{{ trans('message.sports.fields.questiontype') }}</label>"+
                           "<div class='col-md-6'>"+ 
						   "<label><input type='radio' name='radio_button["+j+"]' value='check_box' id='check_box_"+j+"' tt='"+j+"'>{{ trans('message.sports.fields.checkbox') }}</label>"+
                            "<label><input type='radio' name='radio_button["+j+"]' value='radio_button' id='radio_button_"+j+"' tt='"+j+"'>{{ trans('message.sports.fields.radiobox') }}</label>"+
                            "</div>"+
                        "</div>"+
						"<div class='form-group myfields_cls test_"+qn_val+"' id='myfieldsid_"+k+"'>"+
                            "<label class='col-md-4 control-label' id='lable_1'>Options</label>"+
                           "<div class='col-md-6' id='option_typess_1'>"+ 
                                "<input type='text' class='form-control' name='option_type["+j+"][]' id='option_type_"+j+"' tt='"+j+"'>"+
								
								"<a href='javascript:void(0)' name='add_percent' id='add_percent' title='Add More Options'  onclick='addnestedField("+j+","+qn_val+");'>(+)</a>"+                               
                            "</div>"+
                        "</div>"+
						"<a href='javascript:void(0)'  style='float:right;' class='remove' onclick='removeFieldDelete("+qn_val+");' title='Delete'>(Delete)</a>"+
						"</div>";
							$("#question_div").append(newContent); 
							 
		j++;
	  $('#j').val(j);
	  val++;
}
function addnestedField(m,question){
	var k=$('#k').val();
	var j=$('#j').val();
	 //$('.myfields_cls').attr('id','myfieldsid_'+k);
	var newContent =  " <label class='col-md-4 control-label' id='lables_"+k+"'>{{ trans('message.sports.fields.options') }}</label>"+
						"<div class='col-md-6' id='option_typesss_"+k+"'>"+
                                "<input type='text' class='form-control' name='option_type["+m+"][]' id='option_type_"+k+"'  tt='"+k+"'>"+
								"<a href='javascript:void(0)'  class='remove_id' onclick='removeFieldid("+k+");' title='Delete'>(X)</a>"+
                            "</div>";
							
							$(".test_"+question).append(newContent); 
							 
		k++;
	  $('#k').val(k);
}
function removeField(i)
{
	//alert('test');
	//$( ".questiond_"+i ).hide();
	$( "#lable_"+i ).hide();
	$( "#option_typess_"+i ).hide();
	
}
function removeFieldDelete(i)
{
	$( ".questiond_"+i ).hide();
}

function removeFieldForEdit(i,l,option_id)
{
	var pasvalue = $('.deleteForEdit_'+option_id).attr('option_id');
	var value = $('#delete_options').val();
	var final_val = value+','+pasvalue;
	$('#delete_options').val(final_val);
	$( "#option_typess_"+l+"_"+i ).hide();
	$( "#lable_"+l+"_"+i ).hide();
}
function removeFieldid(k)
{
	//$(this).find('.question_div').remove();
	$( "#option_typesss_"+k ).hide();
	$( "#lables_"+k ).hide();
	
}
function deleteQuestion(q_id)
{
	var pasvalue = $('.deleteForQuestion_'+q_id).attr('question_id');
	var value = $('#delete_questions').val();
	var final_val = value+','+pasvalue;
	$('#delete_questions').val(final_val);
	$( "#idfordelete_"+q_id ).hide();
}

</script>