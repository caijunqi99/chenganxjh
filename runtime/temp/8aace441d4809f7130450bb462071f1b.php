<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:91:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\snstrace\index.html";i:1514877309;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>买家动态</h3>
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
                <th><label for="search_uname"><?php echo \think\Lang::get('admin_snsstrace_storename'); ?></label></th>
                <td><input type="text" value="<?php echo \think\Request::instance()->get('search_sname'); ?>" name="search_sname" id="search_sname" class="txt"></td>
                <th><label for="search_content"><?php echo \think\Lang::get('admin_snstrace_content'); ?></label></th>
                <td><input type="text" value="<?php echo \think\Request::instance()->get('search_scontent'); ?>" name="search_scontent" id="search_scontent" class="txt"></td>
                <th><label><?php echo \think\Lang::get('store_sns_trace_type'); ?></label></th>
                <td><select name="search_type">
                    <option value=''><?php echo \think\Lang::get('ds_please_choose'); ?>...</option>
                    <option value="2" <?php echo \think\Request::instance()->get('search_type')=='2'?'selected="selected"':''; ?>><?php echo \think\Lang::get('store_sns_normal'); ?></option>
                    <option value="3" <?php echo \think\Request::instance()->get('search_type')=='3'?'selected="selected"':''; ?>><?php echo \think\Lang::get('store_sns_new'); ?></option>
                    <option value="4" <?php echo \think\Request::instance()->get('search_type')=='4'?'selected="selected"':''; ?>><?php echo \think\Lang::get('store_sns_coupon'); ?></option>
                    <option value="5" <?php echo \think\Request::instance()->get('search_type')=='5'?'selected="selected"':''; ?>><?php echo \think\Lang::get('store_sns_xianshi'); ?></option>
                    <option value="6" <?php echo \think\Request::instance()->get('search_type')=='6'?'selected="selected"':''; ?>><?php echo \think\Lang::get('store_sns_mansong'); ?></option>
                    <option value="7" <?php echo \think\Request::instance()->get('search_type')=='7'?'selected="selected"':''; ?>><?php echo \think\Lang::get('store_sns_bundling'); ?></option>
                    <option value="8" <?php echo \think\Request::instance()->get('search_type')=='8'?'selected="selected"':''; ?>><?php echo \think\Lang::get('store_sns_groupbuy'); ?></option>
                    <option value="9" <?php echo \think\Request::instance()->get('search_type')=='9'?'selected="selected"':''; ?>><?php echo \think\Lang::get('store_sns_recommend'); ?></option>
                    <option value="10" <?php echo \think\Request::instance()->get('search_type')=='10'?'selected="selected"':''; ?>><?php echo \think\Lang::get('store_sns_hotsell'); ?></option>
                </select></td>
                <th><label for="search_stime"><?php echo \think\Lang::get('admin_snstrace_addtime'); ?></label></th>
                <td><input type="text" class="txt date" value="<?php echo \think\Request::instance()->get('search_stime'); ?>" name="search_stime" id="search_stime" class="txt">
                    <label for="search_etime">~</label>
                    <input type="text" class="txt date" value="<?php echo \think\Request::instance()->get('search_etime'); ?>" name="search_etime" id="search_etime" class="txt">
                </td>
                <th><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo \think\Lang::get('ds_query'); ?>"></a></th>
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
            <li><?php echo \think\Lang::get('admin_snstrace_tracelisttip1'); ?></li>
            <li><?php echo \think\Lang::get('admin_snstrace_tracelisttip2'); ?></li>
        </ul>
    </div>

    
    <form method='post' id="form_trace" action="">
        <table class="ds-default-table">
            <tbody>
            <?php if(!(empty($strace_list) || (($strace_list instanceof \think\Collection || $strace_list instanceof \think\Paginator ) && $strace_list->isEmpty()))): if(is_array($strace_list) || $strace_list instanceof \think\Collection || $strace_list instanceof \think\Paginator): $i = 0; $__LIST__ = $strace_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr class="hover edit">
                <td style="vertical-align:top;"><input type="checkbox" name="st_id[]" value="<?php echo $v['strace_id']; ?>" class="checkitem"></td>
                <td class="fd-list">
                    <!-- 动态列表start -->
                    <li>
                        <div class="fd-aside">
              	<span class="thumb size60">
					<a href="<?php echo url('home/storesnshome/index',['sid'=>$v['strace_storeid']]); ?>" target="_blank">
						<img onload="javascript:DrawImage(this,60,60);" src="<?php echo getStoreLogo($v['strace_storelogo']); ?>">
					</a>
              	</span>
                        </div>
                        <dl class="fd-wrap">
                            <dt>
                            <h3><a href="<?php echo url('home/storesnshome/index',['sid'=>$v['strace_storeid']]); ?>" target="_blank"><?php echo $v['strace_storename']; ?></a><?php echo \think\Lang::get('ds_colon'); ?></h3>
                            <h5><?php echo parsesmiles($v['strace_title']); ?></h5>
                            </dt>
                            <dd>
                                <?php echo parsesmiles($v['strace_content']); ?>
                            </dd>
                            <dd>
                                <span class="fc-time fl"><?php echo date('Y-m-d H:i',$v['strace_time']); ?></span>
                                <span class="fr"><?php echo \think\Lang::get('admin_snstrace_forward'); ?>&nbsp;|&nbsp;
                                    <a href="<?php echo url('snsstrace/scomm_list',['st_id'=>$v['strace_id']]); ?>"><?php echo \think\Lang::get('admin_snstrace_comment'); ?><?php echo $v['strace_comment']>0?($v['strace_comment']):''; ?></a>
                                </span>&nbsp;&nbsp;
                                <?php echo \think\Lang::get('admin_snstrace_state'); ?><?php echo \think\Lang::get('ds_colon'); if($v['strace_state'] ==0): ?><font style='color:red;'><?php echo $Thinkl['lang']['admin_snstrace_statehide']; ?></font><?php else: ?><?php echo \think\Lang::get('admin_snstrace_stateshow'); endif; ?></span>
                            </dd>
                            <div class="clear"></div>
                        </dl>
                    </li>
                    <!-- 动态列表end -->
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            <tfoot>
            <tr class="tfoot">
                <td class="w24"><input type="checkbox" class="checkall" id="checkallBottom"></td>
                <td colspan="16"><label for="checkallBottom"><?php echo \think\Lang::get('ds_select_all'); ?></label>
                    &nbsp;&nbsp; <a href="JavaScript:void(0);" class="btn" onclick="submit_form('del');"><span><?php echo \think\Lang::get('ds_del'); ?></span></a> <a href="JavaScript:void(0);" class="btn" onclick="submit_form('hide');"><span><?php echo \think\Lang::get('admin_snstrace_statehide'); ?></span></a> <a href="JavaScript:void(0);" class="btn" onclick="submit_form('show');"><span><?php echo \think\Lang::get('admin_snstrace_stateshow'); ?></span></a>
                    <div class="pagination"> <?php echo $show_page; ?> </div></td>
            </tr>
            </tfoot>
            <?php else: ?>
            <tr class="no_data">
                <td colspan="15"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </form>

</div>

<script type="text/javascript">
    $(function(){
        $('#search_stime').datepicker({dateFormat: 'yy-mm-dd'});
        $('#search_etime').datepicker({dateFormat: 'yy-mm-dd'});
    });
    function submit_form(type){
        if(type=='del'){
            if(!confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')){
                return false;
            }
            $('#form_trace').attr('action','<?php echo url('Snstrace/tracedel'); ?>');
        }else if(type=='hide'){
            $('#form_trace').attr('action','<?php echo url('Snstrace/traceedit','type=hide'); ?>');
        }else{
            $('#form_trace').attr('action','<?php echo url('Snstrace/traceedit','typr=show'); ?>');
        }
        $('#form_trace').submit();
    }
</script>