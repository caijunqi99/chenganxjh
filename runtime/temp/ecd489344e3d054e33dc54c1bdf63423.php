<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:91:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\groupbuy\index.html";i:1514867092;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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

    <form method="get" name="formSearch">
        <table class="search-form">
            <tbody>
            <tr>
                <th><label for="xianshi_name">抢购名称</label></th>
                <td><input type="text" value="<?php echo \think\Request::instance()->get('groupbuy_name'); ?>" name="groupbuy_name" id="groupbuy_name" class="txt" style="width:100px;"></td>
                <th><label for="store_name"><?php echo \think\Lang::get('store_name'); ?></label></th>
                <td><input type="text" value="<?php echo \think\Request::instance()->get('store_name'); ?>" name="store_name" id="store_name" class="txt" style="width:100px;"></td>
                <th><label for="groupbuy_state">状态</label></th>
                <td>
                    <select name="groupbuy_state" class="w90">
                        <?php if(!(empty($groupbuy_state_array) || (($groupbuy_state_array instanceof \think\Collection || $groupbuy_state_array instanceof \think\Paginator ) && $groupbuy_state_array->isEmpty()))): if(is_array($groupbuy_state_array) || $groupbuy_state_array instanceof \think\Collection || $groupbuy_state_array instanceof \think\Paginator): $i = 0; $__LIST__ = $groupbuy_state_array;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $key; ?>" {eq name="key" value="$Request.get.groupbuy_state" }selected{
                        /eq}>
                        <?php echo $val; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                    </select>
                </td>
                <td><a href="javascript:document.formSearch.submit();" class="btn-search" title="<?php echo \think\Lang::get('ds_query'); ?>"></a></td>
            </tr>
            </tbody>
        </table>
    </form>
    <!--  说明 -->
    
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom">
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示" class="arrow"></span>
        </div>
        <ul>
            <li>管理员可以审核新的抢购活动申请、取消进行中的抢购活动或者删除抢购活动</li>
        </ul>
    </div>
    
    
    <form id="list_form" method="post">
        <input type="hidden" id="group_id" name="group_id"/>
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th colspan="2"><?php echo \think\Lang::get('groupbuy_index_name'); ?></th>
                <th class="align-center" width="120"><?php echo \think\Lang::get('groupbuy_index_start_time'); ?></th>
                <th class="align-center" width="120"><?php echo \think\Lang::get('groupbuy_index_end_time'); ?></th>
                <th class="align-center" width="80"><?php echo \think\Lang::get('groupbuy_index_click'); ?></th>
                <th class="align-center" width="80">已购买</th>
                <th class="align-center" width="80"><?php echo \think\Lang::get('ds_recommend'); ?></th>
                <th class="align-center" width="120"><?php echo \think\Lang::get('groupbuy_index_state'); ?></th>
                <th class="align-center" width="120"><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
            </thead>
            <tbody id="treet1">
            <?php if(!(empty($groupbuy_list) || (($groupbuy_list instanceof \think\Collection || $groupbuy_list instanceof \think\Paginator ) && $groupbuy_list->isEmpty()))): if(is_array($groupbuy_list) || $groupbuy_list instanceof \think\Collection || $groupbuy_list instanceof \think\Paginator): $i = 0; $__LIST__ = $groupbuy_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
            <tr class="hover">
                <td class="w60 picture">
                    <div class="size-56x56"><span class="thumb size-56x56"><i></i>
                        <a target="_blank" href="<?php echo url('home/showgroupbuy/groupbuy_detail',['group_id'=>$val['groupbuy_id']]); ?>"><img src="<?php echo gthumb($val['groupbuy_image']); ?>" style=" max-width: 56px; max-height: 56px;"/></a></span></div>
                </td>
                <td class="group"><p>
                    <a target="_blank" href="<?php echo url('home/showgroupbuy/groupbuy_detail',['group_id'=>$val['groupbuy_id']]); ?>"> <?php echo $val['groupbuy_name']; ?></a>
                </p>
                    <p class="goods"><?php echo \think\Lang::get('groupbuy_index_goods_name'); ?>:
                        <a target="_blank" href="<?php echo url('goods/index',['goods_id'=>$val['goods_id']]); ?>" title="<?php echo $val['goods_name']; ?>"><?php echo $val['goods_name']; ?></a></p>
                    <p class="store"><?php echo \think\Lang::get('groupbuy_index_store_name'); ?>:<a href="<?php echo url('home/showstore/index',['store_id'=>$val['store_id']]); ?>" title="<?php echo $val['store_name']; ?>"><?php echo $val['store_name']; ?></a>
                        <?php if(isset($flippedOwnShopIds[$val['store_id']])): ?>
                        <span class="ownshop">[自营]</span>
                        <?php endif; ?>
                    </p>
                </td>
                <td class="align-center nowarp"><?php echo $val['start_time_text']; ?></td>
                <td class="align-center nowarp"><?php echo $val['end_time_text']; ?></td>
                <td class="align-center"><?php echo $val['views']; ?></td>
                <td class="align-center"><?php echo $val['buy_quantity']; ?></td>
                <td class="yes-onoff align-center">
                    <?php if($val['recommended'] == '0'): ?>
                    <a href="JavaScript:void(0);" class=" disabled" ajax_branch='recommended' nc_type="inline_edit" fieldname="recommended" fieldid="<?php echo $val['groupbuy_id']; ?>" fieldvalue="0" title="<?php echo \think\Lang::get('ds_editable'); ?>">
                        <img src="images/transparent.gif"></a>
                    <?php else: ?>
                    <a href="JavaScript:void(0);" class=" enabled" ajax_branch='recommended' nc_type="inline_edit" fieldname="recommended" fieldid="<?php echo $val['groupbuy_id']; ?>" fieldvalue="1" title="<?php echo \think\Lang::get('ds_editable'); ?>">
                        <img src="images/transparent.gif"></a>
                    <?php endif; ?>
                <td class="align-center"><?php echo $val['groupbuy_state_text']; ?></td>
                <td class="align-center">
                    <?php if($val['reviewable']): ?>
                    <a nctype="btn_review_pass" data-groupbuy-id="<?php echo $val['groupbuy_id']; ?>" href="javascript:;">通过</a>
                    <a nctype="btn_review_fail" data-groupbuy-id="<?php echo $val['groupbuy_id']; ?>" href="javascript:;">拒绝</a>
                    <?php endif; if($val['cancelable']): ?>
                    <a nctype="btn_cancel" data-groupbuy-id="<?php echo $val['groupbuy_id']; ?>" href="javascript:;">取消</a>
                    <?php endif; ?>
                    <a nctype="btn_del" data-groupbuy-id="<?php echo $val['groupbuy_id']; ?>" href="javascript:;">删除</a>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="16"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            <?php endif; ?>
            </tbody>
            <?php if(!(empty($groupbuy_list) || (($groupbuy_list instanceof \think\Collection || $groupbuy_list instanceof \think\Paginator ) && $groupbuy_list->isEmpty()))): ?>
            <tfoot>
            <tr class="tfoot">
                <td colspan="16"><label>
                    &nbsp;&nbsp;
                    <div class="pagination"><?php echo $show_page; ?></div>
                </td>
            </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </form>

</div>
<form id="op_form" action="" method="POST">
    <input type="hidden" id="groupbuy_id" name="groupbuy_id">
</form>
<script type="text/javascript">
    $(document).ready(function () {
        $('[nctype="btn_review_pass"]').on('click', function () {
            if (confirm('确认通过该抢购申请？')) {
                var action = "<?php echo url('groupbuy/groupbuy_review_pass'); ?>";
                var groupbuy_id = $(this).attr('data-groupbuy-id');
                $('#op_form').attr('action', action);
                $('#groupbuy_id').val(groupbuy_id);
                $('#op_form').submit();
            }
        });

        $('[nctype="btn_review_fail"]').on('click', function () {
            if (confirm('确认拒绝该抢购申请？')) {
                var action = "<?php echo url('groupbuy/groupbuy_review_fail'); ?>";
                var groupbuy_id = $(this).attr('data-groupbuy-id');
                $('#op_form').attr('action', action);
                $('#groupbuy_id').val(groupbuy_id);
                $('#op_form').submit();
            }
        });

        $('[nctype="btn_cancel"]').on('click', function () {
            if (confirm('确认取消该抢购活动？')) {
                var action = "<?php echo url('groupbuy/groupbuy_cancel'); ?>";
                var groupbuy_id = $(this).attr('data-groupbuy-id');
                $('#op_form').attr('action', action);
                $('#groupbuy_id').val(groupbuy_id);
                $('#op_form').submit();
            }
        });

        $('[nctype="btn_del"]').on('click', function () {
            if (confirm('确认删除该抢购活动？')) {
                var action = "<?php echo url('groupbuy/groupbuy_del'); ?>";
                var groupbuy_id = $(this).attr('data-groupbuy-id');
                $('#op_form').attr('action', action);
                $('#groupbuy_id').val(groupbuy_id);
                $('#op_form').submit();
            }
        });
    });
</script>