<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:94:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\mallconsult\index.html";i:1514877306;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>平台客服</h3>
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
                <th><label for="member_name">咨询人</label></th>
                <td><input class="txt" type="text" name="member_name" id="member_name" value="<?php echo \think\Request::instance()->get('member_name'); ?>" /></td>
                <td><label for="consult_type">咨询类型</label></td>
                <td>
                    <select name="mctid">
                        <option value="0">全部</option>
                        <?php if(!(empty($type_list) || (($type_list instanceof \think\Collection || $type_list instanceof \think\Paginator ) && $type_list->isEmpty()))): if(is_array($type_list) || $type_list instanceof \think\Collection || $type_list instanceof \think\Paginator): $i = 0; $__LIST__ = $type_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
                        <option <?php if(isset($mctid)): if($mctid == $val['mct_id']): ?>selected="selected"<?php endif; endif; ?>value="<?php echo $val['mct_id']; ?>"><?php echo $val['mct_name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                    </select>
                </td>
                <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo \think\Lang::get('ds_query'); ?>"></a>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
    <form method="post" action="<?php echo url('mallconsult/del_consult_batch'); ?>" onsubmit="" name="form1">
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th class="w24"></th>
                <th class="align-center">咨询内容</th>
                <th class="w96 align-center">咨询人</th>
                <th class="w156 align-center">咨询时间</th>
                <th class="w96 align-center">回复状态</th>
                <th class="w72 align-center"><?php echo \think\Lang::get('ds_handle'); ?> </th>
            </tr>
            </thead>
            <tbody>
            <?php if(!(empty($consult_list) || (($consult_list instanceof \think\Collection || $consult_list instanceof \think\Paginator ) && $consult_list->isEmpty()))): if(is_array($consult_list) || $consult_list instanceof \think\Collection || $consult_list instanceof \think\Paginator): $i = 0; $__LIST__ = $consult_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
            <tr class="space">
                <td class="w24"><input type="checkbox" class="checkitem" name="id[]" value="<?php echo $val['mc_id']; ?>" /></td>
                <td><?php echo $val['mc_content']; ?></td>
                <td class="align-center"><?php echo $val['member_name']; ?></td>
                <td class="align-center"><?php echo date("Y-m-d H:i:s",$val['mc_addtime']); ?></td>
                <td class="align-center"><?php echo $state[$val['is_reply']]; ?></td>
                <td>
                    <a href="<?php echo url('mallconsult/consult_reply',['id'=>$val['mc_id']]); ?>"><?php if($val['is_reply'] == 0): ?>回复<?php else: ?>编辑<?php endif; ?></a> |
                    <a href="javascript:if(confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')){location.href='<?php echo url('mall_consult/del_consult',['id'=>$val['mc_id']]); ?>';}" class="normal" ><?php echo \think\Lang::get('ds_del'); ?></a>
                </td>
            </tr>
           <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="20"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            <?php endif; ?>
            </tbody>
            <tfoot>
            <?php if(!(empty($consult_list) || (($consult_list instanceof \think\Collection || $consult_list instanceof \think\Paginator ) && $consult_list->isEmpty()))): ?>
            <tr class="tfoot">
                <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
                <td colspan="16"><label for="checkallBottom"><?php echo \think\Lang::get('ds_select_all'); ?></label>
                    &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="if ($('.checkitem:checked ').length == 0) { alert('请选择需要删除的选项！');return false;}  if(confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')){document.form1.submit();}"><span><?php echo \think\Lang::get('ds_del'); ?></span></a>
                    <div class="pagination"><?php echo $show_page; ?></div></td>
            </tr>
            <?php endif; ?>
            </tfoot>
        </table>
    </form>
</div>

<script type="text/javascript">
    function checkForm(){
        flag = false;
        $.each($("input[name='consult_id[]']"),function(i,n){
            if($(n).attr('checked')){
                flag = true;
                return false;
            }
        });
        if(!flag)alert('<?php echo \think\Lang::get('consulting_del_choose'); ?>');
        return flag;
    }
</script>
<script>
    (function(){
        $('.w').each(function(i){
            var o = document.getElementById("hutia_"+i);
            var s = o.innerHTML;
            var p = document.createElement("span");
            var n = document.createElement("a");
            p.innerHTML = s.substring(0,50);
            n.innerHTML = s.length > 50 ? "<?php echo \think\Lang::get('consulting_index_unfold'); ?>" : "";
            n.href = "###";
            n.onclick = function(){
                if (n.innerHTML == "<?php echo \think\Lang::get('consulting_index_unfold'); ?>"){
                    n.innerHTML = "<?php echo \think\Lang::get('consulting_index_retract'); ?>";
                    p.innerHTML = s;
                }else{
                    n.innerHTML = "<?php echo \think\Lang::get('consulting_index_unfold'); ?>";
                    p.innerHTML = s.substring(0,50);
                }
            }
            o.innerHTML = "";
            o.appendChild(p);
            o.appendChild(n);
        });
    })();
    (function(){
        $('.d').each(function(i){
            var o = document.getElementById("hutia2_"+i);
            var s = o.innerHTML;
            var p = document.createElement("span");
            var n = document.createElement("a");
            p.innerHTML = s.substring(0,50);
            n.innerHTML = s.length > 50 ? "<?php echo \think\Lang::get('consulting_index_unfold'); ?>" : "";
            n.href = "###";
            n.onclick = function(){
                if (n.innerHTML == "<?php echo \think\Lang::get('consulting_index_unfold'); ?>"){
                    n.innerHTML = "<?php echo \think\Lang::get('consulting_index_retract'); ?>";
                    p.innerHTML = s;
                }else{
                    n.innerHTML = "<?php echo \think\Lang::get('consulting_index_unfold'); ?>";
                    p.innerHTML = s.substring(0,50);
                }
            }
            o.innerHTML = "";
            o.appendChild(p);
            o.appendChild(n);
        });
    })();
</script>