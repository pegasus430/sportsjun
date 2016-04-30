jQuery(document).ready(function(){
	"use strict";
	
	/* ---------------------------------------------------------------------- */
	/*	Click to Top 
	/* ---------------------------------------------------------------------- */
	if($('.flexslider').length){
		jQuery('.flexslider').flexslider({
			animation: "slide",
			start: function(slider){
			  jQuery('body').removeClass('loading');
			}
		});
	}
	
	
	
	
	/* ---------------------------------------------------------------------- */
	/*	Progress Bar
	/* ---------------------------------------------------------------------- */
	if($('.kode_team_progress .progress .progress-bar').length){
		$('.kode_team_progress  .progress .progress-bar').progressbar({display_text: 'fill'});
	}	
	
	/* ---------------------------------------------------------------------- */
	/*	Counter Functions
	/* ---------------------------------------------------------------------- */
	if(jQuery('.word-count').length){
		jQuery(".word-count").counterUp({
			delay: 10,
			time: 1000
		});
	}
	
	
	/* ---------------------------------------------------------------------- */
	/*	BxSlider
	/* ---------------------------------------------------------------------- */
	if($('.bxslider').length){
		$('.bxslider').bxSlider({
		   mode: 'fade',
		   pagerCustom: '#bx-pager'
		});
	}
	
	if($('.top_slider_bxslider').length){
		$('.top_slider_bxslider').bxSlider({
		   auto:true,
		    pagerCustom: '#bx-pager'
		});
	}
	
	
	/* ---------------------------------------------------------------------- */
	/*	Sticky header
	/* ---------------------------------------------------------------------- */
	if($('.kode-header-absolute').length){
		// grab the initial top offset of the navigation 
		//var stickyNavTop = $('#mainbanner').offset().top;
		var stickyNavTop = 40;
		// our function that decides weather the navigation bar should have "fixed" css position or not.
		var stickyNav = function(){
			var scrollTop = $(window).scrollTop(); // our current vertical position from the top
			// if we've scrolled more than the navigation, change its position to fixed to stick to top,
			// otherwise change it back to relative
			if (scrollTop > stickyNavTop) { 
				$('.kode-header-absolute').addClass('kf_sticky');
			} else {
				$('.kode-header-absolute').removeClass('kf_sticky'); 
			}
		};
		stickyNav();
		// and run it again every time you scroll
		$(window).scroll(function() {
			stickyNav();
		});
	}
	

	/* ---------------------------------------------------------------------- */
	/*	Click to Top 
	/* ---------------------------------------------------------------------- */
	if($('.owl-carousel').length){
		jQuery('.owl-carousel').owlCarousel({
			loop:true,
			margin:25,
			nav:true,
			navText: [
				'<i class="fa fa-angle-left"></i>',
				'<i class="fa fa-angle-right"></i>'
			],
			responsive:{
				0:{
					items:1
				},
				600:{
					items:3
				},
				1000:{
					items:4
				}
			}
		});
	}
	/* ---------------------------------------------------------------------- */
	/*	Click to Top 
	/* ---------------------------------------------------------------------- */
	if($('.owl-carousel-team').length){
		jQuery('.owl-carousel-team').owlCarousel({
			loop:true,
			margin:25,
			nav:true,
			navText: [
				'<i class="fa fa-angle-left"></i>',
				'<i class="fa fa-angle-right"></i>'
			],
			responsive:{
				0:{
					items:1
				},
				600:{
					items:2
				},
				1000:{
					items:3
				}
			}
		});
	}

    /* ---------------------------------------------------------------------- */
	/*	Click to Top 
	/* ---------------------------------------------------------------------- */
	if($('audio,video').length){
		jQuery('audio,video').mediaelementplayer({});
	}

    /* ---------------------------------------------------------------------- */
	/*	Click to Top 
	/* ---------------------------------------------------------------------- */
	if($('#kodeCountdown').length){
		var austDay = new Date();
		austDay = new Date(2016, 6-1, 5,12,10);
		jQuery('#kodeCountdown').countdown({until: austDay});
		jQuery('#year').text(austDay.getFullYear());
	}

    /* ---------------------------------------------------------------------- */
	/*	Click to Top 
	/* ---------------------------------------------------------------------- */
    if($('#kode-topbtn').length){
		$('#kode-topbtn').on("click",function() {		
			jQuery('html, body').animate({scrollTop : 0},800);
			return false;
		});
	}
	/* ---------------------------------------------------------------------- */
    /*  Accordion Script
    /* ---------------------------------------------------------------------- */
    if($('.accordion').length){
        //custom animation for open/close
        $.fn.slideFadeToggle = function(speed, easing, callback) {
          return this.animate({opacity: 'toggle', height: 'toggle'}, speed, easing, callback);
        };

        $('.accordion').accordion({
          defaultOpen: 'section1',
          cookieName: 'nav',
          speed: 'slow',
          animateOpen: function (elem, opts) { //replace the standard slideUp with custom function
            elem.next().stop(true, true).slideFadeToggle(opts.speed);
          },
          animateClose: function (elem, opts) { //replace the standard slideDown with custom function
            elem.next().stop(true, true).slideFadeToggle(opts.speed);
          }
        });
    }

    /* ---------------------------------------------------------------------- */
    /*  Progress Bar
    /* ---------------------------------------------------------------------- */
    if($('.progress .progress-bar').length){
        jQuery('.progress .progress-bar').progressbar({display_text: 'fill'});
    }
    
    /* ---------------------------------------------------------------------- */
    /*  Circle Progress
    /* ---------------------------------------------------------------------- */
    if($('.circle-progress').length){
        $('.circle-progress').percentcircle({
          animate : true,
          diameter : 100,
          guage: 3,
          coverBg: '#fff',
          bgColor: '#efefef',
          fillColor: '#5c93c8',
          percentSize: '50px',
          percentWeight: 'normal'
        });
    }
	
	/* ---------------------------------------------------------------------- */
	/*	Contact Form
	/* ---------------------------------------------------------------------- */
	
	if($('#contactform').length) {

		var $form = $('#contactform'),
		$loader = '<img src="images/ajax_loading.gif" alt="Loading..." />';
		$form.append('<div class="hidden-me" id="contact_form_responce">');

		var $response = $('#contact_form_responce');
		$response.append('<p></p>');

		$form.submit(function(e){

			$response.find('p').html($loader);

			var data = {
				action: "contact_form_request",
				values: $("#contactform").serialize()
			};

			//send data to server
			$.post("inc/contact-send.php", data, function(response) {

				response = $.parseJSON(response);
				
				$(".incorrect-data").removeClass("incorrect-data");
				$response.find('img').remove();

				if(response.is_errors){

					$response.find('p').removeClass().addClass("error type-2");
					$.each(response.info,function(input_name, input_label) {

						$("[name="+input_name+"]").addClass("incorrect-data");
						$response.find('p').append('Please enter correct "'+input_label+'"!'+ '</br>');
					});

				} else {

					$response.find('p').removeClass().addClass('success type-2');

					if(response.info == 'success'){

						$response.find('p').append('Your email has been sent!');
						$form.find('input:not(input[type="submit"], button), textarea, select').val('').attr( 'checked', false );
						$response.delay(1500).hide(400);
					}

					if(response.info == 'server_fail'){
						$response.find('p').append('Server failed. Send later!');
					}
				}

				// Scroll to bottom of the form to show respond message
				var bottomPosition = $form.offset().top + $form.outerHeight() - $(window).height();

				if($(document).scrollTop() < bottomPosition) {
					$('html, body').animate({
						scrollTop : bottomPosition
					});
				}

				if(!$('#contact_form_responce').css('display') == 'block') {
					$response.show(450);
				}

			});

			e.preventDefault();

		});				

	}
	/* ---------------------------------------------------------------------- */
	/*	BxSlider Remove
	/* ---------------------------------------------------------------------- */
	// $(".kode-testimonials-6 .bx-controls-direction .bx-prev").empty();
	// $(".kode-testimonials-6 .bx-controls-direction .bx-next").empty();
	// $(".kode-testimonials-6 .bx-controls-direction .bx-next").append('<i class="fa fa-angle-right"></i>');
	// $(".kode-testimonials-6 .bx-controls-direction .bx-prev").append('<i class="fa fa-angle-left"></i>');
	
	/* ---------------------------------------------------------------------- */
	/*	Google Map
	/* ---------------------------------------------------------------------- */
	if($('#map-canvas').length){
		google.maps.event.addDomListener(window, 'load', initialize);
	}
});	

/* ---------------------------------------------------------------------- */
/*	Google Map Function for Custom Style
/* ---------------------------------------------------------------------- */
function initialize() {
	var MY_MAPTYPE_ID = 'custom_style';
	var map;
	var brooklyn = new google.maps.LatLng(40.6743890, -73.9455);
	var featureOpts = [
		{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]}

	];
	var mapOptions = {
		zoom: 12,
		center: brooklyn,
		mapTypeControlOptions: {
			mapTypeIds: [google.maps.MapTypeId.ROADMAP, MY_MAPTYPE_ID]
		},
		mapTypeId: MY_MAPTYPE_ID
	};

	map = new google.maps.Map(
		document.getElementById('map-canvas'),
		mapOptions
	);

	var styledMapOptions = {
		name: 'Custom Style'
	};

	var customMapType = new google.maps.StyledMapType(featureOpts, styledMapOptions);

	map.mapTypes.set(MY_MAPTYPE_ID, customMapType);
}