<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\statindustry\scale.html";i:1514867099;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>行业分析</h3>
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

        <div class="w100pre" style="width: 100%;">
            <table class="search-form">
                <tbody>
                <tr>
                    <td id="searchgc_td"></td>
                    <input type="hidden" id="choose_gcid" name="choose_gcid" value="0"/>
                    <td>
                        <select name="search_type" id="search_type" class="querySelect">
                            <option value="day" <?php if(\think\Request::instance()->get('search_type') == 'day'): ?>selected<?php endif; ?>>按照天统计</option>
                            <option value="week" <?php if(\think\Request::instance()->get('search_type') == 'week'): ?>selected<?php endif; ?>>按照周统计</option>
                            <option value="month" <?php if(\think\Request::instance()->get('search_type') == 'month'): ?>selected<?php endif; ?>>按照月统计</option>
                        </select></td>
                    <td id="searchtype_day" style="display:none;">
                        <input class="txt date" type="text" value="<?php echo date('Y-m-d',$search_arr['day']['search_time']); ?>" id="search_time" name="search_time">
                    </td>
                    <td id="searchtype_week" style="display:none;">
                        <select name="searchweek_year" class="querySelect">
                            <?php if(is_array($year_arr) || $year_arr instanceof \think\Collection || $year_arr instanceof \think\Paginator): $i = 0; $__LIST__ = $year_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $key; ?>"<?php if($search_arr['week']['current_year'] == $key): ?> selected<?php endif; ?>><?php echo $v; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        <select name="searchweek_month" class="querySelect">
                            <?php if(is_array($month_arr) || $month_arr instanceof \think\Collection || $month_arr instanceof \think\Paginator): $i = 0; $__LIST__ = $month_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $key; ?>" <?php if($search_arr['week']['current_month'] == $key): ?> selected<?php endif; ?>><?php echo $v; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        <select name="searchweek_week" class="querySelect">
                            <?php if(is_array($week_arr) || $week_arr instanceof \think\Collection || $week_arr instanceof \think\Paginator): $i = 0; $__LIST__ = $week_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $v['key']; ?>" <?php if($search_arr['week']['current_week'] == $v['key']): ?>selected<?php endif; ?>><?php echo $v['val']; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </td>
                    <td id="searchtype_month" style="display:none;">
                        <select name="searchmonth_year" class="querySelect">
                            <?php if(is_array($year_arr) || $year_arr instanceof \think\Collection || $year_arr instanceof \think\Paginator): $i = 0; $__LIST__ = $year_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $key; ?>" <?php if($search_arr['month']['current_year'] == $key): ?>selected<?php endif; ?>><?php echo $v; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        <select name="searchmonth_month" class="querySelect">
                            <?php if(is_array($month_arr) || $month_arr instanceof \think\Collection || $month_arr instanceof \think\Paginator): $i = 0; $__LIST__ = $month_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $key; ?>" <?php if($search_arr['month']['current_month'] == $key): ?>selected<?php endif; ?>><?php echo $v; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </td>
                    <td><a href="javascript:document.formSearch.submit();" class="btn-search tooltip" title="<?php echo \think\Lang::get('ds_query'); ?>"></a></td>
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
            <li>统计某行业子分类在不同时间段的下单金额、下单商品数、下单量，为分析行业销量提供依据</li>
        </ul>
    </div>

    <div id="stat_tabs" class="w100pre close_float ui-tabs" style="min-height:400px">
        <div class="close_float tabmenu">
            <ul class="tab pngFix">
                <li><a href="#orderamount_div" nc_type="showdata" data-param='{"type":"orderamount"}'>下单金额</a></li>
                <li><a href="#goodsnum_div" nc_type="showdata" data-param='{"type":"goodsnum"}'>下单商品数</a></li>
                <li><a href="#ordernum_div" nc_type="showdata" data-param='{"type":"ordernum"}'>下单量</a></li>
            </ul>
        </div>
        <!-- 下单金额 -->
        <div id="orderamount_div" class="close_float" style="text-align:center;"></div>
        <!-- 下单商品数 -->
        <div id="goodsnum_div" class="close_float" style="text-align:center;"></div>
        <!-- 下单量 -->
        <div id="ordernum_div" class="close_float" style="text-align:center;"></div>
    </div>
</div>
<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/common/js/mlselection.js"></script>
<script>
    //展示搜索时间框
    function show_searchtime(){
        s_type = $("#search_type").val();
        $("[id^='searchtype_']").hide();
        $("#searchtype_"+s_type).show();
    }
    $(function () {
        //切换登录卡
        $('#stat_tabs').tabs();

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
            $.getJSON(SITE_URL+'index.php/common/getweekofmonth',{y:year,m:month},function(data){
                if(data != null){
                    for(var i = 0; i < data.length; i++) {
                        $("[name='searchweek_week']").append('<option value="'+data[i].key+'">'+data[i].val+'</option>');
                    }
                }
            });
        });

        //商品分类
        init_gcselect(<?php echo $gc_choose_json; ?>,<?php echo $gc_json; ?>);

        //加载统计数据
        getStatdata('orderamount');
        $("[nc_type='showdata']").click(function(){
            var data_str = $(this).attr('data-param');
            eval('data_str = '+data_str);
            getStatdata(data_str.type);
        });
    });
    //加载统计数据
    function getStatdata(type){
        var choose_gcid = $("#choose_gcid").val();
        $('#'+type+'_div').load("<?php echo url('statindustry/scale_list'); ?>",{stattype:+ type,t:<?php echo $searchtime; ?>,choose_gcid:+ choose_gcid});
    }
</script>