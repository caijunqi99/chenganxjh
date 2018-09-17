<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:93:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\navigation\index.html";i:1514877306;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>导航管理</h3>
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

    <table class="ds-default-table">
        <thead>
            <tr>
                <th><?php echo \think\Lang::get('nav_sort'); ?></th>
                <th><?php echo \think\Lang::get('nav_title'); ?></th>
                <th><?php echo \think\Lang::get('nav_url'); ?></th>
                <th><?php echo \think\Lang::get('nav_location'); ?></th>
                <th><?php echo \think\Lang::get('nav_new_open'); ?></th>
                <th><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($nav_list) || $nav_list instanceof \think\Collection || $nav_list instanceof \think\Paginator): $i = 0; $__LIST__ = $nav_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav): $mod = ($i % 2 );++$i;?>
            <tr>
                <td><?php echo $nav['nav_sort']; ?></td>
                <td><?php echo $nav['nav_title']; ?></td>
                <td><?php echo $nav['nav_url']; ?></td>
                <td><?php if($nav['nav_location'] == 'header'): ?><?php echo \think\Lang::get('nav_top'); elseif($nav['nav_location'] == 'middle'): ?><?php echo \think\Lang::get('nav_midd'); else: ?><?php echo \think\Lang::get('nav_foo'); endif; ?></td>
                <td><?php if($nav['nav_new_open'] == '0'): ?>否<?php else: ?>是<?php endif; ?></td>
                <td>
                    <a href="<?php echo url('/admin/Navigation/edit',['nav_id'=>$nav['nav_id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a>
                    <a href="javascript:if(confirm('<?php echo \think\Lang::get('sure_drop'); ?>'))window.location ='<?php echo url('/admin/Navigation/drop',['nav_id'=>$nav['nav_id']]); ?>'"><?php echo \think\Lang::get('ds_del'); ?></a>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <?php echo $page; ?>
</div>