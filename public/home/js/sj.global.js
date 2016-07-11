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
				window.secure_url = ( typeof secure_url === 'undefined' ) ? 'http://' + window.location.hostname : secure_url;
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
                                        caption: title,
                                        description: details,
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