<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:100:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\promotionbundling\index.html";i:1514877307;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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

    <form method="get" name="formSearch">
        <table class="search-form">
            <tbody>
            <tr>
                <th><label for="mansong_name"><?php echo \think\Lang::get('bundling_name'); ?></label></th>
                <td><input type="text" value="<?php echo \think\Request::instance()->get('bundling_name'); ?>" name="bundling_name" id="bundling_name" class="txt" style="width:100px;"></td>
                <th><label for="store_name"><?php echo \think\Lang::get('bundling_quota_store_name'); ?></label></th>
                <td><input type="text" value="<?php echo \think\Request::instance()->get('store_name'); ?>" name="store_name" id="store_name" class="txt" style="width:100px;"></td>
                <th><label for=""><?php echo \think\Lang::get('ds_state'); ?></label></th>
                <td>
                    <select name="state">
                        <option><?php echo \think\Lang::get('bundling_state_all'); ?></option>
                        <option <?php if(\think\Request::instance()->get('state') == '1'): ?>selected="selected"<?php endif; ?>><?php echo \think\Lang::get('bundling_state_1'); ?></option>
                        <option <?php if(\think\Request::instance()->get('state') == '0'): ?>selected="selected"<?php endif; ?>><?php echo \think\Lang::get('bundling_state_0'); ?></option>
                    </select>
                </td>
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
            <li><?php echo \think\Lang::get('bundling_quota_prompts'); ?></li>
        </ul>
    </div>
    
    <!-- 列表 -->
    <form id="list_form" method="post">
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th><?php echo \think\Lang::get('bundling_quota_store_name'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('bundling_name'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('bundling_price'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('bundling_goods_count'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('ds_status'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
            </thead>
            <tbody id="treet1">
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
            <tr class="hover">
                <td class="align-left"><a href="<?php echo url('home/showstore/inde',['store_id'=>$val['store_id']]); ?>" ><span><?php echo $val['store_name']; ?></span></a>
                    <?php if(isset($flippedOwnShopIds[$val['store_id']])): ?>
                    <span class="ownshop">[自营]</span>
                    <?php endif; ?>
                </td>
                <td class="align-center"><?php echo $val['bl_name']; ?></td>
                <td class="align-center"><?php echo $val['bl_discount_price']; ?></td>
                <td class="align-center"><?php echo $val['count']; ?></td>
                <td class="align-center">
                    <?php echo $state_array[$val['bl_state']]; ?>
                </td>
                <td class="nowrap align-center">
                    <a target="block" href="<?php echo url('home/goods/index',['goods_id'=>$val['goods_id']]); ?>"><?php echo \think\Lang::get('ds_view'); ?></a>
                    <a href="<?php echo url('promotionbundling/del_bundling',['bl_id'=>$val['bl_id']]); ?>"><?php echo \think\Lang::get('ds_del'); ?></a>
                </td>
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
                <td colspan="16"><label>
                    <div class="pagination"><?php echo $show_page; ?> </div></td>
            </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </form>

</div>