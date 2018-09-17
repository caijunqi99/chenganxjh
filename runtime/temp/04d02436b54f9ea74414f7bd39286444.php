<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:104:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\mbcategorypic\category_list.html";i:1514877306;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>分类图片设置</h3>
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

    <div class="fixed-empty"></div>
    
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom">
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示" class="arrow"></span>
        </div>
        <ul>
            <li><?php echo \think\Lang::get('link_help1'); ?></li>
        </ul>
    </div>
    
    
    <form method='post' id="form_link">
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th>&nbsp;</th>
                <th><?php echo \think\Lang::get('link_index_title'); ?></th>
                <th><?php echo \think\Lang::get('link_index_pic_sign'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!(empty($link_list) || (($link_list instanceof \think\Collection || $link_list instanceof \think\Paginator ) && $link_list->isEmpty()))): if(is_array($link_list) || $link_list instanceof \think\Collection || $link_list instanceof \think\Paginator): $i = 0; $__LIST__ = $link_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr class="hover edit">
                <td class="w24"><input type="checkbox" name="del_id[]" value="<?php echo $v['gc_id']; ?>" class="checkitem"></td>
                <td><?php echo $goods_class[$v['gc_id']]['gc_name']; ?></td>
                <td class="picture">
                    <?php if($v['gc_thumb'] != ''): ?>
                    <div class="size-88x31">
                   <span class='thumb size-88x31'><i></i>
                       <img width="88" height="31" src="<?php echo $v['gc_thumb']; ?>" onload='javascript:DrawImage(this,88,31);' /></span>
                    </div>
                   <?php endif; ?>
                </td>
     <td class="w96 align-center">
         <a href="<?php echo url('mbcategorypic/mb_category_edit',['gc_id'=>$v['gc_id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a> | 
         <a href="javascript:if(confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>'))window.location = '<?php echo url('mbcategorypic/mb_category_del',['gc_id'=>$v['gc_id']]); ?>';"><?php echo \think\Lang::get('ds_del'); ?></a></td>
</tr>
<?php endforeach; endif; else: echo "" ;endif; else: ?>
<tr class="no_data">
    <td colspan="10"><?php echo \think\Lang::get('ds_no_record'); ?></td>
</tr>
<?php endif; ?>
</tbody>
<tfoot>
<?php if(!(empty($link_list) || (($link_list instanceof \think\Collection || $link_list instanceof \think\Paginator ) && $link_list->isEmpty()))): ?>
<tr class="tfoot" id="dataFuncs">
    <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
    <td colspan="16" id="batchAction"><label for="checkallBottom"><?php echo \think\Lang::get('ds_select_all'); ?></label>
        &nbsp;&nbsp; <a href="JavaScript:void(0);" class="btn" onclick="if(confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')){$('#form_link').submit();}"><span><?php echo \think\Lang::get('ds_del'); ?></span></a>
</tr>
</tfoot>
<?php endif; ?>
</table>
</form>

</div>