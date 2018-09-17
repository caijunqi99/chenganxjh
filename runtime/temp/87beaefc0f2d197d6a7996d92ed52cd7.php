<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:91:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\activity\index.html";i:1514877303;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>活动管理</h3>
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
                <th><label for="searchtitle"><?php echo \think\Lang::get('activity_index_title'); ?></label></th>
                <td><input type="text" name="searchtitle" id="searchtitle" class="txt" value='<?php echo \think\Request::instance()->get('searchtitle'); ?>'></td>
                <td><select name="searchstate">
                    <option value="0"><?php echo \think\Lang::get('activity_openstate'); ?></option>
                    <option value="2" <?php if(\think\Request::instance()->get('searchstate') == '2'): ?>selected=selected<?php endif; ?>><?php echo \think\Lang::get('activity_openstate_open'); ?></option>
                    <option value="1" <?php if(\think\Request::instance()->get('searchstate') == '1'): ?>selected=selected<?php endif; ?>><?php echo \think\Lang::get('activity_openstate_close'); ?></option>
                </select>
                </td>
                <th colspan="1"><label for="searchstartdate"><?php echo \think\Lang::get('activity_index_periodofvalidity'); ?></label></th>
                <td>
                    <input type="text" name="searchstartdate" id="searchstartdate" class="txt date" readonly='' value='<?php echo \think\Request::instance()->get('searchstartdate'); ?>'>~<input type="text" name="searchenddate" id="searchenddate" class="txt date" readonly='' value='<?php echo \think\Request::instance()->get('searchenddate'); ?>'></td>
                <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo \think\Lang::get('ds_query'); ?>"></a></td>
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
            <li><?php echo \think\Lang::get('activity_index_help1'); ?></li>
            <li><?php echo \think\Lang::get('activity_index_help2'); ?></li>
            <li><?php echo \think\Lang::get('activity_index_help3'); ?></li>
            <li><?php echo \think\Lang::get('activity_index_help4'); ?></li>
        </ul>
    </div>
    
    
    <form id="listform"  method='post'>
        <input type="hidden" id="listop" name="op" value="del" />
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th class="w24">&nbsp;</th>
                <th class="w48 "><?php echo \think\Lang::get('ds_sort'); ?></th>
                <th class="w270"><?php echo \think\Lang::get('activity_index_title'); ?></th>
                <th class="w96"><?php echo \think\Lang::get('activity_index_banner'); ?></th>
                <!-- <th class="align-center"><?php echo \think\Lang::get('activity_index_type'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('activity_index_style'); ?></th> -->
                <th class="align-center"><?php echo \think\Lang::get('activity_index_start'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('activity_index_end'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('activity_openstate'); ?></th>
                <th class="w150 align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
            </thead>
            <tbody id="treet1">
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr class="hover edit row">
                <td><input type="checkbox" name='activity_id[]' value="<?php echo $v['activity_id']; ?>" class="checkitem"></td>
                <td class="sort"><span class=" editable" title="<?php echo \think\Lang::get('ds_editable'); ?>" required="1" fieldid="<?php echo $v['activity_id']; ?>" ajax_branch='activity_sort' fieldname="activity_sort" nc_type="inline_edit" ><?php echo $v['activity_sort']; ?></span></td>
                <td class="name"><span class=" editable" title="<?php echo \think\Lang::get('ds_editable'); ?>" required="1" fieldid="<?php echo $v['activity_id']; ?>" ajax_branch='activity_title' fieldname="activity_title" nc_type="inline_edit" ><?php echo $v['activity_title']; ?></span></td>
                <td>
                   <div class="link-logo">
                    <span class="thumb size-logo"><i></i>
                    <img height="31" width="88" src="{<?php echo UPLOAD_SITE_URL; ?>/<?php echo ATTACH_ACTIVITY; ?>/<?php echo $v['activity_banner']; ?>" onload="javascript:DrawImage(this,88,31);" />
                   </span>
                  </div>
                </td>

                <td class="nowrap align-center"><?php echo date("Y-m-d",$v['activity_start_date']); ?></td>
                <td class="align-center"><?php echo date("Y-m-d",$v['activity_end_date']); ?></td>
                <td class="align-center"><?php if($v['activity_state'] == '1'): ?><?php echo \think\Lang::get('activity_openstate_open'); else: ?><?php echo \think\Lang::get('activity_openstate_close'); endif; ?></td>
                <td class="align-center">
                    <a href="<?php echo url('activity/edit',['activity_id'=>$v['activity_id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a>&nbsp;|&nbsp;
                    <?php if ($v['activity_state'] == 0 || $v['activity_end_date']<time()){?>
                    <a href="javascript:void(0)" onclick="if(confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')){location.href='<?php echo url('activity/del',['activity_id'=>$v['activity_id']]); ?>';}"><?php echo \think\Lang::get('ds_del'); ?></a>&nbsp;|&nbsp;
                    <?php }?>
                    <a href="<?php echo url('activity/detail',['id'=>$v['activity_id']]); ?>"><?php echo \think\Lang::get('activity_index_deal_apply'); ?></a>
                </td>
            </tr>
           <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="10"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            <?php endif; ?>
            </tbody>
            <tfoot>
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
            <tr class="tfoot">
                <td><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal"></td>
                <td colspan="16"><label for="checkallBottom"><?php echo \think\Lang::get('ds_select_all'); ?></label>
                    &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="submit_form('del');"><span><?php echo \think\Lang::get('ds_del'); ?></span></a>
                    </td>
            </tr>
           <?php endif; ?>
            </tfoot>
        </table>
        <?php echo $show_page; ?>
    </form>

</div>
<script type="text/javascript">
    $("#searchstartdate").datepicker({dateFormat: 'yy-mm-dd'});
    $("#searchenddate").datepicker({dateFormat: 'yy-mm-dd'});
    function submit_form(op){
        if(op=='del'){
            if(!confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')){
                return false;
            }
        }
        $('#listop').val(op);
        $('#listform').submit();
    }
</script>
