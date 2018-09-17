<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:98:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\consulting\consulting.html";i:1514877304;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>咨询管理</h3>
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

    <div class="fixed-empty"></div>
    <form method="get" name="formSearch">
        <table class="search-form">
            <tbody>
            <tr>
                <th><label for="member_name"><?php echo \think\Lang::get('consulting_index_sender'); ?></label></th>
                <td><input class="txt" type="text" name="member_name" id="member_name" value="<?php if(isset($member_name)): ?><?php echo $member_name; endif; ?>" /></td>
                <th><label for="consult_content"> <?php echo \think\Lang::get('consulting_index_content'); ?></label></th>
                <td><input class="txt" type="text" name="consult_content" id="consult_content"
                           value="<?php if(isset($consult_content)): ?><?php echo $consult_content; endif; ?>" /></td>
                <td><label for="consult_type">咨询类型</label></td>
                <td>
                    <select name="ctid">
                        <option value="0">全部</option>
                        <?php if(!(empty($consult_type) || (($consult_type instanceof \think\Collection || $consult_type instanceof \think\Paginator ) && $consult_type->isEmpty()))): if(is_array($consult_type) || $consult_type instanceof \think\Collection || $consult_type instanceof \think\Paginator): $i = 0; $__LIST__ = $consult_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
                        <option <?php if($ctid == 'val.ct_id'): ?>selected="selected"
                                <?php endif; ?> value="<?php echo $val['ct_id']; ?>"><?php echo $val['ct_name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                    </select>
                </td>
                <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo \think\Lang::get('ds_query'); ?>"></a>
                    <?php if(isset($form_submit)): if($form_submit == 'ok'): ?>
                    <a class="btns " href="<?php echo url('consulting/consulting'); ?>"
                       title="<?php echo \think\Lang::get('ds_cancel_search'); ?>"><span><?php echo \think\Lang::get('ds_cancel_search'); ?></span></a>
                    <?php endif; endif; ?>
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
          <li><?php echo \think\Lang::get('consulting_index_help1'); ?></li>
      </ul>
  </div>
    
    <form method="post" action="<?php echo url('consulting/delete'); ?>"
          onsubmit="return checkForm() && confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>');" name="form1">
        <table class="ds-default-table">
            <tbody>
            <?php if(!(empty($consult_list) || (($consult_list instanceof \think\Collection || $consult_list instanceof \think\Paginator ) && $consult_list->isEmpty()))): if(is_array($consult_list) || $consult_list instanceof \think\Collection || $consult_list instanceof \think\Paginator): $i = 0; $__LIST__ = $consult_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$consult): $mod = ($i % 2 );++$i;?>
            <tr class="space">
                <th class="w24"><input type="checkbox" class="checkitem" name="consult_id[]"
                                       value="<?php echo $consult['consult_id']; ?>" /></th>
                <th>
                    <strong><?php echo \think\Lang::get('consulting_index_object'); ?>:&nbsp;</strong>
                    <span><a target="_blank"
                             href="<?php echo url('goods/index',['goods_id'=>$consult['goods_id'],'id'=>$consult['store_id']]); ?>"><?php echo $consult['goods_name']; ?></a></span>
                </th>
                <th><strong><?php echo \think\Lang::get('consulting_index_store_name'); ?>:</strong>&nbsp;<a
                        href="<?php echo url('showstore/index',['store_id'=>$consult['store_id']]); ?>" class="normal"><?php echo $consult['store_name']; ?></a>
                </th>
                <th><strong><?php echo \think\Lang::get('ds_handle'); ?>:</strong>&nbsp;
                    <a href="javascript:if(confirm('<?php echo \think\Lang::get('sure_drop'); ?>'))location.href ='<?php echo url('consulting/delete',['consult_id'=>$consult['consult_id']]); ?>'" ><?php echo \think\Lang::get('ds_del'); ?></a>
                </th>
            </tr>
            <tr>
                <td colspan="12">
                    <fieldset style="border: 1px dashed #E7E7E7;padding: 0.5em;">
                        <legend><span><strong><?php echo \think\Lang::get('consulting_index_sender'); ?>:</strong>&nbsp;
                        <?php if(empty($consult['member_id']) || (($consult['member_id'] instanceof \think\Collection || $consult['member_id'] instanceof \think\Paginator ) && $consult['member_id']->isEmpty())): ?>
                        <?php echo \think\Lang::get('consulting_index_guest'); else: ?>
                        <?php echo $consult['member_name']; endif; ?>
              </span>&nbsp;&nbsp;&nbsp;&nbsp;<span><strong><?php echo \think\Lang::get('consulting_index_time'); ?>:</strong>&nbsp;<?php echo date("Y-m-d H:i:s",$consult['consult_addtime']); ?></span>
                        </legend>
                        <div class="formelement" id="hutia_<?php echo $key; ?>"><?php echo $consult['consult_content']; ?></div>
                    </fieldset>
                    <fieldset style="background: none repeat scroll 0 0 #FFFAE3;padding: 0.5em;border: 1px dashed #F1E38B;">
                        <legend><strong><?php echo \think\Lang::get('consulting_index_reply'); ?>:</strong></legend>
                        <div class="formelement" id="hutia2_<?php echo $key; ?>">
                            <?php if(!(empty($consult['consult_reply']) || (($consult['consult_reply'] instanceof \think\Collection || $consult['consult_reply'] instanceof \think\Paginator ) && $consult['consult_reply']->isEmpty()))): ?>
                            <?php echo $consult['consult_reply']; else: ?>
                            <?php echo \think\Lang::get('consulting_index_no_reply'); endif; ?>
                        </div>
                    </fieldset>
                </td>
            </tr>
           <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="20"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            <?php endif; ?>
            </tbody>
            <tfoot>
            <?php if(!(empty($output['consult_list']) || (($output['consult_list'] instanceof \think\Collection || $output['consult_list'] instanceof \think\Paginator ) && $output['consult_list']->isEmpty()))): ?>
            <tr class="tfoot">
                <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
                <td colspan="16"><label for="checkallBottom"><?php echo \think\Lang::get('ds_select_all'); ?></label>
                    &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="document.form1.submit()"><span><?php echo \think\Lang::get('ds_del'); ?></span></a>
                    <div class="pagination"><?php echo $show_page; ?></div>
                </td>
            </tr>
            <?php endif; ?>
            </tfoot>
        </table>
    </form>


</div>