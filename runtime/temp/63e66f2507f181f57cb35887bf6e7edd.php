<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:89:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\points\index.html";i:1514528329;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>积分管理</h3>
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




    <form method="post">
        <div class="ncap-form-default">
            <dl>
                <dt><?php echo \think\Lang::get('member_name'); ?></dt>
                <dd>
                    <input id="member_name" name="member_name" value="" class="input-txt" type="text">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl>
                <dt><?php echo \think\Lang::get('points_type'); ?></dt>
                <dd>
                    <select name="points_type">
                        <option value="1">增加</option>
                        <option value="2">减少</option>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt><?php echo \think\Lang::get('points_num'); ?></dt>
                <dd>
                    <input id="points_num" name="points_num" value="" class="input-txt" type="text">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl>
                <dt><?php echo \think\Lang::get('points_desc'); ?></dt>
                <dd>
                    <textarea name="points_desc"></textarea>
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl>
                <dt></dt>
                <dd>
                    <input class="btn" type="submit" value="提交"/>
                </dd>
            </dl>
        </div>
    </form>
</div>
