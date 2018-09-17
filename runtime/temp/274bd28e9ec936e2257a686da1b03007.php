<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:99:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\promotionxianshi\index.html";i:1514877307;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>限时折扣</h3>
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
                <th><label for="xianshi_name"><?php echo \think\Lang::get('xianshi_name'); ?></label></th>
                <td><input type="text" value="<?php echo \think\Request::instance()->get('xianshi_name'); ?>" name="xianshi_name" id="xianshi_name"
                           class="txt" style="width:100px;"></td>
                <th><label for="store_name"><?php echo \think\Lang::get('store_name'); ?></label></th>
                <td><input type="text" value="<?php echo \think\Request::instance()->get('store_name'); ?>" name="store_name" id="store_name"
                           class="txt" style="width:100px;"></td>
                <th><label for=""><?php echo \think\Lang::get('ds_state'); ?></label></th>
                <td>
                    <select name="state">
                        <?php if(!(empty($xianshi_state_array) || (($xianshi_state_array instanceof \think\Collection || $xianshi_state_array instanceof \think\Paginator ) && $xianshi_state_array->isEmpty()))): if(is_array($xianshi_state_array) || $xianshi_state_array instanceof \think\Collection || $xianshi_state_array instanceof \think\Paginator): $i = 0; $__LIST__ = $xianshi_state_array;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $key; ?>"
                                <?php if($key == \think\Request::instance()->get('state')): ?>selected<?php endif; ?>><?php echo $val; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                    </select>
                </td>
                <td><a href="javascript:document.formSearch.submit();" class="btn-search" title="<?php echo \think\Lang::get('ds_query'); ?>">&nbsp;</a></td>
            </tr>
            </tbody>
        </table>
    </form>
    <!-- 帮助 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom">
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示" class="arrow"></span>
        </div>
        <ul>
            <li><?php echo \think\Lang::get('xianshi_list_help1'); ?></li>
            <li><?php echo \think\Lang::get('xianshi_list_help2'); ?></li>
            <li><?php echo \think\Lang::get('xianshi_list_help3'); ?></li>
        </ul>
    </div>
    
    <!-- 列表 -->
    <form id="list_form" method="post">
        <input type="hidden" id="object_id" name="object_id"/>
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th class="align-left"><span><?php echo \think\Lang::get('xianshi_name'); ?></span></th>
                <th class="align-left" width="240"><span><?php echo \think\Lang::get('store_name'); ?></span></th>
                <th class="align-center" width="120"><span><?php echo \think\Lang::get('start_time'); ?></span></th>
                <th class="align-center" width="120"><span><?php echo \think\Lang::get('end_time'); ?></span></th>
                <th class="align-center" width="80"><span>购买下限</span></th>
                <th class="align-center" width="80"><span><?php echo \think\Lang::get('ds_state'); ?></span></th>
                <th class="align-center" width="120"><span><?php echo \think\Lang::get('ds_handle'); ?></span></th>
            </tr>
            </thead>
            <tbody id="treet1">
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
            <tr class="hover">
                <td class="align-left"><span><?php echo $val['xianshi_name']; ?></span></td>
                <td class="align-left"><a
                        href="<?php echo url('home/showstore/index',['store_id'=>$val['store_id']]); ?>"><span><?php echo $val['store_name']; ?></span></a>
                    <?php if(isset($flippedOwnShopIds[$val['store_id']])): ?>
                    <span class="ownshop">[自营]</span>
                    <?php endif; ?>
                </td>
                <td class="align-center"><span><?php echo date("Y-m-d H:i",$val['start_time']); ?></span></td>
                <td class="align-center"><span><?php echo date("Y-m-d H:i",$val['end_time']); ?></span></td>
                <td class="align-center"><span><?php echo $val['lower_limit']; ?></span></td>
                <td class="align-center"><span><?php echo $val['xianshi_state_text']; ?></span></td>

                <td class="nowrap align-center">
                    <a href="<?php echo url('promotionxianshi/xianshi_detail',['xianshi_id'=>$val['xianshi_id']]); ?>"><?php echo \think\Lang::get('ds_detail'); ?></a>
                    <?php if($val['editable']): ?>
                    <a nctype="btn_cancel" data-xianshi-id="<?php echo $val['xianshi_id']; ?>"
                       href="javascript:;"><?php echo \think\Lang::get('ds_cancel'); ?></a>
                    <?php endif; ?>
                    <a nctype="btn_del" data-xianshi-id="<?php echo $val['xianshi_id']; ?>"
                       href="javascript:;"><?php echo \think\Lang::get('ds_del'); ?></a>
                </td>
            </tr>
           <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="16"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            <?php endif; ?>
            </tbody>
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
            <tfoot>
            <tr class="tfoot">
                <td></td>
                <td colspan="16">
                    <div class="pagination"><?php echo $show_page; ?> </div>
                </td>
            </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </form>

</div>
<form id="submit_form" action="" method="post">
    <input type="hidden" id="xianshi_id" name="xianshi_id" value="">
</form>

<script type="text/javascript">
    $(document).ready(function(){
        //取消限时折扣
        $('[nctype="btn_cancel"]').on('click', function() {
            if(confirm('<?php echo \think\Lang::get('ds_ensure_cancel'); ?>')) {
                var action = "<?php echo url('promotionxianshi/xianshi_cancel'); ?>";
                $('#submit_form').attr('action', action);
                $('#xianshi_id').val($(this).attr('data-xianshi-id'));
                $('#submit_form').submit();
            }
        })

        //删除限时折扣
        $('[nctype="btn_del"]').on('click', function() {
            if(confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')) {
                var action = "<?php echo url('promotionxianshi/xianshi_del'); ?>";
                $('#submit_form').attr('action', action);
                $('#xianshi_id').val($(this).attr('data-xianshi-id'));
                $('#submit_form').submit();
            }
        })
    });
</script>