$(function() {

    var myPlayer;
    var Time = 1;
    var v_url = '';
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
            v_url = response.result[0]['data']['t_url'];
            $('#related').html(HTML(response['result']));
            // myPlayer = videojs('my-player');
            if(user_token != '' && user_token != null){
                if(response.result[0]['data'].t_price == 0){
                    $('#video_screen').hide();
                }else{
                    $.ajax({
                        type:'POST',
                        url:api+'/Teacherdecide/decide.html',
                        data:{
                            key:user_token,
                            member_id:user_member_id,
                            video_id:response.result[0]['data'].t_id
                        },
                        dataType: "json",
                        success: function(res){
                            if(res['code'] == 200){
                                if(res.result[0]['collect'] != 0){
                                    $('#qxCollect').show();
                                    $('#collect').hide();
                                }
                                if(res.result[0]['buy'] != 0){
                                    $('#video_screen').hide();
                                }
                            }else if(res['code'] == 400){
                                $.toast('登陆失效，请重新登录','forbidden',function(){
                                    layout();
                                });
                            }else{
                                $.toast('系统错误','forbidden');
                            }
                        }
                    })
                }

            }
        }
    })

    // setTimeout(function() {
    //     myPlayer.on('timeupdate', function(event) {
    //         Time++;
    //         if (Time == 2) {
    //             addHistory();
    //         }
    //     })
    // }, 1000)

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

