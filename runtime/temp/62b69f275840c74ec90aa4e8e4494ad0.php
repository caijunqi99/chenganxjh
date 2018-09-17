<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:96:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\groupbuy\class_list.html";i:1514877305;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
            <li>抢购分类最多为2级分类，商家发布抢购活动时选择分类，用于抢购聚合页对抢购活动进行筛选</li>
        </ul>
    </div>

    <form id="list_form" method='post'>
        <input id="class_id" name="class_id" type="hidden"/>
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th></th>
                <th><?php echo \think\Lang::get('ds_sort'); ?></th>
                <th><?php echo \think\Lang::get('goods_class_index_name'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
            <tr class="hover edit <?php if($val['class_parent_id'] != '0'): ?> two<?php endif; ?> parent<?php echo $val['class_parent_id']; ?>">
                <td class="w36"><input type="checkbox" value="<?php echo $val['class_id']; ?>" class="checkitem">
                    <?php if($val['have_child'] == '1'): ?>
                    <img class="node_parent" state="close" node_id="parent<?php echo $val['class_id']; ?>"
                         src="<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/treetable/tv-expandable.gif">
                    <?php endif; ?>
                </td>
                <td class="w48 sort">
                    <span title="<?php echo \think\Lang::get('ds_editable'); ?>" ajax_branch="class_sort" datatype="number"
                          fieldid="<?php echo $val['class_id']; ?>" fieldname="sort" nc_type="inline_edit"
                          class="editable "><?php echo $val['sort']; ?></span></td>
                <td class="name">
                    <?php if($val['class_parent_id'] != '0'): ?>
                    <img fieldid="<?php echo $val['class_id']; ?>" status="close" nc_type="flex"
                         src="<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/treetable/tv-item-last.gif">
                    <?php endif; ?>
                    <span title="<?php echo \think\Lang::get('ds_editable'); ?>" required="1" fieldid="<?php echo $val['class_id']; ?>"
                          ajax_branch="class_name" fieldname="class_name" nc_type="inline_edit"
                          class="node_name editable ">
                        <?php echo $val['class_name']; ?>
                        </span>
                    <?php if($val['class_parent_id'] == '0'): ?>
                    <a href="<?php echo url('groupbuy/class_add',['parent_id'=>$val['class_id']]); ?>"
                       class="btn-add-nofloat marginleft"><span><?php echo \think\Lang::get('ds_add_sub_class'); ?></span></a>
                    <?php endif; ?></td>
                <td class="w156 align-center">

                    <a href="JavaScript:void(0);" onclick="submit_delete('<?php echo $val['class_id']; ?>')"><?php echo \think\Lang::get('ds_del'); ?></a>
                </td>
            </tr>
           <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="10"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <td colspan="20"><a class="btn-add marginleft" href="<?php echo url('groupbuy/class_add'); ?>">添加分类</a>
                </td>
            </tr>
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
    $(document).ready(function(){
        $(".two").hide();
        $(".node_parent").click(function(){
            var node_id = $(this).attr('node_id');
            var state = $(this).attr('state');
            if(state == 'close') {
                $("."+node_id).show();
                $(this).attr('state','open');
                $(this).attr('src',"<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/treetable/tv-collapsable.gif");
            }
            else {
                $("."+node_id).hide();
                $(this).attr('state','close');
                $(this).attr('src',"<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/treetable/tv-expandable.gif");
            }
        });
    });
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
            $('#list_form').attr('action',"<?php echo url('groupbuy/class_drop'); ?>");
            $('#class_id').val(id);
            $('#list_form').submit();
        }
    }

</script>