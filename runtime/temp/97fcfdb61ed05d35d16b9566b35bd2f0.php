<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\rechargecard\index.html";i:1514877307;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>平台充值卡</h3>
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
                <th><label for="search-sn">充值卡卡号</label></th>
                <td><input class="txt" type="text" name="sn" id="search-sn" value="<?php echo \think\Request::instance()->get('sn'); ?>" /></td>
                <th><label for="search-batchflag">批次标识</label></th>
                <td><input class="txt" type="text" name="batchflag" id="search-batchflag" value="<?php echo \think\Request::instance()->get('batchflag'); ?>" /></td>
                <th><label for="search-state">领取状态</label></th>
                <td>
                    <select name="state" id="search-state">
                        <option value="">全部</option>
                        <option value="0">未被领取</option>
                        <option value="1">已被领取</option>
                    </select>
                    <script>$('#search-state').val('<?php echo \think\Request::instance()->get('state'); ?>');</script>
                </td>
                <td>
                    <a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo \think\Lang::get('ds_query'); ?>"></a>
                    <?php if(\think\Request::instance()->isget()): ?>
                    <a class="btns " href="<?php echo url('rechargecard/index'); ?>" title="<?php echo \think\Lang::get('ds_cancel_search'); ?>"><span><?php echo \think\Lang::get('ds_cancel_search'); ?></span></a>
                    <?php endif; ?>
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
            <li>平台发布充值卡，用户可在会员中心通过输入正确充值卡号的形式对其充值卡账户进行充值。</li>
        </ul>
    </div>
    

    <div style="text-align: right;"><a class="btns" href="<?php echo url('rechargecard/export_step1'); ?>" target="_blank"><span>导出Excel</span></a></div>

    <form method="post" action="<?php echo url('rechargecard/del_card_batch'); ?>" onsubmit="" name="form1">
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th class="w24"> </th>
                <th class=" ">充值卡卡号</th>
                <th class=" ">批次标识</th>
                <th class="w60 align-center">面额(元)</th>
                <th class="w96 align-center">发布管理员</th>
                <th class="w150 align-center">发布时间</th>
                <th class="w270 align-center">领取状态</th>
                <th class="w48 align-center"><?php echo \think\Lang::get('ds_handle'); ?> </th>
            </tr>
            </thead>
            <?php if(empty($card_list) || (($card_list instanceof \think\Collection || $card_list instanceof \think\Paginator ) && $card_list->isEmpty())): ?>
            <tbody>
            <tr class="no_data">
                <td colspan="20"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            </tbody>
           <?php else: ?>
            <tbody>
            <?php if(is_array($card_list) || $card_list instanceof \think\Collection || $card_list instanceof \think\Paginator): $i = 0; $__LIST__ = $card_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
            <tr class="space">
                <td class="w24">
                    <?php if($val['state'] == 0): ?>
                    <input type="checkbox" class="checkitem" name="ids[]" value="<?php echo $val['id']; ?>" />
                    <?php else: ?>
                    <input type="checkbox" disabled="disabled" />
                    <?php endif; ?>
                </td>
                <td class=""><?php echo $val['sn']; ?></td>
                <td class=""><?php echo $val['batchflag']; ?></td>
                <td class="align-center"><?php echo $val['denomination']; ?></td>
                <td class="align-center"><?php echo $val['admin_name']; ?></td>
                <td class="align-center"><?php echo date("Y-m-d H:i:s",$val['tscreated']); ?></td>
                <td class="align-center">
                    <?php if($val['state'] == 1 && $val['member_id'] >0 && $val['tsused'] >0): ?>
                    会员 <?php echo $val['member_name']; ?> 在 <?php echo date("Y-m-d H:i:s",$val['tsused']); ?>领取
                    <?php else: ?>
                    未被领取
                   <?php endif; ?>
                </td>
                <td class="align-center">
                    <?php if($val['state'] == 0): ?>
                    <a onclick="return confirm('确定删除？');" href="<?php echo url('rechargecard/del_card',['id'=>$val['id']]); ?>" class="normal"><?php echo \think\Lang::get('ds_del'); ?></a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
            <tfoot>
            <tr class="tfoot">
                <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
                <td colspan="16"><label for="checkallBottom"><?php echo \think\Lang::get('ds_select_all'); ?></label>
                    &nbsp;&nbsp;<a href="javascript:void(0);" class="btn" onclick="if ($('.checkitem:checked ').length == 0) { alert('请选择需要删除的选项！');return false;}  if(confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')){document.form1.submit();}"><span><?php echo \think\Lang::get('ds_del'); ?></span></a>
                    </td>
            </tr>
            </tfoot>
            <?php endif; ?>
        </table>
        
        <div class="pagination"><?php echo $show_page; ?></div>
    </form>

</div>