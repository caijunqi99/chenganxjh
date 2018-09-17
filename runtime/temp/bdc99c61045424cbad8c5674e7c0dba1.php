<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:93:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\goodsalbum\index.html";i:1514877305;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>空间管理</h3>
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
                <th><label for="search_brand_name"><?php echo \think\Lang::get('g_album_keyword'); ?></label></th>
                <td><input class="txt" name="keyword" id="keyword" value="" type="text"></td>
                <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo \think\Lang::get('ds_query'); ?>"></a>
                    <?php if($store_name != '' && !empty($list) ){?>
                    <a class="btns" href="<?php echo url('store/index',['store_id'=>$list['0']['store_id']]); ?>"><span><?php echo $store_name; ?></span></a>
                    <?php }?>
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
          <li><?php echo \think\Lang::get('g_album_del_tips'); ?></li>
      </ul>
  </div>
    
    
    <form method='post' id="picForm" name="picForm">
        <input type="hidden" name="form_submit" value="ok" />
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th class="w24"></th>
                <th class="w72 center"><?php echo \think\Lang::get('g_album_fmian'); ?></th>
                <th class="w270"><?php echo \think\Lang::get('g_album_one'); ?></th>
                <th class=" w270"><?php echo \think\Lang::get('g_album_shop'); ?></th>
                <th><?php echo \think\Lang::get('g_album_pic_count'); ?></th>
                <th class="w72 align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr class="hover edit">
                <td><input value="<?php echo $v['aclass_id']; ?>" class="checkitem" type="checkbox" name="aclass_id[]"></td>
                <td>
                    <?php if(!(empty($v['aclass_cover']) || (($v['aclass_cover'] instanceof \think\Collection || $v['aclass_cover'] instanceof \think\Paginator ) && $v['aclass_cover']->isEmpty()))): ?>
                    <img src="<?php echo cthumb($v['aclass_cover'],60,$v['store_id']); ?>" onload="javascript:DrawImage(this,70,70);">
                    <?php else: ?>
                    <img src="<?php echo config('url_domain_root'); ?>uploads/home/common/default_goods_image.gif" onload="javascript:DrawImage(this,70,70);">
                    <?php endif; ?>
                </td>
                <td class="name"><?php echo $v['aclass_name']; ?></td>
                <td class="class"><a href="<?php echo url('home/store/index',['store_id'=>$v['store_id']]); ?>" ><?php echo $v['store_name']; ?></td>
                <td><?php echo !empty($pic_count[$v['aclass_id']])?$pic_count[$v['aclass_id']] : 0; ?></td>
                <td class="align-center">
                    <a href="<?php echo url('Goodsalbum/pic_list',['aclass_id'=>$v['aclass_id']]); ?>"><?php echo \think\Lang::get('g_album_pic_one'); ?></a>&nbsp;|&nbsp;
                    <a href="javascript:void(0)" onclick="if(confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')){location.href='<?php echo url('Goodsalbum/aclass_del',['aclass_id'=>$v['aclass_id']]); ?>';}else{return false;}"><?php echo \think\Lang::get('ds_del'); ?></a></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="10"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            <?php endif; ?>
            </tbody>
            <tfoot>
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
            <tr colspan="15" class="tfoot">
                <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
                <td colspan="16"><label for="checkallBottom"><?php echo \think\Lang::get('ds_select_all'); ?></label>
                    &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="if(confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')){$('#picForm').submit()}"><span><?php echo \think\Lang::get('ds_del'); ?></span></a>
                    <?php echo $page; ?></td>
            </tr>
            <?php endif; ?>
            </tfoot>
        </table>
    </form>

</div>
