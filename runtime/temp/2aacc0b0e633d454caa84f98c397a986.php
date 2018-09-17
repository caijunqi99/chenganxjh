<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:102:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\statmember\stat_newmember.html";i:1514867099;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>会员统计</h3>
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

  <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="" value="" />
    <div class="w100pre" style="width: 100%;">
        <table class="search-form">
          <tbody>
            <tr>
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
              		<option value="<?php echo $key; ?>" <?php echo $search_arr['week']['current_year']==$key?'selected': ''; ?>><?php echo $v; ?></option>
                 <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
                <select name="searchweek_month" class="querySelect">
                  <?php if(is_array($month_arr) || $month_arr instanceof \think\Collection || $month_arr instanceof \think\Paginator): $i = 0; $__LIST__ = $month_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
              		<option value="<?php echo $key; ?>" <?php echo $search_arr['week']['current_month']==$key?'selected':''; ?>><?php echo $v; ?></option>
                  <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
                <select name="searchweek_week" class="querySelect">
                  <?php if(is_array($week_arr) || $week_arr instanceof \think\Collection || $week_arr instanceof \think\Paginator): $i = 0; $__LIST__ = $week_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
              		<option value="<?php echo $v['key']; ?>" <?php echo $search_arr['week']['current_week']==$v['key']?'selected' :''; ?>><?php echo $v['val']; ?></option>
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
                <a href="javascript:void(0);" id="ncsubmit" class="btn-search tooltip" title="<?php echo lang('ds_query'); ?>"></a>
              </td>
            </tr>
          </tbody>
        </table>
        <span class="right" style="margin:12px 0px 6px 4px;">

        </span>
    </div>
  </form>

   <div class="explanation" id="explanation">
        <div class="title" id="checkZoom">
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示" class="arrow"></span>
        </div>
        <ul>
            <li>统计图展示了时间段内新增会员数的走势和与前一时间段的对比</li>
            <li>统计表展示了时间段内新增会员数值和与前一时间段的同比数值，点击每条记录后的“查看”，了解新增会员的详细信息</li>
            <li>点击列表上方的“导出Excel”，将列表数据导出为Excel文件</li>
        </ul>
    </div>
  <div id="container" class="w100pre close_float" style="height:400px"></div>

  <div style="text-align:right;">
  	<input type="hidden" id="export_type" name="export_type" data-param='{"url":"<?php echo $actionurl;?>&exporttype=excel"}' value="excel"/>
  	<a class="btns" href="javascript:void(0);" id="export_btn"><span>导出Excel</span></a>
  </div>
  <table class="ds-default-table">
    <thead>
      <tr class="thead">
        <?php if(is_array($statlist['headertitle']) || $statlist['headertitle'] instanceof \think\Collection || $statlist['headertitle'] instanceof \think\Paginator): $i = 0; $__LIST__ = $statlist['headertitle'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
        <th class="align-center"><?php echo $v; ?></th>
      <?php endforeach; endif; else: echo "" ;endif; ?>
        <th class="align-center">操作</th>
      </tr>
    </thead>
    <tbody id="datatable">
    <?php if(!(empty($statlist['data']) || (($statlist['data'] instanceof \think\Collection || $statlist['data'] instanceof \think\Paginator ) && $statlist['data']->isEmpty()))): if(is_array($statlist['data']) || $statlist['data'] instanceof \think\Collection || $statlist['data'] instanceof \think\Paginator): $i = 0; $__LIST__ = $statlist['data'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
      <tr class="hover">
        <td class="align-center"><?php echo $v['timetext']; ?></td>
        <td class="align-center"><?php echo $v['updata']; ?></td>
        <td class="align-center"><?php echo $v['currentdata']; ?></td>
        <td class="align-center"><?php echo $v['tbrate']; ?></td>
        <td class="align-center">
        	<a href="<?php echo url('statmember/showmember',['type'=>'newbyday','t'=>$v['seartime']]); ?>>">查看</a>
        </td>
      </tr>
   <?php endforeach; endif; else: echo "" ;endif; ?>
    <tr class="hover">
        <td class="align-center"><b>总计</b></td>
        <td class="align-center"><?php echo $count_arr['up']; ?></td>
        <td class="align-center"><?php echo $count_arr['curr']; ?></td>
        <td class="align-center"><?php echo $count_arr['tbrate']; ?></td>
        <td class="align-center">
        	<a href="<?php echo url('statmember/showmember',['type'=>'newbyday','t'=>$count_arr['seartime']]); ?>">查看</a>
        </td>
      </tr>
   <?php else: ?>
    <tr class="no_data">
      	<td colspan="15"><?php echo lang('no_record'); ?></td>
      </tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>
<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/highcharts/highcharts.js"></script>
<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/home/js/common.js"></script>
<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/common/js/statistics.js"></script>

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

    $('#container').highcharts(<?php echo $stat_json; ?>);

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
});
</script>