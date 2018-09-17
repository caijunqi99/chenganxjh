<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:93:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\storegrade\index.html";i:1514877311;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>店铺等级</h3>
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
    <form method="post" name="formSearch">
        <table class="tb-type1 noborder search">
            <tbody>
                <tr>
                    <th><label for="like_sg_name"><?php echo \think\Lang::get('store_grade_name'); ?></label></th>
                    <td><input type="text" value="<?php echo (isset($like_sg_name) && ($like_sg_name !== '')?$like_sg_name:''); ?>" name="like_sg_name" id="like_sg_name" class="txt"></td>
                    <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo \think\Lang::get('ds_query'); ?>"></a>
                        <?php if(!(empty($like_sg_name) || (($like_sg_name instanceof \think\Collection || $like_sg_name instanceof \think\Paginator ) && $like_sg_name->isEmpty()))): ?>
                        <a class="btns " href="<?php echo url('Storegrade/index'); ?>" title="<?php echo \think\Lang::get('cancel_search'); ?>"><span><?php echo \think\Lang::get('cancel_search'); ?></span></a>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>

    <table class="ds-default-table">
        <thead>
            <tr>
                <th><?php echo \think\Lang::get('sg_sort'); ?></th>
                <th><?php echo \think\Lang::get('sg_name'); ?></th>
                <th><?php echo \think\Lang::get('sg_goods_limit'); ?></th>
                <th><?php echo \think\Lang::get('sg_album_limit'); ?></th>
                <th><?php echo \think\Lang::get('sg_template_number'); ?></th>
                <th><?php echo \think\Lang::get('sg_price'); ?></th>
                <th><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if(!(empty($storegrade_list) || (($storegrade_list instanceof \think\Collection || $storegrade_list instanceof \think\Paginator ) && $storegrade_list->isEmpty()))): if(is_array($storegrade_list) || $storegrade_list instanceof \think\Collection || $storegrade_list instanceof \think\Paginator): $i = 0; $__LIST__ = $storegrade_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$storegrade): $mod = ($i % 2 );++$i;?>
            <tr>
                <td><?php echo $storegrade['sg_sort']; ?></td>
                <td><?php echo $storegrade['sg_name']; ?></td>
                <td><?php echo $storegrade['sg_goods_limit']; ?></td>
                <td><?php echo $storegrade['sg_album_limit']; ?></td>
                <td><?php echo $storegrade['sg_template_number']; ?></td>
                <td><?php echo $storegrade['sg_price']; ?></td>
                <td>
                    <a href="<?php echo url('/admin/Storegrade/edit',['sg_id'=>$storegrade['sg_id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a>
                    <a href="<?php echo url('/admin/Storegrade/drop',['sg_id'=>$storegrade['sg_id']]); ?>"><?php echo \think\Lang::get('ds_del'); ?></a>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="20"><?php echo \think\Lang::get('no_record'); ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>