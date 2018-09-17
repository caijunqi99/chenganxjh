<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:99:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\predeposit\pdcash_list.html";i:1514877307;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1536980988;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>想见孩系统后台</title>
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
                <h3>预存款</h3>
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
            <li>未支付的提现单可以点击查看选项更改提现单的支付状态</li>
            <li>点击删除可以删除未支付的提现单</li>
        </ul>
    </div>
    
    <form method="get" >
        <table class="search-form">
            <tbody>
                <tr>
                    <th><?php echo \think\Lang::get('admin_predeposit_membername'); ?></th>
                    <td><input type="text" name="mname" class="txt" value='<?php echo \think\Request::instance()->get('mname'); ?>' /></td>
                    <th><?php echo \think\Lang::get('admin_predeposit_apptime'); ?></th>
                    <td colspan="2">
                        <input type="text" id="stime" name="stime" class="txt date" value="<?php echo \think\Request::instance()->get('stime'); ?>">
                        <label>~</label>
                        <input type="text" id="etime" name="etime" class="txt date" value="<?php echo \think\Request::instance()->get('etime'); ?>">
                    </td>
                </tr>
                <tr>
                    <th><?php echo \think\Lang::get('admin_predeposit_cash_shoukuanname'); ?></th>
                    <td><input type="text" name="pdc_bank_user" class="txt" value='<?php echo \think\Request::instance()->get('pdc_bank_user'); ?>'></td>
                    <th><?php echo \think\Lang::get('admin_predeposit_paystate'); ?></th>
                    <td>
                        <select id="paystate_search" name="paystate_search">
                            <option value=""><?php echo \think\Lang::get('ds_please_choose'); ?></option>
                            <option value="0" <?php if(\think\Request::instance()->get('paystate_search') == '0'): ?>selected="selected"<?php endif; ?>>未支付</option>
                            <option value="1" <?php if(\think\Request::instance()->get('paystate_search') == '1'): ?>selected="selected"<?php endif; ?>>已支付</option>
                        </select>
                    </td>
                    <td>
                        <a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo \think\Lang::get('ds_query'); ?>"></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    
    
    <table class="ds-default-table">
        <thead>
            <tr class="thead">
                <th>&nbsp;</th>
                <th><?php echo \think\Lang::get('admin_predeposit_cs_sn'); ?></th>
                <th><?php echo \think\Lang::get('admin_predeposit_membername'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('admin_predeposit_apptime'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('admin_predeposit_cash_price'); ?>(<?php echo \think\Lang::get('currency_zh'); ?>)</th>
                <th class="align-center"><?php echo \think\Lang::get('admin_predeposit_paystate'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($list) && is_array($list)){ foreach($list as $k => $v){?>
            <tr class="hover">
                <td class="w12">&nbsp;</td>
                <td><?php echo $v['pdc_sn']; ?></td>
                <td><?php echo $v['pdc_member_name']; ?></td>
                <td class="nowrap align-center"><?php echo @date('Y-m-d H:i:s',$v['pdc_add_time']);?></td>
                <td class="align-center"><?php echo $v['pdc_amount'];?></td>
                <td class="align-center"><?php echo str_replace(array('0','1'), array('未支付','已支付'), $v['pdc_payment_state']); ?></td>
                <td class="w90 align-center">
                    <?php if ($v['pdc_payment_state'] == '0'){ ?>
                    <a href="javascript:void(0)" onclick="if(confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')){window.location='<?php echo url('/Admin/Predeposit/pdcash_del',['id'=>$v['pdc_id']]); ?>';}else{return false;}"><?php echo \think\Lang::get('ds_del'); ?></a> 
                    <?php } ?><a href="<?php echo url('/Admin/Predeposit/pdcash_view',['id'=>$v['pdc_id']]); ?>" class="edit"><?php echo \think\Lang::get('ds_view'); ?></a>
                </td>
            </tr>
            <?php } }else { ?>
            <tr class="no_data">
                <td colspan="10"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="16" id="dataFuncs"><div class="pagination"><?php echo $show_page; ?></div></td>
            </tr>
        </tfoot>
    </table>
</div>

<script language="javascript">
$(function(){
	$('#stime').datepicker({dateFormat: 'yy-mm-dd'});
	$('#etime').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>
    