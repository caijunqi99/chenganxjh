<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:94:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\recposition\index.html";i:1514877307;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>推荐位</h3>
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
                <th><?php echo \think\Lang::get('rec_ps_type'); ?></th>
                <td><select name='pic_type'><option value=""><?php echo \think\Lang::get('ds_please_choose'); ?></option><option value="1"><?php echo \think\Lang::get('rec_ps_pic'); ?></option><option value="0"><?php echo \think\Lang::get('rec_ps_txt'); ?></option></select></td>
                <th><?php echo \think\Lang::get('rec_ps_title'); ?></th>
                <td><input type="text" value="" name="keywords" class="txt"></td>
                <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo \think\Lang::get('ds_query'); ?>"></a></td>
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
            <li><?php echo \think\Lang::get('rec_ps_help1'); ?></li>
        </ul>
    </div>
    
    
    <form method='post' id="form_rec">
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th>&nbsp;</th>
                <th><?php echo \think\Lang::get('rec_ps_title'); ?></th>
                <th><?php echo \think\Lang::get('rec_ps_type'); ?></th>
                <th><?php echo \think\Lang::get('rec_ps_content'); ?></th>
                <th><?php echo \think\Lang::get('rec_ps_gourl'); ?></th>
                <th><?php echo \think\Lang::get('rec_ps_target'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr class="hover edit">
                <td class="w24"><input type="checkbox" name="rec_id[]" value="<?php echo $v['rec_id']; ?>" class="checkitem"></td>
                <td><?php echo $v['title']; ?></td>
                <td><?php echo str_replace(array(0,1,2),array(lang('rec_ps_txt'),lang('rec_ps_picb'),lang('rec_ps_picy')),$v['pic_type']);if ($v['pic_type'] != 0){echo count($v['content']['body']) == 1 ? lang('rec_ps_picdan') : lang('rec_ps_picduo');}?>
                </td>
                <td class="picture">
                    <?php if($v['pic_type'] == 0): ?>
                    <?php echo $v['content']['body']['0']['title']; else: ?>
                    <a target='_blank' href="<?php echo $v['content']['body']['0']['title']; ?>"><img height="31" src="<?php echo $v['content']['body']['0']['title']; ?>" /></a>
                    <?php endif; ?>
                   </td>
                <td><?php echo $v['content']['body']['0']['url']; ?></td>
                <td><?php echo str_replace(array(1,2),array(lang('rec_ps_tg1'),lang('rec_ps_tg2')),$v['content']['target']);?></td>
                <td class="w180 align-center"><a href="<?php echo url('recposition/rec_edit',['rec_id'=>$v['rec_id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a> | <a onclick="if(confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')){return true;}else{return false;}" href="<?php echo url('recposition/rec_del',['rec_id'=>$v['rec_id']]); ?>"><?php echo \think\Lang::get('ds_del'); ?></a> | <a nctype="jscode" rec_id="<?php echo $v['rec_id']; ?>" href="javascript:void(0)"><?php echo \think\Lang::get('rec_ps_code'); ?></a> | <a target="_blank" href="<?php echo url('recposition/rec_view',['rec_id'=>$v['rec_id']]); ?>"><?php echo \think\Lang::get('rec_ps_view'); ?></a>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="10"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            <?php endif; ?>
            </tbody>
            <tfoot>
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
            <tr class="tfoot" id="dataFuncs">
                <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
                <td colspan="16" id="batchAction"><label for="checkallBottom"><?php echo \think\Lang::get('ds_select_all'); ?></label>
                    &nbsp;&nbsp; <a href="JavaScript:void(0);" class="btn" onclick="if(confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')){$('#form_rec').submit();}"><span><?php echo \think\Lang::get('ds_del'); ?></span></a>
                    <div class="pagination"> <?php echo $page; ?> </div></td>
            </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </form>
</div>
<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/js/dialog/dialog.js" id="dialog_js"
<script>
    $(function(){

        $('a [nctype="jscode"]').click(function(){
            alert(11);
            copyToClipBoard($(this).attr('rec_id'));return ;
        });
        function ajax_form(id, title, url, width, model)
        {
            if (!width)	width = 480;
            if (!model) model = 1;
            var d = DialogManager.create(id);
            d.setTitle(title);
            d.setContents('ajax', url);
            d.setWidth(width);
            d.show('center',model);
            return d;
        }
        function copyToClipBoard(id){
            if(window.clipboardData)
            {
                window.clipboardData.clearData();
                window.clipboardData.setData("Text", "<?php echo rec("+id+"); ?>");
                alert("<?php echo \think\Lang::get('rec_ps_clip_succ'); ?>!");
            }
            else if(navigator.userAgent.indexOf("Opera") != -1)
            {
                window.location = "<?php echo rec("+id+"); ?>";
                alert("<?php echo \think\Lang::get('rec_ps_clip_succ'); ?>!");
            }
            else
            {
                ajax_form('copy_rec', '<?php echo \think\Lang::get('rec_ps_code'); ?>', "<?php echo url('recposition/rec_code',['rec_id'=>"+id"]); ?>");
            }
        }
    });
</script>