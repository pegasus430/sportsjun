// Include HTMLs
$(function () {
    var includes = $('[data-include]');
    jQuery.each(includes, function () {
        var file = 'inc/' + $(this).data('include') + '.html';
      //  $(this).load(file);
    });
});
// Page Active
jQuery(function () {
    var page = location.pathname.split('/').pop();
    $('#nav li a[href="' + page + '"]').addClass('active')
});
// Overlay
function openNav(i) {
    document.getElementById("myNav"+i).style.width = "100%";
    $.ajax({
    	url:'/editsportprofile/'+i,
    	success:function(response){
    	//	$('#myNav'+i).children('.overlay-content').html(response);
    	}
    })
}

function closeNav(i) {
    document.getElementById("myNav"+i).style.width = "0%";
}
$("#price_slider").slider({});



setTimeout(function(){
     $('.vote-poll').on('ifChecked', function(event,sd){
  
        var poll_id = $(this).attr('poll-id');
        var  option_id = $(this).attr('option-id');
        var organization_id = $(this).attr('organization-id');
        var url = '/organization/'+organization_id+'/polls/'+poll_id+'/vote';
          $.ajax({
            url:url,
            type:'post',
            data:{option_id:option_id}
          })
     })
  
},4000)
   