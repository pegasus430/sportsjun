"use strict";
var SJ = SJ || {};

if (typeof SJ.GLOBAL === 'undefined') {
        (function () {
                var _global_js = 'home/js/sj.global.js';
                window.site_url = (typeof site_url === 'undefined') ? 'http://' + window.location.hostname : site_url;
                window.secure_url = (typeof secure_url === 'undefined') ? 'https://' + window.location.hostname : secure_url;

                var _node = document.createElement('script');
                _node.type = 'text/javascript';
                _node.src = ((window.location.protocol === 'http:') ? site_url : secure_url) + '/' + _global_js;
                document.getElementsByTagName('head')[ 0 ].appendChild(_node);
        })();
}

if (typeof SJ.USER === 'undefined')
{
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

                                if (email === '')
                                {
                                        $email.addClass('error');
                                        //$email.after('<span class="error">Please enter an email address</span>');
                                        errors = 1;
                                }
                                else if (g.emailFilter.test(email) !== true)
                                {
                                        $email.addClass('error');
                                        $email.after('<span class="error">Please enter a valid email address</span>');
                                        errors = 1;
                                }

                                if (password === '')
                                {
                                        $password.addClass('error');
                                        //$password.after('<span class="error">Please enter a password</span>');
                                        errors = 1;
                                }
                                else if (email.length < 6)
                                {
                                        $email.addClass('error');
                                        $email.after('<span class="error">Please enter a valid password</span>');
                                        errors = 1;
                                }

                                if (errors === 0)
                                {
                                        o.loginSubmit(form_id);
                                }
                                else
                                {
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
                                        
                                        switch (data.status)
                                        {
                                                case 'email_not_verified':
                                                        $this.find('.kode-submit').before('<p id="home-login-modal-form-msg">'+ data.message +'</p>');
                                                        break;
                                                case 'invalid_credentials':
                                                        $this.find('.kode-submit').before('<p id="home-login-modal-form-msg">'+ data.message +'</p>');
                                                        break;
                                                case 'account_deactivated':
                                                        $this.find('.kode-submit').before('<p id="home-login-modal-form-msg">'+ data.message +'</p>');
                                                        break;
                                                case 'logged_in':
                                                        g.w.location.href = site_url;
                                                        break;
                                                case 'login_failed':
                                                        $this.find('.kode-submit').before('<p id="home-login-modal-form-msg">'+ data.message +'</p>');
                                                        break;
                                                default:
                                                        $submit.removeAttr('disabled', 'disabled').removeClass('disabled');
                                                        return false;
                                        };
                                        $submit.removeAttr('disabled', 'disabled').removeClass('disabled');
					return false;
				}
                                else
                                {
                                        g.w.location.href = site_url;
                                }
                        },
                        forgotPasswordValidation:function(form_id){
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

                                if (email === '')
                                {
                                        $email.addClass('error');
                                        //$email.after('<span class="error">Please enter an email address</span>');
                                        errors = 1;
                                }
                                else if (g.emailFilter.test(email) !== true)
                                {
                                        $email.addClass('error');
                                        $email.after('<span class="error">Please enter a valid email address</span>');
                                        errors = 1;
                                }

                                if (errors === 0)
                                {
                                        o.forgotPasswordSubmit(form_id);
                                }
                                else
                                {
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
                                        switch (data.status)
                                        {
                                                case 'account_does_not_exist':
                                                        $this.find('.kode-submit').before('<p id="' + msg_id + '">'+ data.message +'</p>');
                                                        break;
                                                case 'account_deactivated':
                                                        $this.find('.kode-submit').before('<p id="' + msg_id + '">'+ data.message +'</p>');
                                                        break;
                                                case 'reset_link_sent':
                                                        $this.find('.kode-submit').before('<p id="' + msg_id + '">'+ data.message +'</p>');
                                                        break;
                                                case 'invalid_user':
                                                        $this.find('.kode-submit').before('<p id="' + msg_id + '">'+ data.message +'</p>');
                                                        break;
                                                default:
                                                        $submit.removeAttr('disabled', 'disabled').removeClass('disabled');
                                                        return false;
                                        }
                                        $submit.removeAttr('disabled', 'disabled').removeClass('disabled');
					return false;
				}
                                else
                                {
                                        g.w.location.href = site_url;
                                }
                        },
                        registerValidation: function (form_id) {
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

                                if (email === '')
                                {
                                        $email.addClass('error');
                                        //$email.after('<span class="error">Please enter an email address</span>');
                                        errors = 1;
                                }
                                else if (g.emailFilter.test(email) !== true)
                                {
                                        $email.addClass('error');
                                        $email.after('<span class="error">Please enter a valid email address</span>');
                                        errors = 1;
                                }

                                if (password === '')
                                {
                                        $password.addClass('error');
                                        //$password.after('<span class="error">Please enter a password</span>');
                                        errors = 1;
                                }
                                else if (email.length < 6)
                                {
                                        $email.addClass('error');
                                        $email.after('<span class="error">Please enter a valid password</span>');
                                        errors = 1;
                                }

                                if (errors === 0)
                                {
                                        o.loginSubmit(form_id);
                                }
                                else
                                {
                                        $submit.removeAttr('disabled', 'disabled').removeClass('disabled');
                                }
                                return false;
                        }
                };
                z.USER = o;
        })(SJ, $);

        $(function () {
                SJ.USER.init();
        });
}