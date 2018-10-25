$(function() {
    // 类别
    $('#category').picker({
        title: "请选择适用的年级",
        cols: [
            {
              textAlign: 'center',
              values: ['父母课堂', '宝宝试听', '精品课堂']
            },
            {
              textAlign: 'center',
              values: ['幼儿园', '小学', '初中', '高中']
            },
            {
              textAlign: 'center',
              values: ['一年级', '二年级', '三年级', '四年级', '五年级', '六年级']
            },
            {
              textAlign: 'center',
              values: ['数学', '语文', '英语']
            }
        ],
        onChange: function(p, v, dv) {
            console.log(p, v, dv);
        },
        onClose: function(p, v, d) {
            console.log("close");
        }
    });

    // 简介
    popupClose = function(event){
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
    pricePopupClose = function(event){
        $('.price_boot_wrap p').each(function(index, el) {
            if($(this).hasClass('action')){
                $('#price').val($(this).html().split(' ')[1].split('.')[0])
            }
        });
        if ($('.price_boot_wrap input').val() != '') {
            $('#price').val($('.price_boot_wrap input').val())
        }
    }
})
