<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:96:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\groupbuy\price_list.html";i:1514877305;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>抢购管理</h3>
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
            <li><?php echo \think\Lang::get('groupbuy_price_range_help1'); ?></li>
        </ul>
    </div>

    <form id="list_form" method='post'>
        <input id="range_id" name="range_id" type="hidden" />
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th></th>
                <th><?php echo \think\Lang::get('range_name'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('range_start'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('range_end'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
            <tr class="hover">
                <td class="w36"><input type="checkbox"  value="<?php echo $val['range_id']; ?>" class="checkitem"></td>
                <td><?php echo $val['range_name']; ?></td>
                <td class="w18pre align-center"><?php echo $val['range_start']; ?></td>
                <td class="w18pre align-center"><?php echo $val['range_end']; ?></td>
                <td class="w156 align-center"><a href="<?php echo url('groupbuy/price_edit',['range_id'=>$val['range_id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a> | <a href="JavaScript:void(0);" onClick="submit_delete('<?php echo $val['range_id']; ?>')"><?php echo \think\Lang::get('ds_del'); ?></a></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="10"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            <?php endif; ?>
            <tr><td colspan="20"><a href="<?php echo url('groupbuy/price_add'); ?>" class="btn-add marginleft"><?php echo \think\Lang::get('groupbuy_price_add'); ?></a></td></tr>
            </tbody>
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
            <tfoot>
            <tr class="tfoot">
                <td><input type="checkbox" class="checkall" id="checkall_1"></td>
                <td id="batchAction" colspan="15"><span class="all_checkbox">
            <label for="checkall_1"><?php echo \think\Lang::get('ds_select_all'); ?></label>
            </span>&nbsp;&nbsp; <a href="JavaScript:void(0);" class="btn" onclick="submit_delete_batch();"><span><?php echo \think\Lang::get('ds_del'); ?></span></a>
            </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </form>

</div>

<script type="text/javascript">
    function submit_delete_batch(){
        /* 获取选中的项 */
        var items = '';
        $('.checkitem:checked').each(function(){
            items += this.value + ',';
        });
        if(items != '') {
            items = items.substr(0, (items.length - 1));
            submit_delete(items);
        }
        else {
            alert('<?php echo \think\Lang::get('ds_please_select_item'); ?>');
        }
    }
    function submit_delete(id){
        if(confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')) {
            $('#list_form').attr('method','post');
            $('#list_form').attr('action',"<?php echo url('groupbuy/price_drop'); ?>");
            $('#range_id').val(id);
            $('#list_form').submit();
        }
    }

</script>