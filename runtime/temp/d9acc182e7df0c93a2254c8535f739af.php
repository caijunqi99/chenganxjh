<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:92:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\snsmember\index.html";i:1514877309;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>会员标签</h3>
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
            <li><?php echo \think\Lang::get('sns_member_index_tips_1'); ?></li>
            <li><?php echo \think\Lang::get('sns_member_index_tips_2'); ?></li>
        </ul>
    </div>
    
    
    <form method='post'>
        <input type="hidden" name="submit_type" id="submit_type" value="" />
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th></th>
                <th><?php echo \think\Lang::get('ds_sort'); ?></th>
                <th><?php echo \think\Lang::get('sns_member_tag_name'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('ds_recommend'); ?></th>
                <th class="w132 align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
            </thead>
            <?php if(!(empty($tag_list) || (($tag_list instanceof \think\Collection || $tag_list instanceof \think\Paginator ) && $tag_list->isEmpty()))): ?>
            <tbody>
            <?php if(is_array($tag_list) || $tag_list instanceof \think\Collection || $tag_list instanceof \think\Paginator): $i = 0; $__LIST__ = $tag_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr class="hover edit">
                <td class="w48"><input type="checkbox" name="id[]" value="<?php echo $v['mtag_id']; ?>" class="checkitem"></td>
                <td class="w48 sort"><span title="<?php echo \think\Lang::get('ds_editable'); ?>" ajax_branch="membertag_sort" datatype="number" fieldid="<?php echo $v['mtag_id']; ?>" fieldname="mtag_sort" nc_type="inline_edit" class="editable "><?php echo $v['mtag_sort']; ?></span></td>
                <td class="w50pre name"><span title="<?php echo \think\Lang::get('ds_editable'); ?>" required="1" fieldid="<?php echo $v['mtag_id']; ?>" ajax_branch="membertag_name" fieldname="mtag_name" nc_type="inline_edit" class="editable "><?php echo $v['mtag_name']; ?></span></td>
                <td class="align-center yes-onoff">
                    <?php if($v['mtag_recommend'] == 0): ?>
                    <a href="JavaScript:void(0);" class=" disabled" fieldvalue="0" fieldid="<?php echo $v['mtag_id']; ?>" ajax_branch="membertag_recommend" fieldname="mtag_recommend" nc_type="inline_edit" title="<?php echo \think\Lang::get('ds_editable'); ?>"><img src=""></a>
                    <?php else: ?>
                    <a href="JavaScript:void(0);" class=" enabled" fieldvalue="1" fieldid="<?php echo $v['mtag_id']; ?>" ajax_branch="membertag_recommend" fieldname="mtag_recommend" nc_type="inline_edit" title="<?php echo \think\Lang::get('ds_editable'); ?>"><img src=""></a>
                    <?php endif; ?></td>
                <td class="w132 align-center">
                    <a href="<?php echo url('snsmember/tag_edit',['id'=>$v['mtag_id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a> |
                    <a href="<?php echo url('snsmember/tag_del',['id'=>$v['mtag_id']]); ?>"><?php echo \think\Lang::get('ds_del'); ?></a> |
                    <a href="<?php echo url('snsmember/tag_member',['id'=>$v['mtag_id']]); ?>"><?php echo \think\Lang::get('sns_member_view_member'); ?></a></td>
            </tr>
           <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
            <tfoot>
            <tr class="tfoot">
                <td><input type="checkbox" class="checkall" id="checkall_1"></td>
                <td id="batchAction" colspan="15"><span class="all_checkbox">
            <label for="checkall_2"><?php echo \think\Lang::get('ds_select_all'); ?></label>
            </span>&nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="if(confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')){$('#submit_type').val('del');$('form:first').submit();}"><span><?php echo \think\Lang::get('ds_del'); ?></span></a>
                    <div class="pagination"> <?php echo $showpage; ?> </div></td>
            </tr>
            </tfoot>
            <?php else: ?>
            <tbody>
            <tr class="no_data">
                <td colspan="10"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            </tbody>
            <?php endif; ?>
        </table>
    </form>
</div>