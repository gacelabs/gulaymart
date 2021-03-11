/* * SendData JavaScript Library v3.0 * https://send-data.co/ * Much simple and optimized loading * Copyright 2021, DataSendData.com */ 
(function (e) {
	"use strict";
	var conncetionState = null, appInitialized = false;
	window.SendData = function(options) {
		if (options == undefined) options = {};
		this.app.options = Object.assign(this.app.options, options);
		if (this.app.options.debug) {
			this.helper.debug(this.app.options.debug);
		}
		this.events = this.plant.Events();
		this.stashes = this.plant.Stashes();
		this.handshakes = this.plant.Handshakes();
		this.runtime.setup(this, function (senddata) {
			if (senddata.app.is_loaded) {
				var server = senddata.handshakes;
				var box = senddata.stashes;
				var actions = senddata.events;
				/*initialize handshakes*/ 
				server.startAuth(function (app) {
					if (app.options.autoConnect) {
						server.connect(function (app) {
							if (app.options.autoRunStash && box != undefined) {
								box.exec(box.clear);
							}
						}, box);
					}
					if (app.options.afterInit != undefined) {
						app.options.afterInit();
					}
				});
			}
		});
		this.connect = function (callback) {
			this.handshakes.connect(callback, this.stashes);
			return this;
		};
		this.allChannels = function () {
			return this.events.channels.all();
		};
		this.channel = function (name) {
			return this.events.channels.find(name);
		};
		this.getSessionId = function () {
			return this.events.channels.getSession();
		};
		this.subscribe = function (name) {
			if (this.app.connected) {
				this.events.channels.add(name);
			} else {
				this.stashes.add(this, [name], "subscribe");
			}
			return this;
		};
		this.unsubscribe = function (name) {
			return this.events.channels.remove(name);
		};
		/*manage events*/ this.allEvents = function () {
			return this.events.all();
		};
		this.event = function (name) {
			return this.events.find(name);
		};
		this.bind = function (name, channel, callback) {
			if (this.app.connected) {
				this.events.add(name, channel, callback);
			} else {
				this.stashes.add(this, [name, channel, callback], "bind");
			}
			return this;
		};
		this.listen = function (name, callback) {
			if (this.app.connected) {
				this.events.bind(name, callback);
			} else {
				this.stashes.add(this, [name, callback], "listen");
			}
			return this;
		};
		this.unbind = function (name) {
			return this.events.remove(name);
		};
		this.trigger = function (event, channel, data) {
			if (this.app.connected) {
				this.events.send(event, channel, data);
			} else {
				this.stashes.add(this, [event, channel, data], "trigger");
			}
			return this;
		};
		this.disconnect = function (callback) {
			this.handshakes.disconnect(callback);
			return this;
		};
	}
	SendData.prototype.helper = {
		checkAppKey: function (key) {
			if (key === null || key === undefined) {
				throw "You must pass your app key when you instantiate SendData.";
			}
		},
		checkScript: function (isSet, url, is_in_head) {
			if (isSet) {
				return Promise.resolve();
			} else {
				return this.loadScript(url, is_in_head);
			}
		},
		loadScript: function (url, is_in_head) {
			return new Promise(function (resolve, reject) {
				var script = document.createElement("script");
				script.type = "text/javascript";
				script.onload = resolve;
				script.onerror = reject;
				script.src = url;
				if (is_in_head) {
					document.getElementsByTagName("head")[0].appendChild(script);
				} else {
					var mainScript = document.getElementsByTagName("script")[0];
					var parentNode = mainScript.parentNode;
					parentNode.insertBefore(script, mainScript);
				}
			});
		},
		submitChannel: function (name, type) {
			var settings = {
				url: SendData.prototype.runtime.endpoint,
				type: "post",
				data: { type: type, app_key: SendData.prototype.app.key, channel: name },
				crossDomain: true,
				success: function (response) {},
				error: function (xhr, code, status) {
					SendData.prototype.app.socket.close();
				},
			};
			this.xhr(type, settings);
		},
		xhr: function (name, options) {
			$.ajax(options).done(function (a, b, c) {
				if (b === "success") {
					var d = new Date();
					SendData.prototype.app.sent["DPT-" + d.getTime()] = { action: name, params: options };
				}
			});
		},
		autobahn: function (url, onOpen, onCLose, oSettings) {
			return new ab.Session(url, onOpen, onCLose, oSettings);
		},
		debug: function (enable) {
			if (enable == undefined) enable = false;
			if (enable) {
				return ab.debug(true, true);
			} else {
				return ab.debug(false, false);
			}
		},
		onCLoseState: function (state, senddata, stashes) {
			if (conncetionState != null && senddata.app.connected == false) {
				state = conncetionState;
				conncetionState = null;
			}
			SendData.prototype.app.connected = false;
			switch (state) {
				case ab.CONNECTION_CLOSED:
					senddata.app.log("App connection closed...", null, 1);
					break;
				case ab.CONNECTION_LOST:
					if (senddata.app.options.autoConnect) {
						senddata.app.log("App connection lost... reconnecting after 9 seconds", null, 1);
						setTimeout(function () {
							senddata.plant.Handshakes().connect(function () {
								if (senddata.app.options.autoRunStash && stashes != undefined) {
									stashes.exec(function (_stashes) {
										/*_stashes.clear();*/
									});
								}
							}, stashes);
						}, 9000);
					} else {
						senddata.app.log("App connection closed...", null, 1);
					}
					break;
				case ab.CONNECTION_UNREACHABLE:
					if (senddata.app.options.autoConnect) {
						senddata.app.log("App connection unreachable...", null, 1);
						senddata.app.log("App reconnecting...", null, 1);
						setTimeout(function () {
							senddata.plant.Handshakes().connect(function () {
								if (senddata.app.options.autoRunStash && stashes != undefined) {
									stashes.exec(function (_stashes) {
										/*_stashes.clear();*/
									});
								}
							}, stashes);
						}, 3000);
					} else {
						senddata.app.log("App connection unreachable, you must re-connect...", null, 1);
					}
					break;
				case ab.CONNECTION_UNSUPPORTED:
					senddata.app.log("App connection unsupported...", null, 1);
					break;
			}
		},
	};
	SendData.prototype.app = {
		key: "A3193CF4AEC1ADD05F4B78C4E0C61C39",
		socket: null,
		sessionid: null,
		is_loaded: false,
		approved: false,
		connected: false,
		options: { debug: false, autoConnect: false, autoRunStash: true, skipSubprotocolCheck: true },
		sent: {},
		run: function (exec, senddata) {
			senddata.app.is_loaded = true;
			exec(senddata);
		},
		log: function (data, type, force) {
			switch (type) {
				case "warn":
					console.warn("%c\u2620 " + data, "font-weight:bold;line-height:18px;");
					break;
				case "error":
					console.error("%c\u26d4 " + data, "font-weight:bold;line-height:18px;");
					break;
				default:
					console.log("%c\u26a1 " + data, "font-weight:bold;line-height:18px;");
					break;
			}
		},
	};
	SendData.prototype.plant = {
		Channels: function () {
			var Channel = class {
				constructor() {
					this.list = {};
					this.saved = null;
					this.sessionid = null;
				}
				add(name) {
					if (this.find(name) == undefined) {
						this.list[name] = SendData.prototype.plant.Events();
						this.saved = name;
						if (name.indexOf(":") >= 0) {
							SendData.prototype.helper.submitChannel(name, "add_channel");
						} else {
							SendData.prototype.app.log("Must call the function `listen` after subscribing channel `" + name + "`", "warn");
						}
					}
					return this;
				}
				find(name) {
					return this.list[name];
				}
				setSession(sessionid) {
					this.sessionid = sessionid;
					return this;
				}
				getSession() {
					return this.sessionid;
				}
				remove(name) {
					SendData.prototype.helper.submitChannel(name, "remove_channel");
					delete this.list[name];
					return this;
				}
				all() {
					return this.list;
				}
			};
			return new Channel();
		},
		Events: function () {
			var Event = class {
				constructor() {
					this.list = {};
					this.channels = SendData.prototype.plant.Channels();
				}
				add(event, channel, callback) {
					if (this.channels.find(event + ":" + channel) == undefined) {
						this.channels.setSession(this.channels.getSession());
						this.channels.add(event + ":" + channel);
					}
					if (typeof callback !== "function") {
						SendData.prototype.app.log("Event callback is not a function on binded event " + event + " for channel " + channel, "error");
					} else {
						var eventFunction = function (eventname, obj) {
							callback(obj, channel);
						};
						SendData.prototype.app.socket.subscribe(channel, eventFunction);
						this.list[event] = SendData.prototype.app.socket;
					}
					return this;
				}
				bind(event, callback) {
					delete this.channels.list[this.channels.saved];
					this.add(event, this.channels.saved, callback);
					this.channels.saved = null;
					return this;
				}
				all() {
					return this.list;
				}
				find(event) {
					return this.list[event];
				}
				remove(event, callback) {
					var socket = this.list[event];
					delete this.list[event];
					socket.unsubscribe(event, callback);
					return this;
				}
				send(event, channel, push_data) {
					if (push_data != undefined) {
						var object = { channel: channel, event: event, app_key: SendData.prototype.app.key, blacklist: [], whitelist: [], broadcast: true, data: null };
						if (typeof push_data.blacklist == "object" && push_data.blacklist.length) {
							object.blacklist.push(push_data.blacklist);
						}
						if (typeof push_data.whitelist == "object" && push_data.whitelist.length) {
							object.whitelist.push(push_data.whitelist);
						}
						if (typeof push_data.broadcast == "boolean") {
							object.broadcast = push_data.broadcast;
						}
						object.data = push_data;
						var settings = {
							url: SendData.prototype.runtime.endpoint,
							type: "post",
							data: object,
							success: function (response) {
								if (typeof push_data.ajax_options !== "undefined") {
									SendData.prototype.helper.xhr("owners_send", push_data.ajax_options);
								}
								SendData.prototype.app.log("Data transmitted!");
							},
							error: function (xhr, code, status) {
								SendData.prototype.app.socket.close();
							},
						};
						SendData.prototype.helper.xhr("default_send", settings);
					}
					return this;
				}
			};
			return new Event();
		},
		Stashes: function () {
			var Stash = class {
				constructor() {
					this.trunk = [];
					this.loads = [];
				}
				exec(callback) {
					if (this.trunk.length) {
						for (var x in this.trunk) {
							var stash = this.trunk[x];
							var object = stash.object,
								args = stash.args,
								method = stash.method;
							object[method].apply(object, args);
						}
						if (typeof callback == "function") {
							callback(this);
						}
					}
					return this;
				}
				add(object, params, method) {
					var main = { object: object, args: params, method: method };
					this.trunk.push(main);
					return this;
				}
				clear() {
					if (this != undefined) {
						if (this.trunk.length) this.trunk.splice(0, this.trunk.length);
						if (this.loads.length) this.loads.splice(0, this.loads.length);
					}
					return this;
				}
				runLoads(callback) {
					if (this.loads.length) {
						for (var x in this.loads) {
							var inits = this.loads[x];
							var args = inits.arg,
								method = inits.func;
							method(args);
						}
						if (typeof callback == "function") {
							callback(this);
						}
					}
					return this;
				}
			};
			return new Stash();
		},
		Handshakes: function () {
			var Handshake = class {
				constructor() {
					SendData.prototype.app.approved = false;
					SendData.prototype.app.connected = false;
					SendData.prototype.app.sessionid = null;
				}
				startAuth(successCallback) {
					var successResponse = function (response) {
						if (response) {
							SendData.prototype.app.approved = true;
							if (typeof successCallback === "function") successCallback(SendData.prototype.app);
						} else {
							SendData.prototype.app.log("App key did not pass!", "error");
						}
					};
					var errorResponse = function (a, b, c) {
						SendData.prototype.app.connected = false;
						SendData.prototype.app.log("App key did not pass! Please check your subscription", "error");
					};
					var options = { url: SendData.prototype.runtime.api_url + "get/script/" + SendData.prototype.app.key + "/0", dataType: "json", success: successResponse, error: errorResponse };
					SendData.prototype.helper.xhr("initial_handshake", options);
				}
				connect(connectedCallback, stashes) {
					if (SendData.prototype.app.connected == false) {
						var ws_protocol = "wss://",
							port = SendData.prototype.runtime.wss_port;
						var url = ws_protocol + SendData.prototype.runtime.ws_host + "/echo";
						var onOpen = function (sessionid, state, ws_version) {
							SendData.prototype.app.connected = true;
							SendData.prototype.app.sessionid = sessionid;
							SendData.prototype.app.log("App Connected!", null, 1);
							if (typeof connectedCallback === "function") {
								connectedCallback(SendData.prototype.app);
								if (appInitialized == false) {
									appInitialized = true;
									stashes.loads.push({ func: connectedCallback, arg: SendData.prototype.app });
								} else if (stashes.loads.length) {
									stashes.runLoads();
								}
							}
							if (SendData.prototype.app.options.afterConnect != undefined) {
								SendData.prototype.app.options.afterConnect();
							}
						};
						var onCLose = function (state) {
							SendData.prototype.app.connected = false;
							SendData.prototype.app.sessionid = null;
							SendData.prototype.app.log("App disconnected!!", "error", 1);
							SendData.prototype.helper.onCLoseState(state, SendData.prototype, stashes);
						};
						var oSettings = { skipSubprotocolCheck: SendData.prototype.app.options.skipSubprotocolCheck };
						SendData.prototype.app.socket = SendData.prototype.helper.autobahn(url, onOpen, onCLose, oSettings);
					}
				}
				disconnect(disConnectedCallback) {
					SendData.prototype.app.connected = false;
					SendData.prototype.app.socket.close();
					if (typeof disConnectedCallback == "function") {
						return disConnectedCallback(SendData.prototype.app);
					}
				}
			};
			return new Handshake();
		},
	};
	SendData.prototype.runtime = {
		ws_host: "app.send-data.co",
		ws_port: 8080,
		wss_port: 443,
		api_url: "https://app.send-data.co/",
		endpoint: "https://app.send-data.co/app/transmit/9725825C796122EF40F01B2D8794F902/0",
		setup: function (senddata, launch) {
			var initializeOnDocumentBody = function (_this) {
				_this.onDocumentBody(senddata, launch);
			};
			return initializeOnDocumentBody(this);
		},
		getDocument: function () {
			return document;
		},
		getProtocol: function () {
			return this.getDocument().location.protocol;
		},
		onDocumentBody: function (senddata, launch) {
			senddata.app.run(launch, senddata);
		},
	};
	var i = setInterval(function() {
		if (window.SDASyncInit != undefined) {
			window.SDASyncInit();
			clearInterval(i);
		}
	}, 10);
	/*combined the autobahn script*/ ("use strict");
	var AUTOBAHNJS_VERSION = "?.?.?";
	var AUTOBAHNJS_DEBUG = true;
	var ab = (window.ab = {});
	ab._version = AUTOBAHNJS_VERSION;
	(function () {
		if (!Array.prototype.indexOf) {
			Array.prototype.indexOf = function (searchElement) {
				"use strict";
				if (this === null) {
					throw new TypeError();
				}
				var t = new Object(this);
				var len = t.length >>> 0;
				if (len === 0) {
					return -1;
				}
				var n = 0;
				if (arguments.length > 0) {
					n = Number(arguments[1]);
					if (n !== n) {
						n = 0;
					} else if (n !== 0 && n !== Infinity && n !== -Infinity) {
						n = (n > 0 || -1) * Math.floor(Math.abs(n));
					}
				}
				if (n >= len) {
					return -1;
				}
				var k = n >= 0 ? n : Math.max(len - Math.abs(n), 0);
				for (; k < len; k++) {
					if (k in t && t[k] === searchElement) {
						return k;
					}
				}
				return -1;
			};
		}
		if (!Array.prototype.forEach) {
			Array.prototype.forEach = function (callback, thisArg) {
				var T, k;
				if (this === null) {
					throw new TypeError(" this is null or not defined");
				}
				var O = new Object(this);
				var len = O.length >>> 0;
				if ({}.toString.call(callback) !== "[object Function]") {
					throw new TypeError(callback + " is not a function");
				}
				if (thisArg) {
					T = thisArg;
				}
				k = 0;
				while (k < len) {
					var kValue;
					if (k in O) {
						kValue = O[k];
						callback.call(T, kValue, k, O);
					}
					k++;
				}
			};
		}
	})();
	ab._sliceUserAgent = function (str, delim, delim2) {
		var ver = [];
		var ua = navigator.userAgent;
		var i = ua.indexOf(str);
		var j = ua.indexOf(delim, i);
		if (j < 0) {
			j = ua.length;
		}
		var agent = ua.slice(i, j).split(delim2);
		var v = agent[1].split(".");
		for (var k = 0; k < v.length; ++k) {
			ver.push(parseInt(v[k], 10));
		}
		return { name: agent[0], version: ver };
	};
	ab.getBrowser = function () {
		var ua = navigator.userAgent;
		if (ua.indexOf("Chrome") > -1) {
			return ab._sliceUserAgent("Chrome", " ", "/");
		} else if (ua.indexOf("Safari") > -1) {
			return ab._sliceUserAgent("Safari", " ", "/");
		} else if (ua.indexOf("Firefox") > -1) {
			return ab._sliceUserAgent("Firefox", " ", "/");
		} else if (ua.indexOf("MSIE") > -1) {
			return ab._sliceUserAgent("MSIE", ";", " ");
		} else {
			return null;
		}
	};
	ab.browserNotSupportedMessage = "Browser does not support WebSockets (RFC6455)";
	ab._idchars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	ab._idlen = 16;
	ab._subprotocol = "wamp";
	ab._newid = function () {
		var id = "";
		for (var i = 0; i < ab._idlen; i += 1) {
			id += ab._idchars.charAt(Math.floor(Math.random() * ab._idchars.length));
		}
		return id;
	};
	ab.log = function (o) {
		if (window.console && console.log) {
			if (arguments.length > 1) {
				console.group("Log Item");
				for (var i = 0; i < arguments.length; i += 1) {
					console.log(arguments[i]);
				}
				console.groupEnd();
			} else {
				console.log(arguments[0]);
			}
		}
	};
	ab._debugrpc = false;
	ab._debugpubsub = false;
	ab._debugws = false;
	ab.debug = function (debugWamp, debugWs) {
		if ("console" in window) {
			ab._debugrpc = debugWamp;
			ab._debugpubsub = debugWamp;
			ab._debugws = debugWs;
		} else {
			throw "browser does not support console object";
		}
	};
	ab.version = function () {
		return ab._version;
	};
	ab.PrefixMap = function () {
		var self = this;
		self._index = {};
		self._rindex = {};
	};
	ab.PrefixMap.prototype.get = function (prefix) {
		var self = this;
		return self._index[prefix];
	};
	ab.PrefixMap.prototype.set = function (prefix, uri) {
		var self = this;
		self._index[prefix] = uri;
		self._rindex[uri] = prefix;
	};
	ab.PrefixMap.prototype.setDefault = function (uri) {
		var self = this;
		self._index[""] = uri;
		self._rindex[uri] = "";
	};
	ab.PrefixMap.prototype.remove = function (prefix) {
		var self = this;
		var uri = self._index[prefix];
		if (uri) {
			delete self._index[prefix];
			delete self._rindex[uri];
		}
	};
	ab.PrefixMap.prototype.resolve = function (curie, pass) {
		var self = this;
		var i = curie.indexOf(":");
		if (i >= 0) {
			var prefix = curie.substring(0, i);
			if (self._index[prefix]) {
				return self._index[prefix] + curie.substring(i + 1);
			}
		}
		if (pass == true) {
			return curie;
		} else {
			return null;
		}
	};
	ab.PrefixMap.prototype.shrink = function (uri, pass) {
		var self = this;
		var i = uri.indexOf(":");
		if (i == -1) {
			for (var i = uri.length; i > 0; i -= 1) {
				var u = uri.substring(0, i);
				var p = self._rindex[u];
				if (p) {
					return p + ":" + uri.substring(i);
				}
			}
		}
		if (pass == true) {
			return uri;
		} else {
			return null;
		}
	};
	ab._MESSAGE_TYPEID_WELCOME = 0;
	ab._MESSAGE_TYPEID_PREFIX = 1;
	ab._MESSAGE_TYPEID_CALL = 2;
	ab._MESSAGE_TYPEID_CALL_RESULT = 3;
	ab._MESSAGE_TYPEID_CALL_ERROR = 4;
	ab._MESSAGE_TYPEID_SUBSCRIBE = 5;
	ab._MESSAGE_TYPEID_UNSUBSCRIBE = 6;
	ab._MESSAGE_TYPEID_PUBLISH = 7;
	ab._MESSAGE_TYPEID_EVENT = 8;
	ab.CONNECTION_CLOSED = 0;
	ab.CONNECTION_LOST = 1;
	ab.CONNECTION_UNREACHABLE = 2;
	ab.CONNECTION_UNSUPPORTED = 3;
	ab.Session = function (wsuri, onopen, onclose, options) {
		var self = this;
		self._wsuri = wsuri;
		self._options = options;
		self._websocket_onopen = onopen;
		self._websocket_onclose = onclose;
		self._websocket = null;
		self._websocket_connected = false;
		self._session_id = null;
		self._calls = {};
		self._subscriptions = {};
		self._prefixes = new ab.PrefixMap();
		self._txcnt = 0;
		self._rxcnt = 0;
		if ("WebSocket" in window) {
			self._websocket = new WebSocket(self._wsuri, [ab._subprotocol]);
		} else if ("MozWebSocket" in window) {
			self._websocket = new MozWebSocket(self._wsuri, [ab._subprotocol]);
		} else {
			if (onclose !== undefined) {
				onclose(ab.CONNECTION_UNSUPPORTED);
				return;
			} else {
				throw ab.browserNotSupportedMessage;
			}
		}
		self._websocket.onmessage = function (e) {
			if (ab._debugws) {
				self._rxcnt += 1;
				console.group("WS Receive");
				console.info(self._wsuri + " [" + self._session_id + "]");
				console.log(self._rxcnt);
				console.log(e.data);
				console.groupEnd();
			}
			var o = JSON.parse(e.data);
			if (o[1] in self._calls) {
				if (o[0] === ab._MESSAGE_TYPEID_CALL_RESULT) {
					var dr = self._calls[o[1]];
					var r = o[2];
					if (ab._debugrpc && dr._ab_callobj !== undefined) {
						console.group("WAMP Call", dr._ab_callobj[2]);
						console.timeEnd(dr._ab_tid);
						console.group("Arguments");
						for (var i = 3; i < dr._ab_callobj.length; i += 1) {
							var arg = dr._ab_callobj[i];
							if (arg !== undefined) {
								console.log(arg);
							} else {
								break;
							}
						}
						console.groupEnd();
						console.group("Result");
						console.log(r);
						console.groupEnd();
						console.groupEnd();
					}
					dr.resolve(r);
				} else if (o[0] === ab._MESSAGE_TYPEID_CALL_ERROR) {
					var de = self._calls[o[1]];
					var uri = o[2];
					var desc = o[3];
					var detail = o[4];
					if (ab._debugrpc && de._ab_callobj !== undefined) {
						console.group("WAMP Call", de._ab_callobj[2]);
						console.timeEnd(de._ab_tid);
						console.group("Arguments");
						for (var j = 3; j < de._ab_callobj.length; j += 1) {
							var arg2 = de._ab_callobj[j];
							if (arg2 !== undefined) {
								console.log(arg2);
							} else {
								break;
							}
						}
						console.groupEnd();
						console.group("Error");
						console.log(uri);
						console.log(desc);
						if (detail !== undefined) {
							console.log(detail);
						}
						console.groupEnd();
						console.groupEnd();
					}
					if (detail !== undefined) {
						de.reject(uri, desc, detail);
					} else {
						de.reject(uri, desc);
					}
				}
				delete self._calls[o[1]];
			} else if (o[0] === ab._MESSAGE_TYPEID_EVENT) {
				var subid = self._prefixes.resolve(o[1], true);
				if (subid in self._subscriptions) {
					var uri2 = o[1];
					var val = o[2];
					if (ab._debugpubsub) {
						console.group("WAMP Event");
						console.info(self._wsuri + " [" + self._session_id + "]");
						console.log(uri2);
						console.log(val);
						console.groupEnd();
					}
					self._subscriptions[subid].forEach(function (callback) {
						callback(uri2, val);
					});
				} else {
				}
			} else if (o[0] === ab._MESSAGE_TYPEID_WELCOME) {
				if (self._session_id === null) {
					self._session_id = o[1];
					self._wamp_version = o[2];
					self._server = o[3];
					if (ab._debugrpc || ab._debugpubsub) {
						console.group("WAMP Welcome");
						console.info(self._wsuri + " [" + self._session_id + "]");
						console.log(self._wamp_version);
						console.log(self._server);
						console.groupEnd();
					}
					if (self._websocket_onopen !== null) {
						self._websocket_onopen(self._session_id, self._wamp_version, self._server);
					}
				} else {
					throw "protocol error (welcome message received more than once)";
				}
			}
		};
		self._websocket.onopen = function (e) {
			if (self._websocket.protocol !== ab._subprotocol) {
				if (typeof self._websocket.protocol === "undefined") {
					if (ab._debugws) {
						console.group("WS Warning");
						console.info(self._wsuri);
						console.log("WebSocket object has no protocol attribute: WAMP subprotocol check skipped!");
						console.groupEnd();
					}
				} else if (self._options && self._options.skipSubprotocolCheck) {
					if (ab._debugws) {
						console.group("WS Warning");
						console.info(self._wsuri);
						console.log("Server does not speak WAMP, but subprotocol check disabled by option!");
						console.log(self._websocket.protocol);
						console.groupEnd();
					}
				} else {
					self._websocket.close(1000, "server does not speak WAMP");
					throw "server does not speak WAMP (but '" + self._websocket.protocol + "' !)";
				}
			}
			if (ab._debugws) {
				console.group("WAMP Connect");
				console.info(self._wsuri);
				console.log(self._websocket.protocol);
				console.groupEnd();
			}
			self._websocket_connected = true;
		};
		self._websocket.onerror = function (e) {};
		self._websocket.onclose = function (e) {
			if (ab._debugws) {
				if (self._websocket_connected) {
					console.log("Autobahn connection to " + self._wsuri + " lost (code " + e.code + ", reason '" + e.reason + "', wasClean " + e.wasClean + ").");
				} else {
					console.log("Autobahn could not connect to " + self._wsuri + " (code " + e.code + ", reason '" + e.reason + "', wasClean " + e.wasClean + ").");
				}
			}
			if (self._websocket_onclose !== undefined) {
				if (self._websocket_connected) {
					if (e.wasClean) {
						self._websocket_onclose(ab.CONNECTION_CLOSED);
					} else {
						self._websocket_onclose(ab.CONNECTION_LOST);
					}
				} else {
					self._websocket_onclose(ab.CONNECTION_UNREACHABLE);
				}
			}
			self._websocket_connected = false;
			self._wsuri = null;
			self._websocket_onopen = null;
			self._websocket_onclose = null;
			self._websocket = null;
		};
	};
	ab.Session.prototype._send = function (msg) {
		var self = this;
		if (!self._websocket_connected) {
			throw "Autobahn not connected";
		}
		var rmsg = JSON.stringify(msg);
		self._websocket.send(rmsg);
		self._txcnt += 1;
		if (ab._debugws) {
			console.group("WS Send");
			console.info(self._wsuri + " [" + self._session_id + "]");
			console.log(self._txcnt);
			console.log(rmsg);
			console.groupEnd();
		}
	};
	ab.Session.prototype.close = function () {
		var self = this;
		if (!self._websocket_connected) {
			throw "Autobahn not connected";
		}
		self._websocket.close();
	};
	ab.Session.prototype.sessionid = function () {
		var self = this;
		return self._session_id;
	};
	ab.Session.prototype.shrink = function (uri, pass) {
		var self = this;
		return self._prefixes.shrink(uri, pass);
	};
	ab.Session.prototype.resolve = function (curie, pass) {
		var self = this;
		return self._prefixes.resolve(curie, pass);
	};
	ab.Session.prototype.prefix = function (prefix, uri) {
		var self = this;
		if (self._prefixes.get(prefix) !== undefined) {
			throw "prefix '" + prefix + "' already defined";
		}
		self._prefixes.set(prefix, uri);
		if (ab._debugrpc || ab._debugpubsub) {
			console.group("WAMP Prefix");
			console.info(self._wsuri + " [" + self._session_id + "]");
			console.log(prefix);
			console.log(uri);
			console.groupEnd();
		}
		var msg = [ab._MESSAGE_TYPEID_PREFIX, prefix, uri];
		self._send(msg);
	};
	ab.Session.prototype.call = function () {
		var self = this;
		var d = new when.defer();
		var callid;
		while (true) {
			callid = ab._newid();
			if (!(callid in self._calls)) {
				break;
			}
		}
		self._calls[callid] = d;
		var procuri = self._prefixes.shrink(arguments[0], true);
		var obj = [ab._MESSAGE_TYPEID_CALL, callid, procuri];
		for (var i = 1; i < arguments.length; i += 1) {
			obj.push(arguments[i]);
		}
		self._send(obj);
		if (ab._debugrpc) {
			d._ab_callobj = obj;
			d._ab_tid = self._wsuri + " [" + self._session_id + "][" + callid + "]";
			console.time(d._ab_tid);
			console.info();
		}
		return d;
	};
	ab.Session.prototype.subscribe = function (topicuri, callback) {
		var self = this;
		var rtopicuri = self._prefixes.resolve(topicuri, true);
		if (!(rtopicuri in self._subscriptions)) {
			if (ab._debugpubsub) {
				console.group("WAMP Subscribe");
				console.info(self._wsuri + " [" + self._session_id + "]");
				console.log(topicuri);
				console.log(callback);
				console.groupEnd();
			}
			var msg = [ab._MESSAGE_TYPEID_SUBSCRIBE, topicuri];
			self._send(msg);
			self._subscriptions[rtopicuri] = [];
		}
		var i = self._subscriptions[rtopicuri].indexOf(callback);
		if (i === -1) {
			self._subscriptions[rtopicuri].push(callback);
		} else {
			throw "callback " + callback + " already subscribed for topic " + rtopicuri;
		}
	};
	ab.Session.prototype.unsubscribe = function (topicuri, callback) {
		var self = this;
		var rtopicuri = self._prefixes.resolve(topicuri, true);
		if (!(rtopicuri in self._subscriptions)) {
			throw "not subscribed to topic " + rtopicuri;
		} else {
			var removed;
			if (callback !== undefined) {
				var idx = self._subscriptions[rtopicuri].indexOf(callback);
				if (idx !== -1) {
					removed = callback;
					self._subscriptions[rtopicuri].splice(idx, 1);
				} else {
					throw "no callback " + callback + " subscribed on topic " + rtopicuri;
				}
			} else {
				removed = self._subscriptions[rtopicuri].slice();
				self._subscriptions[rtopicuri] = [];
			}
			if (self._subscriptions[rtopicuri].length === 0) {
				delete self._subscriptions[rtopicuri];
				if (ab._debugpubsub) {
					console.group("WAMP Unsubscribe");
					console.info(self._wsuri + " [" + self._session_id + "]");
					console.log(topicuri);
					console.log(removed);
					console.groupEnd();
				}
				var msg = [ab._MESSAGE_TYPEID_UNSUBSCRIBE, topicuri];
				self._send(msg);
			}
		}
	};
	ab.Session.prototype.publish = function () {
		var self = this;
		var topicuri = arguments[0];
		var event = arguments[1];
		var excludeMe = null;
		var exclude = null;
		var eligible = null;
		var msg = null;
		if (arguments.length > 3) {
			if (!(arguments[2] instanceof Array)) {
				throw "invalid argument type(s)";
			}
			if (!(arguments[3] instanceof Array)) {
				throw "invalid argument type(s)";
			}
			exclude = arguments[2];
			eligible = arguments[3];
			msg = [ab._MESSAGE_TYPEID_PUBLISH, topicuri, event, exclude, eligible];
		} else if (arguments.length > 2) {
			if (typeof arguments[2] === "boolean") {
				excludeMe = arguments[2];
				msg = [ab._MESSAGE_TYPEID_PUBLISH, topicuri, event, excludeMe];
			} else if (arguments[2] instanceof Array) {
				exclude = arguments[2];
				msg = [ab._MESSAGE_TYPEID_PUBLISH, topicuri, event, exclude];
			} else {
				throw "invalid argument type(s)";
			}
		} else {
			msg = [ab._MESSAGE_TYPEID_PUBLISH, topicuri, event];
		}
		if (ab._debugpubsub) {
			console.group("WAMP Publish");
			console.info(self._wsuri + " [" + self._session_id + "]");
			console.log(topicuri);
			console.log(event);
			if (excludeMe !== null) {
				console.log(excludeMe);
			} else {
				if (exclude !== null) {
					console.log(exclude);
					if (eligible !== null) {
						console.log(eligible);
					}
				}
			}
			console.groupEnd();
		}
		self._send(msg);
	};
}.call(this));
