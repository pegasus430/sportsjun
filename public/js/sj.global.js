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
                        },
                        listen : function( obj ) {
				if( o.isObject( obj ) ) {
					for ( var i in obj ) {
						if ( obj.hasOwnProperty( i ) ) {
							var _obj = obj[ i ];
							for ( var j in _obj ) {
								if ( _obj.hasOwnProperty( j ) ) {
									if ( $.isArray( _obj[ j ] ) ) {
										o.addListener(
											$( _obj[ j ][1] ), i, _obj[ j ][ 0 ], false, j
										);
									} else {
										o.addListener(
											$( j ), i, _obj[ j ]
										);
									}
								}
							}
						}
					}
				}
			},
			addListener : function ( el, evt, _callback, eventBubbleOrCapture, liveEvent ) {
				if( ( typeof _callback == 'string' ) && ( _callback.indexOf( '.' ) !== -1 ) ) {
						_callback = this.executeFunctionByName( _callback, this.w );
				}

				if( ! this.isFunction( _callback ) ) {
					throw "Invalid callback function - " + _callback;
				} else {
					if( eventBubbleOrCapture !== true ) {
						eventBubbleOrCapture = false;
					}

					if( el instanceof $ ) {
						// if the object is an instance of jquery
						if( ( liveEvent !== undefined ) && ( typeof liveEvent === 'string' ) ) {
							el.on( evt, liveEvent, _callback );
						} else {
							el.on( evt, _callback );
						}
					} else {
						if ( this.d.addEventListener ) {
							el.addEventListener( evt, _callback, eventBubbleOrCapture );
						} else {
							if ( this.d.attachEvent ) {
								evt = this.createEventsForIE( evt );

								el.attachEvent( evt, _callback );
							}
						}
					}
				}
							return this;
			},
			removeListener : function ( el, evt, _callback, eventBubbleOrCapture, liveEvent ) {
				if( ( typeof _callback == 'string' ) && ( _callback.indexOf( '.' ) !== -1 ) ) {
						_callback = this.executeFunctionByName( _callback, this.w );
				}

				if( ! this.isFunction( _callback ) ) {
					throw "Invalid callback function - " + _callback;
				} else {
					if( eventBubbleOrCapture !== true ) {
						eventBubbleOrCapture = false;
					}

					if( el instanceof $ ) {
						// if the object is an instance of jquery
						if( ( liveEvent !== undefined ) && ( typeof liveEvent === 'string' ) ) {
							el.off( evt, liveEvent, _callback );
						} else {
							el.off( evt, _callback );
						}
					} else {
						if ( this.d.removeEventListener ) {
							el.removeEventListener( evt, _callback, eventBubbleOrCapture );
						} else {
							if ( this.d.detachEvent ) {
								evt = this.createEventsForIE( evt );

								el.detachEvent( evt, _callback );
							}
						}
					}
				}
							return this;
			},
			executeFunctionByName : function( functionName, context ) {
				/**
				 * Used to get a reference to the callback if it is part of any namespace
				 */
				var namespaces = functionName.split( "." );
				var func = namespaces.pop();

				for( var i = 0; i < namespaces.length; i++ ) {
					context = context[ namespaces[ i ] ];
				}

				return context[ func ]; // return a reference to the callback
			},
			createEventsForIE : function( evt ) {
				evt = $.trim( evt );
				evt = evt.split( /\s+/ );

				for( var i=0;i<evt.length;i++ ) {
					evt[ i ] = 'on' + evt[ i ];
				}

				return evt.join( ' ' );
			},
			addSeparatorsNF : function( nStr, inD, outD, sep ) {
				nStr += '';
				var dpos = nStr.indexOf( inD );
				var nStrEnd = '';

				if( dpos != -1 ) {
					nStrEnd = outD + nStr.substring( dpos + 1, nStr.length );
					nStr = nStr.substring( 0, dpos );
				}

				var rgx = /(\d+)(\d{3})/;
				while ( rgx.test( nStr ) ) {
					nStr = nStr.replace( rgx, '$1' + sep + '$2' );
				}

				return nStr + nStrEnd;
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
                        toTitleCase: function(str)
                        {
                            return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
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
                        replaceQueryString : function( uri, key, value ) {
				var re = new RegExp( "([?|&])" + key + "=.*?(&|$)", "i" );
				var separator = uri.indexOf('?') !== -1 ? "&" : "?";

				if ( uri.match( re ) ) {
					return uri.replace( re, '$1' + key + "=" + value + '$2' );
				} else {
					return uri + separator + key + "=" + value;
				}
			},
                        reload : function()
                        {      
                                this.w.location = this.replaceQueryString(this.l.href, '_r', Math.random());
                        }
                };
                z.GLOBAL = o;
        })(SJ, $);

        $(function () {
                SJ.GLOBAL.init();
        });
}