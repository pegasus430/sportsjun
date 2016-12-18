

$(document).ready(function () {


    $('.mainwarppslide').owlCarousel({
        nav: false,
        autoplay: true,
        pagination: true,
        loop: true,
        dots: true,

        responsive: {
            0: {items: 1},
            600: {items: 1},
            900: {items: 1},
            1000: {items: 1}
        }
    });


    $('.testimornial').owlCarousel({
        nav: true,
        autoplay: true,
        pagination: true,
        loop: false,
        dots: true,

        responsive: {
            0: {items: 1},
            600: {items: 1},
            900: {items: 2},
            1000: {items: 2}
        }
    });

    $('.clientsSlide').owlCarousel({
        nav: true,
        autoplay: true,
        pagination: true,
        loop: false,
        dots: true,

        responsive: {
            0: {items: 2},
            600: {items: 3},
            900: {items: 4},
            1000: {items: 4}
        }
    });


});

$(function () {
    $('.jq-autocomplete').each(function () {
        $(this).autocomplete({
            source: $(this).data('source'),
            minLength: 3,
        });
    });
});

function ajaxLoadOption(el) {
    var targetUrl = $(el).data('url');
    var selected = $(el).val();
    var targetSelect = $(el).data('target')
    var name = $(el).data('name');
    $.get(targetUrl, {
            'selected': selected
        }, function (data) {
            var options = '<option selected disabled>Select '+name+'</option>';
            for (var key in data) {
                options += '<option value="' + key + '">'+data[key]+'</option>';
            }
            $(targetSelect).html(options);
        }
    );


}
