<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:88:"C:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\order\index.html";i:1536983892;s:90:"C:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1536983892;s:95:"C:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1536983892;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>想见孩系统后台</title>
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
                <h3>实物订单</h3>
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
    <form method="get">
        <table class="search-form">
            <tbody>
                <tr>
                    <th><?php echo \think\Lang::get('order_number'); ?></th>
                    <td><input type="text" class="text w150" name="order_sn" value="<?php echo \think\Request::instance()->get('order_sn'); ?>"></td>
                    <th><?php echo \think\Lang::get('store_name'); ?></th>
                    <td><input type="text" class="text w150" name="store_name" value="<?php echo \think\Request::instance()->get('store_name'); ?>"></td>
                    <th><?php echo \think\Lang::get('order_state'); ?></th>
                    <td>
                        <select name="order_state" class="querySelect">
                            <option value=""><?php echo \think\Lang::get('ds_please_choose'); ?></option>
                            <option value="10" <?php if(\think\Request::instance()->get('order_state') == '10'): ?>selected<?php endif; ?>><?php echo \think\Lang::get('order_state_new'); ?></option>
                            <option value="20" <?php if(\think\Request::instance()->get('order_state') == '20'): ?>selected<?php endif; ?>><?php echo \think\Lang::get('order_state_pay'); ?></option>
                            <option value="30" <?php if(\think\Request::instance()->get('order_state') == '30'): ?>selected<?php endif; ?>><?php echo \think\Lang::get('order_state_send'); ?></option>
                            <option value="40" <?php if(\think\Request::instance()->get('order_state') == '40'): ?>selected<?php endif; ?>><?php echo \think\Lang::get('order_state_success'); ?></option>
                            <option value="0" <?php if(\think\Request::instance()->get('order_state') == '0'): ?>selected<?php endif; ?>><?php echo \think\Lang::get('order_state_cancel'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><?php echo \think\Lang::get('order_time_from'); ?></th>
                    <td>
                        <input type="text" class="txt date" name="query_start_time" id="query_start_time" value="<?php echo \think\Request::instance()->get('query_start_time'); ?>">
                        &nbsp;–&nbsp;
                        <input id="query_end_time" class="txt date" type="text" name="query_end_time" value="<?php echo \think\Request::instance()->get('query_end_time'); ?>">
                    </td>
                    <th><?php echo \think\Lang::get('buyer_name'); ?></th>
                    <td><input type="text" class="text w80" name="buyer_name" value="<?php echo \think\Request::instance()->get('buyer_name'); ?>"></td>
                    <td>
                        <select name="payment_code">
                            <option value=""><?php echo \think\Lang::get('ds_please_choose'); ?></option>
                            <?php foreach($payment_list as $val): ?> 
                            <option <?php if(\think\Request::instance()->get('payment_code') == $val['payment_code']): ?>selected<?php endif; ?> value="<?php echo $val['payment_code']; ?>"><?php echo $val['payment_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="w70 tc">
                        <label class="submit-border">
                            <input type="submit" class="submit" value="搜索">
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>

    <table class="ds-default-table">
        <thead>
            <tr>
                <th><?php echo \think\Lang::get('order_number'); ?></th>
                <th><?php echo \think\Lang::get('store_name'); ?></th>
                <th><?php echo \think\Lang::get('buyer_name'); ?></th>
                <th><?php echo \think\Lang::get('order_time'); ?></th>
                <th><?php echo \think\Lang::get('order_total_price'); ?></th>
                <th><?php echo \think\Lang::get('payment'); ?></th>
                <th><?php echo \think\Lang::get('order_state'); ?></th>
                <th><?php echo \think\Lang::get('ds_operation'); ?></th>
            </tr>
        </thead>
        <?php if(empty($order_list) || (($order_list instanceof \think\Collection || $order_list instanceof \think\Paginator ) && $order_list->isEmpty())): ?>
        <tbody>
            <tr class="no_data">
                <td colspan="20"><?php echo \think\Lang::get('no_record'); ?></td>
            </tr>
        </tbody>
        <?php else: ?>
        <tbody>
            <?php if(is_array($order_list) || $order_list instanceof \think\Collection || $order_list instanceof \think\Paginator): $i = 0; $__LIST__ = $order_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$order): $mod = ($i % 2 );++$i;?>
            <tr>
                <td><?php echo $order['order_sn']; ?></td>
                <td><?php echo $order['store_name']; ?></td>
                <td><?php echo $order['buyer_name']; ?></td>
                <td><?php echo date("Y-m-d H:i:s",$order['add_time']); ?></td>
                <td><?php echo $order['order_amount']; ?></td>
                <td><?php echo orderPaymentName($order['payment_code']);?></td>
                <td><?php echo orderState($order);?></td>
                <td>
                    <a href="<?php echo url('order/show_order',['order_id'=>$order['order_id']]); ?>"><?php echo \think\Lang::get('ds_view'); ?></a>
                    <?php if($order['if_cancel']): ?>
                    <a href="javascript:void(0)" onclick="if (confirm('<?php echo \think\Lang::get('order_confirm_cancel'); ?>')){location.href = '<?php echo url('/admin/order/change_state',['state_type'=>'cancel','order_id'=>$order['order_id']]); ?>'}" ><?php echo \think\Lang::get('order_change_cancel'); ?></a>
                    <?php endif; if($order['if_system_receive_pay']): ?>
                    <a href="<?php echo url('/admin/order/change_state',['state_type'=>'receive_pay','order_id'=>$order['order_id']]); ?>"><?php echo \think\Lang::get('order_change_received'); ?></a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
        <?php endif; ?>
    </table>
    <?php echo $show_page; ?>
</div>

<script type="text/javascript">
$(function(){
    $('#query_start_time').datepicker({dateFormat: 'yy-mm-dd'});
    $('#query_end_time').datepicker({dateFormat: 'yy-mm-dd'});
});
</script> 

