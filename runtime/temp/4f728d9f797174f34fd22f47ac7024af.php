<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\adv\ap_manage.html";i:1514877303;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3><?php echo \think\Lang::get('adv_index_manage'); ?></h3>
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
  <form method="get" action="" name="formSearch">
    <table class="search-form">
      <tbody>
        <tr>
          <th><label for="search_name"><?php echo \think\Lang::get('ap_name'); ?></label></th>
          <td><input class="txt" type="text" name="search_name" id="search_name" value="<?php echo \think\Request::instance()->get('search_name'); ?>" /></td>
          <td>
              <a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo \think\Lang::get('ds_query'); ?>"></a>
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
          <li><?php echo \think\Lang::get('adv_help2'); ?></li>
      </ul>
  </div>
  
  
  <form method="post" id="store_form">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="ds-default-table">
      <thead>
        <tr class="thead">
          <th><input type="checkbox" class="checkall"/></th>
          <th><?php echo \think\Lang::get('ap_name'); ?></th>
          <th class="align-center"><?php echo \think\Lang::get('ap_width'); ?></th>
          <th class="align-center"><?php echo \think\Lang::get('ap_height'); ?></th>
          <th class="align-center"><?php echo \think\Lang::get('ap_show_num'); ?></th>
          <th class="align-center"><?php echo \think\Lang::get('ap_publish_num'); ?></th>
          <th class="align-center"><?php echo \think\Lang::get('ap_is_use'); ?></th>
          <th class="align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($ap_list) && is_array($ap_list)){ foreach($ap_list as $k => $v){ ?>
        <tr class="hover">
          <td class="w24"><input type="checkbox" class="checkitem" name="del_id[]" value="<?php echo $v['ap_id']; ?>" /></td>
          <td><span title="<?php echo $v['ap_name'];?>"><?php echo str_cut($v['ap_name'], '40');?></span></td>
          <td class="align-center"><?php echo $v['ap_width'];?></td>
          <td class="align-center"><?php echo $v['ap_height'];?></td>
          <td class="align-center"><?php
					 $i    = 0;
					 $time = time();
					 if(!empty($adv_list)){
					 foreach ($adv_list as $adv_k => $adv_v){
					 	if($adv_v['ap_id'] == $v['ap_id'] && $adv_v['adv_end_date'] > $time && $adv_v['adv_start_date'] < $time && $adv_v['is_allow'] == '1'){
					 		$i++;
					 	}
					 }}
					 echo $i;
					?></td>
          <td class="align-center"><?php
					 $i    = 0;
					 if(!empty($adv_list)){
					 foreach ($adv_list as $adv_k => $adv_v){
					 	if($adv_v['ap_id'] == $v['ap_id']){
					 		$i++;
					 	}
					 }}
					 echo $i;
					?></td>
          <td class="align-center yes-onoff"><?php if($v['is_use'] == '0'){ ?>
            <a href="JavaScript:void(0);" class=" disabled" ajax_branch="is_use" nc_type="inline_edit" fieldname="is_use" fieldid="<?php echo $v['ap_id']?>" fieldvalue="0" title="<?php echo \think\Lang::get('ds_editable'); ?>"><img src="images/transparent.gif"></a>
            <?php }else { ?>
            <a href="JavaScript:void(0);" class=" enabled" ajax_branch="is_use" nc_type="inline_edit" fieldname="is_use" fieldid="<?php echo $v['ap_id']?>" fieldvalue="1" title="<?php echo \think\Lang::get('ds_editable'); ?>"><img src="images/transparent.gif"></a>
            <?php } ?></td>
          <td class="align-center">
              <a href="<?php echo url('/Admin/Adv/adv',['ap_id'=>$v['ap_id']]); ?>">管理广告</a> | 
              <a href="<?php echo url('/Admin/Adv/ap_edit',['ap_id'=>$v['ap_id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a> |
              <a href="<?php echo url('/Admin/Adv/ap_del',['ap_id'=>$v['ap_id']]); ?>"><?php echo \think\Lang::get('ds_del'); ?></a>
          </td>
        </tr>
        <?php } }else { ?>
        <tr class="no_data">
          <td colspan="15"><?php echo \think\Lang::get('ds_no_record'); ?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td><input type="checkbox" class="checkall" id="checkall"/></td>
          <td id="batchAction" colspan="15"><span class="all_checkbox">
            <label for="checkall"><?php echo \think\Lang::get('ds_select_all'); ?></label>
            </span>&nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="if(confirm('<?php echo \think\Lang::get('ap_del_sure'); ?>')){$('#store_form').submit();}"><span><?php echo \think\Lang::get('ds_del'); ?></span></a>
          </td>
        </tr>
      </tfoot>
    </table>
    <?php echo $page; ?>
  </form>
</div>
<script type="text/javascript" src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/js/jquery-ui/jquery-ui.min.js"></script>
