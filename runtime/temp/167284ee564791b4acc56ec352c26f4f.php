<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:111:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\promotionbundling\bundling_setting.html";i:1514877307;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>优惠套装</h3>
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

    <form id="add_form" method="post" action="<?php echo url('promotionbundling/bundling_setting'); ?>">
        <table class="ds-default-table">
            <tbody>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation" for="promotion_bundling_price"><?php echo \think\Lang::get('bundling_gold_price'); ?><?php echo \think\Lang::get('ds_colon'); ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <input type="text" id="promotion_bundling_price" name="promotion_bundling_price" value="<?php echo $setting['promotion_bundling_price']; ?>" class="txt">
                </td>
                <td class="vatop tips"><?php echo \think\Lang::get('bundling_price_prompt'); ?></td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation" for="promotion_bundling_sum"><?php echo \think\Lang::get('bundling_sum'); ?><?php echo \think\Lang::get('ds_colon'); ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <input type="text" id="promotion_bundling_sum" name="promotion_bundling_sum" value="<?php echo $setting['promotion_bundling_sum']; ?>" class="txt">
                </td>
                <td class="vatop tips"><?php echo \think\Lang::get('bundling_sum_prompt'); ?></td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation" for="promotion_bundling_goods_sum"><?php echo \think\Lang::get('bundling_goods_sum'); ?><?php echo \think\Lang::get('ds_colon'); ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <input type="text" id="promotion_bundling_goods_sum" name="promotion_bundling_goods_sum" value="<?php echo $setting['promotion_bundling_goods_sum']; ?>" class="txt">
                </td>
                <td class="vatop tips"><?php echo \think\Lang::get('bundling_goods_sum_prompt'); ?></td>
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
                promotion_bundling_price: {
                    required : true,
                    digits : true,
                    min : 1
                },
                promotion_bundling_sum: {
                    required : true,
                    digits : true
                },
                promotion_bundling_goods_sum: {
                    required : true,
                    digits : true,
                    min : 1,
                    max : 5
                }
            },
            messages : {
                promotion_bundling_price: {
                    required : '<?php echo \think\Lang::get('bundling_price_error'); ?>',
                    digits : '<?php echo \think\Lang::get('bundling_price_error'); ?>',
                    min : '<?php echo \think\Lang::get('bundling_price_error'); ?>'
                },
                promotion_bundling_sum: {
                    required : '<?php echo \think\Lang::get('bundling_sum_error'); ?>',
                    digits : '<?php echo \think\Lang::get('bundling_sum_error'); ?>'
                },
                promotion_bundling_goods_sum: {
                    required : '<?php echo \think\Lang::get('bundling_goods_sum_error'); ?>',
                    digits : '<?php echo \think\Lang::get('bundling_goods_sum_error'); ?>',
                    min : '<?php echo \think\Lang::get('bundling_goods_sum_error'); ?>',
                    max : '<?php echo \think\Lang::get('bundling_goods_sum_error'); ?>'
                }
            }
        });
    });
</script> 