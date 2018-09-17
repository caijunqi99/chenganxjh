<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:104:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\statgoods\stat_goods_prange.html";i:1514855479;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>商品分析</h3>
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
  <form method="get"  name="formSearch" id="formSearch">
    <div class="w100pre" style="width: 100%;">
        <table class="search-form">
          <tbody>
            <tr>
              <td id="searchgc_td"></td>
              <input type="hidden" id="choose_gcid" name="choose_gcid" value="0"/>
              <td>
              	<select name="search_type" id="search_type" class="querySelect">
                  <option value="day" <?php if(isset($search_arr['search_type']) && $search_arr['search_type'] == 'day'): ?>selected<?php endif; ?>>按照天统计</option>
                  <option value="week" <?php if(isset($search_arr['search_type']) && $search_arr['search_type'] == 'week'): ?>selected<?php endif; ?>>按照周统计</option>
                  <option value="month" <?php if(isset($search_arr['search_type']) && $search_arr['search_type'] == 'month'): ?>selected<?php endif; ?>>按照月统计</option>
                </select>
              </td>
              <td id="searchtype_day" style="display:none;">
              	<input class="txt date" type="text" value="<?php echo date('Y-m-d',$search_arr['day']['search_time']); ?>" id="search_time" name="search_time">
              </td>
              <td id="searchtype_week" style="display:none;">
              	<select name="searchweek_year" class="querySelect">
                  <?php if(is_array($year_arr) || $year_arr instanceof \think\Collection || $year_arr instanceof \think\Paginator): $i = 0; $__LIST__ = $year_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
              		<option value="<?php echo $key; ?>" <?php echo $search_arr['week']['current_year']==$key?'selected':''; ?>><?php echo $v; ?></option>
                  <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
                <select name="searchweek_month" class="querySelect">
                  <?php if(is_array($month_arr) || $month_arr instanceof \think\Collection || $month_arr instanceof \think\Paginator): $i = 0; $__LIST__ = $month_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
              		<option value="<?php echo $key; ?>" <?php echo $search_arr['week']['current_month']==$key?'selected':''; ?>><?php echo $v; ?></option>
                  <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
                <select name="searchweek_week" class="querySelect">
                  <?php if(is_array($week_arr) || $week_arr instanceof \think\Collection || $week_arr instanceof \think\Paginator): $i = 0; $__LIST__ = $week_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
              		<option value="<?php echo $v['key']; ?>" <?php echo $search_arr['week']['current_week']==$key?'selected':''; ?>><?php echo $v['val']; ?></option>
                  <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
              </td>
              <td id="searchtype_month" style="display:none;">
              	<select name="searchmonth_year" class="querySelect">
                  <?php if(is_array($year_arr) || $year_arr instanceof \think\Collection || $year_arr instanceof \think\Paginator): $i = 0; $__LIST__ = $year_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
              		<option value="<?php echo $key; ?>" <?php echo $search_arr['month']['current_year']==$key?'selected':''; ?>><?php echo $v; ?></option>
                  <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
                <select name="searchmonth_month" class="querySelect">
                  <?php if(is_array($month_arr) || $month_arr instanceof \think\Collection || $month_arr instanceof \think\Paginator): $i = 0; $__LIST__ = $month_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
              		<option value="<?php echo $key; ?>" <?php echo $search_arr['month']['current_month']==$key?'selected':''; ?>><?php echo $v; ?></option>
                  <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
              </td>
              <td>
                <a href="javascript:void(0);" id="ncsubmit" class="btn-search tooltip"></a>
              </td>
            </tr>
          </tbody>
        </table>
    </div>
  </form>

  <div class="explanation" id="explanation">
        <div class="title" id="checkZoom">
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示" class="arrow"></span>
        </div>
        <ul>
            <li><?php echo lang('stat_validorder_explain'); ?></li>
            <li>点击“设置价格区间”进入设置价格区间页面，下方统计图将根据您设置的价格区间进行统计</li>
            <li>统计图展示符合搜索条件的有效订单中的商品单价，在所设置的价格区间的分布情况</li>
        </ul>
    </div>

  <table class="ds-default-table">
	<thead class="thead">
		<tr class="space">
			<th colspan="15">价格销量分布（<a href="<?php echo url('statgeneral/setting'); ?>" style="font-size:12px; font-weight:normal;">设置价格区间</a>）</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
              <?php if($pricerange_statjson): ?>
              <div id="container_pricerange" class="w100pre close_float" style="height:400px"></div>
              <?php else: ?>
              <div class="w100pre close_float align-center h36 mt10">查看分布情况前，请先设置价格区间。
                <a href="<?php echo url('statgeneral/setting'); ?>" style="font-size:12px; font-weight:normal;">马上设置</a>
              </div>
              <?php endif; ?>
			</td>
		</tr>
	</tbody>
  </table>
</div>

<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/highcharts/highcharts.js"></script>
<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/home/js/common.js"></script>
<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/common/js/statistics.js"></script>
<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/common/js/mlselection.js"></script>
<script>
//展示搜索时间框
function show_searchtime(){
    s_type = $("#search_type").val();
    $("[id^='searchtype_']").hide();
    $("#searchtype_"+s_type).show();
}
$(function () {
    //统计数据类型
    var s_type = $("#search_type").val();
    $('#search_time').datepicker({dateFormat: 'yy-mm-dd'});

    show_searchtime();
    $("#search_type").change(function(){
        show_searchtime();
    });

    //更新周数组
    $("[name='searchweek_month']").change(function(){
        var year = $("[name='searchweek_year']").val();
        var month = $("[name='searchweek_month']").val();
        $("[name='searchweek_week']").html('');
        $.getJSON(SITE_URL+'common/getweekofmonth',{y:year,m:month},function(data){
            if(data != null){
                for(var i = 0; i < data.length; i++) {
                    $("[name='searchweek_week']").append('<option value="'+data[i].key+'">'+data[i].val+'</option>');
                }
            }
        });
    });

    $('#container_pricerange').highcharts(<?php echo $pricerange_statjson; ?>);

    $('#ncsubmit').click(function(){
        $('#formSearch').submit();
    });

    //商品分类
    init_gcselect(<?php echo $gc_choose_json; ?>,<?php echo $gc_json; ?>);
});
</script>