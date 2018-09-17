<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:88:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\goods\index.html";i:1514877305;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\footer.html";i:1514528329;}*/ ?>
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
        
    

        


<script  src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>



<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>商品管理</h3>
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
<form action="" method="get">
    <table class="search-form">
        <tbody>
            <tr>
                <th><?php echo \think\Lang::get('goods_name'); ?></th>
                <td><input type="text" class="text w150" name="goods_name" value="<?php echo ''; ?>"></td>
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
                <th></th>
                <th></th>
                <th><?php echo \think\Lang::get('goods_common'); ?></th>
                <th><?php echo \think\Lang::get('goods_name'); ?></th>
                <th><?php echo \think\Lang::get('brand_cat'); ?></th>
                <th><?php echo \think\Lang::get('price'); ?></th>
                <th><?php echo \think\Lang::get('goods_storage'); ?></th>
                <th><?php echo \think\Lang::get('goods_state'); ?></th>
                <th><?php echo \think\Lang::get('goods_verify'); ?></th>
                <th><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
        </thead>
        <tbody>
           <?php if(!(empty($goods_list) || (($goods_list instanceof \think\Collection || $goods_list instanceof \think\Paginator ) && $goods_list->isEmpty()))): if(is_array($goods_list) || $goods_list instanceof \think\Collection || $goods_list instanceof \think\Paginator): $i = 0; $__LIST__ = $goods_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$goods): $mod = ($i % 2 );++$i;?>
            <tr>
                <td><input type="checkbox" class="checkitem" value="<?php echo $goods['goods_commonid']; ?>"></td>
                <td><img src="<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/treetable/tv-expandable.gif" ectype="flex" status="open" fieldid="<?php echo $goods['goods_commonid']; ?>"></td>
                <td><?php echo $goods['goods_commonid']; ?></td>
                <td><?php echo $goods['goods_name']; ?></td>
                <td>
                    <p><?php echo $goods['gc_name']; ?></p>
                    <p><?php echo $goods['brand_name']; ?></p>
                </td>
                <td><?php echo $goods['goods_price']; ?></td>
                <td><?php echo $storage[$goods['goods_commonid']]['goods_storage']; ?></td>
                <td><?php if($goods['goods_state'] == '0'): ?>下架<?php elseif($goods['goods_state'] == '10'): ?>禁售<?php else: ?>出售中<?php endif; ?></td>
                <td><?php if($goods['goods_verify'] == '0'): ?>未通过<?php elseif($goods['goods_verify'] == '10'): ?>审核中<?php else: ?>通过<?php endif; ?></td>

                <td>
                    <a href="<?php echo url('/home/goods/index',['goods_id' =>$storage[$goods['goods_commonid']][0]['goods_id']]); ?>">查看</a>
                     <?php if(\think\Request::instance()->param('type') == 'lockup'): ?>
                    |
                    <a href="<?php echo url('goods/goods_del',['common_id'=>$goods['goods_commonid']]); ?>"><?php echo lang('ds_del'); ?></a>
                    <?php elseif(\think\Request::instance()->param('type') == 'waitverify'): ?>
                     |
                    <a href="javascript:void(0);" onclick="goods_verify(<?php echo $goods['goods_commonid']; ?>);"><?php echo lang('ds_verify'); ?></a>
                    <?php else: ?>
                    |
                    <a href="javascript:void(0);" onclick="goods_lockup(<?php echo $goods['goods_commonid']; ?>);">违规下架</a>
                    <?php endif; ?>
                </td>
            </tr>
            <tr style="display:none">
                <td colspan="20"><div class="ncsc-goods-sku ps-container"></div></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; else: ?>
           <tr class="no_data">
               <td colspan="10"><?php echo \think\Lang::get('ds_no_record'); ?></td>
           </tr>
        <?php endif; ?>
        </tbody>
    </table>
    <?php echo $page; ?>
</div>


<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script src="<?php echo config('url_domain_root'); ?>static/home/js/common.js"></script>
<script>
    AJAX_URL = "<?php echo url('/admin/Goods/ajax_goodslist'); ?>";
    $(function() {
        $('x_title ul li a').click(
                function() {
                    $('li').removeClass('current');
                    $(this).addClass('add');

                })
        // ajax获取商品列表
        $('img[ectype="flex"]').click(
                function() {
                    var _parenttr = $(this).parents('tr');
                    var _commonid = $(this).attr('fieldid');
                    var src = $(this).attr('src');
                    var status = $(this).attr("status");
                    if (status == 'open') {
                        $(this).attr('src', src.replace("tv-expandable", "tv-collapsable"));
                        $(this).attr('status', 'close');
                        var _div = _parenttr.next().find('.ncsc-goods-sku');
                        if (_div.html() == '') {
                            $.get(AJAX_URL, {commonid: _commonid}, function(date) {
                                var res = eval('(' + date + ')');
                                var _ul = $('<ul class="goods-sku-list"></ul>');
                                for (var i = 0; i < res.length; i++)
                                {
                                    $('<li><div class="goods-thumb" title="商家货号：' + res[i].goods_serial + '"><a href="' + res[i].url + '" target="_blank"><image src="' + res[i].goods_image + '" ></a></div>' + res[i].goods_spec + '<div class="goods-price">价格：<em title="￥' + res[i].goods_price + '">￥' + res[i].goods_price + '</em></div><div class="goods-storage">库存：<em title="' + res[i].goods_storage + '">' + res[i].goods_storage + '</em></div><a href="' + res[i].url + '" target="_blank" class="ncsc-btn-mini">查看商品详情</a></li>').appendTo(_ul);
                                    _ul.appendTo(_div);
                                    _parenttr.next().show();
                                }
                            });
                        } else {
                            _parenttr.next().show()
                        }
                    }
                    if (status == "close")
                    {
                        var src = $(this).attr('src');
                        _parenttr.next().hide()
                        $(this).attr('src', src.replace("tv-collapsable", "tv-expandable"));
                        $(this).attr('status', 'open');
                    }
                });
    });
    // 商品下架
    function goods_lockup(ids) {
        _uri = ADMIN_URL+"goods/goods_lockup?commonid=" + ids;
        CUR_DIALOG = ajax_form('goods_lockup', '违规下架理由', _uri, 350);
    }

    // 商品审核
    function goods_verify(ids) {
        _uri = ADMIN_URL+"goods/goods_verify?commonid=" + ids;
        CUR_DIALOG = ajax_form('goods_verify', '审核商品', _uri, 350);
    }
</script>
</body>
<style>
    .goods-sku-list li {
        font-size: 12px;
        vertical-align: top;
        letter-spacing: normal;
        word-spacing: normal;
        display: inline-block;
        width: 100px;
        padding: 0 9px 0 10px;
        margin-left: -1px;
        border-left: dashed 1px #E6E6E6;
    }
    .goods-sku-list .goods-thumb a img {
        line-height: 0;
        text-align: center;
        vertical-align: middle;
        display: table-cell;
        width: 60px;
        height: 60px;
        overflow: hidden;
    }
</style>