<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:91:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\evaluate\index.html";i:1514877305;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>评价管理</h3>
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

    <form method="get" name="formSearch">
        <table class="search-form">
            <tbody>
            <tr>
                <th><label for="goods_name"><?php echo \think\Lang::get('admin_evaluate_goodsname'); ?></label></th>
                <td><input class="txt" type="text" name="goods_name" id="goods_name" value="<?php echo \think\Request::instance()->get('goods_name'); ?>" /></td>
                <th><label for="store_name"><?php echo \think\Lang::get('admin_evaluate_storename'); ?></label></th>
                <td><input class="txt" type="text" name="store_name" id="store_name" value="<?php echo \think\Request::instance()->get('store_name'); ?>" /></td>
                <td><?php echo \think\Lang::get('admin_evaluate_addtime'); ?></td>
                <td><input class="txt date" type="text" name="stime" id="stime" value="<?php echo \think\Request::instance()->get('stime'); ?>" />
                    ~
                    <input class="txt date" type="text" name="etime" id="etime" value="<?php echo \think\Request::instance()->get('etime'); ?>" /></td>
                <td>
                    <input type="submit" value="<?php echo \think\Lang::get('ds_query'); ?>"/>
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
            <li><?php echo \think\Lang::get('admin_evaluate_help1'); ?></li>
            <li><?php echo \think\Lang::get('admin_evaluate_help2'); ?></li>
        </ul>
    </div>
    
    
    <table class="ds-default-table">
        <thead>
        <tr class="thead">
            <th class="w300"><?php echo \think\Lang::get('admin_evaluate_goodsname'); ?> </th>
            <th><?php echo \think\Lang::get('admin_evaluate_buyerdesc'); ?></th>
            <th class="w108 align-center"><?php echo \think\Lang::get('admin_evaluate_frommembername'); ?> </th>
            <th class="w108 align-center"><?php echo \think\Lang::get('admin_evaluate_storename'); ?></th>
            <th class="w72 align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(!(empty($evalgoods_list) || (($evalgoods_list instanceof \think\Collection || $evalgoods_list instanceof \think\Paginator ) && $evalgoods_list->isEmpty()))): if(is_array($evalgoods_list) || $evalgoods_list instanceof \think\Collection || $evalgoods_list instanceof \think\Paginator): $i = 0; $__LIST__ = $evalgoods_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
        <tr class="hover">
            <td><a href="<?php echo url('home/goods/index',['goods_id'=>$v['geval_goodsid']]); ?>" target="_blank"><?php echo $v['geval_goodsname']; ?></a></td>
            <td class="evaluation"><div>商品评分：<span class="raty" data-score="<?php echo $v['geval_scores']; ?>"></span><time>[<?php echo date('Y-m-d',$v['geval_addtime']); ?>]</time></div>
                <div>评价内容：<?php echo $v['geval_content']; ?></div>

                <?php if(!(empty($v['geval_image']) || (($v['geval_image'] instanceof \think\Collection || $v['geval_image'] instanceof \think\Paginator ) && $v['geval_image']->isEmpty()))): ?>
                <div>晒单图片：
                    <ul class="evaluation-pic-list">
                        <?php $image_array = explode(',', $v['geval_image']);foreach ($image_array as $value) { ?>
                        <li><a nctype="nyroModal"  href="<?php echo snsThumb($value, 1024);?>"> <img src="<?php echo snsThumb($value);?>"> </a></li>
                        <?php } ?>
                    </ul>
                </div>
                <?php endif; if(!(empty($v['geval_explain']) || (($v['geval_explain'] instanceof \think\Collection || $v['geval_explain'] instanceof \think\Paginator ) && $v['geval_explain']->isEmpty()))): ?>
                <div id="explain_div_<?php echo $v['geval_id']; ?>"> <span style="color:#996600;padding:5px 0px;">[<?php echo \think\Lang::get('admin_evaluate_explain'); ?>]<?php echo $v['geval_explain']; ?></span> </div>
                <?php endif; ?></td>
            <td class="align-center"><?php echo $v['geval_frommembername']; ?></td>
            <td class="align-center"><?php echo $v['geval_storename']; ?></td>
            <td class="align-center"><a nctype="btn_del" href="javascript:void(0)" data-geval-id="<?php echo $v['geval_id']; ?>"><?php echo \think\Lang::get('ds_del'); ?></a></td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; else: ?>
        <tr class="no_data">
            <td colspan="15"><?php echo \think\Lang::get('ds_no_record'); ?></td>
        </tr>
        <?php endif; if(!(empty($evalgoods_list) || (($evalgoods_list instanceof \think\Collection || $evalgoods_list instanceof \think\Paginator ) && $evalgoods_list->isEmpty()))): ?>
        <tfoot>
        <tr class="tfoot">
            <td colspan="15" id="dataFuncs"><div class="pagination"><?php echo $show_page; ?></div></td>
        </tr>
        </tfoot>
        <?php endif; ?>
    </table>
    <form id="submit_form" action="<?php echo url('evaluate/evalgoods_del'); ?>" method="post">
        <input id="geval_id" name="geval_id" type="hidden">
    </form>

</div>
<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/js/jquery.nyroModal/custom.min.js"></script>
<link rel="stylesheet" href="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/js/jquery.nyroModal/styles/nyroModal.css">
<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/js/jquery.raty/jquery.raty.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#stime').datepicker({dateFormat: 'yy-mm-dd'});
        $('#etime').datepicker({dateFormat: 'yy-mm-dd'});

        $('.raty').raty({
            path: "SITE_URL"+'/js/jquery.raty/img',
            readOnly: true,
            score: function() {
                return $(this).attr('data-score');
            }
        });

        $('a[nctype="nyroModal"]').nyroModal();

        $('[nctype="btn_del"]').on('click', function() {
            if(confirm("<?php echo \think\Lang::get('ds_ensure_del'); ?>")) {
                var geval_id = $(this).attr('data-geval-id');
                $('#geval_id').val(geval_id);
                $('#submit_form').submit();
            }
        });
    });
</script> 
