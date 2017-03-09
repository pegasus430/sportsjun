// Include HTMLs
$(function () {
    var includes = $('[data-include]');
    jQuery.each(includes, function () {
        var file = 'inc/' + $(this).data('include') + '.html';
        $(this).load(file);
    });
});
// Page Active
jQuery(function () {
    var page = location.pathname.split('/').pop();
    $('#nav li a[href="' + page + '"]').addClass('active')
});
// Overlay
function openNav() {
    document.getElementById("myNav").style.width = "100%";
}

function closeNav() {
    document.getElementById("myNav").style.width = "0%";
}
$("#price_slider").slider({});