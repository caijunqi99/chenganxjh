<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:91:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\document\index.html";i:1514867095;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>会员协议</h3>
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

    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom">
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示" class="arrow"></span>
        </div>
        <ul>
            <li><?php echo \think\Lang::get('document_index_help1'); ?></li>
        </ul>
    </div>
    
    <table class="ds-default-table">
        <thead>
        <tr class="thead">
            <th><?php echo \think\Lang::get('document_index_title'); ?></th>
            <th class="align-center"><?php echo \think\Lang::get('document_edit_time'); ?></th>
            <th><?php echo \think\Lang::get('ds_handle'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(!(empty($doc_list) || (($doc_list instanceof \think\Collection || $doc_list instanceof \think\Paginator ) && $doc_list->isEmpty()))): if(is_array($doc_list) || $doc_list instanceof \think\Collection || $doc_list instanceof \think\Paginator): $i = 0; $__LIST__ = $doc_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
        <tr class="hover">
            <td ><?php echo $v['doc_title']; ?></td>
            <td class="w25pre align-center"><?php echo date("Y-m-d H:i:s",$v['doc_time']); ?></td>
            <td class="w96"><a href="<?php echo url('document/edit',['doc_id'=>$v['doc_id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a></td>
        </tr>
       <?php endforeach; endif; else: echo "" ;endif; else: ?>
        <tr class="no_data">
            <td colspan="15"><?php echo \think\Lang::get('ds_no_record'); ?></td>
        </tr>
        <?php endif; ?>
        </tbody>
    </table>

</div>