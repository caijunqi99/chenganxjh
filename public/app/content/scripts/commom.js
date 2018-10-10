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
$(function() {
    // foot定位底部
    footerfixed();
    $(window).resize(function() {
        footerfixed();
    });

    //启用fastclick
    FastClick.attach(document.body);


    //获取cookie中存储的token,member_id
    var user_token = $.cookie('token');
    var user_member_id = $.cookie('member_id');
})

//返回函数
function historyback() {
    if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) { //判断iPhone|iPad|iPod|iOS
        window.webkit.messageHandlers.backClick();
    } else if (/(Android)/i.test(navigator.userAgent)) { //判断Android
        Android.backToApp();
    } else { //pc
        window.history.back(-1);
    };
}