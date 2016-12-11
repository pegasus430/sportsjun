function requestDelete(url){
    $.ajax({
        type: "GET",
        url: '/data/token',
        success: function (result) {
            var token = result;
            $.ajax({
                url: url,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token
                },
                success:function (){
                    location.reload();
                }
            });
        }
    });
}

function removeConfirm(el) {
    var title = $(el).data('title');
    var url = $(el).data('url');
    var confirmText = $(el).data('confirm');
    if (confirmText) {
        if (confirm(confirmText.replace('{title}', title))) {
            requestDelete(url);
        }
    }
    else
        requestDelete(url);
}