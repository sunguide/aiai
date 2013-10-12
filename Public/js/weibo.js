define("http://sjs.sinajs.cn/cblog/js/module/autoHeight",
function(c, b, d) {
	function f() {
		if (!e) {
			$(".content").height(a);
			e = a - $(".step1").height()
		} else {
			$(".content").height($(".step1").height() + e)
		}
	}
	var a = $(window).innerHeight() - $(".content").offset().top - parseInt($(".content").css("paddingTop"), 10) - ($.browser.msie ? 1 : 0);
	var e = 0;
	window.fixCir = f;
	return f
});
define("http://sjs.sinajs.cn/cblog/js/module/calculateTextHeight",
function(b, a, c) {
	var d = $("<span>");
	var e = function(f) {
		var g = $(f).val();
		d.text(g);
		d.css("width", f.width()).css("fontSize", f.css("font-size")).css("fontFamily", f.css("fontFamily")).css("lineHeight", f.css("fontFamily"));
		return d.offsetHeight()
	};
	return e
});
define("http://sjs.sinajs.cn/cblog/js/module/checkAuthor",
function(d, c, e) {
	var b = d("util");
	var a = function() {
		var h = unescape(b.getCookie("SUP"));
		var i = {};
		if (h && h != "") {
			i.$UID = b.keyValue(h, "uid");
			i.$nick = decodeURIComponent(b.keyValue(h, "nick"));
			i.$isLogin = !!(i.$UID)
		} else {
			h = b.getCookie("SU");
			if (h && h != "") {
				var f = h.match(/^([^:]*:){2}(\d{5,11})/);
				var g = (f && f[2]) || null;
				i.$UID = g;
				i.$isLogin = !!(g)
			} else {
				i.$UID = null;
				i.$isLogin = false;
				i.$isAdmin = false
			}
		}
		return i
	};
	return a
});
define("http://sjs.sinajs.cn/cblog/js/module/customLogin",
function(b, a, c) {
	var d = b("listener");
	$.getScript("http://i.sso.sina.com.cn/js/ssologin.js",
	function() {
		window.SSO = new SSOController();
		SSO.init();
		SSO.from = "";
		SSO.name = "SSO";
		SSO.entry = "cblog";
		SSO.domain = "sina.com.cn";
		SSO.setDomain = true;
		SSO.noActiveTime = 14400;
		SSO.customInit = function() {};
		SSO.loginExtraQuery.vsnf = 1;
		SSO.useIframe = true;
		SSO.customLoginCallBack = function(e) {};
		SSO.customLogoutCallBack = function(e) {};
		SSO.cLogin = function(f, i) {
			var h = f.username,
			e = f.password,
			g = f.time || 0;
			f.door ? SSO.loginExtraQuery.door = f.door: "";
			f.vsnval ? SSO.loginExtraQuery.vsnval = f.vsnval: "";
			SSO.customLoginCallBack = function(j) {
				i(j);
				SSO.customLoginCallBack = function() {}
			};
			SSO.login(h, e, g)
		};
		SSO.cPreLogin = function(f, e) {
			SSO.prelogin({
				username: f,
				checkpin: 1
			},
			e)
		};
		SSO.cLogout = function(e) {
			SSO.customLogoutCallBack = function(f) {
				e(f);
				SSO.customLogoutCallBack = function() {}
			};
			SSO.logout()
		};
		d.notify("ssologinjs-loaded", {})
	})
});
define("http://sjs.sinajs.cn/cblog/js/module/favorite",
function(c, b, d) {
	function a(f, e) {
		if (window.sidebar) {
			window.sidebar.addPanel(f, e, "")
		} else {
			if (document.all) {
				window.external.AddFavorite(e, f)
			} else {
				alert("对不起，您的浏览器不支持此功能\n请使用（ctrl+D）收藏本工具。")
			}
		}
		return false
	}
	$(".topNav .btn_sc").bind("click",
	function() {
		a(document.title, window.location.href)
	})
});
define("http://sjs.sinajs.cn/cblog/js/module/goToStep",
function(e, c, f) {
	var b = e("util");
	var a = !!window.name;
	var g = function() {
		var h = $.browser;
		var i = window;
		if (h.msie && 9 > parseInt(h.version, 10)) {
			i = document.documentElement
		}
		return i
	};
	var d = {
		goToTop: function(k) {
			var h = $.browser;
			var i = g();
			if (h.msie && 7 > parseInt(h.version, 10)) {
				$(i).scrollTop(0);
				$("div.step2").hide();
				$("div.step3").hide();
				$("div.step3Bottom").hide();
				$("div.stepBtm").get(0).zoom = 1;
				$("div.stepBtm").show();
				typeof k === "function" && k()
			} else {
				var j = $(document).scrollTop();
				$(i).tween({
					scrollTop: {
						start: j,
						stop: 0,
						time: 0,
						duration: 0.5,
						units: "px",
						effect: "cubicOut",
						onStop: function() {
							$("div.step2").hide();
							$("div.step3").hide();
							$("div.step3Bottom").hide();
							$("div.stepBtm").show();
							typeof k === "function" && k()
						}
					}
				}).play()
			}
		},
		goToStep1: function(l) {
			$("div.loginLayer").css("marginTop", -485);
			var k = $(document).scrollTop();
			var i = $(document).height();
			var j = g();
			var h = $.browser;
			if (h.msie && 7 > parseInt(h.version, 10)) {
				$(j).scrollTop(i);
				$("div.stepBtm").get(0).zoom = 1;
				typeof l === "function" && l()
			} else {
				$(j).tween({
					scrollTop: {
						start: k,
						stop: i,
						time: 0,
						duration: 0.8,
						units: "px",
						effect: "cubicOut",
						onStop: function() {
							typeof l === "function" && l()
						}
					}
				}).play()
			}
		},
		goToStep2: function(o) {
			var h = $(window).scrollTop();
			var j = $("div.step2");
			var k = $(window).height();
			var l = j.height();
			var i = g();
			var n;
			if (k > l) {
				j.height(k)
			}
			var m = b.getPosition(j[0])[1];
			var p = $.browser;
			if (p.msie && 7 > parseInt(p.version, 10)) {
				setTimeout(function() {
					$(i).scrollTop(m);
					$("div.stepBtm").get(0).zoom = 1;
					typeof o === "function" && o()
				},
				100)
			} else {
				$(i).tween({
					scrollTop: {
						start: h,
						stop: m,
						time: 0,
						duration: 0.8,
						units: "px",
						effect: "cubicOut",
						onStop: function() {
							typeof o === "function" && o()
						}
					}
				}).play()
			}
		},
		goToStep3: function(p) {
			var q = $("div.step3ToStep1");
			$("div.goTop").hide();
			var t = $.browser;
			if (t.msie && 7 > parseInt(t.version, 10)) {
				$(q).hide();
				$("div.step3").show();
				document.documentElement.scrollTop = 0;
				typeof p === "function" && p()
			} else {
				var k = $("div.step3Bottom");
				if (!k.get(0)) {
					$("div.conLayer").append($('<div class="step3Bottom"></div>'));
					k = $("div.step3Bottom")
				} else {
					k.show()
				}
				var r = q.outerHeight();
				var m = $("div.step3").show();
				var n = b.getPosition(m[0])[1];
				var l = $(window).height();
				var s = m.outerHeight();
				var h = $(".content").css("paddingTop");
				var o = l - s;
				k.css("height", o);
				var j = document.documentElement;
				if ($.browser.webkit) {
					j = document.body
				}
				var i = $(j).scrollTop();
				JSTween.tween(j, {
					scrollTop: {
						start: i,
						stop: n,
						time: 0,
						duration: 0.8,
						units: "px",
						effect: "easeOut",
						onStop: function() {
							$("div.goTop").fadeOut();
							var u = $(j).scrollTop();
							j.style.overflow = "hidden";
							if ($.browser.webkit) {
								document.documentElement.style.overflow = "hidden"
							}
							j.scrollTop = u;
							$(document.body).attr("current-top", u);
							typeof p === "function" && p();
							q.css("visibility", "hidden");
							if (a) {
								$("div.topNav > div.btn").fadeIn()
							}
						}
					}
				});
				JSTween.play()
			}
		}
	};
	return d
});
define("http://sjs.sinajs.cn/cblog/js/module/goTop",
function(c, b, d) {
	var f = c("listener");
	var e = 0;
	f.on({
		name: "goToStep",
		callBack: function(g) {
			e = g.step
		}
	});
	var a = {
		goPos: function(h) {
			a.doAni = true;
			var g = $.browser.msie ? document.documentElement: window;
			$(g).tween({
				scrollTop: {
					start: $(g).scrollTop(),
					stop: h,
					time: 0,
					duration: 0.5,
					effect: "easeOut",
					onStop: function() {
						a.doAni = false
					}
				}
			});
			$.play();
			return false
		}
	};
	a.doAni = false;
	$(".goTop").bind("click",
	function() {
		$(".goTop").css("display", "none");
		return a.goPos(0)
	});
	$(window).bind("scroll",
	function() {
		if (3 === e) {
			return
		}
		if ($(document).height() + parseInt($(".loginLayer").css("marginTop"), 10) > $(window).innerHeight() && $(window).scrollTop() && !a.doAni) {
			$(".goTop").fadeIn()
		} else {
			$(".goTop").fadeOut()
		}
	});
	return a
});
define("http://sjs.sinajs.cn/cblog/js/module/ie6Tips",
function(c, b, e) {
	if (!$.browser.msie) {
		return
	}
	var a = parseInt($.browser.version);
	if (a > 6) {
		return
	}
	var d = '<div class="bTip"><div class="con"><p>您当前使用的IE6浏览器版本过低，升级浏览器后将获得更好的长微博使用体验。</p><a href="javascript:;" class="close" title="关闭"></a></div></div>';
	var f = $(d);
	f.insertAfter($(document.body.firstChild));
	f.fadeIn();
	$("a.close", f).on("click",
	function(g) {
		$("div.bTip").fadeOut({
			complete: function() {
				$("div.topNav.ie6fixedTL").css("top", "0px")
			}
		})
	});
	$("div.topNav.ie6fixedTL").css("top", f.outerHeight() + "px")
});
define("http://sjs.sinajs.cn/cblog/js/module/initStep1",
function(e, O, c) {
	var i = e("util");
	var q = e("loadPic").LoadPic;
	var P = e("listener");
	var y = e("checkAuthor");
	var C = e("goToStep");
	var x = "cblog-pub-weibo";
	var S = false;
	var n = !!window.name;
	var p = e("LS");
	$("#blog-content").on("scroll",
	function(U) {
		//U.preventDefault()
	});
	var M = function() {
		var V = $("#blog-content");
		var W = V.val();
		var U = !!i.trim(W);
		if (U && (W === V.attr("placeholder"))) {
			U = false
		}
		return U
	};
	var u = function(W, Y) {
		if (W.attr("blocked")) {
			return
		} else {
			W.attr("blocked", "1")
		}
		$("#" + x).addClass("dis");
		var U = $("#blog-title");
		var aa = U.val();
		var Z = $("#blog-content").val();
		if (i.trim(aa) === U.attr("placeholder")) {
			aa = ""
		}
		var X = $("span.slt", $("div.skins", $("div.step1")));
		var V = X.attr("tpl");
		$.ajax({
			url: "http://c.blog.sina.com.cn/cblogpost.php?rnd=" + Math.random(),
			type: "POST",
			cache: false,
			data: {
				title: aa,
				content: Z,
				tmp: V ? V: 0
			},
			dataType: "json",
			success: function(ac) {
				p.removeItem("blog-content");
				p.removeItem("blog-title");
				W.attr("blocked", "");
				if ("publish-cblog" === W.get(0).id) {
					if ("A00006" === ac.code) {
						W.attr("blogid", ac.data.blogid)
					} else {
						W.attr("blogid", "")
					}
					if (!W.attr("showtired")) {
						W.removeClass("loading")
					}
				}
				if (ac && ac.data.blogid) {
					$("textarea.jsUserPubText").val(aa + Z)
				}
				var ab = i.leftB(i.trim(Z), 460).replace(/\r|\n/g, "");
				var ad = i.leftB(ab, 260);
				Q(ad, ac, Y)
			},
			error: function(ab, ac) {
				W.attr("blocked", "");
				if ("publish-cblog" == W.get(0).id) {
					W.removeClass("loading");
					W.attr("pubTime", "");
					W.attr("blogid", "")
				}
			}
		})
	};
	var o = function(X) {
		var W = $("div.content div.step2");
		l(W);
		var V = arguments;
		var U = "http://c.blog.sina.com.cn/repair.php?rnd=" + Math.floor(Math.random() * 1000);
		if (!V.callee.count) {
			V.callee.count = 0
		}
		$.ajax({
			url: U,
			type: "POST",
			cache: false,
			data: {
				blogid: X
			},
			dataType: "json",
			success: function(Y) {
				a(W, X)
			},
			error: function(Y, Z) {
				V.callee.count++;
				if (V.callee.count > 10) {
					V.callee.count = null;
					t($("div.step2"));
					return
				}
				setTimeout(function() {
					V.callee(X)
				},
				500)
			}
		})
	};
	var h = function() {
		var V = $("#publish-cblog").parent();
		var W = $("div.stip.totiried", V);
		if (!W.get(0)) {
			var U = '<div class="stip totiried" style="display:none;"><div class="con"><span class="cor"></span><p>长微博有点累了，休息一会儿再生成图片吧！<a action-type="closeTired" href="javascript:;" class="close" title="关闭"></a></p></div></div>';
			$("#publish-cblog").parent().append(U);
			W = $("div.stip.totiried", V);
			W.css("left", 32);
			W.on("click",
			function(aa) {
				var Z = $(aa.target).attr("action-type");
				if ("closeTired" === Z) {
					var Y = $("#publish-cblog").parent();
					var X = $("div.stip.totiried", Y);
					X.fadeOut()
				}
			});
			W.fadeIn();
			W = null;
			V = null
		} else {
			W.fadeIn()
		}
	};
	var I = function() {
		var U = $("#publish-cblog").parent();
		var V = $("div.stip", U);
		if (V.get(0)) {
			V.fadeOut()
		}
	};
	var g = function(X) {
		var W = $.browser;
		var V = W.msie ? parseInt(W.version, 10) : 1000;
		var U = V > 8 ? window: document.documentElement;
		var Y = i.getPosition(X.get(0));
		JSTween.tween(U, {
			scrollTop: {
				start: $(U).scrollTop(),
				stop: Y[1] - 100,
				time: 0,
				duration: 0.5,
				units: "px",
				effect: "cubicOut",
				onStop: function() {}
			}
		});
		JSTween.play()
	};
	var z = function(U) {
		var V = $("#publish-cblog");
		if (V.attr("showtired")) {
			return
		}
		var W = 30;
		if (U) {
			W -= Math.ceil(new Date().getTime() / 1000) - U
		}
		if (W < 0) {
			W = 0
		}
		if (W === 0) {
			return
		}
		V.addClass("loading");
		V.attr("showtired", "1");
		var X = Math.min(W, 30);
		$("#publish-cblog > span").html("生成长微博图片（" + X + "秒）");
		h();
		var Y = setInterval(function() {
			X--;
			var Z = $("span", V);
			if (0 >= X) {
				V.attr("showtired", "");
				V.removeClass("loading");
				Z.html("生成长微博图片");
				I();
				clearInterval(Y)
			} else {
				Z.html("生成长微博图片（" + X + "秒）")
			}
		},
		1000)
	};
	var m = function() {
		C.goToTop()
	};
	var F = function() {
		var V = $("div.step2 a.view").get(0);
		if ($.browser.msie) {
			V.click()
		} else {
			var U;
			if (/Mobile/i.test(navigator.userAgent)) {
				U = document.createEvent("HTMLEvents")
			} else {
				U = document.createEvent("MouseEvents")
			}
			U.initEvent("click", true, true);
			V.dispatchEvent(U)
		}
	};
	var D = function() {
		var U = $("div.review");
		var V = U.attr("bindingResend");
		if (V) {
			return
		}
		U.attr("bindingResend", "1");
		U.on("click",
		function(W) {
			if (!W || !$(W.target).attr("action-type")) {
				return
			}
			var Y = $(W.target);
			var X = Y.attr("action-type");
			if ("resend" === X) {
				L(Y)
			} else {
				if ("giveUp" === X) {
					m()
				} else {
					if (X === "preview-big-img") {
						F()
					}
				}
			}
		});
		U = null
	};
	var L = function(V) {
		var V = $("#publish-cblog");
		var U = V.attr("blogid");
		if (!U) {
			u(V, "resend")
		} else {
			o(U)
		}
	};
	var l = function(U) {
		var V = $("div.bbd > div.review", U);
		V.html('<img class="loading" action-type="bigPic" src="http://simg.sinajs.cn/cblog/images/cwb/ico_trans.gif">');
		$("div.links", U).css("visibility", "hidden")
	};
	var T = function(V, Y) {
		var W = i.trim(V.val());
		if (!W) {
			V.val(Y);
			var U = i.byteLength(i.trim(Y));
			var X = Math.max(0, 130 - Math.ceil(U / 2));
			$("div.count > span", V.parent()).html(X)
		}
	};
	var t = function(V) {
		var W = $("div.bbd > div.review", V);
		var U = ['<div class="err">', '<img src="http://simg.sinajs.cn/cblog/images/cwb/ico_trans.gif">', '<div class="txt">', "<strong>生成图片失败</strong>", '<a href="javascript:;" action-type="resend">重新生成</a><a href="javascript:;" action-type="giveUp">放弃</a>', "</div>", "</div>"].join("");
		W.html(U)
	};
	var v = function(aa, W) {
		var Z = $("#cblog-pub-weibo");
		Z.removeClass("dis");
		var X = W.pre_pic_url;
		var Y = W.pic_url;
		var U = W.download_url;
		var ag = $("#cblog-weibo-text");
		var af = $("div.step2 div.bbd");
		var V = $("div.review > img", af);
		V.removeClass("loading");
		V.attr("action-type", "preview-big-img");
		V.css("cursor", "pointer");
		V.get(0).src = X;
		var ac = V.parent();
		if (!$("div.mask", ac).get(0)) {
			ac.append($('<div class="mask" action-type="preview-big-img" style="cursor:pointer;"></div>'))
		}
		var ae = $("div.links > a", af);
		var ad, ab;
		if (ad = ae.get(0)) {
			ad.href = Y;
			ad.target = "_blank"
		}
		if (ab = ae.get(1)) {
			ab.href = U
		}
		$("div.links", aa).css("visibility", "")
	};
	var f = function(W, V) {
		var U = 0;
		V = V || W;
		V.style.cursor = "pointer";
		V.onmousedown = function(Y) {
			var Y = Y || window.event;
			U = Y.clientY - W.offsetTop;
			var X = 0,
			Z = 0;
			X = parseInt($(W).css("top"), 10) || 0;
			document.onmousemove = function(ab) {
				var ab = ab || window.event;
				var aa = ab.clientY - U;
				W.style.top = aa + "px";
				Z = aa;
				return false
			};
			document.onmouseup = function() {
				var aa = $(W).find("a.close");
				if (aa) {
					var ab = parseInt(aa.css("top"), 10) || 0;
					if (Z <= -36) {
						aa.css("top", (ab + X - Z) + "px")
					} else {
						aa.css("top", "-12px")
					}
				}
				document.onmousemove = null;
				document.onmouseup = null
			};
			return false
		}
	};
	var G = function(U) {
		var V = 0;
		U = U || window.event;
		if (U.wheelDelta) {
			V = U.wheelDelta / 120
		} else {
			if (U.detail) {
				V = U.detail / 3
			}
		}
		w(V)
	};
	var w = function(Z) {
		var U = Z * 20,
		X = $(".dragDropLayer"),
		V = X.find("a.close");
		if (X) {
			var Y = parseInt(X.css("top"), 10) || 0;
			if (V) {
				var W = parseInt(V.css("top"), 10) || 0;
				if (Y <= -36) {
					V.css("top", (W + U) + "px")
				} else {
					V.css("top", "-12px")
				}
			}
			X.css("top", (Y - U) + "px")
		}
		V = null;
		X = null
	};
	if (document.addEventListener) {
		document.addEventListener("DOMMouseScroll", G, false)
	} else {
		window.onmousewheel = document.onmousewheel = G
	}
	var E = function(X) {
		var V = $("div.step3ToStep1"),
		Z = document.body.clientHeight,
		Y = document.documentElement.clientHeight,
		U = (Y <= Z && Y != 0) ? true: false,
		W = U ? document.documentElement: document.body;
		if (S) {
			S = false;
			if (V) {
				V.show()
			}
			W.style.overflow = "";
			$("body").css({
				"margin-right": "0px"
			});
			if (X) {
				$(window).scrollTop(X)
			}
		} else {
			S = true;
			if (V) {
				V.hide()
			}
			W.style.overflow = "hidden";
			$("body").css({
				"margin-right": "-17px"
			})
		}
	};
	var a = function(V, W) {
		var X = 0;
		var Z = function(aa) {
			if (aa && ("A00006" === aa.code)) {
				Y && clearInterval(Y);
				var ab = aa.data;
				v(V, ab)
			} else {
				if (X > 10) {
					Y && clearInterval(Y);
					t(V)
				}
			}
		};
		var U = new q();
		var Y = setInterval(function() {
			X++;
			try {
				U.lookupPic(W, Z)
			} catch(aa) {
				Y && clearInterval(Y)
			}
		},
		3000)
	};
	var k = function() {
		var U = $.browser;
		var V = window;
		if (U.msie && 9 > parseInt(U.version, 10)) {
			V = document.documentElement
		}
		return V
	};
	var Q = function(ab, Z, Y) {
		var X;
		if (Z.data) {
			X = Z.data.blogid
		}
		var U = k();
		var aa = $(U).scrollTop();
		var V = $("div.content div.step2");
		V.show();
		$(U).scrollTop(aa);
		l(V);
		var W = $("#cblog-weibo-text");
		T(W, ab);
		D();
		if (X) {
			a(V, X)
		} else {
			t(V)
		}
		P.notify("goToStep", {
			step: 2,
			from: 1
		});
		if ("resend" !== Y) {
			C.goToStep2()
		}
	};
	var r = function(X) {
		var U = $(X);
		var W = U.parent();
		if (!W.attr("isresize")) {
			return
		}
		var Y = W.height();
		var V = X.offsetHeight;
		if (V == Y) {
			return
		}
		W.attr("isresize", "1");
		JSTween.tween(W.get(0), {
			height: {
				start: Y,
				stop: V,
				time: 0,
				duration: 0.5,
				units: "px",
				effect: "easeOut",
				onFrame: function(ab, ac) {
					var aa = arguments;
					var Z = $(document).height();
					$(window).scrollTop(Z)
				},
				onStop: function() {
					W.attr("isresize", "")
				}
			}
		});
		JSTween.play()
	};
	var B = function(U) {
		var W = U.attr("placeholder");
		var V = i.trim(U.val());
		if (!V || W === V) {
			return true
		}
		return false
	};
	var K = function(X) {
		var W = $(X);
		var U = $("#publish-cblog");
		var V = U.get(0);
		if (U.hasClass("loading")) {
			return
		}
		if (B(W)) {
			U.addClass("dis")
		} else {
			U.removeClass("dis")
		}
	};
	var H = function(Z) {
		var X = y();
		if (!X.$isLogin) {
			return
		}
		var W = $("#publish-cblog");
		var U = M();
		if (!U) {
			return
		}
		var V = W.attr("pubtime");
		if (V) {
			V = parseInt(V, 10)
		} else {
			V = 0
		}
		var Y = Math.ceil(new Date().getTime() / 1000);
		var aa = Y - V;
		if (V && 30 > aa) {
			if (2 === Z) {
				g(W)
			}
			z(V);
			return
		}
		W.addClass("loading");
		W.attr("pubtime", "" + Math.ceil((new Date().getTime()) / 1000));
		u(W, "pub", Z)
	};
	var j = function(W, X, Y) {
		var U = W[0].offsetHeight;
		var V = Math.ceil(U / X);
		if (V <= 2) {
			return Y
		} else {
			if (V > 2) {
				Y = Y.substring(0, Y.length - 1);
				W.text(Y);
				return arguments.callee(W, X, Y)
			}
		}
	};
	var b = function(ab) {
		var Z = $("div.jsCalculateTitleHeight span");
		var V = $("#blog-title");
		var ac = V.width();
		var aa = parseInt(V.css("lineHeight"), 10);
		if (!Z.get(0)) {
			var U = $('<div style="position:absolute;top:-1000px;height:1px;" class="jsCalculateTitleHeight"><span>&nbsp;</span></div>');
			$(document.body).append(U);
			U.css("width", ac);
			Z = $("span", U);
			Z.css("fontSize", V.css("fontSize"));
			Z.css("fontFamily", V.css("fontFamily"));
			Z.css("lineHeight", aa + "px");
			Z.css("letterSpacing", V.css("letterSpacing"))
		} else {
			Z.text("&nbsp;")
		}
		var Y = $(ab.target).val();
		Z.text(Y);
		p.setItem("blog-title", Y);
		var W = Z.get(0).offsetHeight;
		var ad = Math.ceil(W / aa);
		if (ad > 2) {
			V.height(2 * aa);
			var X = j(Z, aa, Y);
			V[0].value = X
		} else {
			if (ad === 2) {
				V.height(2 * aa)
			} else {
				V.height(aa)
			}
		}
	};
	$("#blog-title").on("focus",
	function(U) {
		var V = $(U.target);
		V.addClass("focus");
		var X = V.val();
		var W = V.attr("placeholder");
		if (W == i.trim(X)) {
			V.val("")
		}
	}).on("keydown",
	function(V) {
		var U = y();
		if (!U.$isLogin) {
			//V.preventDefault();
			return
		}
		var W = V.keyCode;
		if (13 == W || 100 == W) {
			V.preventDefault();
			return
		}
	}).on("keyup",
	function(V) {
		var W = V.target.value;
		var U = /\r|\n/.test(W);
		if (U) {
			V.target.value = W.replace(/\r|\n/g, "")
		}
		var X = V.keyCode;
		b(V)
	}).on("paste",
	function(U) {
		setTimeout(function() {
			var W = U.target.value;
			var V = /\r|\n/.test(W);
			if (V) {
				U.target.value = W.replace(/\r|\n/g, "")
			}
			b(U)
		},
		15)
	}).on("blur",
	function(U) {
		var V = $(U.target);
		var X = i.trim(V.val());
		var W = V.attr("placeholder");
		if (!X || W == X) {
			V.removeClass("focus")
		}
		if (!X && $.browser.msie) {
			var Y = V.attr("placeholder");
			V.val(Y)
		}
	});
	var s = function(Z, ae, ab) {
		var U = Z.val();
		var ad = parseInt(Z.css("lineHeight"));
		var aa = ab / ae;
		var Y = Math.ceil(U.length * aa);
		var V = U.substring(0, Y);
		var W = U.substring(Y, U.length);
		var ac = W.split(/\b/g);
		for (var X = 0; X < ac.length; X++) {
			Z.val(V);
			line = Z[0].scrollHeight / ad;
			if (line < ab) {
				V += ac[X];
				continue
			} else {
				return
			}
		}
	};
	$("#blog-content").on("focus",
	function(U) {
		var V = $(U.target);
		V.addClass("focus");
		var X = V.val();
		var W = V.attr("placeholder");
		if (W == i.trim(X)) {
			V.val("")
		}
	}).on("keydown",
	function(W) {
		var X = $(W.target);
		var Z = X.val();
		if (20000 < i.byteLength(Z)) {
			Z = i.leftB(Z, 20000);
			X.val(Z)
		}
		var Y = parseInt(X.css("lineHeight"));
		var V = Math.ceil(W.target.scrollHeight / Y);
		var U = y();
		if (!U.$isLogin) {
			//W.preventDefault();
			W.stopPropagation()
		}
	}).on("keyup",
	function(W) {
		var V = $(W.target);
		var Z = V.val();
		if (20000 < i.byteLength(Z)) {
			Z = i.leftB(Z, 20000);
			V.val(Z)
		}
		var Y = parseInt(V.css("lineHeight"));
		var U = Math.ceil(W.target.scrollHeight / Y);
		var X = 700;
		if (U > X) {
			s(V, U, X)
		}
		K(W.target)
	}).on("blur",
	function(U) {
		var V = $(U.target);
		var X = i.trim(V.val());
		var W = V.attr("placeholder");
		if (!X || W == X) {
			V.removeClass("focus")
		}
		if (!X && $.browser.msie) {
			var Y = V.attr("placeholder");
			V.val(Y)
		}
		K(U.target)
	});
	$("#publish-cblog").on("click",
	function(U) {
		H(1)
	});
	$(document).ready(function() {
		$(document.body).append($('<div style="display:none;"><textarea class="jsUserPubText"></textarea></div>'))
	});
	var A;
	var J = $("#blog-title");
	if (A = p.getItem("blog-title")) {
		J.val(A)
	}
	if ($.browser.msie) {
		var d = $("#blog-content");
		var N = d.attr("placeholder");
		A = A || J.attr("placeholder");
		if (!i.trim(d.val())) {
			d.val(N)
		}
		if (!i.trim(J.val())) {
			J.val(A)
		}
	}
	P.on({
		name: "repub-pic",
		callBack: function(U) {
			H(U.from)
		}
	});
	if (!n) {
		$("div.topNav").show();
		var R = false;
		$(".jslogout a:eq(1)").bind("click mouseover",
		function() {
			$(".jslogoutbtn").css("display", "");
			R = true;
			var U = setInterval(function() {
				if (!R) {
					$(".jslogoutbtn").css("display", "none");
					clearInterval(U)
				}
			},
			1000);
			return false
		});
		$(".jslogoutbtn").bind("mouseout",
		function() {
			R = false
		});
		$(".jslogoutbtn").bind("mouseover",
		function() {
			R = true;
			var U = setInterval(function() {
				if (!R) {
					$(".jslogoutbtn").css("display", "none");
					clearInterval(U)
				}
			},
			1000);
			return false
		})
	} else {
		$("div.topNav > div.btn").hide()
	}
	setTimeout(function() {
		var U = p.getItem("blog-content");
		if (U) {
			$("#blog-content").val(U);
			$("#blog-content").focus()
		}
	},
	50)
});
define("http://sjs.sinajs.cn/cblog/js/module/initStep2",
function(k, A, c) {
	var b = k("util");
	var f = k("listener");
	var j = k("goToStep");
	var g = k("loginForm");
	var x = k("checkAuthor");
	var z = "cblog-pub-weibo";
	var n = !!window.name;
	var w = x();
	var r = function(E) {
		var D = $("#cblog-weibo-text");
		var F = b.trim(D.val());
		if (!F) {
			return false
		}
		var C = $("div.links > a.view", $("div.step2")).get(0);
		if ( - 1 === C.href.indexOf("http://")) {
			return false
		}
		if ("continue-pub" != E && B()) {
			return false
		}
		return true
	};
	var d = function() {
		var F = $("#" + z);
		var D = $("div.stip.networkerr");
		if (!D.get(0)) {
			var C = '<div class="stip networkerr" style="display:none;"><div class="con"><span class="cor"></span><p>无法连接到服务器，请检查您的网络设置！<a action-type="closeTired" href="javascript:;" class="close" title="关闭"></a></p></div></div>';
			$(document.body).append(C);
			D = $("div.stip.networkerr");
			D.css("left", 32);
			D.on("click",
			function(I) {
				var G = $(I.target).attr("action-type");
				if ("closeTired" === G) {
					var H = $("div.stip.networkerr");
					H.fadeOut()
				}
			})
		}
		var E = b.getPosition(F.get(0));
		D.css("left", E[0] + F.width() / 2 - D.width() / 2).css("top", E[1] - D.height() - 5);
		D.fadeIn();
		setTimeout(function() {
			D.fadeOut()
		},
		10000)
	};
	var e = function(G, C, E, D, F) {
		$.ajax({
			url: "http://c.blog.sina.com.cn/weibopost.php?rnd=" + Math.random(),
			type: "POST",
			cache: false,
			data: {
				picUrl: C,
				content: G,
				sendblog: D,
				bloguid: F
			},
			dataType: "json",
			success: function(H) {
				s(H, E)
			},
			error: function(I, J) {
				d();
				var H = $("#" + z);
				H.removeClass("loading").removeClass("dis");
				$("span", H).html("发布长微博")
			}
		})
	};
	var s = function(D, H) {
		var I = $("#step3");
		var F, G;
		var E = $("#" + z);
		E.removeClass("loading");
		var C = $("span", E);
		C.html("发布长微博");
		if ("A00006" === D.code) {
			F = '<strong>长微博发表成功！</strong><div class="links"><a action-type="onemoretime" href="javascript:;">再发一条</a><span> </span><a action-type="go" ' + (!n ? 'target="_blank" href="http://weibo.com/"': 'href="javascript:;"') + ">去看看</a></div>";
			if (I.hasClass("sub_false")) {
				I.removeClass("sub_false")
			}
			G = "sub_true";
			I.addClass(G);
			$("div.text", I).html(F)
		} else {
			if ("A00007" === D.code) {
				F = '<div class="info"><strong>长微博发表成功！</strong><div class="links"><a action-type="onemoretime" href="javascript:;">再发一条</a><span> </span><a action-type="go" ' + (!n ? 'target="_blank" href="http://weibo.com/"': 'href="javascript:;"') + '>去看看</a></div></div><p>抱歉，文章没有同步到博客，请您<a href="http://control.blog.sina.com.cn/myblog/htmlsource/blog_notopen.php?uid=' + w.$UID + '&version=7" target="_blank">开通博客</a></p>';
				if (I.hasClass("sub_true")) {
					I.removeClass("sub_true")
				}
				G = "sub_false";
				I.addClass(G);
				$("div.text", I).html(F)
			} else {
				F = '<div class="info"><strong>长微博发表失败！</strong><div class="links"><a href="javascript:;" action-type="giveUp">放弃</a><span></span>';
				if ("A00003" === D.code) {
					F += '<a href="javascript:;" action-type="re-edit">编辑微博</a></div></div><p>相同微博内容请间隔10分钟再发布！如需帮助，请<a href="http://help.weibo.com/self/query?typeid=1034" target="_blank">联系客服</a></p>'
				} else {
					F += '<a href="javascript:;" action-type="repub">重新发表</a></div></div>';
					if (D.message) {
						F = [F, "<p>", D.message, "</p>"].join("")
					}
				}
				if (I.hasClass("sub_true")) {
					I.removeClass("sub_true")
				}
				G = "sub_false";
				I.addClass("sub_false");
				$("div.text", I).html(F)
			}
		}
		$("div.content > div.stepBtm").css("display", "none");
		f.notify("goToStep", {
			step: 1,
			from: 3
		});
		if ("repub" !== H) {
			$("div.stepBtm").hide();
			f.notify("goToStep", {
				step: 3,
				from: 3,
				type: H
			});
			j.goToStep3()
		}
	};
	var u = function(H) {
		var D = $("div.links > a.view", $("div.step2")).get(0);
		var F = $("#" + z);
		if (F.hasClass("loading") || F.hasClass("dis")) {
			return
		}
		F.addClass("loading");
		var C = $("span", F);
		C.html('<em class="img"></em>');
		if (!r(H)) {
			C.html("发布长微博");
			F.removeClass("loading");
			return
		}
		var J = $("#cblog-weibo-text").val();
		var E = D.href;
		var G = $("div.ck input[name='sync_blog']").is(":checked") ? 1 : 0;
		if ($("div.ck input[name='sync_blog']").is(":checked")) {
			G = $("div.ck input[name='sync_blog']").attr("blogsend")
		} else {
			G = 0
		}
		var I = $("div.ck input[name='choose_bloger']").val();
		e(J, E, H, G, I)
	};
	var l = function(C) {};
	var y = function() {
		var D = $("div.edit_tip");
		if (!D.get(0)) {
			var E = '<div class="edit_tip" style="display:none;"><a class="close" href="javascript:;">X</a><p class="tip_text">您已经重新编辑了长微博内容！</p><div class="btn_wrap"><a href="javascript:;" class="edit_btn repub"><span>重新生成图片</span></a><a href="javascript:;" class="edit_btn continue"><span>继续发布</span></a></div></div>';
			D = $(E);
			$(document.body).append(D);
			$("a.close", D).on("click",
			function(G) {
				$("#" + z).removeClass("dis");
				$("div.edit_tip").fadeOut()
			});
			$("a.edit_btn.repub", D).on("click",
			function(G) {
				f.notify("repub-pic", {
					from: 2
				});
				$("div.edit_tip").fadeOut()
			});
			$("a.edit_btn.continue", D).on("click",
			function(G) {
				$("#" + z).removeClass("dis");
				u("continue-pub");
				$("div.edit_tip").fadeOut()
			})
		}
		var C = $("#" + z);
		var F = b.getPosition(C.get(0));
		D.css("left", F[0] + C.width() / 2 - D.width() / 2).css("top", F[1] - D.height() - 5);
		D.fadeIn()
	};
	var B = function() {
		var E = $("textarea.jsUserPubText").val();
		var C = $("#blog-title");
		var F = C.val();
		if (b.trim(F) === C.attr("placeholder")) {
			F = ""
		}
		var D = $("#blog-content").val();
		if (E !== (F + D)) {
			y();
			return true
		}
		return false
	};
	var i = function() {
		var E = $("#blog-title");
		E.val("");
		E.height(parseInt(E.css("line-height")));
		E.removeClass("focus");
		var F = $("#blog-content");
		F.val("");
		F.removeClass("focus");
		var C = F[0].ccminHeight;
		if (C) {
			$("#blog-content").height(C)
		}
		$("#publish-cblog").addClass("dis");
		$("#cblog-weibo-text").val("");
		$("#" + z).addClass("dis");
		if ($.browser.msie) {
			var D = F.attr("placeholder");
			F.val(D);
			var H = E.attr("placeholder");
			E.val(H)
		}
		var G = $("div.jsCalculateTitleHeight span");
		G.text(" ")
	};
	var p = function(F) {
		var C = $("div.step3ToStep1");
		C.css("visibility", "inherit");
		if (n) {
			$("div.topNav > div.btn").hide()
		}
		var E = $.browser;
		g.getNickName(w.$UID,
		function() {});
		if (E.msie && 7 > parseInt(E.version, 10)) {
			C.show();
			if (0 === F) {
				$("div.step3Bottom").hide();
				$(window).scrollTop(0);
				$("div.step2").hide();
				$("div.step3").hide()
			} else {
				$("div.step3Bottom").hide();
				$(window).scrollTop(b.getPosition($("div.step2").get(0))[1]);
				$("div.step3").hide()
			}
		} else {
			C.show();
			var H = document.documentElement;
			if ($.browser.webkit) {
				H = document.body;
				document.documentElement.style.overflow = ""
			} else {
				H.style.overflow = ""
			}
			var G = $(document.body).attr("current-top");
			H.scrollTop = parseInt(G);
			$("div.desc").show();
			$("div.step1").show();
			$("div.step2").show();
			var D = 0;
			if (2 === F) {
				D = b.getPosition($("div.step2").get(0))[1]
			}
			var I = $(H).scrollTop();
			setTimeout(function() {
				JSTween.tween(H, {
					scrollTop: {
						start: I,
						stop: D,
						time: 0,
						duration: 0.6,
						units: "px",
						effect: "linear",
						onStop: function() {
							if (0 === F) {
								$("div.step2").hide()
							}
							$("div.step3").hide();
							$("div.step3Bottom").hide();
							$("div.stepBtm").show()
						}
					}
				});
				JSTween.play()
			},
			50)
		}
	};
	$("div.ck span.ico_f", $("div.step2")).bind("click",
	function(C) {
		if ($(this).hasClass("ico_t")) {
			$(this).removeClass("ico_t");
			$(this).addClass("ico_f");
			$("div.ck input[name='sync_blog']").prop("checked", false)
		} else {
			$(this).addClass("ico_t");
			$("div.ck input[name='sync_blog']").prop("checked", true)
		}
		return false
	});
	var t = function() {
		var C = ['<div class="relBlog jsbindblog" style="top:-380px; left:-1000px;">', '<div class="hd"></div>', '<div class="bd">', "<ul></ul>", "</div>", '<div class="ft"></div>', "</div>"].join("");
		$bindBlogEl = $(C).appendTo(document.body);
		return $bindBlogEl
	};
	var m = function(C) {
		var J = $("a.jsckbloger");
		var D = C.nick;
		J.attr("title", D);
		strLen = b.byteLength(D);
		if (strLen > 10) {
			D = b.leftB(D, 10)
		}
		J.html(D + "<em></em>");
		var K = J.parent();
		$("img", K).attr("src", C.head);
		var G = $('input[name="choose_bloger"]');
		var F = G.val();
		G.val(C.uid);
		var E = $("div.relBlog.jsbindblog");
		$('li[uid="' + F + '"]', E).show();
		$('li[uid="' + C.uid + '"]', E).hide();
		$('li[class="nobd"]', E).removeClass("nobd");
		var I = $("li", E);
		var L = I.length - 1;
		var H = $(I[L]).attr("uid");
		if (H == C.uid) {
			$(I[L - 1]).addClass("nobd")
		} else {
			$(I[L]).addClass("nobd")
		}
	};
	var h = function(F, K) {
		var J = "";
		var D, C, E;
		$("li", F).remove();
		var H = $("ul", F);
		for (var G = 0,
		I = K.length; G < I; G++) {
			D = K[G];
			E = D.nick;
			C = b.byteLength(E);
			if (C > 16) {
				E = b.leftB(E, 14) + "..."
			}
			E = E.replace(/</g, "&lt;").replace(/>/g, "&gt;");
			$blogerEl = $('<li uid="' + D.uid + '"><a href="javascript:void(0);" uid="' + D.uid + '"><img src="' + D.head + '">' + E + "</a></li>");
			$($blogerEl).appendTo(H);
			$("a", $blogerEl).attr("title", D.nick)
		}
	};
	var a = function(C) {
		var D = $("div.relBlog.jsbindblog");
		if (!D[0]) {
			D = t()
		}
		if (C) {
			h(D, C)
		}
		return D
	};
	var o = function(E, D) {
		var C = a(D);
		var F = b.getPosition($("em", E)[0]);
		C.show();
		C.css("left", F[0] - C.width() / 2).css("top", F[1] + 15)
	};
	var q = function() {
		var C = a();
		C.hide();
		var D = $("div.ck a.jsckbloger", $("div.step2"));
		D.removeClass("hover")
	};
	var v = function(D) {
		D.attr("bind-click", "1");
		var E = 0;
		D.on("click",
		function(H) {
			var G = $(".conLayer .content .step2 .ck");
			var F = "hover";
			$this = $(this);
			if ($this.hasClass(F)) {
				$this.removeClass(F);
				q()
			} else {
				$this.addClass(F);
				o(G)
			}
			return false
		});
		$(document.body).on("click",
		function(F) {
			var H = $(F.target);
			var G = $("div.relBlog").get(0);
			if (G) {
				var I = $.contains(G, H[0]);
				if (!I) {
					q()
				}
			}
		});
		var C = a();
		$("ul", C).on("click", "a",
		function(G) {
			var J = $(this);
			var I = J.attr("uid");
			var F = J.text();
			var H = $("img", J).attr("src");
			m({
				uid: I,
				head: H,
				nick: F
			});
			q()
		})
	};
	f.on({
		name: "userinfo-loaded",
		callBack: function(G) {
			var F = $("div.ck", $("div.step2"));
			if (!G || !G.data) {
				return
			}
			var H = G.data;
			if (!H.isOpenBlog && !H.isbind) {
				F.show();
				var D = $("span.ico_f", F);
				D.removeClass("ico_t");
				D.addClass("ico_f");
				$("input[name='sync_blog']", F).prop("checked", true);
				$("input[name='sync_blog']", F).attr("blogsend", "isopen");
				$('span[class="ico_f"]').get(0).nextSibling.nodeValue = "开通并同步到博客"
			} else {
				if (H.isOpenBlog && !H.isbind) {
					F.show();
					var D = $("span.ico_f", F);
					D.removeClass("ico_f");
					D.addClass("ico_t");
					$("input[name='sync_blog']", F).prop("checked", true);
					$("input[name='sync_blog']", F).attr("blogsend", "isbind");
					$('span[class="ico_t"]').get(0).nextSibling.nodeValue = "同步到博客"
				} else {
					if (H.isOpenBlog && H.isbind) {
						F.show();
						var D = $("span.ico_f", F);
						D.removeClass("ico_f");
						D.addClass("ico_t");
						$("input[name='sync_blog']", F).prop("checked", true);
						$("input[name='sync_blog']", F).attr("blogsend", "issend");
						$('span[class="ico_t"]').get(0).nextSibling.nodeValue = "同步到博客"
					} else {
						F.hide()
					}
				}
			}
			if (H.blogUserInfo && 1 < H.blogUserInfo.length) {
				var E = H.blogUserInfo;
				var I = $("div.ck a.jsckbloger", $("div.step2"));
				var C = I.attr("bind-click");
				if (!C) {
					v(I)
				}
				h(a(), E.slice(0, 4));
				m(E[0])
			} else {
				$("div.ck > label:eq(1)", $("div.step2")).hide();
				$("div.relBlog.jsbindblog").hide()
			}
		}
	});
	$("div.textArea", $("#step3")).on("click",
	function(C) {
		var D = C.target;
		if ("A" !== D.nodeName.toUpperCase()) {
			return
		}
		var E = $(D).attr("action-type");
		if ("repub" === E) {
			u("repub")
		} else {
			if ("giveUp" === E) {
				f.notify("goToStep", {
					step: 1,
					from: 3,
					type: E
				});
				p(0)
			} else {
				if ("onemoretime" === E) {
					f.notify("goToStep", {
						step: 1,
						from: 3,
						type: E
					});
					p(0);
					i()
				} else {
					if ("re-edit" === E) {
						f.notify("goToStep", {
							step: 2,
							from: 3,
							type: E
						});
						p(2)
					} else {
						if ("go" === E && n) {
							C.preventDefault();
							C.stopPropagation();
							window.close()
						}
					}
				}
			}
		}
	});
	$("#cblog-weibo-text").on("focus",
	function(C) {
		var D = $(C.target);
		D.addClass("focus")
	}).on("keydown",
	function(C) {
		var F = C.keyCode;
		if (13 == F) {
			C.preventDefault()
		}
		var D = $(C.target);
		var E = D.val();
		if (260 < b.byteLength(E)) {
			E = b.leftB(E, 260);
			D.val(E)
		}
	}).on("keyup",
	function(C) {
		var E = $(C.target);
		var F = E.val();
		var D = b.byteLength(b.trim(F));
		if (260 < D) {
			F = b.leftB(F, 260);
			E.val(F)
		}
		var G = Math.max(0, 130 - Math.ceil(D / 2));
		$("div.shareTo div.count span").html(G)
	}).on("blur",
	function(C) {
		var D = $(C.target);
		var E = D.val();
		if (!b.trim(E)) {
			D.removeClass("focus")
		}
	});
	$("#" + z).on("click",
	function() {
		u("pub")
	})
});
define("http://sjs.sinajs.cn/cblog/js/module/listener",
function(b, a, c) {
	var e = {};
	e.listeners = {};
	var d = {
		add: function(f) {
			if (e.listeners[f]) {
				throw new Error("listener '" + f + "' has registed!")
			}
			e.listeners[f] = []
		},
		remove: function(f) {
			if (e.listeners[f]) {
				e.listeners[f] = null;
				return true
			}
			return false
		},
		notify: function(g, l) {
			var j = e.listeners[g],
			k = null;
			if (!j) {
				return
			}
			for (var h = 0,
			f = j.length; h < f; h++) {
				k = j[h];
				try {
					k.callBack.call(k.scope, l)
				} catch(m) {}
			}
		},
		on: function(k) {
			var g = arguments,
			j = null;
			for (var h = 0,
			f = g.length; h < f; h++) {
				j = g[h];
				if (!this.isListen(j)) {
					if (!e.listeners[j.name]) {
						e.listeners[j.name] = []
					}
					e.listeners[j.name].push(j)
				}
			}
		},
		off: function(k) {
			var g = e.listeners[k.name];
			if (!g) {
				return true
			}
			var j = null;
			for (var h = 0,
			f = g.length; h < f; h++) {
				j = g[h];
				if (j.callBack == k.callBack && j.scope == k.scope) {
					g.splice(h, 1)
				}
			}
		},
		isListen: function(k) {
			var g = e.listeners[k.name];
			var j = null;
			if (!g) {
				return false
			}
			for (var h = 0,
			f = g.length; h < f; h++) {
				j = g[h];
				if (j.callBack == k.callBack && j.scope == k.scope) {
					return true
				}
			}
			return false
		}
	};
	return d
});
define("http://sjs.sinajs.cn/cblog/js/module/listennerTextareaHeight",
function(b, a, d) {
	function c(g, f) {
		f = f || "";
		if (!g.getTextHeightobj) {
			var h = document.createElement("div");
			h.style.display = "none";
			h.style.width = $(g).width() + "px";
			h.style.wordWrap = "break-word";
			h.style.lineHeight = $(g).css("lineHeight");
			h.style.padding = $(g).css("padding");
			h.style.fontSize = $(g).css("fontSize");
			document.body.appendChild(h);
			g.getTextHeightobj = h
		}
		g.getTextHeightobj.innerHTML = g.value.replace("\n", "<br/>") + f;
		return $(h).height()
	}
	function e(f, g) {
		$(f).bind("keydown",
		function(i) {
			var h = c(f, "**");
			if (h != $(f).height()) {
				$(f).height(h)
			} else {
				if (i.which == 13) {
					$(f).height($(f).height() + parseInt($(f).css("lineHeight"), 10))
				}
			}
		})
	}
	e($(".input_content textarea")[0],
	function() {
		alert("你要再输，我可就要换行了 ")
	});
	return c
});
define("http://sjs.sinajs.cn/cblog/js/module/loadPic",
function(c, b, d) {
	var a = function() {};
	$.extend(a.prototype, {
		lookupPic: function(e, f) {
			$.ajax({
				url: "http://c.blog.sina.com.cn/getpicurl.php?rnd=" + Math.random(),
				type: "POST",
				cache: false,
				dataType: "json",
				data: {
					blogid: e
				},
				success: function(g) {
					f(g)
				},
				error: function(g, i, h) {
					f({
						code: i,
						data: {}
					})
				}
			})
		}
	});
	b.LoadPic = a
});
define("http://sjs.sinajs.cn/cblog/js/module/localStorage",
function() {
	var a = {
		storage: null,
		_init: function() {
			var c;
			if (typeof localStorage == "object") {
				c = localStorage
			} else {
				if (typeof globalStorage == "object") {
					c = globalStorage;
					c.setItem = function(d, e) {
						globalStorage[document.domain][d] = e
					};
					c.getItem = function(d) {
						return globalStorage[document.domain][d]
					}
				} else {
					var b = $("#__dataStorage");
					if (!b[0]) {
						b = $('<input id="#__dataStorage" type="hidden"/>');
						b.appendTo(document.body || document.getElementsByTagName("head")[0]);
						b[0].addBehavior("#default#userData");
						b[0].load("BehaviorsInfo")
					}
					if (!b[0].setItem) {
						b[0].setItem = function(d, e) {
							b[0].setAttribute(d, e);
							b[0].save("BehaviorsInfo")
						};
						b[0].getItem = function(d) {
							b[0].load("BehaviorsInfo");
							return b[0].getAttribute(d)
						};
						b[0].removeItem = function(d) {
							b[0].removeAttribute(d);
							b[0].save("BehaviorsInfo")
						}
					}
					c = b[0]
				}
			}
			this.storage = c
		},
		setItem: function(b, c) {
			this.storage.setItem(b, c)
		},
		getItem: function(b) {
			return this.storage.getItem(b)
		},
		removeItem: function(b) {
			return this.storage.removeItem(b)
		}
	};
	a._init();
	return a
});
define("http://sjs.sinajs.cn/cblog/js/module/login",
function(b, a, c) {
	b("customLogin");
	b("favorites");
	var f = b("loginForm");
	var d = b("tray");
	var e = b("listener");
	e.on({
		name: "ssologinjs-loaded",
		callBack: function() {
			if (SSO.getSinaCookie()) {
				f.getNickName(SSO.getSinaCookie()["uid"] || "",
				function() {
					d.reflash()
				})
			} else {
				d.reflash()
			}
			if (!SSO.getSinaCookie()) {
				$(".textArea textarea").bind("click", f.show);
				$(".textArea .btn a").bind("click", f.show)
			}
		}
	})
});
define("http://sjs.sinajs.cn/cblog/js/module/loginForm",
function(require, exports, module) {
	require("jsTween");
	var tray = require("tray");
	var gStep = require("goToStep");
	var listener = require("listener");
	var loginForm = {
		bindEvent: function() {
			$("#btnlogin").bind("click", loginForm.loginFormSubmit);
			$(".form input").bind("keydown",
			function(e) {
				$(".form .stip").css("display", "none");
				if (13 == e.which) {
					loginForm.loginFormSubmit(e);
					return false
				}
			});
			$(".form input").bind("focus",
			function() {
				$(this).addClass("focus");
				$(".form .stip").css("display", "none")
			});
			$(".form input").bind("blur",
			function() {
				$(this).removeClass("focus");
				$(".form .stip").css("display", "none")
			});
			$(".textArea .ico_f").bind("click",
			function(e) {
				if ($(this).hasClass("ico_t")) {
					$(this).removeClass("ico_t");
					$(".form input[name='autologin']").prop("checked", false)
				} else {
					$(this).addClass("ico_t");
					$(".form input[name='autologin']").prop("checked", true)
				}
				return false
			});
			$(".form input[name='username']").bind("blur",
			function() {
				this.value = this.value.replace(/(\s| )/g, "");
				SSO.cPreLogin(this.value,
				function(res) {
					switch (res.showpin) {
					case 2:
						loginForm.showVsnval();
						break;
					case 1:
						loginForm.showDoor();
						break;
					case 0:
						loginForm.hiddentVsnval();
						loginForm.hiddenDoor();
					default:
					}
				})
			});
			$(".form .vcode a").bind("click",
			function() {
				loginForm.showDoorCode();
				return false
			})
		},
		unbindEvent: function() {
			$(".textArea textarea").unbind("click", loginForm.show);
			$(".textArea .btn a").unbind("click", loginForm.show);
			$("#publish-cblog").unbind("click", loginForm.show)
		},
		show: function() {
			tray.hidden();
			var ele = $(".loginLayer").get(0);
			ele.style.display = "";
			height = $(ele).height();
			$(".form input[name='username']").focus();
			$(".form input[name='username']").addClass("focus");
			$(".loginLayer").tween({
				marginTop: {
					start: -485,
					stop: 0,
					time: 0,
					duration: 0.5,
					units: "px",
					effect: "easeOut",
					onStop: function() {
						loginForm.bindEvent();
						$(".textArea textarea").unbind("click", loginForm.show);
						$(".textArea .btn a").unbind("click", loginForm.show);
						$("#publish-cblog").unbind("click", loginForm.show)
					}
				}
			});
			$.play();
			return false
		},
		hidden: function() {
			$(".loginLayer").tween({
				marginTop: {
					start: 0,
					stop: -1 * $(".loginLayer").height(),
					time: 0,
					duration: 0.5,
					units: "px",
					effect: "easeOut",
					onStop: function() {
						tray.reflash();
						if (!SSO.getSinaCookie()) {
							$(".textArea textarea").bind("click", loginForm.show);
							$(".textArea .btn a").bind("click", loginForm.show);
							$("#publish-cblog").bind("click", loginForm.show)
						} else {
							$(".textArea textarea").unbind("click", loginForm.show);
							$(".textArea .btn a").unbind("click", loginForm.show);
							$("#publish-cblog").unbind("click", loginForm.show)
						}
					}
				}
			});
			$.play()
		},
		showmsg: function(msg, ele) {
			ele = ele || $(".form input[name='username']");
			$(".form .stip").css("top", ele.offset()["top"] - $(".form input[name='username']").offset()["top"] + 33 + "px");
			$(".form .stip").css("display", "");
			$(".form .stip p .jserrmsg").html(msg);
			$(".form .stip p a.close").bind("click",
			function() {
				$(".form .stip").css("display", "none")
			})
		},
		loginFormSubmit: function(e) {
			$(".form .stip").css("display", "none");
			if (!$(".form input[name='username']").val()) {
				loginForm.showmsg("请输入邮箱/会员帐号/手机号", $(".form input[name='username']"));
				return false
			}
			if (!$(".form input[name='password']").val()) {
				loginForm.showmsg("请输入密码", $(".form input[name='password']"));
				return false
			}
			if ($(".form input[name='door']").is(":visible") && !$(".form input[name='door']").val()) {
				loginForm.showmsg("请输入验证码", $(".form input[name='door']"));
				return false
			}
			if ($(".form input[name='vsnval']").is(":visible") && !$(".form input[name='vsnval']").val()) {
				loginForm.showmsg("请输入微盾动态码", $(".form input[name='vsnval']"));
				return false
			}
			var callBack = function(result) {
				$(".textArea textarea").unbind("click", loginForm.show);
				$(".textArea .btn a").unbind("click", loginForm.show);
				$("#publish-cblog").unbind("click", loginForm.show);
				if (result.result) {
					loginForm.getNickName(result.userinfo.uid,
					function() {
						loginForm.hidden()
					});
					return false
				}
				switch (result.errno) {
				case "101":
					loginForm.showmsg(result.reason, $(".form input[name='username']"));
					break;
				case "4049":
				case "2070":
					loginForm.showDoor();
					loginForm.showmsg(result.reason, $(".form input[name='door']"));
					break;
				case "5024":
				case "5025":
					loginForm.showVsnval();
					loginForm.showmsg(result.reason, $(".form input[name='vsnval']"));
					break;
				default:
					loginForm.showmsg(result.reason);
					break
				}
			};
			SSO.cLogin({
				username:
				$(".form input[name='username']").val(),
				password: $(".form input[name='password']").val(),
				time: ($(".form input[name='autologin']").prop("checked") ? 30 : 0),
				door: $(".form input[name='door']").val(),
				vsnval: $(".form input[name='vsnval']").val()
			},
			callBack);
			e.preventDefault();
			return false
		},
		showDoorCode: function() {
			$(".form .vcode img")[0].src = SSO.getPinCodeUrl()
		},
		showDoor: function() {
			loginForm.hiddentVsnval();
			$(".form input[name='door']").parent().parent().css("display", "");
			loginForm.showDoorCode()
		},
		hiddenDoor: function() {
			$(".form input[name='door']").parent().parent().css("display", "none")
		},
		showVsnval: function() {
			loginForm.hiddenDoor();
			$(".form input[name='vsnval']").parent().parent().css("display", "")
		},
		hiddentVsnval: function() {
			$(".form input[name='vsnval']").parent().parent().css("display", "none")
		},
		getNickName: function(uid, callback) {
			var url = "http://c.blog.sina.com.cn/getuserinfo.php?rnd=" + Math.random();
			$.post(url, {
				uid: uid
			},
			function(data) {
				data = eval("(" + data + ")");
				if ("A00006" === data.code) {
					SSO.nickname = data.data.name;
					callback(SSO.nickname);
					listener.notify("userinfo-loaded", data)
				} else {
					window.location.href = "http://weibo.com/signup/full_info.php";
					return false
				}
			})
		}
	};
	window.loginForm = loginForm;
	return loginForm
});
define("http://sjs.sinajs.cn/cblog/js/module/memberTpl",
function(j, q, d) {
	var b = j("util");
	var g = j("listener");
	var l = -1;
	var n = function(t) {
		t = t ||
		function() {};
		$.ajax({
			url: "http://c.blog.sina.com.cn/ismember.php?rnd=" + Math.random(),
			type: "POST",
			cache: false,
			data: {},
			dataType: "json",
			success: function(u) {
				if ("A00006" == u.code) {
					l = 1;
					t(!0)
				} else {
					l = 0;
					t(!1)
				}
			},
			error: function(u, v) {
				l = 0;
				t(!1)
			}
		})
	};
	var m = function() {
		if (!l) {
			return ! 1
		}
		var t = b.getCookie("lst");
		if (t && parseInt(t, 10) >= 3) {
			return ! 1
		}
		return ! 0
	};
	$(window).scroll(function() {
		var t = b.getCookie("lst");
		var u = $("div.stip.jsvipletter");
		if (u[0] && (!t || parseInt(t) < 3)) {
			c()
		}
	});
	var c = function() {
		var x = $("div.stip.jsvipletter");
		if (!x[0]) {
			x = f()
		}
		var z = $(".skins > .show > ul > li");
		x.show();
		var u = x[0].offsetWidth;
		var t = z[0].offsetWidth;
		var y = b.getPosition(z.get(0));
		var w = t / 2 + y[0] - u / 2 - 47;
		var v = y[1] - x.height() - 5;
		x.css("left", w).css("top", v)
	};
	var s = function(t) {
		if (t) {
			$('<span class="slt" tpl="1"></span>').appendTo($(".skins > .show > ul > li > a")[0])
		}
		if (!t || !m()) {
			return
		}
		setTimeout(function() {
			c()
		},
		4000)
	};
	n(s);
	g.on({
		name: "userinfo-loaded",
		callBack: function(t) {
			n(k)
		}
	});
	var e = function() {
		var t = '<div class="showimg jsvip" style="left:-5000px;position:absolute;"><div class="loading"></div></div>';
		var u = $(t).appendTo(document.body);
		return u
	};
	var i = function() {
		var u = ['<div style="left:-5000px;" class="stip jsvip">', '<div class="con"><span class="cor"></span>', '<p><span class="jserrmsg"><a class="jsopenvip" href="http://vip.weibo.com/paycenter?from=cwb" target="_blank">开通会员</a>后才可使用该信纸.</span><a title="关闭" class="close" href="javascript:void(0);"></a> </p>', "</div>", "</div>"].join("");
		var t = $(u).appendTo(document.body);
		$("a.close", t).on("click",
		function() {
			t.hide()
		});
		$("a.jsopenvip").on("click",
		function() {
			t.hide();
			a()
		});
		return t
	};
	var f = function() {
		var u = ['<div style="left:-5000px;" class="stip jsvipletter">', '<div class="con"><span class="cor"></span>', '<p><span class="jserrmsg">你可免费使用长微博信纸</span><a title="关闭" class="close" href="javascript:void(0);"></a> </p>', "</div>", "</div>"].join("");
		var t = $(u).appendTo(document.body);
		$("a.close", t).on("click",
		function() {
			var v = b.getCookie("lst");
			if (v) {
				v = parseInt(v, 10);
				if (v < 8) {
					b.setCookie("lst", ++v)
				}
			} else {
				b.setCookie("lst", 1)
			}
			t.remove();
			t.hide()
		});
		return t
	};
	var k = function(t) {
		if (!t) {
			return
		}
		$("span.novip").text("会员特权")
	};
	var r = function() {
		var u = ['<div class="sPop jsvip" style="z-index:1000;left:-1000px; top:-100px;">', '<a href="javascript:void(0);" title="关闭" class="close"></a>', '<div class="hd"></div>', '<div class="bd">', '<div class="con">', "<p>如果您已开通会员，可使用会员信纸</p>", '<div class="btn"><a href="javascript:void(0);" class="jsmenberopened"><span class="dis">已开通</span></a><a href="javascript:void(0);" class="jsmenbernothanks"><span class="dis">不,谢谢</span></a></div>', "</div>", "</div>", '<div class="ft"></div>', "</div>"].join("");
		var t = '<div style="top:0px;left:0px;"></div>';
		var v = $(document.body);
		var x = $(t).appendTo(v);
		x.addClass("jsmask").css("z-index", 999).css("opacity", "0.6").css("width", v.width()).css("height", v.height()).css("position", "absolute").css("background-color", "#ccc");
		var w = $(u).appendTo(v);
		$("a.close", w).on("click",
		function(y) {
			$("div.sPop").hide();
			x.hide()
		});
		$("a.jsmenberopened", w).on("click",
		function(y) {
			$("div.sPop").hide();
			n(k);
			x.hide()
		});
		$("a.jsmenbernothanks", w).on("click",
		function(y) {
			$("div.sPop").hide();
			x.hide()
		});
		w.$mask = x;
		return w
	};
	var o = !1;
	var p, h = !0;
	$(".skins > .show > ul").mouseover(function(t) {
		h = !1
	}).mouseout(function(t) {
		h = !0
	});
	$(".skins > .show > ul > li").mouseover(function(t) {
		var w = t.target;
		var v = $(t.delegateTarget);
		var x = $("div.showimg.jsvip");
		if (!x[0]) {
			x = e()
		}
		p = v.attr("tpl");
		var u = new Image();
		u.src = "http://simg.sinajs.cn/cblog/images/skins/tmp/showimg_0" + p + ".png";
		u.tpl = p;
		setTimeout(function() {
			if (h || p !== u.tpl) {
				return
			}
			if (u.complete) {
				x.html("");
				x.append($(u))
			} else {
				u.onload = function() {
					if (p != u.tpl) {
						return
					}
					x.html("");
					x.append($(u))
				}
			}
			x.show();
			var A = x[0].offsetWidth;
			var z = v[0].offsetWidth;
			var y = x[0].offsetHeight;
			var D = b.getPosition(v[0]);
			var C = z / 2 + D[0] - A / 2 + 4;
			var B = D[1] - y;
			x.css("left", C).css("top", B);
			o = !0
		},
		500)
	}).mouseout(function(t) {
		var u = t.target;
		o = !1;
		setTimeout(function() {
			if (o) {
				return
			}
			var v = $(t.delegateTarget);
			var w = $("div.showimg.jsvip");
			w.hide()
		},
		200)
	}).on("click",
	function(E) {
		var z = $(E.delegateTarget);
		if (l > 0) {
			var C = $("span.slt", z.parent());
			var D = $("a", z);
			var x = C.attr("tpl");
			if (C[0]) {
				C.remove()
			}
			var u = z.attr("tpl");
			if (u !== x) {
				$('<span class="slt" tpl="' + u + '"></span>').appendTo(D)
			}
		} else {
			if (l === 0) {
				var w = $(".skins", $(".step1"));
				var F = $("div.stip.jsvip");
				if (!F.get(0)) {
					F = i()
				}
				F.show();
				var t = F[0].offsetWidth;
				var y = z[0].offsetWidth;
				var B = b.getPosition(z.get(0));
				var v = y / 2 + B[0] - t / 2 - 47;
				var A = B[1] - F.height() - 5;
				F.css("left", v).css("top", A)
			}
		}
	});
	var a = function(u) {
		var t = $("div.sPop.jsvip");
		if (!t[0]) {
			t = r()
		}
		t.show();
		var A = t.width();
		var z = t.height();
		var v = $(window).width();
		var w = $(window).height();
		var y = (v - A) / 2;
		var x = (w - z) / 2 + $(window).scrollTop();
		t.css("left", y).css("top", x);
		$body = $(document.body);
		$mask = $("div.jsmask");
		$mask.css("width", $body.width()).css("height", $body.height());
		$mask.show()
	};
	$("span.novip > a").on("click", a)
});
define("http://sjs.sinajs.cn/cblog/js/module/selfAdaption",
function(d, f, b) {
	var g = d("util");
	var l = d("LS");
	var j, e; (function() {
		function m(t, s) {
			var q = ["letter-spacing", "font-family", "font-weight", "font-size", "font-style", "line-height", "width", "padding-top", "padding-right", "padding-bottom", "padding-left", "margin-top", "margin-right", "margin-bottom", "margin-left", "border"];
			var u, r;
			for (var p = 0,
			n = q.length; p < n; p++) {
				u = q[p];
				r = s.css(q[p]);
				t.css(u, r)
			}
			var o = $.browser;
			if (o.msie && 9 > parseInt(o.version, 10)) {
				t.get(0).style.letterSpacing = "2px"
			}
			$("#blog-content").css("letter-spacing", "2px")
		}
		j = function(p, o, n, q, s) {
			s = s || "";
			if (!e) {
				e = $('<div style="position:absolute;top:-10000px;left:-1000px;height:1px;"><textarea id="__testHeightEl"></textarea></div>');
				m($("textarea", e), p);
				e.appendTo(document.body);
				e.width(p.width());
				$("textarea", e).css("overflow", "hidden").css("resize", "none")
			}
			var r = p.val();
			if (r !== p.attr("placeholder")) {
				l.setItem("blog-content", r)
			}
			r = (q ? q: r) + s;
			var t = $("#__testHeightEl");
			t.val(r);
			setTimeout(function() {
				var u = $("#__testHeightEl").get(0).scrollHeight;
				n(u, p, o)
			},
			25)
		}
	})();
	var a = function() {
		var o = $(window).height();
		var p = $(window).scrollTop();
		var n = $("#blog-content");
		var q = $(".step3ToStep1").parent().offset()["top"];
		var r = $(".step3ToStep1").height() + parseInt(q, 10);
		var s = o - r;
		var m = n.height() + s;
		var t = m % 32;
		m = Math.max(32, m - t);
		if (32 < m) {
			$("div.step3ToStep1 div.clear").css("height", t + "px")
		}
		if (m <= n.height()) {
			return false
		}
		n[0].ccminHeight = m;
		if (!l.getItem("blog-content")) {
			n.tween({
				height: {
					start: n.height(),
					stop: m,
					time: 0,
					duration: 0.3,
					units: "px",
					effect: "easeIn",
					onStop: function() {
						n.css("height", m)
					}
				}
			}).play()
		}
	};
	a();
	var h = null; (function() {
		var m = function(w, x, u) {
			var s = x.height();
			w = Math.max(w, x[0].ccminHeight);
			if (p != w) {
				p = w;
				x.height(w);
				var r = $(document).scrollTop();
				var q = (document.body.scrollHeight || document.documentElement.scrollHeight) - $(window).height();
				var z = k($("#blog-content")[0]);
				if (z.end < x.val().length - 40) {
					return
				}
				if (z) {
					var t = $("div.step2");
					var v = $("div.step1");
					var y;
					if ("none" !== t.css("display")) {
						y = g.getPosition(v[0]);
						q = y[1] + v.height() - $(window).height()
					} else {
						y = g.getPosition(v[0]);
						q = y[1] + v.height()
					}
				}
				$(window).scrollTop(q)
			}
		};
		var p = 32;
		var n = $.browser;
		if (n.msie && 9 > parseInt(n.version, 10)) {
			var o = function() {
				var t = $("#blog-content");
				var r = document.documentElement;
				var q = t[0].ccminHeight;
				if (!q) {
					q = t.height();
					t[0].ccminHeight = q;
					$(".step3ToStep1")[0].ccminHeight = $(".step3ToStep1").height()
				}
				var s = parseInt(t.css("line-height"), 10);
				j(t, r,
				function(v) {
					v = v + s - (v % s);
					var u = Math.max(q, v);
					t.height(u)
				})
			};
			$("#blog-content").on("propertychange",
			function(r) {
				var q = r.originalEvent.propertyName;
				if ("value" !== q) {
					return
				}
				o()
			}).on("paste",
			function() {
				setTimeout(function() {
					o()
				},
				25)
			})
		} else {
			$("#blog-content").bind("focus",
			function() {
				if (!$("#blog-content")[0].ccminHeight) {
					$("#blog-content")[0].ccminHeight = $("#blog-content").height();
					$(".step3ToStep1")[0].ccminHeight = $(".step3ToStep1").height()
				}
				var q = window;
				h = setInterval(function() {
					var r = $("#blog-content");
					j(r, q, m)
				},
				100)
			});
			$("#blog-content").bind("blur",
			function() {
				clearInterval(h)
			})
		}
	})();
	function c(o, n, m) {
		n = n || "";
		var p = o.getTextHeightobj;
		if (!p) {
			p = document.createElement("div");
			$(p).css("display", "none");
			$(p).css("width", $(o).css("width"));
			$(p).css("wordWrap", "break-word");
			$(p).css("lineHeight", $(o).css("lineHeight"));
			$(p).css("letterSpacing", $("#blog-content").css("letterSpacing"));
			$(p).css("fontSize", $(o).css("fontSize"));
			document.body.appendChild(p);
			o.getTextHeightobj = p
		}
		o.getTextHeightobj.innerHTML = (m ? m: o.value).replace("/\r\n/g", "<br/>") + n;
		return $(p).height()
	}
	function k(n) {
		var m = {
			text: "",
			start: 0,
			end: 0
		};
		if (n.setSelectionRange) {
			m.start = n.selectionStart;
			m.end = n.selectionEnd;
			m.text = (m.start != m.end) ? n.value.substring(m.start, m.end) : ""
		} else {
			if (document.selection) {
				var o, p = document.selection.createRange(),
				q = document.body.createTextRange();
				q.moveToElementText(n);
				m.text = p.text;
				m.bookmark = p.getBookmark();
				for (o = 0; q.compareEndPoints("StartToStart", p) < 0 && p.moveStart("character", -1) !== 0; o++) {
					if (n.value.charAt(o) == "\n") {
						o++
					}
				}
				m.start = o;
				m.end = m.text.length + m.start
			}
		}
		return m
	}
	var i = $(window).height();
	$(window).resize(function(m) {
		setTimeout(function() {
			var p = $("#blog-content");
			var q = p.attr("placeholder");
			var r = p.val();
			if (r && r !== q) {
				return
			}
			var n = $(window).height();
			var o = $("div.content").outerHeight();
			if (i !== n && (n > o - 10)) {
				i = n;
				a()
			}
		},
		50)
	})
});
define("http://sjs.sinajs.cn/cblog/js/module/shareToQzone",
function(c, a, e) {
	var g = {
		url: "http://c.blog.sina.com.cn/cblog.php",
		desc: "",
		summary: "无需再受140字的微博限制了，我在使用新浪长微博工具发表图片清晰、字体美观、适合手机阅读的长微博。",
		showcount: "1",
		title: "新浪长微博工具",
		site: "http://c.blog.sina.com.cn",
		pics: config ? config.sharePic: "",
		style: "203",
		width: 98,
		height: 22
	};
	var f = [];
	for (var d in g) {
		f.push(d + "=" + encodeURIComponent(g[d] || ""))
	}
	var b = "http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?" + f.join("&");
	$(".share .qq").bind("click",
	function(h) {
		window.open(b, "cblog_Qzone", "toolbar=0,resizable=1,scrollbars=yes,status=1,width=626,height=436")
	})
});
define("http://sjs.sinajs.cn/cblog/js/module/shareToWeibo",
function(b, a, c) {
	var d = {
		$weiboMsg: "无需再受140字的微博限制了，我在使用新浪长微博工具发表图片清晰、字体美观、适合手机阅读的长微博",
		$sharePic: config ? config.sharePic: "",
		$weiboUrl: "http://c.blog.sina.com.cn/cblog.php",
		$ralateUid: config && config.ralateUid ? config.ralateUid: ""
	};
	var e = function(A, m, k, g, i, h, y, o, n, v) {
		var j = "http://v.t.sina.com.cn/share/share.php?",
		x = o || m.location,
		h = ["appkey=1841970403", "&ralateUid=", "&url=", k(x), "&title=", k(y || m.title), "&source=", k(g), "&sourceUrl=", k(i), "&content=", n || "gb2312", "&pic=", k(h || ""), "&ralateUid=", v].join("");
		function q() {
			if (!window.open([j, h].join(""), "mb", ["toolbar=0,status=0,resizable=1,width=650,height=560,left=", (A.width - 440) / 2, ",top=", (A.height - 430) / 2].join(""))) {
				x.href = [j, h].join("")
			}
		}
		if (/Firefox/.test(navigator.userAgent)) {
			setTimeout(q, 0)
		} else {
			q()
		}
	};
	$(".share .sina").bind("click",
	function(f) {
		e(screen, document, encodeURIComponent, "", "", d.$sharePic, d.$weiboMsg, location.href, "utf-8", d.$ralateUid)
	})
});
define("http://sjs.sinajs.cn/cblog/js/module/suda",
function(b, a, c) {
	if (typeof _S_pSt == "function") {
		return
	}
	$.getScript("http://www.sinaimg.cn/unipro/pub/suda_s_v851c.js",
	function() {
		try {
			_S_pSt("")
		} catch(d) {}
	})
});
define("http://sjs.sinajs.cn/cblog/js/module/tray",
function(c, b, d) {
	c("customLogin");
	c("jsTween");
	var a = !!window.name;
	var e = {
		reflash: function() {
			if (a) {
				$(".topNav .jslogin").css("display", "none");
				return
			}
			$(".topNav .btn").css("display", "");
			var f = c("loginForm");
			if (!SSO.getSinaCookie()) {
				$(".topNav .jslogin").css("display", "");
				$(".topNav .jslogout").css("display", "none");
				$(".topNav .jslogin .sBtn").unbind("click", f.show);
				$(".topNav .jslogin .sBtn").bind("click", f.show)
			} else {
				$(".topNav .jslogout em").html("   你好，" + SSO.nickname);
				$(".topNav .jslogout .func a").die();
				$(".topNav .jslogout .func a").bind("click",
				function() {
					SSO.cLogout(function() {
						location.reload(true)
					});
					return false
				});
				$(".topNav .jslogin").css("display", "none");
				$(".topNav .jslogout").css("display", "")
			}
		},
		show: function() {
			$(".topNav .nickName").css("display", "")
		},
		hidden: function() {
			$(".topNav .nickName").css("display", "none")
		}
	};
	$(".jslogoutlogo").bind("click",
	function() {
		location.reload(true)
	});
	return e
});
define("http://sjs.sinajs.cn/cblog/js/module/util",
function() {
	var a = {
		setCookie: function(c, g, d, j, f, b) {
			var h = [];
			h.push(c + "=" + escape(g));
			if (d) {
				var i = new Date();
				var e = i.getTime() + d * 3600000;
				i.setTime(e);
				h.push("expires=" + i.toGMTString())
			}
			if (j) {
				h.push("path=" + j)
			}
			if (f) {
				h.push("domain=" + f)
			}
			if (b) {
				h.push(b)
			}
			document.cookie = h.join(";")
		},
		getCookie: function(b) {
			b = b.replace(/([\.\[\]\$])/g, "\\$1");
			var d = new RegExp(b + "=([^;]*)?;", "i");
			var e = document.cookie + ";";
			var c = e.match(d);
			if (c) {
				return c[1] || ""
			} else {
				return ""
			}
		},
		parseParam: function(d) {
			var b = d.indexOf("?");
			var c = {};
			if ( - 1 === b) {
				return c
			}
			d.substring(b + 1, d.length);
			d.replace(/([^=&]+)=([^&]*)/g,
			function(e, f, g) {
				c[decodeURIComponent(f)] = g ? decodeURIComponent(g) : "";
				return ""
			});
			return c
		},
		keyValue: function(d, c) {
			var b = d.match(new RegExp("(\\?|&)" + c + "=([^&]*)(&|$)"));
			if (b != null) {
				return b[2]
			}
			return null
		},
		byteLength: function(c) {
			if (typeof c == "undefined") {
				return 0
			}
			var b = c.match(/[^\x00-\x80]/g);
			return (c.length + (!b ? 0 : b.length))
		},
		leftB: function(d, b) {
			if (this.byteLength(d) > b) {
				var c = d.replace(/\*/g, " ").replace(/[^\x00-\xff]/g, "**");
				d = d.slice(0, c.slice(0, b).replace(/\*\*/g, " ").replace(/\*/g, "").length);
				d = d.slice(0, d.length)
			}
			return d
		},
		getPosition: function(f) {
			var c = f.offsetTop;
			var d = f.offsetLeft;
			var g = [d, c],
			b;
			if (f.offsetParent != null) {
				b = arguments.callee(f.offsetParent);
				g[0] += b[0];
				g[1] += b[1]
			}
			return g
		},
		trim: function(b) {
			return b.replace(/^(\u3000|\s|\t)*/gi, "").replace(/(\u3000|\s|\t)*$/gi, "")
		}
	};
	return a
});
var baseUrl = "http://sjs.sinajs.cn/cblog";
seajs.config({
	base: "http://sjs.sinajs.cn/cblog",
	alias: {
		util: baseUrl + "/js/module/util",
		suda: baseUrl + "/js/module/suda",
		LS: baseUrl + "/js/module/localStorage",
		ie6Tips: baseUrl + "/js/module/ie6Tips",
		selfAdaption: baseUrl + "/js/module/selfAdaption",
		shareToQzone: baseUrl + "/js/module/shareToQzone",
		shareToWeibo: baseUrl + "/js/module/shareToWeibo",
		loginPost: baseUrl + "/js/module/loginPost",
		login: baseUrl + "/js/module/login",
		customLogin: baseUrl + "/js/module/customLogin",
		loginForm: baseUrl + "/js/module/loginForm",
		loadPic: baseUrl + "/js/module/loadPic",
		initStep1: baseUrl + "/js/module/initStep1",
		initStep2: baseUrl + "/js/module/initStep2",
		goToStep: baseUrl + "/js/module/goToStep",
		tray: baseUrl + "/js/module/tray",
		favorites: baseUrl + "/js/module/favorites",
		goTop: baseUrl + "/js/module/goTop",
		autoHeight: baseUrl + "/js/module/autoHeight",
		listener: baseUrl + "/js/module/listener",
		checkAuthor: baseUrl + "/js/module/checkAuthor",
		memberTpl: baseUrl + "/js/module/memberTpl"
	},
	charset: "utf-8",
	timeout: 20000,
	debug: false,
	map: [[/^(.*\/module\/.*\.(?:css|js))(?:.*)$/i, "$1?20130221"]]
});
seajs.use("suda");
seajs.use("listener",
function(a) {
	a.add("goToStep")
});
seajs.use("ie6Tips");
seajs.use("selfAdaption");
seajs.use("initStep1");
seajs.use("initStep2");
seajs.use("shareToQzone");
seajs.use("shareToWeibo");
//seajs.use("login");
seajs.use("goTop");
seajs.use("autoHeight");
seajs.use("memberTpl");
if ("undefined" == typeof console) {
	window.console = {
		log: function() {}
	}
};