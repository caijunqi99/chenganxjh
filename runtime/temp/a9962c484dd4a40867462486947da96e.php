<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:91:"C:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\wechat\setting.html";i:1536983892;s:90:"C:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1536983892;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>想见孩系统后台</title>
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
                <h3>公众号配置</h3>
                <h5></h5>
            </div>
        </div>
    </div>

    <form method="post"  name="form1">
        <div class="ncap-form-default">
            <dl>
                <dt><?php echo \think\Lang::get('wx_url'); ?></dt>
                <dd>
                    <input  name="apiurl" value="<?php echo $wx_apiurl; ?>" class="input-txt" type="text">
                </dd>
            </dl>
            <dl>
                <dt><?php echo \think\Lang::get('wx_token'); ?></dt>
                <dd>
                   <input  name="wx_token" value="<?php echo $wx_config['token']; ?>" class="input-txt" type="text">
                </dd>
            </dl>
            <dl>
                <dt><?php echo \think\Lang::get('wx_name'); ?></dt>
                <dd>
                   <input  name="wx_name" value="<?php echo $wx_config['wxname']; ?>" class="input-txt" type="text">
                </dd>
            </dl>
            <dl>
                <dt><?php echo \think\Lang::get('wx_appid'); ?></dt>
                <dd>
                   <input  name="wx_appid" value="<?php echo $wx_config['appid']; ?>" class="input-txt" type="text">
                </dd>
            </dl>
            <dl>
                <dt><?php echo \think\Lang::get('wx_AppSecret'); ?></dt>
                <dd>
                   <input  name="wx_AppSecret" value="<?php echo $wx_config['appsecret']; ?>" class="input-txt" type="text">
                </dd>
            </dl>
            <dl>
                <dt><input type="hidden" name="wx_id" value="<?php echo $wx_config['id']; ?>"></dt>
                <dd><a href="JavaScript:void(0);" class="btn" onclick="document.form1.submit()">确认提交</a></dd>
            </dl>
        </div>
    </form>

</div>








