jQuery( document ).ready(function($) {
	"use strict";	
	/* ---------------------------------------------------------------------- */
	/*	PrettyPhoto Modal Box Script
	/* ---------------------------------------------------------------------- */
	
	if($('.kode-gallery-pretty').length){
		$(".kode-gallery-pretty:first a[data-gal^='prettyphoto']").prettyPhoto({
			animation_speed: 'normal',
			slideshow: 10000,
			autoplay_slideshow: true
		});
		$(".kode-gallery-pretty:gt(0) a[data-gal^='prettyphoto']").prettyPhoto({
			animation_speed: 'fast',
			slideshow: 10000,
			hideflash: true
		});
		
	}
		$("a[data-rel^='prettyphoto']").prettyPhoto({
			animation_speed: 'fast',
			slideshow: 10000,
			hideflash: true
		});
	
});	
	