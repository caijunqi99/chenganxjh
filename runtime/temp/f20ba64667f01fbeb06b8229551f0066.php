<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:99:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\complain\complain_list.html";i:1514867092;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>投诉管理</h3>
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

    <form id="search_form" method="post" name="formSearch">
        <table class="search-form">
            <tbody>
            <tr>
                <th><label for="input_complain_accuser"><?php echo \think\Lang::get('complain_accuser'); ?></label></th>
                <td><input class="txt" type="text" name="input_complain_accuser" id="input_complain_accuser" value="<?php echo \think\Request::instance()->get('input_complain_accuser'); ?>"></td>
                <th><label for="input_complain_subject_content"><?php echo \think\Lang::get('complain_subject_content'); ?></label></th>
                <td colspan="2"><input class="txt2" type="text" name="input_complain_subject_content" id="input_complain_subject_content" value="<?php echo \think\Request::instance()->get('input_complain_subject_content'); ?>"></td>
            </tr>
            <tr>
                <th><label for="input_complain_accused"><?php echo \think\Lang::get('complain_accused'); ?></label></th>
                <td><input class="txt" type="text" name="input_complain_accused" id="input_complain_accused" value="<?php echo \think\Request::instance()->get('input_complain_accused'); ?>"></td>
                <th><label for="time_from"><?php echo \think\Lang::get('complain_datetime'); ?></label></th>
                <td><input id="time_from" class="txt date" type="text" name="input_complain_datetime_start" value="<?php echo \think\Request::instance()->get('input_complain_datetime_start'); ?>">
                    <label for="time_to">~</label>
                    <input id="time_to" class="txt date" type="text" name="input_complain_datetime_end" value="<?php echo \think\Request::instance()->get('input_complain_datetime_end'); ?>"></td>
                <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo \think\Lang::get('ds_query'); ?>"></a>
                    <?php if(!(empty($_GET['input_complain_accuser'])&&empty($_GET['input_complain_accused'])&&empty($_GET['input_complain_subject_content'])&&empty($_GET['input_complain_datetime_start'])&&empty($_GET['input_complain_datetime_end']))) { ?>
                    <a class="btns " href="<?php echo url('complain/$op'); ?>" title="<?php echo \think\Lang::get('ds_cancel_search'); ?>"><span><?php echo \think\Lang::get('ds_cancel_search'); ?></span></a>
                    <?php }?></td>
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
            <li><?php echo \think\Lang::get('complain_help1'); ?></li>
            <li><?php echo \think\Lang::get('complain_help2'); ?></li>
            <li><?php echo \think\Lang::get('complain_help3'); ?></li>
        </ul>
    </div>
    
    <form method='post' id="list_form" action="<?php echo url('voucherprice/voucher_price_drop'); ?>">
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th class="w12">&nbsp;</th>
                <th><?php echo \think\Lang::get('complain_accuser'); ?></th>
                <th><?php echo \think\Lang::get('complain_accused'); ?></th>
                <th><?php echo \think\Lang::get('complain_subject_content'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('complain_datetime'); ?></th>
                <th class="align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr class="hover">
                <td>&nbsp;</td>
                <td><?php echo $v['accuser_name']; ?></td>
                <td><?php echo $v['accused_name']; ?></td>
                <td><?php echo $v['complain_subject_content']; ?></td>
                <td class="nowarp align-center"><?php echo date("Y-m-d H:i:s",$v['complain_datetime']); ?></td>
                <td class="align-center"><a href="<?php echo url('complain/complain_progress',['complain_id'=>$v['complain_id']]); ?>"><?php echo \think\Lang::get('complain_text_detail'); ?></a></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="15"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            <?php endif; ?>
            </tbody>

        </table>
        <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
        <?php echo $show_page; endif; ?>
    </form>

</div>

<script type="text/javascript">
    $(document).ready(function(){
        //表格移动变色
        $("tbody .line").hover(
            function()
            {
                $(this).addClass("complain_highlight");
            },
            function()
            {
                $(this).removeClass("complain_highlight");
            });
        $('#time_from').datepicker({dateFormat: 'yy-mm-dd',onSelect:function(dateText,inst){
            var year2 = dateText.split('-') ;
            $('#time_to').datepicker( "option", "minDate", new Date(parseInt(year2[0]),parseInt(year2[1])-1,parseInt(year2[2])) );
        }});
        $('#time_to').datepicker({onSelect:function(dateText,inst){
            var year1 = dateText.split('-') ;
            $('#time_from').datepicker( "option", "maxDate", new Date(parseInt(year1[0]),parseInt(year1[1])-1,parseInt(year1[2])) );
        }});

    });
</script>