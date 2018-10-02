$(function () {
    // foot定位底部
    footerfixed();
    $(window).resize(function () {
        footerfixed();
    });

    //启用fastclick
    FastClick.attach(document.body);

})

function doLogin(){
    var appType = getCookie('appType');
    //'android', 'wap', 'wechat', 'ios', 'windows', 'jswechat'
    if (appType == 'Android') {alert('Android - 登陆方法');}
    if (appType == 'IOS') {alert('IOS - 登陆方法');}
}

// foot定位底部函数
function footerfixed() {
    $("#footer").css("position", "static");
    var bodyheitht = $(document.body).height();
    var windowheight = $(window).height();
    if (bodyheitht < windowheight) {
        $("#footer").css({
            "position": "fixed",
            "bottom": 0
        });
    } else {
        $("#footer").css({
            "position": "static",
            "bottom": "atuo"
        });
    }
}
function getQueryString(e) {
    var t = new RegExp("(^|&)" + e + "=([^&]*)(&|$)");
    var a = window.location.search.substr(1).match(t);
    if (a != null) return a[2];
    return ""
}
function addCookie(e, t, a) {
    var n = e + "=" + escape(t) + "; path=/";
    if (a > 0) {
        var r = new Date;
        r.setTime(r.getTime() + a * 3600 * 1e3);
        n = n + ";expires=" + r.toGMTString()
    }
    document.cookie = n
}
function setCookie(name, value, days) {
    var exp = new Date();
    exp.setTime(exp.getTime() + days * 24 * 60 * 60 * 1000);
    var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
    document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString();
}
function getCookie(e) {
    var t = document.cookie;
    var a = t.split("; ");
    for (var n = 0; n < a.length; n++) {
        var r = a[n].split("=");
        if (r[0] == e) return unescape(r[1])
    }
    return null
}
function delCookie(e) {
    var t = new Date;
    t.setTime(t.getTime() - 1);
    var a = getCookie(e);
    if (a != null) document.cookie = e + "=" + a + "; path=/;expires=" + t.toGMTString()
}
function checkLogin(e) {
    if (e == 0) {
        location.href = WapSiteUrl + "/tmpl/member/login.html";
        return false
    } else {
        return true
    }
}
function contains(e, t) {
    var a = e.length;
    while (a--) {
        if (e[a] === t) {
            return true
        }
    }
    return false
}
function buildUrl(e, t) {
    switch (e) {
    case "keyword":
        return WapSiteUrl + "/tmpl/product_list.html?keyword=" + encodeURIComponent(t);
    case "special":
        return WapSiteUrl + "/special.html?special_id=" + t;
    case "goods":
        return WapSiteUrl + "/tmpl/product_detail.html?goods_id=" + t;
    case "url":
        return t
    }
    return WapSiteUrl
}

function getSearchName() {
    var e = decodeURIComponent(getQueryString("keyword"));
    if (e == "") {
        if (getCookie("deft_key_value") == null) {
            $.getJSON(ApiUrl + "/Index/search_hot_info.html",
            function(e) {
                var t = e.result.hot_info;
                if (typeof t.name != "undefined") {
                    $("#keyword").attr("placeholder", t.name);
                    $("#keyword").html(t.name);
                    addCookie("deft_key_name", t.name, 1);
                    addCookie("deft_key_value", t.value, 1)
                } else {
                    addCookie("deft_key_name", "", 1);
                    addCookie("deft_key_value", "", 1)
                }
            })
        } else {
            $("#keyword").attr("placeholder", getCookie("deft_key_name"));
            $("#keyword").html(getCookie("deft_key_name"))
        }
    }
}

function updateCookieCart(e) {
    var t = decodeURIComponent(getCookie("goods_cart"));
    if (t) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/Membercart/cart_batchadd.html",
            data: {
                key: e,
                cartlist: t
            },
            dataType: "json",
            async: false
        });
        delCookie("goods_cart")
    }
}
function getCartCount(e, t) {
    var a = 0;
    delCookie("cart_count")
    if (getCookie("key") !== null && getCookie("cart_count") === null) {
        var e = getCookie("key");
        $.ajax({
            type: "post",
            url: ApiUrl + "/Membercart/cart_count.html",
            data: {
                key: e
            },
            dataType: "json",
            async: false,
            success: function(e) {
                if (typeof e.result.cart_count != "undefined") {
                    addCookie("cart_count", e.result.cart_count, t);
                    a = e.result.cart_count
                }
            }
        })
    } else {
        a = getCookie("cart_count")
    }
    if (a > 0 && $(".nctouch-nav-menu").has(".cart").length > 0) {
        $(".nctouch-nav-menu").has(".cart").find(".cart").parents("li").find("sup").show();
        $("#header-nav").find("sup").show()
    }
}

/**
 * 购物车数量
 * @return {[type]} [description]
 */
function getChatCount() {

}

function loadSeccode() {
    $("#codeimage").attr("src", ApiUrl + "/Seccode/makecode.html")
}

/**
 * 收藏
 * @param  {[type]} e [description]
 * @return {[type]}   [description]
 */
function favoriteStore(e) {
    
}
/**
 * 删除收藏
 * @param  {[type]} e [description]
 * @return {[type]}   [description]
 */
function dropFavoriteStore(e) {
    
}
/**
 * 添加收藏商品
 * @param  {[type]} e [description]
 * @return {[type]}   [description]
 */
function favoriteGoods(e) {
    
}
/**
 * 删除收藏商品
 * @param  {[type]} e [description]
 * @return {[type]}   [description]
 */
function dropFavoriteGoods(e) {
    
}

/**
 * 动态加载css
 * @param  {[type]} e [description]
 * @return {[type]}   [description]
 */
function loadCss(e) {
    var t = document.createElement("link");
    t.setAttribute("type", "text/css");
    t.setAttribute("href", e);
    t.setAttribute("href", e);
    t.setAttribute("rel", "stylesheet");
    css_id = document.getElementById("auto_css_id");
    if (css_id) {
        document.getElementsByTagName("head")[0].removeChild(css_id)
    }
    document.getElementsByTagName("head")[0].appendChild(t)
}
/**
 * 动态加载js
 * @param  {[type]} e [description]
 * @return {[type]}   [description]
 */
function loadJs(e) {
    var t = document.createElement("script");
    t.setAttribute("type", "text/javascript");
    t.setAttribute("src", e);
    t.setAttribute("id", "auto_script_id");
    script_id = document.getElementById("auto_script_id");
    if (script_id) {
        document.getElementsByTagName("head")[0].removeChild(script_id)
    }
    document.getElementsByTagName("head")[0].appendChild(t)
}
