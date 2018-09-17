<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:99:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\vrorder\vr_order_index.html";i:1514867091;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3><?php echo \think\Lang::get('order_manage'); ?></h3>
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
    <div class="fixed-empty"></div>
    <form method="get" action="" name="formSearch" id="formSearch">
        <table class="search-form">
            <tbody>
                <tr>
                    <th><label><?php echo \think\Lang::get('order_number'); ?></label></th>
                    <td><input class="txt2" type="text" name="order_sn" value="<?php echo \think\Request::instance()->get('order_sn'); ?>" /></td>
                    <th><?php echo \think\Lang::get('store_name'); ?></th>
                    <td><input class="txt-short" type="text" name="store_name" value="<?php echo \think\Request::instance()->get('store_name'); ?>" /></td>
                    <th><label><?php echo \think\Lang::get('order_state'); ?></label></th>
                    <td colspan="4"><select name="order_state" class="querySelect">
                            <option value=""><?php echo \think\Lang::get('ds_please_choose'); ?></option>
                            <option value="10" <?php if(\think\Request::instance()->get('order_state') == '10'): ?>selected<?php endif; ?>><?php echo \think\Lang::get('order_state_new'); ?></option>
                            <option value="20" <?php if(\think\Request::instance()->get('order_state') == '20'): ?>selected<?php endif; ?>>已付款</option>
                            <option value="40" <?php if(\think\Request::instance()->get('order_state') == '40'): ?>selected<?php endif; ?>><?php echo \think\Lang::get('order_state_success'); ?></option>
                            <option value="0" <?php if(\think\Request::instance()->get('order_state') == '0'): ?>selected<?php endif; ?>><?php echo \think\Lang::get('order_state_cancel'); ?></option>
                        </select></td>

                </tr>
                <tr>
                    <th><label for="query_start_time"><?php echo \think\Lang::get('order_time_from'); ?></label></th>
                    <td><input class="txt date" type="text" value="<?php echo \think\Request::instance()->get('query_start_time'); ?>" id="query_start_time" name="query_start_time">
                        <label for="query_start_time">~</label>
                        <input class="txt date" type="text" value="<?php echo \think\Request::instance()->get('query_end_time'); ?>" id="query_end_time" name="query_end_time"/></td>
                    <th><?php echo \think\Lang::get('buyer_name'); ?></th>
                    <td><input class="txt-short" type="text" name="buyer_name" value="<?php echo \think\Request::instance()->get('buyer_name'); ?>" /></td> <th>付款方式</th>
                    <td>
                        <select name="payment_code" class="w100">
                            <option value=""><?php echo \think\Lang::get('ds_please_choose'); ?></option>
                            <?php foreach($payment_list as $val) { if ($val['payment_code'] == 'offline') continue;?>
                            <option <?php if(\think\Request::instance()->get('payment_code') == $val['payment_code']): ?>selected<?php endif; ?> value="<?php echo $val['payment_code']; ?>"><?php echo $val['payment_name']; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <input type="submit" id="submit" value="<?php echo \think\Lang::get('ds_query'); ?>">
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
            <li>点击查看操作将显示订单（包括电子兑换码）的详细信息</li>
            <li>未付款的订单可以点击取消操作来取消订单</li>
            <li>如果平台已确认收到买家的付款，但系统支付状态并未变更，可以点击收到货款操作，并填写相关信息后更改订单支付状态</li>
        </ul>
    </div>


    <div style="text-align:right;"><a class="btns" target="_blank" href="index.php?<?php echo $_SERVER['QUERY_STRING'];?>&op=export_step1"><span><?php echo \think\Lang::get('ds_export'); ?>Excel</span></a></div>
    <table class="ds-default-table">
        <thead>
            <tr class="thead">
                <th><?php echo \think\Lang::get('order_number'); ?></th>
                <th><?php echo \think\Lang::get('store_name'); ?></th>
                <th><?php echo \think\Lang::get('buyer_name'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('order_time'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('order_total_price'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('payment'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('order_state'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($order_list)>0){foreach($order_list as $order){?>
            <tr class="hover">
                <td><?php echo $order['order_sn'];?></td>
                <td><?php echo $order['store_name'];?></td>
                <td><?php echo $order['buyer_name'];?></td>
                <td class="nowrap align-center"><?php echo date('Y-m-d H:i:s',$order['add_time']);?></td>
                <td class="align-center"><?php echo $order['order_amount'];?></td>
                <td class="align-center"><?php echo orderPaymentName($order['payment_code']);?></td>
                <td class="align-center"><?php echo $order['state_desc'];?></td>
                <td class="w144 align-center"><a href="<?php echo url('/Admin/Vrorder/show_order',['order_id'=>$order['order_id']]); ?>"><?php echo \think\Lang::get('ds_view'); ?></a>

                    <!-- 取消订单 -->
                    <?php if($order['if_cancel']) {?>
                    | <a href="javascript:void(0)" onclick="if (confirm('<?php echo \think\Lang::get('order_confirm_cancel'); ?>')) {
                                location.href = '<?php echo url(' / Admin / Vrorder / change_state',['state_type'=>'cancel','order_id'=>$order['order_id']]); ?>'
                            }">
                        <?php echo \think\Lang::get('order_change_cancel'); ?></a>
                    <?php }?>

                    <!-- 收款 -->
                    <?php if($order['if_system_receive_pay']) {?>
                    | <a href="<?php echo url('/Admin/Vrorder/change_state',['state_type'=>'receive_pay','order_id'=>$order['order_id']]); ?>">
                        <?php echo \think\Lang::get('order_change_received'); ?></a>
                    <?php }?>
                </td>
            </tr>
            <?php }}else{?>
            <tr class="no_data">
                <td colspan="15"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            <?php }?>
        </tbody>
        <tfoot>
            <tr class="tfoot">
                <td colspan="15" id="dataFuncs"><?php echo $show_page; ?></td>
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript">
    $(function () {
        $('#query_start_time').datepicker({dateFormat: 'yy-mm-dd'});
        $('#query_end_time').datepicker({dateFormat: 'yy-mm-dd'});
    });
</script> 
