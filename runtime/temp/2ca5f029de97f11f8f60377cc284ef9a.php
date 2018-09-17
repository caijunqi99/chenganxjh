<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:99:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\goodsclass\goods_class.html";i:1514877305;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3><?php echo \think\Lang::get('goods_class_index_class'); ?></h3>
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
            <li><?php echo \think\Lang::get('goods_class_index_help1'); ?></li>
            <li><?php echo \think\Lang::get('goods_class_index_help2'); ?></li>
            <li><?php echo \think\Lang::get('goods_class_index_help3'); ?></li>
        </ul>
    </div>
    
  <form method='post'>
    <table class="ds-default-table">
        <input type="hidden" name="submit_type" id="submit_type" value="" />
      <thead>
        <tr class="thead">
          <th></th>
          <th><?php echo \think\Lang::get('ds_sort'); ?></th>
          <th><?php echo \think\Lang::get('goods_class_index_name'); ?></th>
          <th><?php echo \think\Lang::get('goods_class_add_type'); ?></th>
          <th><?php echo \think\Lang::get('goods_class_add_commis_rate'); ?></th>
          <th></th>
          <th><?php echo \think\Lang::get('ds_handle'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($class_list) && is_array($class_list)){ foreach($class_list as $k => $v){ ?>
        <tr class="hover edit">
          <td class="w48"><input type="checkbox" name="check_gc_id[]" value="<?php echo $v['gc_id'];?>" class="checkitem">
            <?php if(isset($v['have_child']) && $v['have_child'] == '1'){ ?>
            <img fieldid="<?php echo $v['gc_id'];?>" status="open" nc_type="flex" src="<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/treetable/tv-expandable.gif">
            <?php }else{ ?>
            <img fieldid="<?php echo $v['gc_id'];?>" status="close" nc_type="flex" src="<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/treetable/tv-item.gif">
            <?php } ?></td>
          <td class="w48 sort"><span title="<?php echo \think\Lang::get('ds_editable'); ?>" ajax_branch="goods_class_sort" datatype="number" fieldid="<?php echo $v['gc_id'];?>" fieldname="gc_sort" nc_type="inline_edit" class="editable "><?php echo $v['gc_sort'];?></span></td>
          <td class="w50pre name">
          <span title="<?php echo \think\Lang::get('ds_editable'); ?>" required="1" fieldid="<?php echo $v['gc_id'];?>" ajax_branch="goods_class_name" fieldname="gc_name" nc_type="inline_edit" class="editable "><?php echo $v['gc_name'];?></span>
          <a class="btn-add-nofloat marginleft" href="<?php echo url('/Admin/Goodsclass/goods_class_add',['gc_parent_id'=>$v['gc_id']]); ?>"><span><?php echo \think\Lang::get('ds_add_sub_class'); ?></span></a>
          </td>
          <td><?php echo $v['type_name'];?></td>
          <td><?php echo $v['commis_rate'];?> %</td>
          <td><?php if ($v['gc_virtual'] == 1) {?>虚拟<?php }?></td>
          <td class="w96">
              <a href="<?php echo url('/Admin/Goodsclass/nav_edit',['gc_id'=>$v['gc_id']]); ?>">设置</a> | 
              <a href="<?php echo url('/Admin/Goodsclass/goods_class_edit',['gc_id'=>$v['gc_id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a> | 
              <a href="javascript:if(confirm('<?php echo \think\Lang::get('goods_class_index_ensure_del'); ?>'))window.location = '<?php echo url('/Admin/Goodsclass/goods_class_del',['gc_id'=>$v['gc_id']]); ?>';"><?php echo \think\Lang::get('ds_del'); ?></a>
          </td>
        </tr>
        <?php } }else { ?>
        <tr class="no_data">
          <td colspan="10"><?php echo \think\Lang::get('ds_no_record'); ?></td>
        </tr>
        <?php } ?>
      </tbody>
      <?php if(!empty($class_list) && is_array($class_list)){ ?>
      <tfoot>
        <tr class="tfoot">
          <td><input type="checkbox" class="checkall" id="checkall_2"></td>
          <td id="batchAction" colspan="15"><span class="all_checkbox">
            <label for="checkall_2"><?php echo \think\Lang::get('ds_select_all'); ?></label>
            </span>&nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="if(confirm('<?php echo \think\Lang::get('goods_class_index_ensure_del'); ?>')){$('#submit_type').val('del');$('form:first').submit();}"><span><?php echo \think\Lang::get('ds_del'); ?></span></a>
            </td>
        </tr>
      </tfoot>
      <?php } ?>
    </table>
  </form>
</div>

<!--<script type="text/javascript" src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/jquery.edit.js" charset="utf-8"></script>-->
<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/admin/js/jquery.goods_class.js"></script>
