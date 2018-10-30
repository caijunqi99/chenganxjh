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
    // FastClick.attach(document.body);


})

//返回函数
function historyback() {
    if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) { //判断iPhone|iPad|iPod|iOS
        // window.webkit.messageHandlers.backClick();
        // window.webkit.messageHandlers.backClick.postMessage();
        window.webkit.messageHandlers.backClick.postMessage('back!!');
    } else if (/(Android)/i.test(navigator.userAgent)) { //判断Android
        Android.backToApp();
    } else { //pc
        window.history.back(-1);
    };
}

function layout(){
    if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) { //判断iPhone|iPad|iPod|iOS
        window.webkit.messageHandlers.outClick.postMessage('back!!');
    } else if (/(Android)/i.test(navigator.userAgent)) { //判断Android
        Android.outClick();
    } else { //pc
    };
}

//赛心情发生

function send_mood(){
    if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) { //判断iPhone|iPad|iPod|iOS
        window.webkit.messageHandlers.sendClick.postMessage('back!!');
    } else if (/(Android)/i.test(navigator.userAgent)) { //判断Android
        Android.sendClick();
    } else { //pc
    };
}

function GetQueryString(name)
{
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if(r!=null)return  unescape(r[2]); return null;
}

//刷新页面

function reLoad(){
    location.reload()
}

//教孩视频详情
function videoClick(id){
    var url = http_url+"app/teachchild/details.html?id="+id;
    if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) { //判断iPhone|iPad|iPod|iOS
        window.webkit.messageHandlers.videoClick.postMessage(url);
    } else if (/(Android)/i.test(navigator.userAgent)) { //判断Android
        Android.videoClick(url);
    } else { //pc
    };
}