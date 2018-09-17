<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\inviter\index.html";i:1514528328;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>推广设置</h3>
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

    <form method="post" enctype="multipart/form-data" name="form1" action="">
        <div class="ncap-form-default">
            <dl>
                <dt><?php echo \think\Lang::get('inviter_back'); ?></dt>
                <dd>
                    <span class="type-file-show"><img class="show_image" src="<?php echo \think\Config::get('url_domain_root'); ?>/static/admin/images/preview.png">
                        <div class="type-file-preview"><img src="<?php echo \think\Config::get('url_domain_root'); ?><?php echo '/uploads/'.ATTACH_COMMON;?>/<?php echo $list_setting['inviter_back']; ?>"></div>
                    </span>
                    <span class="type-file-box"><input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
                        <input name="inviter_back" type="file" class="type-file-file" id="inviter_back" size="30" hidefocus="true" nc_type="change_inviter_back">
                    </span>
                    <p class="notic">推广背景图片，最佳显示尺寸为320*426像素</p>
                </dd>
            </dl>
            <dl>
                <dt>一级推广佣金比例</dt>
                <dd>
                    <input name="inviter_ratio_1" value="<?php echo $list_setting['inviter_ratio_1']; ?>" class="input-txt" type="text">%
                    <span class="err"></span>
                    <p class="notic">基数为订单总商品金额，请确保一二三级佣金比例之和不要小于最小分类分佣比例</p>
                </dd>
            </dl>
            <dl>
                <dt>二级推广佣金比例</dt>
                <dd>
                    <input name="inviter_ratio_2" value="<?php echo $list_setting['inviter_ratio_2']; ?>" class="input-txt" type="text">%
                    <span class="err"></span>
                    <p class="notic">基数为订单总商品金额，请确保一二三级佣金比例之和不要小于最小分类分佣比例</p>
                </dd>
            </dl>
            <dl>
                <dt>三级推广佣金比例</dt>
                <dd>
                    <input name="inviter_ratio_3" value="<?php echo $list_setting['inviter_ratio_3']; ?>" class="input-txt" type="text">%
                    <span class="err"></span>
                    <p class="notic">基数为订单总商品金额，请确保一二三级佣金比例之和不要小于最小分类分佣比例</p>
                </dd>
            </dl>
            <dl>
                <dt></dt>
                <dd><a href="JavaScript:void(0);" class="btn" onclick="document.form1.submit()">确认提交</a></dd>
            </dl>
        </div>
    </form>
</div>
