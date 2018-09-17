<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:97:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\vrgroupbuy\class_add.html";i:1514877313;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>添加分类</h3>
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

    <form id="add_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="submit_type" id="submit_type" value="" />
        <table class="ds-default-table">
            <tbody>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation" for="class_name">分类名称<?php echo \think\Lang::get('ds_colon'); ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><input type="text" name="class_name" id="class_name" class="txt"></td>
                <td class="vatop tips"></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label for="parent_class_id">上级分类<?php echo \think\Lang::get('ds_colon'); ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><select id="parent_class_id" name="parent_class_id" class="valid" >
                    <option value="0"><?php echo \think\Lang::get('ds_common_pselect'); ?></option>
                    <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo $val['class_id']; ?>" <?php if($parent_class_id == $val['class_id']): ?>selected<?php endif; ?>><?php echo $val['class_name']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                </select></td>
                <td class="vatop tips"></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label for="class_sort" class="validation"><?php echo \think\Lang::get('ds_sort'); ?><?php echo \think\Lang::get('ds_colon'); ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><input id="class_sort" name="class_sort" type="text" class="txt" value="255" /></td>
                <td class="vatop tips"><?php echo \think\Lang::get('class_sort_explain'); ?></td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2"><a id="submit" href="javascript:void(0)" class="btn"><span><?php echo \think\Lang::get('ds_submit'); ?></span></a></td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#submit').click(function(){
            $('#add_form').submit();
        });

        $('#add_form').validate({
            errorPlacement: function(error, element){
                error.appendTo(element.parent().parent().prev().find('td:first'));
            },
            success: function(label){
                label.addClass('valid');
            },
            rules : {
                class_name: {
                    required : true,
                    maxlength : 10
                },
                class_sort: {
                    required : true,
                    digits: true,
                    max: 255,
                    min: 0
                }
            },
            messages : {
                class_name: {
                    required : "分类名称不能为空",
                    maxlength : jQuery.validator.format("分类名称长度最多10个字符")
                },
                class_sort: {
                    required : "排序不能为空",
                    digits: "排序必须是数字,且数值0-255",
                    max : jQuery.validator.format("排序必须是数字,且数值0-255"),
                    min : jQuery.validator.format("排序必须是数字,且数值0-255")
                }
            }
        });
    });
</script>