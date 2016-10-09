<div class="row">
    <div class="col-sm-12">
		<input class="fileupload" type="file" name="files[]" id="{{ $field }}" @if ($uploadLimit > 1) multiple="multiple" @endif>		
    </div>
</div>
 
<?php 

if(isset($fieldname) && $fieldname!='') {
	$fieldname = $fieldname;
}
else{
	$fieldname='Choose Profile Pic';
}
?>
<script type="text/javascript">
//var field = '{{ $field }}';
//var uploadLimit = '{{ $uploadLimit }}';

</script>

<input type="hidden" id="filelist_{{ $field }}" name="filelist_{{ $field }}" autocomplete="off"/>
<script type="text/javascript">
var CSRF_TOKEN = $('input[name="_token"]').val();
$(document).ready(function() {   
    //Example 2
    if({{ $uploadLimit }} == 1){
        //$('#filer_input2').filer({
        $('#{{ $field }}').filer({
            limit: 1,
            fileMaxSize: 3,
            extensions: ['jpg', 'jpeg', 'png', 'gif'],
            changeInput: true,
            showThumbs: true,
            templates: {
            box: '<ul class="jFiler-items-list jFiler-items-grid"></ul>',
            item: '<li class="jFiler-item">\
                        <div class="jFiler-item-container">\
                            <div class="jFiler-item-inner">\
                                <div class="jFiler-item-thumb">\
                                    <div class="jFiler-item-status"></div>\
                                    <div class="jFiler-item-info">\
                                        <span class="jFiler-item-title"><b title="{-{fi-name}-}">{-{fi-name | limitTo: 25}-}</b></span>\
                                        <span class="jFiler-item-others">{-{fi-size2}-}</span>\
                                    </div>\
                                    {-{fi-image}-}\
                                </div>\
                                <div class="jFiler-item-assets jFiler-row">\
                                    <ul class="list-inline pull-left">\
                                        <li>{-{fi-progressBar}-}</li>\
                                    </ul>\
                                    <ul class="list-inline pull-right">\
                                        <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
                                    </ul>\
                                </div>\
                            </div>\
                        </div>\
                    </li>',
            itemAppend: '<li class="jFiler-item">\
                            <div class="jFiler-item-container">\
                                <div class="jFiler-item-inner">\
                                    <div class="jFiler-item-thumb">\
                                        <div class="jFiler-item-status"></div>\
                                        <div class="jFiler-item-info">\
                                            <span class="jFiler-item-title"><b title="{-{fi-name}-}">{-{fi-name | limitTo: 25}-}</b></span>\
                                            <span class="jFiler-item-others">{-{fi-size2}-}</span>\
                                        </div>\
                                        {-{fi-image}-}\
                                    </div>\
                                    <div class="jFiler-item-assets jFiler-row">\
                                        <ul class="list-inline pull-left">\
                                            <li><span class="jFiler-item-others">{-{fi-icon}-}</span></li>\
                                        </ul>\
                                        <ul class="list-inline pull-right">\
                                            <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
                                        </ul>\
                                    </div>\
                                </div>\
                            </div>\
                        </li>',
            progressBar: '<div class="bar"></div>',
            itemAppendToEnd: false,
            removeConfirmation: true,
            _selectors: {
                list: '.jFiler-items-list',
                item: '.jFiler-item',
                progressBar: '.bar',
                remove: '.jFiler-item-trash-action'
            }
        },
        beforeSelect: function(itemEl, file, id, listEl, boxEl, newInputEl, inputEl){
            $('.deletePhoto').remove();
            return true;
        },
			onRemove: function(itemEl, file, id, listEl, boxEl, newInputEl, inputEl){
            var filerKit = inputEl.prop("jFiler"),
            file_name = filerKit.files_list[id].name;            
            $('#filelist_{{ $field }}').val('');     
            //$.post(uploadImageRemoveURL+ '?_token=' + CSRF_TOKEN, {file: file_name});            
            /*var file = file.name;
            $.post(uploadImageRemoveURL, {file: file});*/
        	},          
            uploadFile: {
                url: uploadImageURL+ '?_token=' + CSRF_TOKEN,
                data: null,//{_token: CSRF_TOKEN},//null,//'_token='+$('[name=_token]').val(),
                type: 'POST',
                enctype: 'multipart/form-data',
                beforeSend: function(){},
                success: function(data, el){   
                var dd = $.parseJSON(data);                    
                $('#filelist_{{ $field }}').val($('#filelist_{{ $field }}').val()+","+dd.name);     
                    var parent = el.find(".jFiler-jProgressBar").parent();
                    el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                        $("<div class=\"jFiler-item-others text-success\"><i class=\"icon-jfi-check-circle\"></i> Success</div>").hide().appendTo(parent).fadeIn("slow");    
                    });
                },
                error: function(el){                
                    var parent = el.find(".jFiler-jProgressBar").parent();
                    el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                        $("<div class=\"jFiler-item-others text-error\"><i class=\"icon-jfi-minus-circle\"></i> Error</div>").hide().appendTo(parent).fadeIn("slow");    
                    });
                },
                statusCode: null,
                onProgress: null,
                onComplete: null
             },
			  captions: {
            button: "<i class='fa fa-upload' style='padding-right:10px; color:#b5c1c7;'></i>Choose Files",
            feedback: '{{ $fieldname }}',
            feedback2: "files were chosen",
            drop: "Drop file here to Upload",
            removeConfirmation: "Do you want to delete this pic?",
            errors: {
                filesLimit: "Only {-{fi-limit}-} files are allowed to be uploaded.",
                filesType: "Only Images are allowed to be uploaded.",
                filesSize: "{-{fi-name}-} is too large! Please upload file up to {-{fi-maxSize}-} MB.",
                filesSizeAll: "Files you've choosed are too large! Please upload files up to {-{fi-maxSize}-} MB."
            }
        },
        });
    }else{
    //$("#filer_input2").filer({
    $('#{{ $field }}').filer({
        limit: null,//uploadLimit,
        fileMaxSize: 3,
        extensions: ['jpg', 'jpeg', 'png', 'gif'],
        changeInput: '<div class="jFiler-input-dragDrop"><div class="jFiler-input-inner"><div class="jFiler-input-icon"><i class="icon-jfi-cloud-up-o"></i></div><div class="jFiler-input-text"><h3>Drag&Drop files here</h3> <span style="display:inline-block; margin: 15px 0">or</span></div><a class="jFiler-input-choose-btn blue">Browse Files</a></div></div>',
        showThumbs: true,
        theme: "dragdropbox",
        templates: {
            box: '<ul class="jFiler-items-list jFiler-items-grid"></ul>',
            item: '<li class="jFiler-item">\
                        <div class="jFiler-item-container">\
                            <div class="jFiler-item-inner">\
                                <div class="jFiler-item-thumb">\
                                    <div class="jFiler-item-status"></div>\
                                    <div class="jFiler-item-info">\
                                        <span class="jFiler-item-title"><b title="{-{fi-name}-}">{-{fi-name | limitTo: 25}-}</b></span>\
                                        <span class="jFiler-item-others">{-{fi-size2}-}</span>\
                                    </div>\
                                    {-{fi-image}-}\
                                </div>\
                                <div class="jFiler-item-assets jFiler-row">\
                                    <ul class="list-inline pull-left">\
                                        <li>{-{fi-progressBar}-}</li>\
                                    </ul>\
                                    <ul class="list-inline pull-right">\
                                        <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
                                    </ul>\
                                </div>\
                            </div>\
                        </div>\
                    </li>',
            itemAppend: '<li class="jFiler-item">\
                            <div class="jFiler-item-container">\
                                <div class="jFiler-item-inner">\
                                    <div class="jFiler-item-thumb">\
                                        <div class="jFiler-item-status"></div>\
                                        <div class="jFiler-item-info">\
                                            <span class="jFiler-item-title"><b title="{-{fi-name}-}">{-{fi-name | limitTo: 25}-}</b></span>\
                                            <span class="jFiler-item-others">{-{fi-size2}-}</span>\
                                        </div>\
                                        {-{fi-image}-}\
                                    </div>\
                                    <div class="jFiler-item-assets jFiler-row">\
                                        <ul class="list-inline pull-left">\
                                            <li><span class="jFiler-item-others">{-{fi-icon}-}</span></li>\
                                        </ul>\
                                        <ul class="list-inline pull-right">\
                                            <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
                                        </ul>\
                                    </div>\
                                </div>\
                            </div>\
                        </li>',
            progressBar: '<div class="bar"></div>',
            itemAppendToEnd: false,
            removeConfirmation: true,
            _selectors: {
                list: '.jFiler-items-list',
                item: '.jFiler-item',
                progressBar: '.bar',
                remove: '.jFiler-item-trash-action'
            }
        },
        dragDrop: {
            dragEnter: null,
            dragLeave: null,
            drop: null,
        },
        uploadFile: {
            url: uploadImageURL+ '?_token=' + CSRF_TOKEN,
            data: null,//{_token: CSRF_TOKEN},//null,//'_token='+$('[name=_token]').val(),
            type: 'POST',
            enctype: 'multipart/form-data',
            beforeSend: function(){},
            success: function(data, itemEl, listEl, boxEl, newInputEl, inputEl, id){   
                //console.log(el.find('.jFiler-item-title b').attr('title',));
                var dd = $.parseJSON(data);
                $('#filelist_{{ $field }}').val($('#filelist_{{ $field }}').val()+","+dd.name);
                filerKit = inputEl.prop("jFiler");
                filerKit.files_list[id].name = dd.name;  
                var parent = itemEl.find(".jFiler-jProgressBar").parent();
                itemEl.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                    $("<div class=\"jFiler-item-others text-success\"><i class=\"icon-jfi-check-circle\"></i> Success</div>").hide().appendTo(parent).fadeIn("slow");    
                });
            },
            error: function(el){                
                var parent = el.find(".jFiler-jProgressBar").parent();
                el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                    $("<div class=\"jFiler-item-others text-error\"><i class=\"icon-jfi-minus-circle\"></i> Error</div>").hide().appendTo(parent).fadeIn("slow");    
                });
            },
            statusCode: null,
            onProgress: null,
            onComplete: null,
        },
        files: null,
        addMore: false,
        clipBoardPaste: true,
        excludeName: null,
        beforeRender: null,
        afterRender: null,
        beforeShow: null,
        beforeSelect: null,
        onSelect: null,
        afterShow: null,
        onRemove: function(itemEl, file, id, listEl, boxEl, newInputEl, inputEl){
            var filerKit = inputEl.prop("jFiler"),
            file_name = filerKit.files_list[id].name;            
            $('#filelist_{{ $field }}').val($('#filelist_{{ $field }}').val().replace(','+file_name,''));     
            //$.post(uploadImageRemoveURL, {file: file_name});            
            /*var file = file.name;
            $.post(uploadImageRemoveURL, {file: file});*/
        },
        onEmpty: null,
        options: null,
       
    });
}
    
});
</script>
