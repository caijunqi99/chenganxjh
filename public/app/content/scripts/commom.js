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
$(function () {
    // foot定位底部
    footerfixed();
    $(window).resize(function () {
        footerfixed();
    });

    //启用fastclick
    FastClick.attach(document.body);

})