<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:96:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\statgeneral\general.html";i:1514528338;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>概念及设置</h3>
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

    
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom">
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示" class="arrow"></span>
        </div>
        <ul>
            <li><?php echo \think\Lang::get('stat_validorder_explain'); ?></li>
        </ul>
    </div>


    <table class="ds-default-table">
        <thead class="thead">
        <tr class="space">
            <th colspan="15"><?php echo date("Y-m-d",$stat_time); ?>最新情报</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                下单金额&nbsp;<i title="有效订单的总金额" class="tip icon-question-sign"></i>
                <br><b><?php echo $statnew_arr['orderamount']; ?>元</b>
            </td>
            <td>
                下单会员数&nbsp;<i title="有效订单的下单会员总数" class="tip icon-question-sign"></i>
                <br><b><?php echo $statnew_arr['ordermembernum']; ?></b>
            </td>
            <td>
                下单量&nbsp;<i title="有效订单的总数量" class="tip icon-question-sign"></i>
                <br><b><?php echo $statnew_arr['ordernum']; ?></b>
            </td>
            <td>
                下单商品数&nbsp;<i title="有效订单包含的商品总数量" class="tip icon-question-sign"></i>
                <br><b><?php echo $statnew_arr['ordergoodsnum']; ?></b>
            </td>
        </tr>
        <tr>
            <td>
                平均价格&nbsp;<i title="有效订单包含商品的平均单价" class="tip icon-question-sign"></i>
                <br><b><?php echo $statnew_arr['priceavg']; ?>元</b>
            </td>
            <td>
                平均客单价&nbsp;<i title="有效订单的平均每单的金额" class="tip icon-question-sign"></i>
                <br><b><?php echo $statnew_arr['orderavg']; ?></b>
            </td>
            <td>
                新增会员&nbsp;<i title="期间内新注册会员总数" class="tip icon-question-sign"></i>
                <br><b><?php echo $statnew_arr['newmember']; ?></b>
            </td>
            <td>
                会员数量&nbsp;<i title="平台所有会员的数量" class="tip icon-question-sign"></i>
                <br><b><?php echo $statnew_arr['membernum']; ?></b>
            </td>
        </tr>
        <tr>
            <td>
                新增店铺&nbsp;<i title="期间内新注册店铺总数" class="tip icon-question-sign"></i>
                <br><b><?php echo $statnew_arr['newstore']; ?></b></td>
            <td>
                店铺数量&nbsp;<i title="平台所有店铺的数量" class="tip icon-question-sign"></i>
                <br><b><?php echo $statnew_arr['storenum']; ?></b></td>
            <td>
                新增商品&nbsp;<i title="期间内新增商品总数" class="tip icon-question-sign"></i>
                <br><b><?php echo $statnew_arr['newgoods']; ?></b></td>
            <td>
                商品数量&nbsp;<i title="平台所有商品的数量" class="tip icon-question-sign"></i>
                <br><b><?php echo $statnew_arr['goodsnum']; ?></b></td>
        </tr>
        </tbody>
    </table>

    <table class="ds-default-table">
        <thead class="thead">
        <tr class="space">
            <th colspan="15"><?php echo date("Y-m-d",$stat_time); ?>销售走势</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><div id="container" class="w100pre close_float" style="height:400px"></div></td>
        </tr>
        </tbody>
    </table>


    <div class="w40pre floatleft">
        <table class="ds-default-table">
            <thead class="thead">
            <tr class="space">
                <th colspan="15">7日内店铺销售TOP30&nbsp;<i title="从昨天开始7日内热销店铺前30名" class="tip icon-question-sign"></i></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>序号</td>
                <td>店铺名称</td>
                <td>下单金额</td>
            </tr>
            <?php if(is_array($storetop30_arr) || $storetop30_arr instanceof \think\Collection || $storetop30_arr instanceof \think\Paginator): $i = 0; $__LIST__ = $storetop30_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr>
                <td><?php echo $key+1; ?></td>
                <td><?php echo $v['store_name']; ?></td>
                <td><?php echo $v['orderamount']; ?></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
    </div>

    <div class="w50pre floatleft" style="margin-left: 50px;">
        <table class="ds-default-table">
            <thead class="thead">
            <tr class="space">
                <th colspan="15">7日内商品销售TOP30&nbsp;<i title="从昨天开始7日内热销商品前30名" class="tip icon-question-sign"></i></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>序号</td>
                <td>商品名称</td>
                <td>销量</td>
            </tr>
            <?php if(is_array($goodstop30_arr) || $goodstop30_arr instanceof \think\Collection || $goodstop30_arr instanceof \think\Paginator): $i = 0; $__LIST__ = $goodstop30_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr>
                <td><?php echo $key+1; ?></td>
                <td class="alignleft"><a href="<?php echo url('home/goods/index',['goods_id'=>$v['goods_id']]); ?>" target="_blank"><?php echo $v['goods_name']; ?></a></td>
                <td><?php echo $v['ordergoodsnum']; ?></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
    </div>
    <div class="close_float"></div>
</div>
</div>
<script>
    jQuery.browser={};(function(){jQuery.browser.msie=false; jQuery.browser.version=0;if(navigator.userAgent.match(/MSIE ([0-9]+)./)){ jQuery.browser.msie=true;jQuery.browser.version=RegExp.$1;}})();
</script>
<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/jquery.poshytip.min.js"></script>
<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/highcharts/highcharts.js"></script>
<script>
    $(function () {
        //Ajax提示
        $('.tip').poshytip({
            className: 'tip-yellowsimple',
            showTimeout: 1,
            alignTo: 'target',
            alignX: 'center',
            alignY: 'top',
            offsetY: 5,
            allowTipHover: false
        });
    });
    var chart = new Highcharts.Chart('container', <?php echo $stattoday_json; ?>);
</script>