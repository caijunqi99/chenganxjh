<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:92:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\pointprod\index.html";i:1514877307;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>兑换礼品</h3>
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
                <th><label for="pg_name"><?php echo \think\Lang::get('admin_pointprod_goods_name'); ?></label></th>
                <td><input type="text" name="pg_name" id="pg_name" class="txt" value='<?php echo \think\Request::instance()->get('pg_name'); ?>'></td>
                <td><select name="pg_state">
                    <option value="" ><?php echo \think\Lang::get('admin_pointprod_state'); ?></option>
                    <option value="show" <?php if(\think\Request::instance()->get('pg_state') == 'show'): ?>selected=selected<?php endif; ?>><?php echo \think\Lang::get('admin_pointprod_show_up'); ?></option>
                    <option value="nshow" <?php if(\think\Request::instance()->get('pg_state') == 'nshow'): ?>selected=selected<?php endif; ?>><?php echo \think\Lang::get('admin_pointprod_show_down'); ?></option>
                    <option value="commend" <?php if(\think\Request::instance()->get('pg_state') == 'commend'): ?>selected=selected<?php endif; ?>><?php echo \think\Lang::get('admin_pointprod_commend'); ?></option>
                </select></td>
                <td>
                    <a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo \think\Lang::get('ds_query'); ?>"></a>
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
            <li><?php echo \think\Lang::get('pointprod_help1'); ?></li>
        </ul>
    </div>
    <form method='post' id="form_prod" action="<?php echo url('pointprod/prod_dropall'); ?>">
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th><?php echo \think\Lang::get('admin_pointprod_goods_name'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('admin_pointprod_goods_points'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('admin_pointprod_goods_price'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('admin_pointprod_goods_storage'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('admin_pointprod_goods_view'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('admin_pointprod_salenum'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('admin_pointprod_show_up'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('admin_pointprod_commend'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!(empty($prod_list) || (($prod_list instanceof \think\Collection || $prod_list instanceof \think\Paginator ) && $prod_list->isEmpty()))): if(is_array($prod_list) || $prod_list instanceof \think\Collection || $prod_list instanceof \think\Paginator): $i = 0; $__LIST__ = $prod_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr class="hover">
                <td class="w24"><input type="checkbox" name="pg_id[]" value="<?php echo $v['pgoods_id']; ?>" class="checkitem"></td>
                <td class="w48 picture"><div class="size-44x44"><span class="thumb size-44x44"><i></i><img src="<?php echo $v['pgoods_image_small']; ?>" onload="javascript:DrawImage(this,44,44);"/></span></div></td>
                <td><a href="<?php echo url('home/pointprod/pinfo',['id'=>$v['pgoods_id']]); ?>" target="_blank" ><?php echo $v['pgoods_name']; ?></a></td>
                <td class="align-center"><?php echo $v['pgoods_points']; ?></td>
                <td class="align-center"><?php echo $v['pgoods_price']; ?></td>
                <td class="align-center"><?php echo $v['pgoods_storage']; ?></td>
                <td class="align-center"><?php echo $v['pgoods_view']; ?></td>
                <td class="align-center"><?php echo $v['pgoods_salenum']; ?></td>
                <td class="align-center power-onoff">
                    <?php if($v['pgoods_show'] == '0'): ?>
                    <a href="JavaScript:void(0);" class=" disabled" ajax_branch='pgoods_show' nc_type="inline_edit" fieldname="pgoods_show" fieldid="<?php echo $v['pgoods_id']; ?>" fieldvalue="0" title="<?php echo \think\Lang::get('ds_editable'); ?>"><img src="<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/treetable/transparent.gif"></a>
                    <?php else: ?>
                    <a href="JavaScript:void(0);" class=" enabled" ajax_branch='pgoods_show' nc_type="inline_edit" fieldname="pgoods_show" fieldid="<?php echo $v['pgoods_id']; ?>" fieldvalue="1" title="<?php echo \think\Lang::get('ds_editable'); ?>"><img src="<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/treetable/transparent.gif"></a>
                    <?php endif; ?>
                <td class="align-center yes-onoff">
                <?php if($v['pgoods_commend'] == '0'): ?>
                    <a href="JavaScript:void(0);" class=" disabled" ajax_branch='pgoods_commend' nc_type="inline_edit" fieldname="pgoods_commend" fieldid="<?php echo $v['pgoods_id']; ?>" fieldvalue="0" title="<?php echo \think\Lang::get('ds_editable'); ?>"><img src="<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/treetable/transparent.gif"></a>
                    <?php else: ?>
                    <a href="JavaScript:void(0);" class=" enabled" ajax_branch='pgoods_commend' nc_type="inline_edit" fieldname="pgoods_commend" fieldid="<?php echo $v['pgoods_id']; ?>" fieldvalue="1" title="<?php echo \think\Lang::get('ds_editable'); ?>"><img src="<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/treetable/transparent.gif"></a>
                    <?php endif; ?></td>
                <td class="w72 align-center"><a href="<?php echo url('pointprod/prod_edit',['pg_id'=>$v['pgoods_id']]); ?>" class="edit"><?php echo \think\Lang::get('ds_edit'); ?></a> |
                    <a href="javascript:void(0)" onclick="if(confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')){
                    window.location='<?php echo url('pointprod/prod_drop',['pg_id'=>$v['pgoods_id']]); ?>';}
                    else{return false;}">
                    <?php echo \think\Lang::get('ds_del'); ?>
                </a></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="10"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            <?php endif; ?>
            </tbody>
            <tfoot>
            <?php if(!(empty($prod_list) || (($prod_list instanceof \think\Collection || $prod_list instanceof \think\Paginator ) && $prod_list->isEmpty()))): ?>
            <tr>
                <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
                <td colspan="16" id="dataFuncs"><label for="checkallBottom"><?php echo \think\Lang::get('ds_select_all'); ?></label>
                    &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="submit_form('prod_dropall');"><span><?php echo \think\Lang::get('ds_del'); ?></span></a>
                    <div class="pagination"> <?php echo $show_page; ?> </div></td>
            </tr>
            <?php endif; ?>
            </tfoot>
        </table>
    </form>

</div>
<script type="text/javascript">
    function submit_form(op){
        if(op=='prod_dropall'){
            if(!confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')){
                return false;
            }
        }
        $('#list_op').val(op);
        $('#form_prod').submit();
    }
</script>