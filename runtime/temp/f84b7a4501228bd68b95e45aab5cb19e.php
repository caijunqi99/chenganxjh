<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:89:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\vrbill\index.html";i:1514867091;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
        <h3>虚拟订单结算</h3>
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
          <th><?php echo \think\Lang::get('order_time_from'); ?>按年份搜索</th>
          <td>
              <select name="query_year" class="querySelect">
                  <option value=""><?php echo \think\Lang::get('ds_please_choose'); ?></option>
                  <?php for($i = date('Y',TIMESTAMP)-5; $i <= date('Y',TIMESTAMP)+2; $i++) { ?>
                  <option value="<?php echo $i;?>" <?php if(\think\Request::instance()->get('query_year') == $i): ?>selected<?php endif; ?>><?php echo $i;?></option>
                  <?php } ?>
              </select>
          </td>
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
          <li>此处列出了平台每月的结算信息汇总，点击查看可以查看本月详细的店铺账单信息列表</li>
      </ul>
  </div>
  
  
  <table class="ds-default-table">
    <thead>
      <tr class="thead">
        <th><?php echo \think\Lang::get('order_number'); ?>账单（月）</th>
        <th class="align-center">开始日期</th>
        <th class="align-center">结束日期</th>
        <th class="align-center">订单金额</th>
        <th class="align-center">收取佣金</th>
        <th class="align-center">本期应结</th>
        <th class="align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(is_array($bill_list) && !empty($bill_list)){foreach($bill_list as $bill){?>
      <tr class="hover">
        <td>
            <?php echo substr($bill['os_month'],0,4).'-'.substr($bill['os_month'],4);?>
        </td>
        <td class="nowrap align-center"><?php echo date('Y-m-d',$bill['os_start_date']);?></td>
        <td class="nowrap align-center"><?php echo date('Y-m-d',$bill['os_end_date']);?></td>
        <td class="align-center"><?php echo $bill['os_order_totals'];?></td>
        <td class="align-center"><?php echo $bill['os_commis_totals'];?></td>
        <td class="align-center"><?php echo $bill['os_result_totals'];?></td>
        <td class="align-center">
        <a href="<?php echo url('vrbill/show_statis',['os_month'=>$bill['os_month']]); ?>"><?php echo \think\Lang::get('ds_view'); ?></a>
        </td>
      </tr>
      <?php }}else{?>
      <tr class="no_data">
        <td colspan="15"><?php echo \think\Lang::get('ds_no_record'); ?></td>
      </tr>
      <?php }?>
    </tbody>
    <?php if(is_array($bill_list) && !empty($bill_list)){?>
    <tfoot>
      <tr class="tfoot">
        <td colspan="15" id="dataFuncs"><?php echo $show_page; ?></td>
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