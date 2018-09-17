<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:94:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\membergrade\index.html";i:1514878459;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>会员等级</h3>
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
        <table class="ds-default-table">
            <thead>
                <tr>
                    <th><?php echo \think\Lang::get('mg_gradename'); ?></th>
                    <th><?php echo \think\Lang::get('mg_integral'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>V1</td>
                    <td>
                        <input type="text" name="exppoints[1]" value="<?php echo (isset($mg['1']['exppoints']) && ($mg['1']['exppoints'] !== '')?$mg['1']['exppoints']:''); ?>" class="w200">
                    </td>
                </tr>

                <tr>
                    <td>V2</td>
                    <td>
                        <input type="text" name="exppoints[2]" value="<?php echo (isset($mg['2']['exppoints']) && ($mg['2']['exppoints'] !== '')?$mg['2']['exppoints']:''); ?>" class="w200">
                    </td>
                </tr>
                <tr>
                    <td>V3</td>
                    <td>
                        <input type="text" name="exppoints[3]" value="<?php echo (isset($mg['3']['exppoints']) && ($mg['3']['exppoints'] !== '')?$mg['3']['exppoints']:''); ?>" class="w200">
                    </td>
                </tr>
                <tr>
                    <td>V4</td>
                    <td>
                        <input type="text" name="exppoints[4]" value="<?php echo (isset($mg['4']['exppoints']) && ($mg['4']['exppoints'] !== '')?$mg['4']['exppoints']:''); ?>" class="w200">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input class=" btn" type="submit" value="提交"/>
                    </td>
                </tr>
            </tbody>
        </table>
        
    </form>
</div>