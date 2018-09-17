<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:92:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\exppoints\index.html";i:1514867089;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>经验值管理</h3>
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

    <form method="get" name="formSearch" id="formSearch" action="">
        <table class="search-form">
            <tbody>
                <tr>
                    <th><label>会员名称</label></th>
                    <td><input type="text" name="mname" class="txt" value='<?php echo \think\Request::instance()->get('mname'); ?>'></td>
                    <th>添加时间</th>
                    <td>
                        <input type="text" id="stime" name="stime" class="txt date" value="<?php echo \think\Request::instance()->get('stime'); ?>">
                        <label>~</label>
                        <input type="text" id="etime" name="etime" class="txt date" value="<?php echo \think\Request::instance()->get('etime'); ?>">
                    </td>
                    <td>
                        <select name="stage">
                            <option value="" <?php if(!(empty(\think\Request::instance()->get('stage')) || ((\think\Request::instance()->get('stage') instanceof \think\Collection || \think\Request::instance()->get('stage') instanceof \think\Paginator ) && \think\Request::instance()->get('stage')->isEmpty()))): ?>selected=selected<?php endif; ?>>操作阶段</option>
                            <?php if(is_array($stage_arr) || $stage_arr instanceof \think\Collection || $stage_arr instanceof \think\Paginator): if( count($stage_arr)==0 ) : echo "" ;else: foreach($stage_arr as $k=>$v): ?>
                            <option value="<?php echo $k; ?>" <?php if(\think\Request::instance()->get('stage') == $k): ?>selected=selected<?php endif; ?>><?php echo $v; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </td>
                    <th>描述</th>
                    <td><input type="text" id="description" name="description" class="txt2" value="<?php echo \think\Request::instance()->get('description'); ?>" ></td>
                    <td>
                        <input type="submit" value="<?php echo \think\Lang::get('ds_query'); ?>"/>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>

    <div style="text-align:right;">
        <a class="btn" href="<?php echo url('Exppoints/export_step1'); ?>" id="ncexport">
            <span><?php echo lang('ds_export'); ?>Excel</span>
        </a>
    </div>
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom">
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示" class="arrow"></span>
        </div>
        <ul>
            <li>经验值明细，展示了会员经验值增减情况的详细情况记录，经验值前有符号“-”表示减少，无符号表示增加</li>
        </ul>
    </div>
    <table class="ds-default-table">
        <thead>
            <tr>
                <th><?php echo \think\Lang::get('exp_membername'); ?></th>
                <th><?php echo \think\Lang::get('exp_value'); ?></th>
                <th><?php echo \think\Lang::get('exp_addtime'); ?></th>
                <th><?php echo \think\Lang::get('exp_stage'); ?></th>
                <th><?php echo \think\Lang::get('exp_desc'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if(!(empty($list_log) || (($list_log instanceof \think\Collection || $list_log instanceof \think\Paginator ) && $list_log->isEmpty()))): if(is_array($list_log) || $list_log instanceof \think\Collection || $list_log instanceof \think\Paginator): $i = 0; $__LIST__ = $list_log;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$log): $mod = ($i % 2 );++$i;?>
            <tr>
                <td><?php echo $log['exp_membername']; ?></td>
                <td><?php echo $log['exp_points']; ?></td>
                <td><?php echo date("Y-m-d H:i:s",$log['exp_addtime']); ?></td>
                <td><?php echo $log['exp_stage']; ?></td>
                <td><?php echo $log['exp_desc']; ?></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="15"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php echo $show_page; ?>

</div>

<script language="javascript">
    $(function () {
        $('#stime').datepicker({dateFormat: 'yy-mm-dd'});
        $('#etime').datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>