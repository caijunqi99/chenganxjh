<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:107:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\promotionmansong\mansong_quota.html";i:1514867090;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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

    <form method="get" name="formSearch">
        <table class="search-form">
            <tbody>
            <tr>
                <th><label for="store_name"><?php echo \think\Lang::get('store_name'); ?></label></th>
                <td><input type="text" value="<?php echo \think\Request::instance()->get('store_name'); ?>" name="store_name" id="store_name" class="txt" style="width:100px;"></td>
                <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo \think\Lang::get('ds_query'); ?>"></a></td>
            </tr>
            </tbody>
        </table>
    </form>
    <!-- 帮助 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom">
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示" class="arrow"></span>
        </div>
        <ul>
            <li><?php echo \think\Lang::get('mansong_quota_list_help1'); ?></li>
        </ul>
    </div>
    
    <!-- 列表 -->
    <form id="list_form" method="post">
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th class="w24"></th>
                <th class="align-left"><span><?php echo \think\Lang::get('store_name'); ?></span></th>
                <th class="align-center"><span><?php echo \think\Lang::get('mansong_quota_start_time'); ?></span></th>
                <th class="align-center"><span><?php echo \think\Lang::get('mansong_quota_end_time'); ?></span></th>
            </tr>
            </thead>
            <tbody id="treet1">
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
            <tr class="hover">
                <td></td>
                <td class="align-left"><a href="<?php echo url('home/showstore/index',['store_id'=>$val['store_id']]); ?>" ><span><?php echo $val['store_name']; ?></span></a></td>
                <td class="align-center"><span><?php echo date("Y-m-d",$val['start_time']); ?></span></td>
                <td class="align-center"><span><?php echo date("Y-m-d",$val['end_time']); ?></span></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="16"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            <?php endif; ?>
            </tbody>
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
            <tfoot>
            <tr class="tfoot">
                <td colspan="16">
                    <div class="pagination"><?php echo $show_page; ?> </div></td>
            </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </form>

</div>