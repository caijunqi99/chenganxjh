$(function() {
<<<<<<< HEAD
=======
    fz_video= function(video_url){
        alert(video_url);return false;
        video = video_url;
        var _videoSource = document.getElementById("video_true");
        _videoSource.src = video_url;
        _videoSource.poster = video_url+'?vframe/jpg/offset/1';
    }
    fz_video();
>>>>>>> c8d2bd1f2a128bd3848a1233feccd35d611e063d
    // 获取价格
    $.ajax({
        url: api + '/teacherhome/index',
        type: 'POST',
        dataType: 'json',
        success: function(response){
            var template = '';
            price = response['result'][0]['price'];
            for(var i = 0; i < price.length; i++){
                template += '<p>￥ '+ price[i]['pkg_price'] +'</p>'
            }
            $('.http_price_wrap').html(template);
        }
    })

    // 类别
    $('#category').cityPicker({
        title: "请选择类别",
        onChange: function(p, v, dv) {
            $('.isKemu').hide();
        },
        onClose: function(p, v, d) {
            // 判断是否有四级分类
            var tokens = p.value;
            if (tokens[1] != tokens[2]) {
<<<<<<< HEAD
                $('.isKemu').show();
=======
>>>>>>> c8d2bd1f2a128bd3848a1233feccd35d611e063d
                $.ajax({
                    url: api + '/teacherhome/index',
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        // 获取类别
                        var tabs = response['result'][0]['categorize'];
                        for (var i = 0; i < tabs.length; i++) {
                            if (tokens[0] == tabs[i]['gc_id']) {
                                for (var s = 0; s < tabs[i]['childTwo'].length; s++) {
                                    if (tokens[1] == tabs[i]['childTwo'][s]['gc_id']) {
                                        var Two = tabs[i]['childTwo'][s]['childThree']
                                        for (var x = 0; x < Two.length; x++) {
                                            if (tokens[2] == Two[x]['gc_id']) {
                                                var Three = Two[x]['childFour'];
                                                if(Three){
<<<<<<< HEAD
=======
                                                    $('.isKemu').show();
>>>>>>> c8d2bd1f2a128bd3848a1233feccd35d611e063d
                                                    var params = {
                                                        textAlign: 'center',
                                                        values: []
                                                    }
                                                    var data = {};
                                                    for (var r = 0; r < Three.length; r++) {
                                                        params['values'].push(Three[r]['gc_name']);
                                                        data[Three[r]['gc_name']] = Three[r]['gc_id'];
                                                    }
                                                    prickKemu(params, data);
                                                }else {
                                                    $('.isKemu').hide();
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                })
            } else {
                $('.isKemu').hide();
            }
        }
    });

    // 简介
    popupClose = function(event) {
        $('#aboutValue').val($('#sizeStatistics').val())
    }

    // 遍历价格添加颜色
    setInterval(function(){
        $('.price_boot_wrap p').each(function(index, el) {
            $(this).click(function(event) {
                $('.price_boot_wrap p').removeClass('action')
                $(this).addClass('action');
            });
        });
    }, 20)

    $('.price_boot_wrap input').focus(function(event) {
        $('.price_boot_wrap p').removeClass('action')
    });

    // 选择价格
    pricePopupClose = function(event) {
        $('.price_boot_wrap p').each(function(index, el) {
            if ($(this).hasClass('action')) {
                $('#price').val($(this).html().split(' ')[1])
            }
        });
        if ($('.price_boot_wrap input').val() != '') {
            $('#price').val($('.price_boot_wrap input').val())
        }
    }

    function prickKemu(value, data) {
        var arr = [];
        arr.push(value)
        $('#kemu').picker({
            title: "请选择科目",
            cols: arr,
            onClose: function() {
                $('#kemu').attr('data-id', data[$('#kemu').val()])
            }
        })
    }


    // 确认上传和取消
    isUpload = function(isTrue) {
        if (isTrue == 0) {
            var video_img = video+'?vframe/jpg/offset/1';
            if (video == '') {
                $.toast("获取上传视频失败，请重新上传",'forbidden');
                return false;
            } else if ($('#bang_name').val() == '') {
                $.toast("请先填写课程标题",'forbidden');
                return false;
            } else if ($('#aboutValue').val() == '') {
                $.toast("请先填写课程简介",'forbidden');
                return false;
            } else if ($('#zuozhe').val() == '') {
                $.toast("请先填写作者的姓名",'forbidden');
                return false;
            } else if ($('#category').val() == '') {
                $.toast("请先选择类别",'forbidden');
                return false;
            } else if ($('#price').val() == '') {
                $.toast("请先填写价格",'forbidden');
                return false;
            } else if (!$('#kemu').is(':hidden')) {
                if ($('#kemu').val() == '') {
                    $.toast("请先选择科目",'forbidden');
                    return false;
                }
            }
            var params = {
                key: user_token,
                member_id: user_member_id,
                title: $('#bang_name').val(),
                profile: $('#aboutValue').val(),
                price: $('#price').val(),
                author: $('#zuozhe').val(),
<<<<<<< HEAD
                url: video_url
=======
                url: video,
                pic: video_img
>>>>>>> c8d2bd1f2a128bd3848a1233feccd35d611e063d
            }
            var types = $('#category').get(0).dataset.codes;
            if (types.split(',')[1] == types.split(',')[2]) {
                params['type'] = types.split(',')[0];
                params['type2'] = types.split(',')[1];
            } else {
                params['type'] = types.split(',')[0];
                params['type2'] = types.split(',')[1];
                params['type3'] = types.split(',')[2];
            }
            // 上传课件
            $.ajax({
                url: api + '/teacherupload/index',
                type: 'POST',
                dataType: 'json',
                data: params,
                success: function(response){
                    if(response['code'] == 200){
<<<<<<< HEAD
                        $.toast("上传成功");
                        // historyback();
                    }else {
                        console.log(response['message']);
=======
                        $.toast("上传成功",'',function(){
                            location.href=http_url+'app/teachchild/myupload.html';
                        });
                    }else {
                        $.toast(response['message'],'forbidden');
>>>>>>> c8d2bd1f2a128bd3848a1233feccd35d611e063d
                    }
                }
            })
        } else {
<<<<<<< HEAD
            // historyback();
=======
            $.confirm('确定要删除退出吗？','提示',function(){
                    historyback();
            },function(){

            })
>>>>>>> c8d2bd1f2a128bd3848a1233feccd35d611e063d
        }
    }
})
