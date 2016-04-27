// =============================================================
/* Animation */
// =============================================================
$(window).scroll(function() {
    $(".animated-area").each(function() {
        if ($(window).height() + $(window).scrollTop() - $(this).offset().top > 0) {
            $(this).trigger("animate-it");
        }
    });
});
$(".animated-area").on("animate-it", function() {
    var cf = $(this);
    cf.find(".animated").each(function() {
        $(this).css("-webkit-animation-duration", "0.9s");
        $(this).css("-moz-animation-duration", "0.9s");
        $(this).css("-ms-animation-duration", "0.9s");
        $(this).css("animation-duration", "0.9s");
        $(this).css("-webkit-animation-delay", $(this).attr("data-animation-delay"));
        $(this).css("-moz-animation-delay", $(this).attr("data-animation-delay"));
        $(this).css("-ms-animation-delay", $(this).attr("data-animation-delay"));
        $(this).css("animation-delay", $(this).attr("data-animation-delay"));
        $(this).addClass($(this).attr("data-animation"));
    });
});
// =============================================================
/* Header Title */
// =============================================================
var windowWidth = $(window).width();
var windowHeight = $(window).height();
$('.header').css({
    'width': windowWidth,
    'height': windowHeight
});
$('.header h1').css({
    'paddingTop': windowHeight / 2.2 + "px"
});
// =============================================================
/* Panes */
// =============================================================
$(document).ready(function(){
    var container_1 = $('#paneContainer_1');
    var container_2 = $('#paneContainer_2');
    container_1.css('display', 'block');
    container_2.css('display', 'none');
    $('#pane_1').click(function(){
        $('#pane_2').removeClass('current');
        $('#pane_1').addClass('current');
        container_1.css('display', 'block');
        container_2.css('display', 'none');
    });
    $('#pane_2').click(function(){
        $('#pane_1').removeClass('current');
        $('#pane_2').addClass('current');
        container_2.css('display', 'block');
        container_1.css('display', 'none');
    });
    var container_3 = $('#paneContainer_3');
    var container_4 = $('#paneContainer_4');
    container_3.css('display', 'block');
    container_4.css('display', 'none');
    $('#pane_3').click(function(){
        $('#pane_4').removeClass('current');
        $('#pane_3').addClass('current');
        container_3.css('display', 'block');
        container_4.css('display', 'none');
    });
    $('#pane_4').click(function(){
        $('#pane_3').removeClass('current');
        $('#pane_4').addClass('current');
        container_4.css('display', 'block');
        container_3.css('display', 'none');
    });
});