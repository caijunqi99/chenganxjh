<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:107:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\vrrefund\vr_refund_manage_list.html";i:1514867093;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>虚拟订单退款</h3>
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
        <th><select name="type">
            <option value="order_sn" <?php if(\think\Request::instance()->get('type') == 'order_sn'): ?>selected<?php endif; ?>><?php echo \think\Lang::get('refund_order_ordersn'); ?></option>
            <option value="refund_sn" <?php if(\think\Request::instance()->get('type') == 'refund_sn'): ?>selected<?php endif; ?>><?php echo \think\Lang::get('refund_order_refundsn'); ?></option>
            <option value="store_name" <?php if(\think\Request::instance()->get('type') == 'store_name'): ?>selected<?php endif; ?>><?php echo \think\Lang::get('refund_store_name'); ?></option>
            <option value="goods_name" <?php if(\think\Request::instance()->get('type') == 'goods_name'): ?>selected<?php endif; ?>><?php echo '商品名称'; ?></option>
            <option value="buyer_name" <?php if(\think\Request::instance()->get('type') == 'buyer_name'): ?>selected<?php endif; ?>><?php echo \think\Lang::get('refund_order_buyer'); ?></option>
          </select></th>
        <td><input type="text" class="text" name="key" value="<?php echo \think\Request::instance()->get('key'); ?>" /></td>
          <th><label for="add_time_from">申请时间</label></th>
          <td><input class="txt date" type="text" value="<?php echo \think\Request::instance()->get('add_time_from'); ?>" id="add_time_from" name="add_time_from">
            <label for="add_time_to">~</label>
            <input class="txt date" type="text" value="<?php echo \think\Request::instance()->get('add_time_to'); ?>" id="add_time_to" name="add_time_to"/></td>
          <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo \think\Lang::get('ds_query'); ?>"></a>
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
          <li>买家提交申请，经平台审核后，退款金额以预存款的形式返还给买家（充值卡部分只能退回到充值卡余额）。</li>
      </ul>
  </div>
  
  <table class="ds-default-table">
    <thead>
      <tr class="thead">
        <th><?php echo \think\Lang::get('refund_order_ordersn'); ?></th>
        <th><?php echo \think\Lang::get('refund_order_refundsn'); ?></th>
        <th><?php echo \think\Lang::get('refund_store_name'); ?></th>
        <th>商品名称</th>
        <th><?php echo \think\Lang::get('refund_order_buyer'); ?></th>
        <th class="align-center"><?php echo \think\Lang::get('refund_buyer_add_time'); ?></th>
        <th class="align-center"><?php echo \think\Lang::get('refund_order_refund'); ?></th>
        <th class="align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (is_array($refund_list) && !empty($refund_list)) { foreach ($refund_list as $key => $val) { ?>
      <tr class="bd-line" >
        <td><a href="<?php echo url('vrorder/show_order',['order_id'=>$val['order_id']]); ?>"><?php echo $val['order_sn'];?></a></td>
        <td><?php echo $val['refund_sn'];?></td>
        <td><?php echo $val['store_name']; ?></td>
        <td><a href="<?php echo url('/Home/Goods/index',['goods_id'=>$val['goods_id']]); ?>" target="_blank"><?php echo $val['goods_name']; ?></a></td>
        <td><?php echo $val['buyer_name']; ?></td>
        <td class="align-center"><?php echo date('Y-m-d H:i:s',$val['add_time']);?></td>
        <td class="align-center"><?php echo $val['refund_amount'];?></td>
        <td class="align-center"><a href="<?php echo url('vrrefund/edit',['refund_id'=>$val['refund_id']]); ?>"> 审核 </a></td>
      </tr>
      <?php } ?>
    </tbody>
    <?php } else { ?>
    <tbody>
      <tr class="no_data">
        <td colspan="20"><?php echo \think\Lang::get('no_record'); ?></td>
      </tr>
    </tbody>
    <?php } if (is_array($refund_list) && !empty($refund_list)) { ?>
    <tfoot>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $show_page; ?></div></td>
      </tr>
    </tfoot>
    <?php } ?>
  </table>
</div>
<script type="text/javascript">
$(function(){
    $('#add_time_from').datepicker({dateFormat: 'yy-mm-dd'});
    $('#add_time_to').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>
