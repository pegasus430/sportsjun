<div class="form-group">
    <label class="col-md-4 control-label">Type</label>
    <div class="col-md-6">
		<input type="file" name="files[]" id="filer_input2" @if ($uploadLimit > 1) multiple="multiple" @endif>		
    </div>
</div>
{!! HTML::style('/css/filer/jquery.filer.css') !!}
{!! HTML::style('/css/filer/jquery.filer-dragdropbox-theme.css') !!}
{!! HTML::script('/js/filer/jquery.filer.min.js') !!}
{!! HTML::script('/js/filer/upload-config.js') !!}
<!-- <meta name="csrf-token" content="{{!! csrf_token() !!}}" /> -->
<input type="hidden" id="filelist" name="filelist"/>
<script type="text/javascript">
var uploadLimit = {{ $uploadLimit }};
var uploadImageURL = "{{ URL('tempphoto/store') }}";
var uploadImageRemoveURL = "{{ URL('tempphoto/delete') }}";
//console.log(uploadImageURL);
//console.log(uploadImageRemoveURL);
/*$.ajaxSetup({
       headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
   });*/
</script>