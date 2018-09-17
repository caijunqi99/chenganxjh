<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:105:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\storeclass\store_class_index.html";i:1514877311;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3><?php echo \think\Lang::get('store_class'); ?></h3>
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
  
  <div class="explanation" id="explanation">
      <div class="title" id="checkZoom">
          <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
          <span id="explanationZoom" title="收起提示" class="arrow"></span>
      </div>
      <ul>
          <li><?php echo \think\Lang::get('store_class_help1'); ?></li>
          <li><?php echo \think\Lang::get('store_class_help2'); ?></li>
      </ul>
  </div>
  
  <form method='post'>
    <input type="hidden" name="form_submit" value="ok" />
    <table class="ds-default-table">
      <thead>
        <tr class="thead">
          <th><input type="checkbox" class="checkall" id="checkall_1"></th>
          <th><?php echo \think\Lang::get('ds_sort'); ?></th>
          <th><?php echo \think\Lang::get('store_class_name'); ?></th>
          <th class="align-center"><?php echo \think\Lang::get('store_class_bail'); ?>(<?php echo \think\Lang::get('currency_zh'); ?>)</th>
          <th><?php echo \think\Lang::get('ds_handle'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($class_list) && is_array($class_list)){ foreach($class_list as $k => $v){ ?>
        <tr class="hover edit">
          <td class="w36"><input type="checkbox" name='check_sc_id[]' value="<?php echo $v['sc_id'];?>" class="checkitem"></td>
          <td class="w48 sort"><span title="<?php echo \think\Lang::get('can_edit'); ?>" ajax_branch='store_class_sort' datatype="number" fieldid="<?php echo $v['sc_id'];?>" fieldname="sc_sort" nc_type="inline_edit" class="editable"><?php echo $v['sc_sort'];?></span></td>
          <td class="name">
          	<span title="<?php echo \think\Lang::get('store_class_name'); ?>" required="1" fieldid="<?php echo $v['sc_id'];?>" ajax_branch='store_class_name' fieldname="sc_name" nc_type="inline_edit" class="node_name editable"><?php echo $v['sc_name'];?></span>
          </td>
          <td class="align-center"><?php echo $v['sc_bail'];?></td>
          <td class="w84">
              <span>
                  <a href="<?php echo url('/Admin/Storeclass/store_class_edit',['sc_id'=>$v['sc_id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a> | 
                  <a href="javascript:if(confirm('<?php echo \think\Lang::get('del_store_class'); ?>'))window.location = '<?php echo url('/Admin/Storeclass/store_class_del',['sc_id'=>$v['sc_id']]); ?>';"><?php echo \think\Lang::get('ds_del'); ?></a> 
              </span>
          </td>
        </tr>
        <?php } }else { ?>
        <tr class="no_data">
          <td colspan="10"><?php echo \think\Lang::get('ds_no_record'); ?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <?php if(!empty($class_list) && is_array($class_list)){ ?>
        <tr id="batchAction" >
          <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
          <td colspan="16" id="dataFuncs"><label for="checkallBottom"><?php echo \think\Lang::get('ds_select_all'); ?></label>
            &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="if(confirm('<?php echo \think\Lang::get('del_store_class'); ?>')){$('form:first').submit();}"><span><?php echo \think\Lang::get('ds_del'); ?></span></a>
            <div class="pagination"><?php echo $page; ?></div></td>
            </td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
  </form>
</div>
