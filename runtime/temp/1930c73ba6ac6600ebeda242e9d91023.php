<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:97:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\vrgroupbuy\area_list.html";i:1514877313;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>区域管理</h3>
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
                <th><label for="area_name">区域名称:</label></th>
                <td>
                    <input type="text" value="<?php if(isset($area_name)): ?><?php echo $area_name; endif; ?>" name="area_name" id="area_name" class="txt">
                </td>
                <th><label for="first_letter">首字母:</label></th>
                <td>
                    <select name='first_letter'>
                        <option value=""><?php echo \think\Lang::get('ds_please_choose'); ?>...</option>
                        <?php if(is_array($letter) || $letter instanceof \think\Collection || $letter instanceof \think\Paginator): $i = 0; $__LIST__ = $letter;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$l): $mod = ($i % 2 );++$i;?>
                        <option value='<?php echo $l; ?>' <?php if(isset($first_letter)): if($first_letter == $l): ?>selected<?php endif; endif; ?>><?php echo $l; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </td>
                <td><a href="javascript:document.formSearch.submit();" class="btn-search tooltip" title="<?php echo \think\Lang::get('ds_query'); ?>"></a></td>
            </tr>
            </tbody>
        </table>
    </form>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom">
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示" class="arrow"></span>
        </div>
        <ul>
            <li>商家发布虚拟商品的抢购时，需要选择虚拟抢购所属区域</li>
            <li>显示一级城市名称，可以编辑、删除一级城市，点击查看区域，可以查看该城市下区域列表</li>
            <li>可以按照区域名称、首字母进行查询</li>
        </ul>
    </div>
    
    <form id="list_form" method='post'>
        <input id="area_id" name="area_id" type="hidden" />
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th>区域名称</th>
                <th>首字母</th>
                <th>区号</th>
                <th>邮编</th>
                <th>显示</th>
                <th>添加时间</th>
                <th class="w200 align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
            <tr class="hover edit">
                <td><?php echo $val['area_name']; ?>&nbsp;<a class="btn-add-nofloat marginleft" href="<?php echo url('vrgroupbuy/area_add',['area_id'=>$val['area_id']]); ?>"><span>新增下级</span></a></td>
                <td><?php echo $val['first_letter']; ?></td>
                <td><?php echo $val['area_number']; ?></td>
                <td><?php echo $val['post']; ?></td>
                <td>
                    <?php if($val['hot_city'] == '1'): ?>
                    <?php echo \think\Lang::get('ds_yes'); else: ?>
                    <?php echo \think\Lang::get('ds_no'); endif; ?>
                </td>
                <td><?php echo date("Y-m-d",$val['add_time']); ?></td>
                <td class='align-center'>
                    <a href="<?php echo url('vrgroupbuy/area_view',['parent_area_id'=>$val['area_id']]); ?>">查看区域</a>
                    |
                    <a href="<?php echo url('vrgroupbuy/area_edit',['area_id'=>$val['area_id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a>
                    |
                    <a href="javascript:submit_delete(<?php echo $val['area_id']; ?>)"><?php echo \think\Lang::get('ds_del'); ?></a>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="10"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            <?php endif; ?>
            </tbody>
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
            <tfoot>
            <tr class="tfoot">
                <td id="batchAction" colspan="15">
                    <!--
                    <span class="all_checkbox">
                    <label for="checkall_1"><?php echo \think\Lang::get('ds_select_all'); ?></label>
                    </span>&nbsp;&nbsp; <a href="javascript:void(0)" class="btn" onclick="submit_delete_batch();"><span><?php echo \think\Lang::get('ds_del'); ?></span></a>
                    -->
                    <div class="pagination"><?php echo $show_page; ?></div>
            </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </form>

</div>

<script type="text/javascript">
    function submit_delete_batch() {
        /* 获取选中的项 */
        var items = '';
        $('.checkitem:checked').each(function(){
            items += this.value + ',';
        });
        if (items != '') {
            items = items.substr(0, (items.length - 1));
            submit_delete(items);
        } else {
            alert('<?php echo \think\Lang::get('ds_please_select_item'); ?>');
        }
    }
    function submit_delete(id) {
        if (confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')) {
            $('#list_form').attr('method','post');
            $('#list_form').attr('action',"<?php echo url('vrgroupbuy/area_drop'); ?>");
            $('#area_id').val(id);
            $('#list_form').submit();
        }
    }
</script>