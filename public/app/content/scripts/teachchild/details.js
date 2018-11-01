$(function() {

    var myPlayer;
    var Time = 1;
    // 获取详情
    $.ajax({
        url: api + '/Teacherdetail/detail',
        type: 'POST',
        dataType: 'json',
        data: {
            key: user_token,
            t_id: GetPar("id")
        },
        success: function(response) {
            var _videoSource = document.getElementById("video_true");
            _videoSource.src = response.result[0]['data']['t_url'];
            _videoSource.poster = response.result[0]['data']['t_videoimg'];
            // $('#video_image').attr('src',response.result[0]['data']['t_videoimg']);
            $('#related').html(HTML(response['result']));

            /*var videoObject = {
                container: '#video',//“#”代表容器的ID，“.”或“”代表容器的class
                variable: 'player',//该属性必需设置，值等于下面的new chplayer()的对象
                autoplay: true, //是否自动播放
                video:response.result[0]['data']['t_url']//视频地址
            };
            var player = new ckplayer(videoObject);*/

           /* var vID = "c1"; //vID
            var vWidth = "750"; //宽度设定，配合CSS实现
            var vHeight = "400"; //高度设定，配合CSS实现
            var vFile = "CuSunV4set.xml"; //配置文件地址:支持五种广告设定
            var vPlayer = "player.swf?v=4.0"; //播放器文件地址
            var vPic = "pic/pic01.jpg"; //视频缩略图
            var vAutoPlay = "none"; //是否自动播放
            var vEndTime = 0; //预览时间(秒数),默认为0
            var vLogoPath = "images/logo.png"; //Logo地址
            var vPlayMod = 0; //播放模式优先级,默认=0,html5优先=1,flash优先=2
            var vMp4url = response.result[0]['data']['t_url']; //视频文件地址推荐用mp4文件(h264编码)*/

        }
    })

    // 添加观看记录
    function addHistory() {
        $.ajax({
            url: api + '/teacherhistory/addhistory',
            type: 'POST',
            dataType: 'json',
            data: {
                key: user_token,
                member_id: user_member_id,
                tid: GetPar("id")
            },
            success: function(response) {
                if (response['code'] == 200) {
                    return true;
                } else {
                    return false;
                };
            }
        })
    }


    // 封装获取ID方法
    function GetPar(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return decodeURIComponent(r[2]);
        return null;
    }

    // 收藏视频
    collectVideo = function(event) {
        if(!user_token){
         $.toast('请前往登陆','forbidden');return false;
        }
        $.ajax({
            url: api + '/teachercollect/collect',
            type: 'POST',
            dataType: 'json',
            data: {
                key: user_token,
                member_id: user_member_id,
                tid: $('#my-player').attr('data-id')
            },
            success: function(response) {
                if (response['code'] == 200) {
                    $('#qxCollect').show();
                    $('#collect').hide();
                    $.toast('收藏成功');
                } else {
                    $.toast(response['message'],'forbidden');
                }
            }
        })
    }

    qxCollectVideo = function(event) {
        if(!user_token){
            $.toast('请前往登陆','forbidden');return false;
        }
        $.ajax({
            url: api + '/teachercollect/collect',
            type: 'POST',
            dataType: 'json',
            data: {
                key: user_token,
                member_id: user_member_id,
                tid: $('#my-player').attr('data-id')
            },
            success: function(response) {
                if (response['code'] == 200) {
                    $('#qxCollect').hide();
                    $('#collect').show();
                    $.toast('取消收藏成功');
                } else {
                    $.toast(response['message'],'forbidden');
                }
            }
        })
    }
    // HTML模板
    function HTML(data) {
        var template = '';
        var List = '';
        var price = '';
        for (var i = 0; i < data[1]['lists'].length; i++) {
            List += '<li class="related_list_li clearBoth">' +
                '<a href="details.html?id='+data[1]['lists'][i]['t_id']+'" >' +
                '<div class="img_wrap float_left">' +
                '<img src="' + data[1]['lists'][i]['t_videoimg'] + '" alt="' + data[1]['lists'][i]['t_url'] + '">' +
                '</div>' +
                '<div class="content_wrap float_left">' +
                '<p class="title">' + data[1]['lists'][i]['t_title'] + '</p>' +
                '<p class="content">' + data[1]['lists'][i]['t_profile'] + '</p>' +
                '</div>' +
                '</a></li>';
        }
        if (data[0]['data']['t_price'] == 0) {
            price = '免费观看';
        } else {
            price = '￥ ' + data[0]['data']['t_price'] + ' 购买'
        }
        template = '<div class="related_wrap body_content" id="my-player" data-id="' + data[0]['data']['t_id'] + '">' +
            '<div class="related">' +
            '<div class="related1">' +
            '<h2 class="title">' + data[0]['data']['t_title'] + '</h2>' +
            '<p class="content">' + data[0]['data']['t_profile'] + '</p>' +
            '</div>' +
            '<div class="related2">' +
            '<button type="button" onclick="pay(' + data[0]['data']['t_id'] + ');">' + price + '</button>' +
            '</div>' +
            '</div>' +
            '<div class="related_list">' +
            '<p class="title_more">更多课程</p>' +
            '<ul>' + List + '</ul>' +
            '</div>' +
            '</div>';
        return template;
    }

});
//跳转购买页面
function pay(t_id){
    if(user_token){
        location.href=http_url+"app/user/pay.html?t_id="+t_id;
    }else{
        $.toast('请前往登陆','forbidden');
    }
}

