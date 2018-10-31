$(function() {
    // 类别
    $('#category').cityPicker({
        title: "请选择类别",
        onChange: function(p, v, dv) {},
        onClose: function(p, v, d) {
            // 判断是否有四级分类
            var tokens = p.value;
            if (tokens.length == 3) {
                $('.isKemu').show();
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
    $('.price_boot_wrap p').each(function(index, el) {
        $(this).click(function(event) {
            $('.price_boot_wrap p').removeClass('action')
            $(this).addClass('action');
        });
    });
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
            if ($('#bang_name').val() == '') {
                $.alert("请先填写课程标题");
                return false;
            } else if ($('#aboutValue').val() == '') {
                $.alert("请先填写课程简介");
                return false;
            } else if ($('#zuozhe').val() == '') {
                $.alert("请先填写作者的姓名");
                return false;
            } else if ($('#category').val() == '') {
                $.alert("请先选择类别");
                return false;
            } else if ($('#price').val() == '') {
                $.alert("请先填写价格");
                return false;
            } else if (!$('#kemu').is(':hidden')) {
                if ($('#kemu').val() == '') {
                    $.alert("请先选择科目");
                    return false;
                }
            } else {
                var params = {
                    key: user_token,
                    member_id: user_member_id,
                    title: $('#bang_name').val(),
                    profile: $('#aboutValue').val(),
                    price: $('#price').val(),
                    author: $('#zuozhe').val(),
                }
                var types = $('#category').get(0).dataset.codes;

                if (types[1] === types[2]) {
                    params['type'] = types[0];
                    params['type2'] = types[1];
                } else {
                    params['type'] = types[0];
                    params['type2'] = types[1];
                    params['type3'] = types[2];
                }
                if (!$('kemu').is(':hidden')) {
                    params['type4'] = $('#kemu').get(0).dataset.id;
                }
                // 上传课件
                // $.ajax({
                //     url: api + '/teacherupload/index',
                //     type: 'POST',
                //     dataType: 'json',
                //     data: params,
                //     success: function(response){
                //         if(response['code'] == 200){
                //
                //         }else {
                //             console.log(response['message']);
                //         }
                //     }
                // })
            }
        } else {
            window.history.back(-1);
        }
    }
})