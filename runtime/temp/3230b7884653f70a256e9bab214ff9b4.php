<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:98:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\stattrade\stat_income.html";i:1514867091;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>销量分析</h3>
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

 <form method="get" name="formSearch" id="formSearch">
    <div class="w100pre" style="width: 100%;">
      <table class="search-form">
        <tbody>
          <tr>
            <th>年份</th>
            <td>
              <select name="search_year" id="search_year" class="querySelect">
                <?php $__FOR_START_19347__=2017;$__FOR_END_19347__=2028;for($i=$__FOR_START_19347__;$i < $__FOR_END_19347__;$i+=1){ ?>
              <option value="<?php echo $i; ?>" <?php echo \think\Request::instance()->param('search_year')==$i?'selected' : ''; ?>><?php echo $i; ?></option>
              <?php } ?>
              </select>
            </td>
            <th>月份</th>
            <td>
              <select name="search_month" id="search_month" class="querySelect">
              <option value="01" <?php echo \think\Request::instance()->param('search_month')=='01'?'selected':''; ?>>1</option>
              <option value="02" <?php echo \think\Request::instance()->param('search_month')=='02'?'selected':''; ?>>2</option>
              <option value="03" <?php echo \think\Request::instance()->param('search_month')=='03'?'selected':''; ?>>3</option>
              <option value="04" <?php echo \think\Request::instance()->param('search_month')=='04'?'selected':''; ?>>4</option>
              <option value="05" <?php echo \think\Request::instance()->param('search_month')=='05'?'selected':''; ?>>5</option>
              <option value="06" <?php echo \think\Request::instance()->param('search_month')=='06'?'selected':''; ?>>6</option>
              <option value="07" <?php echo \think\Request::instance()->param('search_month')=='07'?'selected':''; ?>>7</option>
              <option value="08" <?php echo \think\Request::instance()->param('search_month')=='08'?'selected':''; ?>>8</option>
              <option value="09" <?php echo \think\Request::instance()->param('search_month')=='09'?'selected':''; ?>>9</option>
              <option value="10" <?php echo \think\Request::instance()->param('search_month')=='10'?'selected':''; ?>>10</option>
              <option value="11" <?php echo \think\Request::instance()->param('search_month')=='11'?'selected':''; ?>>11</option>
              <option value="12" <?php echo \think\Request::instance()->param('search_month')=='12'?'selected':''; ?>>12</option>
              </select>
            </td>
            <td>
              <a href="javascript:void(0);" id="ncsubmit" class="btn-search tooltip" title="<?php echo lang('ds_query'); ?>"></a>
            </td>
          </tr>
        </tbody>
      </table>
      <span class="right" style="margin:12px 0px 6px 4px;"> </span> </div>
  </form>

<div class="stat-info">
  <span>收款金额：<strong><?php echo number_format($plat_data['oot'],2); ?></strong>元</span>
  <span>退款金额：<strong><?php echo number_format($plat_data['oort'],2); ?></strong>元</span>
  <span>实收金额：<strong><?php echo number_format($plat_data['oot']-$plat_data['oort'],2); ?></strong>元</span>
  <span>佣金总额：<strong><?php echo number_format($plat_data['oct'],2); ?></strong>元</span>
  <span>店铺费用：<strong><?php echo number_format($plat_data['osct'],2); ?></strong>元</span>
  <span>总收入：<strong><?php echo number_format($plat_data['ort'],2); ?></strong>元</span>
</div>
  <div id="container" class="w100pre close_float" style="height:50px"></div>
  <div style="text-align:right;">
    <input type="hidden" id="export_type" name="export_type" data-param='{"url":"income?search_year=<?php echo intval(\think\Request::instance()->param('search_year')); ?>&search_month=<?php echo trim(\think\Request::instance()->param('search_month')); ?>&exporttype=excel"}' value="excel"/>
    <a class="btns" href="javascript:void(0);" id="export_btn"><span>导出Excel</span></a> </div>
  <table class="ds-default-table">
    <thead>
      <tr class="thead">
        <th class="align-center">店铺名称</th>
        <th class="align-center">卖家账号</th>
        <th class="align-center">订单金额</th>
        <th class="align-center">收取佣金</th>
        <th class="align-center">退单金额</th>
        <th class="align-center">退回佣金</th>
        <th class="align-center">店铺费用</th>
        <th class="align-center">结算金额</th>
        <th class="align-center">操作</th>
      </tr>
    </thead>
    <tbody id="datatable">
    <?php if(!(empty($store_list) || (($store_list instanceof \think\Collection || $store_list instanceof \think\Paginator ) && $store_list->isEmpty()))): if(is_array($store_list) || $store_list instanceof \think\Collection || $store_list instanceof \think\Paginator): $i = 0; $__LIST__ = $store_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
      <tr class="hover">
        <td class="align-center">
          <a href="<?php echo url('home/store/index',['store_id'=>$v['ob_store_id']]); ?>" target="_blank"><?php echo $v['ob_store_name']; ?></a>
        </td>
        <td class="align-center"><?php echo $v['member_name']; ?></td>
        <td class="align-center"><?php echo $v['ob_order_totals']; ?></td>
        <td class="align-center"><?php echo $v['ob_commis_totals']; ?></td>
        <td class="align-center"><?php echo $v['ob_order_return_totals']; ?></td>
        <td class="align-center"><?php echo $v['ob_commis_return_totals']; ?></td>
        <td class="align-center"><?php echo $v['ob_store_cost_totals']; ?></td>
        <td class="align-center"><?php echo $v['ob_result_totals']; ?></td>
        <td class="align-center">
          <a href="<?php echo url('stattrade/sale',['search_type'=>'month','search_time_month'=>\think\Request::instance()->param('search_month'),'search_time_year'=>\think\Request::instance()->param('search_year'),'store_name'=>$v['ob_store_name']]); ?>">详细</a>
        </td>
      </tr>
     <?php endforeach; endif; else: echo "" ;endif; else: ?>
      <tr class="no_data">
        <td colspan="15"><?php echo lang('ds_no_record'); ?></td>
      </tr>
      <?php endif; ?>
    </tbody>
    <?php if(!(empty($store_list) || (($store_list instanceof \think\Collection || $store_list instanceof \think\Paginator ) && $store_list->isEmpty()))): ?>
    <tfoot>
      <tr class="tfoot">
        <td colspan="15" id="dataFuncs"><div class="pagination"> <?php echo $show_page; ?> </div></td>
      </tr>
    </tfoot>
    <?php endif; ?>
  </table>
</div>
<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/highcharts/highcharts.js"></script>
<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/home/js/common.js"></script>
<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/common/js/statistics.js"></script>
<script>
$(function () {
    $('#ncsubmit').click(function(){
        $('#formSearch').submit();
    });

    //导出图表
    $("#export_btn").click(function(){
        var item = $("#export_type");
        var type = $(item).val();
        if(type == 'excel'){
            download_excel(item);
        }
    });

    $('#ncexport').click(function(){
        $("#")
        $('#formSearch').submit();
    });
});
</script>