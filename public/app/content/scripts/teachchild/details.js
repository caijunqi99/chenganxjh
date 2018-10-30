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
            $('#related').html(HTML(response['result']))
            // myPlayer = videojs('my-player');
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
        $.ajax({
            url: api + '/teachercollect/collect',
            type: 'POST',
            dataType: 'json',
            data: {
                key: user_token,
                member_id: user_member_id,
                tid: $('#my-player').get(0).dataset.id
            },
            success: function(response) {
                if (response['code'] == 200) {
                    $.toast(response['result'][0]['data']);
                } else {
                    console.log(response['message']);
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
                '<a href="details.html?id=' + data[1]['lists'][i]['t_id'] + '" >' +
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
        //  '<div class="video_wrap">'+
        // 	'<video'+
        // 	' id="my-player"'+
        // 	' class="video-js"'+
        // 	' data-id="'+ data[0]['data']['t_id'] +'"'+
        // 	' controls'+
        // 	' preload="auto"'+
        // 	// ' src="'+ data[0]['data']['t_url'] +'"'+
        // 	' poster="'+ data[0]['data']['t_videoimg'] +'">'+
        // 	'<source src="'+ data[0]['data']['t_url'] +'" type="video/mp4" />'+
        // '</div>'+
        template = '<div class="related_wrap body_content">' +
            '<div class="related">' +
            '<div class="related1">' +
            '<h2 class="title">' + data[0]['data']['t_title'] + '</h2>' +
            '<p class="content">' + data[0]['data']['t_profile'] + '</p>' +
            '</div>' +
            '<div class="related2">' +
            '<button type="button" name="button">' + price + '</button>' +
            '</div>' +
            '</div>' +
            '<div class="related_list">' +
            '<p class="title_more">更多课程</p>' +
            '<ul>' + List + '</ul>' +
            '</div>' +
            '</div>';
        return template;
    }

})