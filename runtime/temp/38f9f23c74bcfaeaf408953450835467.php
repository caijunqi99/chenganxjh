<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:91:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\delivery\index.html";i:1514867094;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>物流自提服务站</h3>
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

    <form method="get" name="formSearch" id="formSearch">
        
        <table class="search-form">
            <tbody>
            <tr>
                <th><label for="search_title">真实姓名</label></th>
                <td><input type="text" value="<?php echo \think\Request::instance()->get('search_name'); ?>" name="search_name" id="search_name" class="txt"></td>
                <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo \think\Lang::get('ds_query'); ?>">&nbsp;</a>
                    <?php if(!(empty($search_name) || (($search_name instanceof \think\Collection || $search_name instanceof \think\Paginator ) && $search_name->isEmpty()))): ?>
                    <a href="<?php echo url('delivery/index'); ?>" class="btns " title="<?php echo \think\Lang::get('ds_cancel_search'); ?>"><span><?php echo \think\Lang::get('ds_cancel_search'); ?></span></a>
                    <?php endif; ?></td>
            </tr>
            </tbody>
        </table>
    </form>
    
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom">
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示" class="arrow"></span>
        </div>
        <ul>
            <li>物流自提服务站关闭后，被用户选择设置成收货地址的记录会被删除，请谨慎操作。</li>
        </ul>
    </div>
    
    <form method="post" id="form_article">
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th>用户名</th>
                <th>真实姓名</th>
                <th>收货地址</th>
                <th class="align-center">状态</th>
                <th class="align-center">申请时间</th>
                <th class="w96 align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!(empty($dp_list) || (($dp_list instanceof \think\Collection || $dp_list instanceof \think\Paginator ) && $dp_list->isEmpty()))): if(is_array($dp_list) || $dp_list instanceof \think\Collection || $dp_list instanceof \think\Paginator): $i = 0; $__LIST__ = $dp_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr class="hover">
                <td><?php echo $v['dlyp_name']; ?></td>
                <td><?php echo $v['dlyp_truename']; ?></td>
                <td>
                    <p><?php echo $v['dlyp_address_name']; ?></p>
                    <p><?php echo $v['dlyp_area_info']; ?>&nbsp;&nbsp;<?php echo $v['dlyp_address']; ?></p>
                </td>
                <td class="align-center"><?php echo $delivery_state[$v['dlyp_state']]; ?></td>
                <td class="nowrap align-center"><?php echo date("Y-m-d H:i:s",$v['dlyp_addtime']); ?></td>
                <td class="align-center"><a href="<?php echo url('delivery/edit_delivery',['d_id'=>$v['dlyp_id']]); ?>">编辑</a> | <a href="<?php echo url('delivery/edit_order_list',['d_id'=>$v['dlyp_id']]); ?>">查看订单</a></td>
            </tr>
           <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="10"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
           <?php endif; ?>
            </tbody>
            <tfoot>
            <?php if(!(empty($dp_list) || (($dp_list instanceof \think\Collection || $dp_list instanceof \think\Paginator ) && $dp_list->isEmpty()))): ?>
            <tr class="tfoot">
                <td colspan="16">
                    <div class="pagination"> <?php echo $show_page; ?> </div></td>
            </tr>
            <?php endif; ?>
            </tfoot>
        </table>
    </form>

</div>