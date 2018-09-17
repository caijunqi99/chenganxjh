<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:97:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\promotionbooth\index.html";i:1514877307;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>推荐展位</h3>
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

    <form method="get" name="formSearch" id="formSearch">

        <table class="search-form">
            <tbody>
            <tr>
                <th><label>商品分类</label></th>
                <td id="searchgc_td"></td><input type="hidden" id="choose_gcid" name="choose_gcid" value="0"/>
                <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo \think\Lang::get('ds_query'); ?>"></a></td>
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
            <li>被推荐商品将在该商品所在的分类及其上级分类的商品列表左侧随机出现</li>
        </ul>
    </div>
    
    <form method='post' id="form_goods" action="<?php echo url('promotionbooth/del_goods'); ?>">
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th class="w24"></th>
                <th colspan="2">商品名称</th>
                <th class="align-center w72">分类</th>
                <th class="align-center w72">价格</th>
                <th class="w48 align-center"><?php echo \think\Lang::get('ds_handle'); ?> </th>
            </tr>
            </thead>
            <tbody>
            <?php if(!(empty($goods_list) || (($goods_list instanceof \think\Collection || $goods_list instanceof \think\Paginator ) && $goods_list->isEmpty()))): if(is_array($goods_list) || $goods_list instanceof \think\Collection || $goods_list instanceof \think\Paginator): $i = 0; $__LIST__ = $goods_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr class="hover edit">
                <td><input type="checkbox" name="goods_id[]" value="<?php echo $v['goods_id']; ?>" class="checkitem"></td>
                <td class="w60 picture"><div class="size-56x56"><span class="thumb size-56x56"><i></i><img src="<?php echo thumb($v,60); ?>" onload="javascript:DrawImage(this,56,56);"/></span></div></td>
                <td class="goods-name w270"><p><span><?php echo $v['goods_name']; ?></span></p>
                    <p class="store">店铺名称：<?php echo $v['store_name']; if(isset($flippedOwnShopIds[$v['store_id']])): ?>
                        <span class="ownshop">[自营]</span>
                       <?php endif; ?>
                    </p></td>
                <td class="align-center"><?php echo $gc_list[$v['gc_id']]['gc_name']; ?></td>
                <td class="align-center"><?php echo \think\Lang::get('currency'); ?><?php echo $v['goods_price']; ?></td>
                <td class="align-center">
                    <p><a href="<?php echo url('home/goods/index',['goods_id'=>$v['goods_id']]); ?>" target="_blank"><?php echo \think\Lang::get('ds_view'); ?></a></p>
                    <p><a href="javascript:void(0);" onclick="ajaxget(<?php echo url('promotionbooth/del_goods',['goods_id'=>$v['goods_id']]); ?>)">删除</a></p>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
             <tfoot>
            <tr class="tfoot">
                <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
                <td colspan="16"><label for="checkallBottom"><?php echo \think\Lang::get('ds_select_all'); ?></label>
                    <a href="JavaScript:void(0);" class="btn btn-small" nctype="del_batch"><span><?php echo \think\Lang::get('ds_del'); ?></span></a>
                </td>
            </tr>
            </tfoot>
            <?php else: ?>
            <tr class="no_data">
                <td colspan="15"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
        <?php echo $page; ?>
    </form>
</div>
<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/common/js/mlselection.js"></script>
<script type="text/javascript">
    $(function(){
        init_gcselect(<?php echo $gc_choose_json; ?>,<?php echo $gc_json; ?>);
        // 批量删除
        $('a[nctype="del_batch"]').click(function(){
            ajaxpost('form_goods', '', '', 'onerror');
        });
    });

    //获得选中哎
    function getId() {
        var str = '';
        $('#form_goods').find('input[name="id[]"]:checked').each(function(){
            id = parseInt($(this).val());
            if (!isNaN(id)) {
                str += id + ',';
            }
        });
        if (str == '') {
            return false;
        }
        str = str.substr(0, (str.length - 1));
        return str;
    }
</script>
