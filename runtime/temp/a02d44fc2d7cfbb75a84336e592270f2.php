<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:93:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\vrgroupbuy\index.html";i:1514877313;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>分类管理</h3>
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
            <li>商家发布虚拟商品的抢购时，需要选择虚拟抢购所属分类</li>
            <li>通过修改排序数字可以控制前台线下商城分类的显示顺序，数字越小越靠前</li>
            <li>可以对分类名称进行修改,可以新增下级分类</li>
            <li>可以对分类进行编辑、删除操作</li>
            <li>点击行首的"+"号，可以展开下级分类</li>
        </ul>
    </div>
    
    
    <form method='post' id="list_form">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="submit_type" id="submit_type" value="" />
        <input type="hidden" name="class_id" id="class_id">
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th></th>
                <th><?php echo \think\Lang::get('ds_sort'); ?></th>
                <th>分类名称</th>
                <th><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;if($val['parent_class_id'] == '0'): ?>
            <tr class="hover edit">
                <td class="w48">
                    <input type="checkbox" value="<?php echo $val['class_id']; ?>" class="checkitem">
                    <img class="class_parent" class_id="class_id<?php echo $val['class_id']; ?>" status="open" src="<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/treetable/tv-expandable.gif">
                </td>
                <td class="w48 sort">
                    <span nc_type="inline_edit" ajax_branch="class" column_id="<?php echo $val['class_id']; ?>" title="<?php echo \think\Lang::get('ds_editable'); ?>" class="editable tooltip" fieldid="<?php echo $val['class_id']; ?>" fieldname="class_sort" ><?php echo $val['class_sort']; ?></span>
                </td>
                <td class="name">
                    <span nc_type="inline_edit" ajax_branch="class" column_id="<?php echo $val['class_id']; ?>" title="<?php echo \think\Lang::get('ds_editable'); ?>" class="editable tooltip" fieldname="class_name" fieldid="<?php echo $val['class_id']; ?>"><?php echo $val['class_name']; ?></span>
                    <a class="btn-add-nofloat marginleft" href="<?php echo url('vrgroupbuy/class_add',['parent_class_id'=>$val['class_id']]); ?>"><span>新增下级</span></a>
                </td>
                <td>
                    <a href="<?php echo url('vrgroupbuy/class_edit',['class_id'=>$val['class_id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a> |
                    <a href="javascript:submit_delete(<?php echo $val['class_id']; ?>)"><?php echo \think\Lang::get('ds_del'); ?></a>
                </td>
            </tr>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vall): $mod = ($i % 2 );++$i;if($vall['parent_class_id'] == $val['class_id']): ?>
            <tr class="hover edit class_id<?php echo $val['class_id']; ?>" style="display:none;">
                <td class="w48"><input type="checkbox" value="<?php echo $vall['class_id']; ?>" class="checkitem"></td>
                <td class="w48 sort"><span nc_type="inline_edit" ajax_branch="class" column_id="<?php echo $vall['class_id']; ?>" title="<?php echo \think\Lang::get('ds_editable'); ?>" class="editable tooltip" fieldid="<?php echo $vall['class_id']; ?>" fieldname="class_sort" ><?php echo $vall['class_sort']; ?></span></td>
                <td class="name">
                    <span nc_type="inline_edit" ajax_branch="class" column_id="<?php echo $vall['class_id']; ?>" title="<?php echo \think\Lang::get('ds_editable'); ?>" class="editable tooltip" fieldname="class_name" fieldid="<?php echo $vall['class_id']; ?>"><?php echo $vall['class_name']; ?></span></td>
                <td class="w200">
                    <a href="<?php echo url('vrgroupbuy/class_edit',['class_id'=>$vall['class_id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a> |
                    <a href="javascript:submit_delete(<?php echo $vall['class_id']; ?>)"><?php echo \think\Lang::get('ds_del'); ?></a>
                </td>
            </tr>
            <?php endif; endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="10"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            <?php endif; ?>
            </tbody>
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
            <tfoot>
            <tr class="tfoot">
                <td><input type="checkbox" class="checkall" id="checkall_1"></td>
                <td id="batchAction" colspan="15"><span class="all_checkbox">
            <label for="checkall_1"><?php echo \think\Lang::get('ds_select_all'); ?></label>
            </span>&nbsp;&nbsp; <a href="javascript:void(0)" class="btn" onclick="submit_delete_batch();"><span><?php echo \think\Lang::get('ds_del'); ?></span></a>
            </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </form>

</div>
<script type="text/javascript" src="<?php echo config('url_domain_root'); ?>static/plugins/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){
        $(".class_parent").click(function() {
            if ($(this).attr("status") == "open") {
                $(this).attr("status","close");
                $(this).attr("src","<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/treetable/tv-collapsable.gif");
                $("."+$(this).attr("class_id")).show();
            } else {
                $(this).attr("status","open");
                $(this).attr("src","<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/treetable/tv-expandable.gif");
                $("."+$(this).attr("class_id")).hide();
            }
        });

        //行内ajax编辑
        $('span[nc_type="class_sort"]').inline_edit();
        $('span[nc_type="class_name"]').inline_edit();

    });
    function submit_delete_batch(){
        /* 获取选中的项 */
        var items = '';
        $('.checkitem:checked').each(function() {
            items += this.value + ',';
        });
        if (items != '') {
            items = items.substr(0, (items.length - 1));
            submit_delete(items);
        } else {
            alert('<?php echo \think\Lang::get('ds_please_select_item'); ?>');
        }
    }
    function submit_delete(id){
        if (confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')) {
            $('#list_form').attr('method','post');
            $('#list_form').attr('action',"<?php echo url('vrgroupbuy/class_del'); ?>");
            $('#class_id').val(id);
            $('#list_form').submit();
        }
    }

</script>