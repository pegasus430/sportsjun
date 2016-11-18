$(document).ready(function () {
    $(".box_noti").click(function () {
        var id = $(this).attr("id");
        $(".notific_wrapp").hide();
        $("#" + id + "_drop").show();
    });

    $(document).mouseup(function (e) {
        var container = $(".notific_wrapp");
        if (!container.is(e.target) // if the target of the click isn't the container...
            && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            container.hide();
        }
    });


});
    $(document).ready(function () {

        $('#page_effect').fadeIn(2000);
        $(".round_test").addClass("round");

    });

//homepage slide show
$(function($) {
    var url = 'images';


    $.supersized({

        // Functionality
        slide_interval: 5000, // Length between transitions
        transition: 1, // 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
        transition_speed: 700, // Speed of transition

        // Components
        slide_links: 'blank', // Individual links for each slide (Options: false, 'num', 'name', 'blank')
        slides: [ // Slideshow Images
            {
                image: url + '/slider.jpg',
                title: 'Some testimonials. Thanks for letting us share great memories.',
                description: 'Flat 30% cash back',
                price: '$499'
            }, {
                image: url + '/slider1.jpg',
                title: 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. ',
                description: 'With luxuary accommodation',
                price: '$609'
            },

            {
                image: url + '/slider.jpg',
                title: 'Lorem Ipsum Prabhul. ',
                description: 'With luxuary accommodation',
                price: '$609'
            }
        ]
    });
});

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


$(document).ready(function () {


        $(document).delegate('*[data-toggle="lightbox"]', 'click', function (event) {
            event.preventDefault();
            $(this).ekkoLightbox();
        });

        var topPos = $(".total_menu_wrap").offset().top; // get the initial position for div


        $(window).scroll(function () {
            var scroll = $(window).scrollTop();
            if ($(window).scrollTop() >= topPos) {
                $(".total_menu_wrap").addClass("menu_fix");
            }
            else {
                $(".total_menu_wrap").removeClass("menu_fix");
            }
        });
        //--------teach-down- it will work
    }
)


$(function () {
    $('.jq-autocomplete').each(function() {
        $(this).autocomplete({
            source: $(this).data('source'),
            minLength: 3,
        });
    });


});