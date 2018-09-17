<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:103:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\predeposit\pdrecharge_list.html";i:1514877307;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>预存款</h3>
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
    <form method="get"  name="formSearch" id="formSearch">
        <table class="search-form">
            <tbody>
                <tr>
                    <th><?php echo \think\Lang::get('admin_predeposit_membername'); ?></th>
                    <td><input type="text" name="mname" class="txt" value='<?php echo \think\Request::instance()->get('mname'); ?>'></td>
                    <th><?php echo \think\Lang::get('admin_predeposit_addtime'); ?></th>
                    <td><input type="text" id="query_start_date" name="query_start_date" class="txt date" value="<?php echo \think\Request::instance()->get('query_start_date'); ?>" >
                        <label>~</label>
                        <input type="text" id="query_end_date" name="query_end_date" class="txt date" value="<?php echo \think\Request::instance()->get('query_start_date'); ?>" ></td>
                    <td>
                        <select id="paystate_search" name="paystate_search">
                            <option value=""><?php echo \think\Lang::get('admin_predeposit_paystate'); ?></option>
                            <option value="0" <?php if(\think\Request::instance()->get('paystate_search') == '0'): ?>selected="selected"<?php endif; ?>>未支付</option>
                            <option value="1" <?php if(\think\Request::instance()->get('paystate_search') == '1'): ?>selected="selected"<?php endif; ?>>已支付</option>
                        </select>
                    </td>
                    <td>
                        <input type="submit" class="submit" value="查询">
                    </td>
                </tr>
            </tbody>
        </table>
    </form>


    <table class="ds-default-table">
        <thead>
            <tr class="thead">
                <th><?php echo \think\Lang::get('admin_predeposit_sn'); ?></th>
                <th><?php echo \think\Lang::get('admin_predeposit_membername'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('admin_predeposit_addtime'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('admin_predeposit_paytime'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('admin_predeposit_payment'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('admin_predeposit_recharge_price'); ?>(<?php echo \think\Lang::get('currency_zh'); ?>)</th>
                <th class="align-center"><?php echo \think\Lang::get('admin_predeposit_paystate'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if(!(empty($recharge_list) || (($recharge_list instanceof \think\Collection || $recharge_list instanceof \think\Paginator ) && $recharge_list->isEmpty()))): if(is_array($recharge_list) || $recharge_list instanceof \think\Collection || $recharge_list instanceof \think\Paginator): $i = 0; $__LIST__ = $recharge_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$recharge): $mod = ($i % 2 );++$i;?>
            <tr>
                <td><?php echo $recharge['pdr_sn']; ?></td>
                <td><?php echo $recharge['pdr_member_name']; ?></td>
                <td><?php echo date("Y-m-d H:i:s",$recharge['pdr_add_time']); ?></td>
                <td><?php if($recharge['pdr_payment_time']|intval('###')): if($recharge['pdr_payment_time'] == '0'): ?><?php echo date("Y-m-d",$recharge['pdr_payment_time']); else: ?><?php echo date("Y-m-d H:i:s",$recharge['pdr_payment_time']); endif; endif; ?></td>
                <td><?php echo $recharge['pdr_payment_name']; ?></td>
                <td><?php echo $recharge['pdr_amount']; ?></td>
                <td><?php if($recharge['pdr_payment_state'] == '0'): ?>未支付<?php else: ?>已支付<?php endif; ?></td>
                <td>
                    <a href="<?php echo url('Predeposit/recharge_info',['id'=>$recharge['pdr_id']]); ?>">查看</a>
                    <a href="javascript:if(confirm('<?php echo \think\Lang::get('sure_drop'); ?>'))window.location ='<?php echo url('Predeposit/drop',['id'=>$recharge['pdr_id']]); ?>'" ><?php echo \think\Lang::get('ds_del'); ?></a>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="20"><?php echo \think\Lang::get('no_record'); ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>


</div>

<script language="javascript">
    $(function () {
        $('#query_start_date').datepicker({dateFormat: 'yy-mm-dd'});
        $('#query_end_date').datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>