<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:92:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\groupbuy\slider.html";i:1514528329;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>DsMall(php)B2B2C商城系统后台</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="<?php echo \think\Config::get('url_domain_root'); ?>static/admin/css/admin.css">
        <link rel="stylesheet" href="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/js/jquery-ui/jquery-ui.min.css">
        <script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/jquery-2.1.4.min.js"></script>
        <script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/jquery.validate.min.js"></script>
        <script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/jquery.cookie.js"></script>
        <script src="<?php echo \think\Config::get('url_domain_root'); ?>static/admin/js/admin.js"></script>
        <script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/js/jquery-ui/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/font-awesome/css/font-awesome.min.css">
        <script type="text/javascript">
            var SITE_URL = "<?php echo \think\Config::get('url_domain_root'); ?>";
            var ADMIN_URL = "<?php echo \think\Config::get('url_domain_root'); ?>index.php/Admin/";
        </script>
    </head>
    <body>
        
    

        


<div id="append_parent"></div>
<div id="ajaxwaitid"></div>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>抢购管理</h3>
                <h5></h5>
            </div>
            <?php if($admin_item): ?>
<ul class="tab-base ds-row">
    <?php if(is_array($admin_item) || $admin_item instanceof \think\Collection || $admin_item instanceof \think\Paginator): if( count($admin_item)==0 ) : echo "" ;else: foreach($admin_item as $key=>$item): ?>
    <li><a href="<?php echo $item['url']; ?>" <?php if($item['name'] == $curitem): ?>class="current"<?php endif; ?>><span><?php echo $item['text']; ?></span></a></li>
    <?php endforeach; endif; else: echo "" ;endif; ?>
</ul>
<?php endif; ?>
        </div>
    </div>
    
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom">
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示" class="arrow"></span>
        </div>
        <ul>
            <li>该组幻灯片滚动图片应用于抢购聚合页上部使用，最多可上传4张图片。</li>
            <li>图片要求使用宽度为970像素，高度为300像素jpg/gif/png格式的图片。</li>
            <li>上传图片后请添加格式为“http://网址...”链接地址，设定后将在显示页面中点击幻灯片将以另打开窗口的形式跳转到指定网址。</li>
            <li>清空操作将删除聚合页上的滚动图片，请注意操作</li>
        </ul>
    </div>
    
    <form id="live_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="form_submit" value="ok" />
        <table class="ds-default-table">
            <tbody>
            <tr>
                <td class="required"><label>滚动图片1:</label></td>
                <td><label>链接地址:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/preview.png">
            <div class="type-file-preview"><img src="<?php echo \think\Config::get('url_domain_root'); ?>uploads/admin/groupbuy/<?php echo $list_setting['live_pic1']; ?>"></div>
            </span><span class="type-file-box">
            <input type='text' name='textfield1' id='textfield1' class='type-file-text' />
            <input type='button' name='button' id='button' value='' class='type-file-button' />
            <input name="live_pic1" type="file" class="type-file-file" id="live_pic1" size="30" hidefocus="true" />
            </span></td>
                <td class="vatop"><input type="text" name="live_link1" class="w200" value="<?php echo $list_setting['live_link1']; ?>"></td>
            </tr>
            <tr>
                <td class="required"><label>滚动图片2:</label></td>
                <td><label>链接地址:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/preview.png">
            <div class="type-file-preview"><img src="<?php echo \think\Config::get('url_domain_root'); ?>uploads/admin/groupbuy/<?php echo $list_setting['live_pic2']; ?>"></div>
            </span><span class="type-file-box">
            <input type='text' name='textfield2' id='textfield2' class='type-file-text' />
            <input type='button' name='button' id='button' value='' class='type-file-button' />
            <input name="live_pic2" type="file" class="type-file-file" id="live_pic2" size="30" hidefocus="true" />
            </span></td>
                <td class="vatop tips"><input type="text" name="live_link2" class="w200" value="<?php echo $list_setting['live_link2']; ?>"></td>
            </tr>
            <tr>
                <td class="required"><label>滚动图片3:</label></td>
                <td><label>链接地址:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/preview.png">
            <div class="type-file-preview"><img src="<?php echo \think\Config::get('url_domain_root'); ?>uploads/admin/groupbuy/<?php echo $list_setting['live_pic3']; ?>"></div>
            </span><span class="type-file-box">
            <input type='text' name='textfield3' id='textfield3' class='type-file-text' />
            <input type='button' name='button' id='button' value='' class='type-file-button' />
            <input name="live_pic3" type="file" class="type-file-file" id="live_pic3" size="30" hidefocus="true" />
            </span></td>
                <td class="vatop tips"><input type="text" name="live_link3" class="w200" value="<?php echo $list_setting['live_link3']; ?>"></td>
            </tr>
            <tr>
                <td class="required"><label>滚动图片4:</label></td>
                <td><label>链接地址:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/preview.png">
            <div class="type-file-preview"><img src="<?php echo \think\Config::get('url_domain_root'); ?>uploads/admin/groupbuy/<?php echo $list_setting['live_pic4']; ?>"></div>
            </span><span class="type-file-box">
            <input type='text' name='textfield4' id='textfield4' class='type-file-text' />
            <input type='button' name='button' id='button' value='' class='type-file-button' />
            <input name="live_pic4" type="file" class="type-file-file" id="live_pic4" size="30" hidefocus="true" />
            </span></td>
                <td class="vatop tips"><input type="text" name="live_link4" class="w200" value="<?php echo $list_setting['live_link4']; ?>"></td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2">
                    <a href="JavaScript:void(0);" class="btn" id="submitBtn"><span>保存</span></a>
                    <a href="JavaScript:void(0);" class="btn" id="clearBtn"><span>清空</span></a>
                </td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>

<script>
    //按钮先执行验证再提交表单
    $(function(){
        // 图片js
        $("#live_pic1").change(function(){$("#textfield1").val($("#live_pic1").val());});
        $("#live_pic2").change(function(){$("#textfield2").val($("#live_pic2").val());});
        $("#live_pic3").change(function(){$("#textfield3").val($("#live_pic3").val());});
        $("#live_pic4").change(function(){$("#textfield4").val($("#live_pic4").val());});

        $('#live_form').validate({
            errorPlacement: function(error, element){
                error.appendTo(element.parent().parent().prev().find('td:first'));
            },
            success: function(label){
                label.addClass('valid');
            },
            rules : {
                live_link1: {
                    url : true
                },
                live_link2:{
                    url : true
                },
                live_link3:{
                    url : true
                },
                live_link4:{
                    url : true
                },
            },
            messages : {
                live_link1: {
                    url : '链接地址格式不正确'
                },
                live_link2:{
                    url : '链接地址格式不正确'
                },
                live_link3:{
                    url : '链接地址格式不正确'
                },
                live_link4:{
                    url : '链接地址格式不正确'
                },
            }
        });

        $('#clearBtn').click(function(){
            if (!confirm('确认清空虚拟抢购幻灯片设置？')) {
                return false;
            }
            $.ajax({
                type:'get',
                url:"<?php echo url('groupbuy/slider_clear'); ?>",
                dataType:'json',
                success:function(result){
                    if(result.result){
                        alert('清空成功');
                        location.reload();
                    }
                }
            });
        });

        $("#submitBtn").click(function(){
            $("#live_form").submit();
        });
    });
</script>