<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:88:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\brand\index.html";i:1514877304;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>品牌管理</h3>
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

    <form method="get" name="formSearch" id="formSearch">
        <table class="search-form">
            <tbody>
            <tr>
                <th><label for="search_brand_name"><?php echo \think\Lang::get('brand_index_name'); ?></label></th>
                <td><input class="txt" name="search_brand_name" id="search_brand_name" value="<?php echo $search_brand_name; ?>" type="text"></td>
                <th><label for="search_brand_class"><?php echo \think\Lang::get('brand_index_class'); ?></label></th>
                <td><input class="txt" name="search_brand_class" id="search_brand_class" value="<?php echo $search_brand_class; ?>" type="text"></td>
                <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo \think\Lang::get('ds_query'); ?>"></a>
                    <?php if($search_brand_name != '' || $search_brand_class != ''): ?>
                    <a class="btns " href="<?php echo url('brand/index'); ?>" title="<?php echo \think\Lang::get('ds_cancel_search'); ?>"><span><?php echo \think\Lang::get('ds_cancel_search'); ?></span></a>
                   <?php endif; ?>
                </td>
            </tr>
            </tbody>
        </table>
        <input type="hidden" value="" name="export">
    </form>
    
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom">
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示" class="arrow"></span>
        </div>
        <ul>
            <li><?php echo \think\Lang::get('brand_index_help1'); ?></li>
            <li><?php echo \think\Lang::get('brand_index_help2'); ?></li>
            <li><?php echo \think\Lang::get('brand_index_help3'); ?></li>
        </ul>
    </div>
    
    
    <form method='post' onsubmit="if(confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')){return true;}else{return false;}" name="brandForm">
        <div style="text-align:right;"><a class="btns" href="<?php echo url('brand/export_step1'); ?>" id="ncexport"><span><?php echo \think\Lang::get('ds_export'); ?>Excel</span></a></div>
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th class="w24"></th>
                <th class="w48"><?php echo \think\Lang::get('ds_sort'); ?></th>
                <th class="w270"><?php echo \think\Lang::get('brand_index_name'); ?></th>
                <th class="w150"><?php echo \think\Lang::get('brand_index_class'); ?></th>
                <th><?php echo \think\Lang::get('brand_index_pic_sign'); ?></th>
                <th class="align-center">展示方式</th>
                <th class="align-center"><?php echo \think\Lang::get('ds_recommend'); ?></th>
                <th class="w72 align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!(empty($brand_list) || (($brand_list instanceof \think\Collection || $brand_list instanceof \think\Paginator ) && $brand_list->isEmpty()))): if(is_array($brand_list) || $brand_list instanceof \think\Collection || $brand_list instanceof \think\Paginator): $i = 0; $__LIST__ = $brand_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr class="hover edit">
                <td><input value="<?php echo $v['brand_id']; ?>" class="checkitem" type="checkbox" name="del_brand_id[]"></td>
                <td class="sort"><span class=" editable"  nc_type="inline_edit" fieldname="brand_sort" ajax_branch='brand_sort' fieldid="<?php echo $v['brand_id']; ?>" datatype="pint" maxvalue="255" title="<?php echo \think\Lang::get('ds_editable'); ?>"><?php echo $v['brand_sort']; ?></span></td>
                <td class="name"><span class=" editable" nc_type="inline_edit" fieldname="brand_name" ajax_branch='brand_name' fieldid="<?php echo $v['brand_id']; ?>" required="1"  title="<?php echo \think\Lang::get('ds_editable'); ?>"><?php echo $v['brand_name']; ?></span></td>
                <td class="class"><?php echo $v['brand_class']; ?></td>
                <td class="picture"><div class="brand-picture"><img src="<?php echo brandImage($v['brand_pic']); ?>" style="height: 55px;width: 55px"/></div></td>
                <td class="align-center"><?php echo $v['show_type']==1?'文字':'图片'; ?></td>
                <td class="align-center yes-onoff">
                    <?php if($v['brand_recommend'] == '0'): ?>
                    <a href="JavaScript:void(0);" class=" disabled" ajax_branch='brand_recommend' nc_type="inline_edit" fieldname="brand_recommend" fieldid="<?php echo $v['brand_id']; ?>" fieldvalue="0" title="<?php echo \think\Lang::get('ds_editable'); ?>"><img src="<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/treetable/transparent.gif"></a>
                    <?php else: ?>
                    <a href="JavaScript:void(0);" class=" enabled" ajax_branch='brand_recommend' nc_type="inline_edit" fieldname="brand_recommend" fieldid="<?php echo $v['brand_id']; ?>" fieldvalue="1"  title="<?php echo \think\Lang::get('ds_editable'); ?>"><img src="<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/treetable/transparent.gif"></a>
                   <?php endif; ?>
                </td>
                <td class="align-center">
                    <a href="<?php echo url('brand/brand_edit',['brand_id'=>$v['brand_id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a>&nbsp;|&nbsp;
                    <a href="javascript:void(0)" onclick="if(confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>'))
                        location.href='<?php echo url('brand/brand_del',['brand_id'=>$v['brand_id']]); ?>';"><?php echo \think\Lang::get('ds_del'); ?></a></td>
            </tr>
           <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="10"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            <?php endif; ?>
            </tbody>
            <tfoot>
            <?php if(!(empty($brand_list) || (($brand_list instanceof \think\Collection || $brand_list instanceof \think\Paginator ) && $brand_list->isEmpty()))): ?>
            <tr colspan="15" class="tfoot">
                <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
                <td colspan="16"><label for="checkallBottom"><?php echo \think\Lang::get('ds_select_all'); ?></label>
                    &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="document.brandForm.submit()"><span><?php echo \think\Lang::get('ds_del'); ?></span></a>
                    <div class="pagination"> <?php echo $page; ?> </div></td>
            </tr>
           <?php endif; ?>
            </tfoot>
        </table>
    </form>
    <div class="clear"></div>
</div>
<script type="text/javascript" src="<?php echo config('url_domain_root'); ?>static/plugins/jquery.edit.js" charset="utf-8"></script>
