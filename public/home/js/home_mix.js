"use strict";
var SJ = SJ || {};

if (typeof SJ.GLOBAL === 'undefined')
{
        (function (z, $) {
                var o = {
                        d : document,
			w : window,
			l : location,
                        emailFilter: /^[a-zA-Z0-9._-]+@([0-9a-z][0-9a-z.-]+\.)+[a-zA-Z]{2,4}$/i,
                        defaultAjaxSettings : {},
                        init : function () {
                                window.site_url = ( typeof site_url === 'undefined' ) ? 'http://' + window.location.hostname : site_url;
				window.secure_url = ( typeof secure_url === 'undefined' ) ? 'http://' + window.location.host: secure_url;
                                this.defaultAjaxSettings = $.extend( true, {}, $.ajaxSettings );
                                o.bootstrapModalInit();
                                if (window.location.href.indexOf("?open_popup=login") > -1)
                                {
                                        $('#top_bar_login').trigger('click');
                                }
                                
                                if (window.location.href.indexOf("?open_popup=register") > -1)
                                {
                                        $('#top_bar_register').trigger('click');
                                }
                        },
                        bootstrapModalInit: function(){
                                $('.modal').on('show.bs.modal', function () {
                                    $('.modal').not($(this)).each(function () {
                                        $(this).modal('hide');
                                    });
                                });
                                $('.modal').on('shown.bs.modal', function () {
                                        $('body').addClass("modal-open");
                                });
                        },
                        makeRequest: function (_url, _method, _data, successHandler, errorHandler, beforeSendHandler, additionalParams, setUp)
                        {
                                if (Object.keys(this.defaultAjaxSettings).length === 0) {
                                        // if this object is not initialised, do it
                                        this.defaultAjaxSettings = $.extend(true, {}, $.ajaxSettings);
                                }

                                // use this function to make server requests
                                var isJsonPadded = false; // for cross-domain requests

                                if ((_method === undefined) || (typeof _method === 'undefined') || (_method === '')) {
                                        _method = 'GET';
                                } else {
                                        switch (_method.toLowerCase()) {
                                                case 'get':
                                                case 'post':
                                                        break;
                                                default:
                                                        _method = 'GET';
                                                        break;
                                        }
                                }

                                var _dataType = (this.getProtocol(_url) === this.l.protocol.toLowerCase()) ? 'json' : 'jsonp';

                                if ((typeof setUp === "undefined") && !o.isObject(setUp)) {
                                        var setUp = {};
                                } else {
                                        if (typeof setUp.dataType !== "undefined" && setUp.dataType === "json") {
                                                _dataType = 'json';
                                        }
                                }

                                if (_dataType === 'jsonp') {
                                        isJsonPadded = true;

                                        setUp.crossDomain = isJsonPadded;
                                        setUp.dataType = _dataType;
                                }

                                if ((beforeSendHandler === undefined) || (!this.isFunction(beforeSendHandler))) {
                                        beforeSendHandler = function () {

                                        };
                                }

                                // make a deep copy of the default settings to prevent any
                                // user defined settings to affect the default setting
                                var _settings = $.extend(true, {}, this.defaultAjaxSettings);

                                if (Object.keys(setUp).length > 0) {
                                        // set up custom ajax options, if available
                                        for (var i in setUp) {
                                                if (setUp.hasOwnProperty(i)) {
                                                        _settings[ i ] = setUp[ i ];
                                                }
                                        }
                                }

                                try {
                                        $.ajax({
                                                url: _url,
                                                type: _method,
                                                data: _data,
                                                beforeSend: beforeSendHandler,
                                                cache: false,
                                                settings: _settings, // set custom $.ajaxSettings parameters
                                                xhrFields: {
                                                        withCredentials: true
                                                },
                                                headers: {
                                                        'X-Requested-With': 'XMLHttpRequest',
                                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                },
                                                success: function (data) {
                                                        if (!o.isFunction(successHandler)) {
                                                                throw "Invalid success handler function - " + successHandler;
                                                        } else {
                                                                if (additionalParams === undefined) {
                                                                        successHandler(data);
                                                                } else {
                                                                        successHandler(data, additionalParams);
                                                                }
                                                        }
                                                },
                                                error: function (_x, _y, _z) {
                                                        if (!o.isFunction(errorHandler)) {
                                                                throw "Invalid error handler function - " + errorHandler;
                                                        } else {
                                                                if (additionalParams === undefined) {
                                                                        errorHandler(_x, _y, _z);
                                                                } else {
                                                                        errorHandler(additionalParams, _x, _y, _z);
                                                                }
                                                        }
                                                }
                                        });
                                } catch (err) {
                                        //throw err;

                                } finally {
                                        // run clean up
                                }
                        },
                        getProtocol : function( u ) {
				// return the protocol of a given url
				var link = this.d.createElement( 'a' );
				link.href = u;

				return link.protocol.toLowerCase();
			},
                        isFunction : function( fn ) {
				// check if a given object is a function
				var getType = {};
				return fn && getType.toString.call( fn ) === '[object Function]';

				//return ( test === undefined ) ? false : true;
			},
                        isArray : function( fn ) {
				// check if a given object is a array
				var getType = {};
				var test = fn && getType.toString.call( fn ) === '[object Array]';

				return ( test === undefined ) ? false : true;
			},
			isObject : function( fn ) {
				if (Object.prototype.toString.call(fn) === '[object Array]') {
					return false;
				}

				return fn !== null && typeof fn === 'object';
			},
			isNumber : function( num ) {
				return ! isNaN( parseFloat( num ) ) && isFinite( num );
			},
			isFloat : function( num ) {
				return !!( num % 1 );
			},
                        popup : function( mylink, windowname, width, height )
                        {
                                if (!window.focus) return;
                                var href;
                                if (typeof(mylink) === 'string')
                                        href=mylink;
                                else
                                        href=mylink.href;
                                if (!windowname)
                                        windowname='mywindow';
                                if (!width)
                                        width=600;
                                if (!height)
                                        height=350;
                                window.open(href, windowname, 'resizable=yes,width='+width+',height='+height+',scrollbars=yes');
                        },
                        closePopup : function()
                        {
                                window.close();
                        },
                        share : function(url, channel)
                        {
                                this.popup(url, channel, 700, 400);
                        },
                        shareFacebook : function(url,title, picture,details )
                        {
                                FB.ui(
                                        {
                                        method: 'feed',
                                        name: title,
                                        link: url,
                                        picture: picture,
                                        caption: title + "BY SPORTSJUN",
                                        description: details ,
                                        message: ""
                                        });
                        },
                };
                z.GLOBAL = o;
        })(SJ, $);

        $(function () {
                SJ.GLOBAL.init();
        });
}
"use strict";
var SJ = SJ || {};

if (typeof SJ.GLOBAL === 'undefined') {
    (function () {
        var _global_js = 'home/js/sj.global.js';
        window.site_url = (typeof site_url === 'undefined') ? 'http://' + window.location.hostname : site_url;
        window.secure_url = (typeof secure_url === 'undefined') ? 'http://' + window.location.hostname : secure_url;

        var _node = document.createElement('script');
        _node.type = 'text/javascript';
        _node.src = ((window.location.protocol === 'http:') ? site_url : secure_url) + '/' + _global_js;
        document.getElementsByTagName('head')[0].appendChild(_node);
    })();
}

if (typeof SJ.USER === 'undefined') {
    (function (z, $) {
        var g = SJ.GLOBAL;
        var o = {
            init: function () {
                $('input[type=text],input[type=password]').focusout(function () {
                    $(this).removeClass('error');
                    $(this).parent().find('span.error').remove();
                });
            },
            loginValidation: function (form_id) {
                var errors = 0;

                var $this = $('#' + form_id);
                var $email = $this.find('input[name=email]');
                var $password = $this.find('input[name=password]');
                var $submit = $this.find('input[type=submit]');

                $submit.attr('disabled', 'disabled').addClass('disabled');

                // reset form
                $this.find('span.error').remove();
                $email.removeClass('error');
                $password.removeClass('error');
                $('#home-login-modal-form-msg').remove();

                var email = $email.val();
                var password = $password.val();

                if (email === '') {
                    $email.addClass('error');
                    //$email.after('<span class="error">Please enter an email address</span>');
                    errors = 1;
                }
                else if (g.emailFilter.test(email) !== true) {
                    $email.addClass('error');
                    $email.after('<span class="error">Please enter a valid email address</span>');
                    errors = 1;
                }

                if (password === '') {
                    $password.addClass('error');
                    //$password.after('<span class="error">Please enter a password</span>');
                    errors = 1;
                }
                else if (email.length < 6) {
                    $email.addClass('error');
                    $email.after('<span class="error">Please enter a valid password</span>');
                    errors = 1;
                }

                if (errors === 0) {
                    o.loginSubmit(form_id);
                }
                else {
                    $submit.removeAttr('disabled', 'disabled').removeClass('disabled');
                }
                return false;
            },
            loginSubmit: function (form_id) {
                var _path = secure_url + '/auth/login';
                var _data = $("#" + form_id).serialize();
                var _beforeSend = function () {
                };
                var _setUpOptions = {
                    'dataType': "json"
                };
                var _params = {
                    'form_id': form_id
                };
                g.makeRequest(_path, 'POST', _data, o.loginSubmitResponse, o.handleError, _beforeSend, _params, _setUpOptions);
            },
            loginSubmitResponse: function (result, _params) {
                var data;
                try {
                    data = $.parseJSON(JSON.stringify(result));
                } catch (e) {
                    console.log(e);
                    data = result;
                }

                if (g.isObject(data) && typeof data.status !== 'undefined' && typeof _params.form_id !== 'undefined') {
                    var $this = $('#' + _params.form_id);
                    var $submit = $this.find('input[type=submit]');

                    switch (data.status) {
                        case 'email_not_verified':
                            $this.find('.kode-submit').before('<p id="home-login-modal-form-msg">' + data.message + '</p>');
                            break;
                        case 'invalid_credentials':
                            $this.find('.kode-submit').before('<p id="home-login-modal-form-msg">' + data.message + '</p>');
                            break;
                        case 'account_deactivated':
                            $this.find('.kode-submit').before('<p id="home-login-modal-form-msg">' + data.message + '</p>');
                            break;
                        case 'logged_in':
                            g.w.location.href = site_url;
                            break;
                        case 'login_failed':
                            $this.find('.kode-submit').before('<p id="home-login-modal-form-msg">' + data.message + '</p>');
                            break;
                        default:
                            $submit.removeAttr('disabled', 'disabled').removeClass('disabled');
                            return false;
                    }
                    ;
                    $submit.removeAttr('disabled', 'disabled').removeClass('disabled');
                    return false;
                }
                else {
                    g.w.location.href = site_url;
                }
            },
            forgotPasswordValidation: function (form_id) {
                var errors = 0;

                var $this = $('#' + form_id);
                var $email = $this.find('input[name=email]');
                var $submit = $this.find('input[type=submit]');

                $submit.attr('disabled', 'disabled').addClass('disabled');

                // reset form
                $this.find('span.error').remove();
                $email.removeClass('error');
                $('#home-forgot-password-modal-form-msg').remove();

                var email = $email.val();

                if (email === '') {
                    $email.addClass('error');
                    //$email.after('<span class="error">Please enter an email address</span>');
                    errors = 1;
                }
                else if (g.emailFilter.test(email) !== true) {
                    $email.addClass('error');
                    $email.after('<span class="error">Please enter a valid email address</span>');
                    errors = 1;
                }

                if (errors === 0) {
                    o.forgotPasswordSubmit(form_id);
                }
                else {
                    $submit.removeAttr('disabled', 'disabled').removeClass('disabled');
                }
                return false;
            },
            forgotPasswordSubmit: function (form_id) {
                var _path = secure_url + '/password/email';
                var _data = $("#" + form_id).serialize();
                var _beforeSend = function () {
                };
                var _setUpOptions = {
                    'dataType': "json"
                };
                var _params = {
                    'form_id': form_id
                };
                g.makeRequest(_path, 'POST', _data, o.forgotPasswordResponse, o.handleError, _beforeSend, _params, _setUpOptions);
            },
            forgotPasswordResponse: function (result, _params) {
                var data;
                try {
                    data = $.parseJSON(JSON.stringify(result));
                } catch (e) {
                    console.log(e);
                    data = result;
                }

                if (g.isObject(data) && typeof data.status !== 'undefined' && typeof _params.form_id !== 'undefined') {
                    var $this = $('#' + _params.form_id);
                    var $submit = $this.find('input[type=submit]');
                    var msg_id = 'home-forgot-password-modal-form-msg';
                    switch (data.status) {
                        case 'account_does_not_exist':
                            $this.find('.kode-submit').before('<p id="' + msg_id + '">' + data.message + '</p>');
                            break;
                        case 'account_deactivated':
                            $this.find('.kode-submit').before('<p id="' + msg_id + '">' + data.message + '</p>');
                            break;
                        case 'reset_link_sent':
                            $this.find('.kode-submit').before('<p id="' + msg_id + '">' + data.message + '</p>');
                            break;
                        case 'invalid_user':
                            $this.find('.kode-submit').before('<p id="' + msg_id + '">' + data.message + '</p>');
                            break;
                        default:
                            $submit.removeAttr('disabled', 'disabled').removeClass('disabled');
                            return false;
                    }
                    $submit.removeAttr('disabled', 'disabled').removeClass('disabled');
                    return false;
                }
                else {
                    g.w.location.href = site_url;
                }
            },
            refreshCaptcha: function (form_id) {
                var _path = secure_url + '/refereshcapcha';
                var _data = {};
                var _beforeSend = function () {
                };
                var _setUpOptions = {
                    'dataType': "json"
                };
                var _params = {
                    'form_id': form_id
                };
                g.makeRequest(_path, 'GET', _data, o.refreshCaptchaResponse, o.handleError, _beforeSend, _params, _setUpOptions);
            },
            refreshCaptchaResponse: function (result, _params) {
                try {
                    var $this = $('#' + _params.form_id);
                    $this.find('span.capcha').html(result);
                    $this.find('input[name=captcha]').val('');
                } catch (e) {
                    console.log(e);
                    alert('Please try again!');
                }
                return false;
            },
            ajaxSubmitModalRegister: function (form) {
                var jForm = $(form);
                var button = jForm.find('.continueBt');
                $(button).prop('disabled', true);
                var errors = 0;

                var formData = new FormData(form);
                $.ajax({
                    type: "GET",
                    url: '/data/token',
                    success: function (result) {
                        var token = result;
                        $.ajax({
                            type: "POST",
                            url: $(form).attr('action'),
                            data: formData,
                            contentType: false,
                            processData: false,
                            headers: {
                                'X-CSRF-TOKEN': token
                            },

                            success: function (data) {
                                var email = jForm.find('[name=email]');
                                $('#verify-email-id').html(email.val());
                                jForm.find('span.error').remove();
                                jForm.find('.error').removeClass('error');
                                jForm.find('input').val('');
                                jForm.find('select').prop('selectedIndex', 0);
                                jForm.parents('.modal').hide();
                                $('#home-email-verify-modal').modal('show');
                                $(button).prop('disabled', false);
                            },
                            error: function (data) {
                                jForm.find('span.error').remove();
                                var data = jQuery.parseJSON(data.responseText);
                                $.each(data, function (key, value) {
                                    var el = jForm.find('[name=' + key + ']');
                                    el.addClass('error');
                                    if (el.attr('type') != 'checkbox')
                                        el.after('<span class="error">' + value + '</span>');
                                    else
                                        el.parent('label').after('<span class="error">' + value + '</span>');
                                });
                                jForm.find('.captcha-refresh').click();
                                $(button).prop('disabled', false);
                            }
                        });
                    },
                    error: function (data) {
                        $(button).prop('disabled', false);
                    }
                });
            },
            registerValidation: function (form_id) {
                var errors = 0;

                var $this = $('#' + form_id);
                var $firstname = $this.find('input[name=firstname]');
                var $lastname = $this.find('input[name=lastname]');
                var $email = $this.find('input[name=email]');
                var $password = $this.find('input[name=password]');
                var $password_confirmation = $this.find('input[name=password_confirmation]');
                var $captcha = $this.find('input[name=captcha]');
                var $tos = $this.find('input[name=tos]');
                var $submit = $this.find('input[type=submit]');

                $submit.attr('disabled', 'disabled').addClass('disabled');

                // reset form
                $this.find('span.error').remove();
                $firstname.removeClass('error');
                $lastname.removeClass('error');
                $email.removeClass('error');
                $password.removeClass('error');
                $password_confirmation.removeClass('error');
                $captcha.removeClass('error');
                $('#home-register-modal-form-msg').remove();

                var firstname = $firstname.val();
                var lastname = $lastname.val();
                var email = $email.val();
                var password = $password.val();
                var password_confirmation = $password_confirmation.val();
                var captcha = $captcha.val();

                if (firstname === '') {
                    $firstname.addClass('error');
                    errors = 1;
                }

                if (lastname === '') {
                    $lastname.addClass('error');
                    errors = 1;
                }

                if (email === '') {
                    $email.addClass('error');
                    //$email.after('<span class="error">Please enter an email address</span>');
                    errors = 1;
                }
                else if (g.emailFilter.test(email) !== true) {
                    $email.addClass('error');
                    $email.after('<span class="error">Please enter a valid email address</span>');
                    errors = 1;
                }

                if (password === '') {
                    $password.addClass('error');
                    //$password.after('<span class="error">Please enter a password</span>');
                    errors = 1;
                }
                else if (email.length < 6) {
                    $password.addClass('error');
                    $password.after('<span class="error">The password must be at least 6 characters</span>');
                    errors = 1;
                }

                if (password_confirmation === '') {
                    $password_confirmation.addClass('error');
                    errors = 1;
                }
                else if (password_confirmation !== password) {
                    $password_confirmation.addClass('error');
                    $password_confirmation.after('<span class="error">Passwords do not match</span>');
                    errors = 1;
                }

                if (captcha === '') {
                    $captcha.addClass('error');
                    errors = 1;
                }
                else if (captcha.length < 6) {
                    $captcha.addClass('error');
                    $this.find('a.signup_capthca').after('<span class="error">Invalid captcha</span>');
                    errors = 1;
                }

                if (!$tos.is(':checked')) {
                    errors = 1;
                    $this.find('.kode-submit').before('<p id="home-register-modal-form-msg">Please accept the terms and conditions</p>');
                }

                if (errors === 0) {
                    o.registerSubmit(form_id);
                }
                else {
                    $submit.removeAttr('disabled', 'disabled').removeClass('disabled');
                }
                return false;
            },
            registerSubmit: function (form_id) {
                var _path = secure_url + '/auth/register';
                var _data = $("#" + form_id).serialize();
                var _beforeSend = function () {
                };
                var _setUpOptions = {
                    'dataType': "json"
                };
                var _params = {
                    'form_id': form_id
                };
                g.makeRequest(_path, 'POST', _data, o.registerSubmitResponse, o.registerError, _beforeSend, _params, _setUpOptions);
            },
            registerSubmitResponse: function (result, _params) {
                var data;
                try {
                    data = $.parseJSON(JSON.stringify(result));
                } catch (e) {
                    console.log(e);
                    data = result;
                }

                if (g.isObject(data) && typeof data.status !== 'undefined' && data.status === 'success' && typeof _params.form_id !== 'undefined') {
                    var $this = $('#' + _params.form_id);
                    var $email = $this.find('input[name=email]');
                    var $submit = $this.find('input[type=submit]');

                    $('#verify-email-id').html($email.val());
                    $submit.removeAttr('disabled', 'disabled').removeClass('disabled');

                    $('#home-register-modal').modal('hide');
                    $('#home-email-verify-modal').modal('show');

                    return false;
                }
                else {
                    g.w.location.href = site_url;
                }
            },
            registerError: function (_params, result) {
                var data;
                try {
                    data = $.parseJSON(JSON.stringify(result));
                } catch (e) {
                    console.log(e);
                    data = result;
                }

                if (g.isObject(data) && typeof data.status !== 'undefined' && data.status === 422 && typeof data.responseJSON !== 'undefined' && typeof _params.form_id !== 'undefined') {
                    var $this = $('#' + _params.form_id);
                    var $email = $this.find('input[name=email]');
                    var $submit = $this.find('input[type=submit]');
                    var $firstname = $this.find('input[name=firstname]');
                    var $lastname = $this.find('input[name=lastname]');
                    var $password = $this.find('input[name=password]');

                    var _obj = $.parseJSON(JSON.stringify(data.responseJSON));

                    $.each(_obj, function (key, value) {
                        if (key === 'firstname') {
                            $firstname.after('<span class="error">' + value + '</span>');
                        }
                        else if (key === 'lastname') {
                            $lastname.after('<span class="error">' + value + '</span>');
                        }
                        else if (key === 'password') {
                            $password.after('<span class="error">' + value + '</span>');
                        }
                        else if (key === 'email') {
                            $email.after('<span class="error">' + value + '</span>');
                        }
                        else if (key === 'captcha') {
                            $this.find('a.signup_capthca').after('<span class="error">Invalid captcha</span>');
                        }
                    });
                    SJ.USER.refreshCaptcha(_params.form_id);
                    $submit.removeAttr('disabled', 'disabled').removeClass('disabled');
                    return false;
                }
                else {
                    g.w.location.href = site_url;
                }
            }
        };
        z.USER = o;
    })(SJ, $);

    $(function () {
        SJ.USER.init();
    });
}
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


function appendTabElement(a, b, c, d) {
	var userSportCount = $('#userSportCount').val();
	if(userSportCount>7) {
		$.alert({
			title: "Alert!",
			content: 'You can add upto 8 teams only.'
		});
		return false;
	}
	$('<li class=""><a href="#addplayer_' + b + '" data-toggle="tab" aria-expanded="false" onclick="displaySportQuestions(\'unfollow\',' + b + "," + c + ",'" + d + "');\">" + d + "</a><span class='btn-tooltip' data-toggle='tooltip' data-placement='top' title='Remove "+d+"' onclick='removeUserStats(\"false\","+b+","+c+",\"follow\");'><i class='fa fa-remove'></i></span></li>").insertBefore("#unfollowedSportsLi");
	$("#sport_name_" + b).parent().remove();
	var e = $("#addplayer_" + b).wrap("<p/>").parent().html();
	$("#addPlayerDiv").append(e);
	$("#addplayer_" + b).unwrap();
	$("#addplayer_" + b).remove();
	displaySportQuestions(a, b, c, d);
}

function displaySportQuestions(a, b, c, d) {
	if (!b) return false;
	$.ajax({
		url: "/viewpublic/getquestions",
		type: "GET",
		data: {
			flag: a,
			sportsid: b,
			userId: c,
			viewflag: $("#user_question").val()
		},
		dataType: "html",
		beforeSend: function() {

		},
		success: function(d) {
			//$.unblockUI();
			if($.trim(d) == 'countexceed')
			{
				$.alert({
					title: "Alert!",
					content: 'You can add upto 8 teams only.'
				});
				return false;
			}else
			{
				$(".question_div_class").html("");
				$(".custom_form").hide();
				$("#sportsjun_forms_" + b).show();
				$("#question_div_" + b).html(d);
				var userSportCount = $('#userSportCount').val();
				if ("follow" == a) {
					if (1 == $("#user_question").val()) {
						$("#sport_name_" + b).addClass("active");
						$("#sport_name_" + b).attr("onclick", "displaySportQuestions('unfollow'," + b + "," + c + ")");
					} else if (2 == $("#user_question").val()) {
						$("#addplayer_" + b).show();
						$('.nav-tabs a[href="#addplayer_' + b + '"]').tab("show");
						userSportCount++;
						$('#userSportCount').val(userSportCount);
					}
				}
				suggestedWidget("teams", c, b, "player_to_team",'');
				suggestedWidget("tournaments", c, b, "player_to_tournament",'');
			}
		}
	});
}


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

//# sourceMappingURL=home_mix.js.map
