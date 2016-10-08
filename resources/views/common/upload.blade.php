{!! HTML::style('/css/filer/jquery.filer.css') !!}
{!! HTML::style('/css/filer/jquery.filer-dragdropbox-theme.css') !!}
{!! HTML::script('/js/filer/jquery.filer.min.js') !!}
<script type="text/javascript">
var uploadImageURL = "{{ URL('tempphoto/store') }}";
var uploadImageRemoveURL = "{{ URL('tempphoto/delete') }}";
</script>