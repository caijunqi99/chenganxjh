<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:109:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\promotionmansong\mansong_setting.html";i:1514867090;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>满即送</h3>
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

    <form id="add_form" method="post" enctype="multipart/form-data" action="<?php echo url('promotionmansong/mansong_setting_save'); ?>">
        <table class="ds-default-table">
            <tbody>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation"><?php echo \think\Lang::get('mansong_price'); ?>:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <input type="text" id="promotion_mansong_price" name="promotion_mansong_price" value="<?php echo $setting['promotion_mansong_price']; ?>" class="txt">
                </td>
                <td class="vatop tips"><?php echo \think\Lang::get('mansong_price_explain'); ?></td>
            </tr>
            </tbody>
            <tfoot>
            <tr class="tfoot">
                <td colspan="15"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo \think\Lang::get('ds_submit'); ?></span></a></td>
            </tr>
            </tfoot>
        </table>
    </form>

</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#submitBtn").click(function(){
            $("#add_form").submit();
        });
        //页面输入内容验证
        $("#add_form").validate({
            errorPlacement: function(error, element){
                error.appendTo(element.parent().parent().prev().find('td:first'));
            },

            rules : {
                promotion_mansong_price: {
                    required : true,
                    digits : true,
                    min : 1
                }
            },
            messages : {
                promotion_mansong_price: {
                    required : '<?php echo \think\Lang::get('mansong_price_error'); ?>',
                    digits : '<?php echo \think\Lang::get('mansong_price_error'); ?>',
                    min : '<?php echo \think\Lang::get('mansong_price_error'); ?>'
                }
            }
        });
    });
    //submit函数
    function submit_form(submit_type){
        $('#submit_type').val(submit_type);
        $('#add_form').submit();
    }
</script> 