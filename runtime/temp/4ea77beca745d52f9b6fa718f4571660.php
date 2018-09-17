<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:92:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\storehelp\index.html";i:1514877311;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>店铺帮助</h3>
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

    <form method="get" action="" name="formSearch" id="formSearch">
        <table class="search-form">
            <tbody>
            <tr>
                <th>帮助标题</th>
                <td><input type="text" class="text" name="key" value="<?php echo \think\Request::instance()->get('key'); ?>" /></td>
                <th>帮助类型</th>
                <td><select name="type_id" id="type_id">
                    <option value=""><?php echo \think\Lang::get('ds_please_choose'); ?>...</option>
                    <?php if(!(empty($type_list) || (($type_list instanceof \think\Collection || $type_list instanceof \think\Paginator ) && $type_list->isEmpty()))): if(is_array($type_list) || $type_list instanceof \think\Collection || $type_list instanceof \think\Paginator): $i = 0; $__LIST__ = $type_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
                    <option <?php if($val['type_id'] == 'Request.get.type_id'): ?>select<?php endif; ?> value="<?php echo $val['type_id']; ?>"><?php echo $val['type_name']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                </select></td>
                <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo \think\Lang::get('ds_query'); ?>"></a>
                </td>
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
            <li>帮助内容排序显示规则为排序小的在前，新增内容的在前</li>
        </ul>
    </div>

    <table class="ds-default-table">
        <thead>
        <tr class="thead">
            <th><?php echo \think\Lang::get('ds_sort'); ?></th>
            <th>帮助标题</th>
            <th>帮助类型</th>
            <th class="align-center">更新时间</th>
            <th class="align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(!(empty($help_list) || (($help_list instanceof \think\Collection || $help_list instanceof \think\Paginator ) && $help_list->isEmpty()))): if(is_array($help_list) || $help_list instanceof \think\Collection || $help_list instanceof \think\Paginator): $i = 0; $__LIST__ = $help_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
        <tr class="hover">
            <td class="w48 sort"><?php echo $val['help_sort']; ?></td>
            <td><?php echo $val['help_title']; ?></td>
            <td><?php echo $type_list[$val['type_id']]['type_name']; ?></td>
            <td class="w150 align-center"><?php echo date("Y-m-d H:i:s",$val['update_time']); ?></td>
            <td class="w150 align-center"><a href="<?php echo url('Storehelp/edit_help',['help_id'=>$val['help_id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a> |
                <a href="javascript:if(confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')) window.location = '<?php echo url('Storehelp/del_help',['help_id'=>$val['help_id']]); ?>';"><?php echo \think\Lang::get('ds_del'); ?></a></td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; else: ?>
        <tr class="no_data">
            <td colspan="15"><?php echo \think\Lang::get('ds_no_record'); ?></td>
        </tr>
       <?php endif; ?>
        </tbody>
    </table>
    <?php if(!(empty($help_list) || (($help_list instanceof \think\Collection || $help_list instanceof \think\Paginator ) && $help_list->isEmpty()))): ?>
    <?php echo $show_page; endif; ?>
</div>

