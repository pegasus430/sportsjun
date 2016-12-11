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
                var errors = 0;
                var jForm = $(form);
                var formData = new FormData(form);
                $.get('/data/token', function (result) {
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
                        }
                    });
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