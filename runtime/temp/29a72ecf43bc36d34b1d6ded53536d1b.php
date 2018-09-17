<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:96:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\vrgroupbuy\area_add.html";i:1514877313;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>添加区域</h3>
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

    <form id="add_form" method="post">
        <table class="ds-default-table">
            <tbody>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation" for="area_name">区域名称<?php echo \think\Lang::get('ds_colon'); ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><input type="text" value="" name="area_name" id="area_name" class="txt"></td>
                <td class="vatop tips"></td>
            </tr>

            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation" for="first_letter">首字母<?php echo \think\Lang::get('ds_colon'); ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <select name='first_letter'>
                        <?php if(is_array($letter) || $letter instanceof \think\Collection || $letter instanceof \think\Paginator): $i = 0; $__LIST__ = $letter;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lv): $mod = ($i % 2 );++$i;?>
                        <option value='<?php echo $lv; ?>'><?php echo $lv; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </td>
            </tr>

            <tr class="noborder">
                <td colspan="2" class="required"><label for="area_number">区号<?php echo \think\Lang::get('ds_colon'); ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><input type="text" value="" name="area_number" id="area_number" class="txt"></td>
            </tr>

            <tr class="noborder">
                <td colspan="2" class="required"><label for="post">邮编<?php echo \think\Lang::get('ds_colon'); ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><input type="text" value="" name="post" id="post" class="txt"></td>
            </tr>

            <?php if($area_id): ?>
            <tr class="noborder">
                <td colspan="2" class="required"><label for="area_class">上级区域<?php echo \think\Lang::get('ds_colon'); ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                   <?php echo $area_name; ?>
                    <input type='hidden' name='parent_area_id' value="<?php echo $area_id; ?>">
                </td>
            </tr>
            <?php else: ?>
            <tr class="noborder">
                <td colspan="2" class="required"><label for="area_class">显示<?php echo \think\Lang::get('ds_colon'); ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform onoff">
                    <label for="hot1" class="cb-enable" ><span><?php echo \think\Lang::get('open'); ?></span></label>
                    <label for="hot0" class="cb-disable selected" ><span><?php echo \think\Lang::get('close'); ?></span></label>
                    <input id="hot1" name="is_hot"  value="1" type="radio">
                    <input id="hot0" name="is_hot"  checked="checked" value="0" type="radio">
                </td>
            </tr>
            <?php endif; ?>
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
        $("#submit").click(function(){
            $("#add_form").submit();
        });

        $('#add_form').validate({
            errorPlacement: function(error, element){
                error.appendTo(element.parent().parent().prev().find('td:first'));
            },
            success: function(label){
                label.addClass('valid');
            },
            rules : {
                area_name: {
                    required : true
                },
                area_number:{
                    number: true
                },
                post:{
                    number: true
                }
            },
            messages : {
                area_name: {
                    required : '区域名称不能为空'
                },
                area_number:{
                    number:'区号必须是数字'
                },
                post:{
                    number:'邮编必须是数字'
                }
            }
        });
    });
</script>