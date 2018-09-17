<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\inform\inform_list.html";i:1514867089;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>举报管理</h3>
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

    <form id="search_form" method="get" name="formSearch">
        <table class="search-form">
            <tbody>
            <tr>
                <th> <label for="input_inform_goods_name"><?php echo \think\Lang::get('inform_goods_name'); ?></label></th>
                <td><input class="txt" type="text" name="input_inform_goods_name" id="input_inform_goods_name" value="<?php echo \think\Request::instance()->get('input_inform_goods_name'); ?>"></td>
                <th><label for="input_inform_type"><?php echo \think\Lang::get('inform_type'); ?></label></th>
                <td colspan="2"><input class="txt" type="text" name="input_inform_type" id="input_inform_type" value="<?php echo \think\Request::instance()->get('input_inform_type'); ?>"  style=" width:100px;">
                    <label for="input_inform_member_name"><?php echo \think\Lang::get('inform_member_name'); ?></label>
                    <input class="txt" type="text" name="input_inform_member_name" id="input_inform_member_name" value="<?php echo \think\Request::instance()->get('input_inform_member_name'); ?>" style=" width:100px;"></td>
            </tr>
            <tr>
                <th><label for="input_inform_subject"><?php echo \think\Lang::get('inform_subject'); ?></label></th>
                <td><input class="txt" type="text" name="input_inform_subject" id="input_inform_subject" value="<?php echo \think\Request::instance()->get('input_inform_subject'); ?>"></td>
                <th><label for="time_from"><?php echo \think\Lang::get('inform_datetime'); ?></label></th>
                <td><input id="time_from" class="txt date" type="text" name="input_inform_datetime_start" value="<?php echo \think\Request::instance()->get('input_inform_datetime_start'); ?>">
                    <label for="time_to">~</label>
                    <input id="time_to" class="txt date" type="text" name="input_inform_datetime_end" value="<?php echo \think\Request::instance()->get('input_inform_datetime_end'); ?>"></td>
                <td>
                    <a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo \think\Lang::get('ds_query'); ?>"></a>
             <?php if(!(empty(\think\Request::instance()->get('name')) || ((\think\Request::instance()->get('name') instanceof \think\Collection || \think\Request::instance()->get('name') instanceof \think\Paginator ) && \think\Request::instance()->get('name')->isEmpty()))): ?>
                    <a href="<?php echo url('inform/inform_list'); ?>" class="btns" title="<?php echo \think\Lang::get('ds_cancel_search'); ?>"><span><?php echo \think\Lang::get('ds_cancel_search'); ?></span></a>
                  <?php endif; ?>
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
            <li><?php echo \think\Lang::get('inform_help1'); ?></li>
            <li><?php echo \think\Lang::get('inform_help2'); ?></li>
        </ul>
    </div>
    
    <form method='post' id="list_form" action="<?php echo url('voucherprice/voucher_price_drop'); ?>">
        <table class="ds-default-table">
            <thead>
            <tr>
                <th><?php echo \think\Lang::get('inform_goods_name'); ?></th>
                <th><?php echo \think\Lang::get('inform_member_name'); ?></th>
                <th><?php echo \think\Lang::get('inform_type'); ?></th>
                <th><?php echo \think\Lang::get('inform_subject'); ?></th>
                <th><?php echo \think\Lang::get('inform_pic'); ?></th>
                <th><?php echo \think\Lang::get('inform_datetime'); ?></th>
                <th><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr class="line">
                <td><a href="<?php echo url('goods/index',['goods_id'=>$v['inform_goods_id']]); ?>" target="_blank"><?php echo $v['inform_goods_name']; ?></a></td>
                <td><span><?php echo $v['inform_member_name']; ?></span></td>
                <td><span><?php echo $v['inform_subject_type_name']; ?></span></td>
                <td><span><?php echo $v['inform_subject_content']; ?></span></td>
                <td>
                    <?php if(empty($v['inform_pic1']) || (($v['inform_pic1'] instanceof \think\Collection || $v['inform_pic1'] instanceof \think\Paginator ) && $v['inform_pic1']->isEmpty())): ?>
                    <?php echo \think\Lang::get('inform_pic_none'); else: ?>
                    <a href="<?php echo $pic_link;?>" target="_blank"><?php echo \think\Lang::get('inform_pic_view'); ?></a>
                    <?php endif; ?>
                </td>
                <td><span><?php echo date("Y-m-d",$v['inform_datetime']); ?></span></td>
                <td><a href="JavaScript:void(0);" class="show_detail"><?php echo \think\Lang::get('ds_detail'); ?></a> <a href="<?php echo url('inform/show_handle_page',['inform_id'=>$v['inform_id'],'inform_goods_name'=>$v['inform_goods_name']]); ?>"><?php echo \think\Lang::get('inform_text_handle'); ?></a></td>
            </tr>
            <tr class="inform_detail">
                <td colspan="15"><div class="shadow2">
                    <div class="content">
                        <dl>
                            <dt><?php echo \think\Lang::get('inform_content'); ?></dt>
                            <dd><?php echo $v['inform_content']; ?></dd>
                        </dl>
                        <div class="close_detail"><a href="JavaScript:void(0);" title="<?php echo \think\Lang::get('ds_close'); ?>"><?php echo \think\Lang::get('ds_close'); ?></a></div>
                    </div>
                </div></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="7"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
            <?php endif; ?>
            </tbody>
            <tfoot>
            <?php if(!(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty()))): ?>
            <tr class="tfoot">
                <td  colspan="15"><div class="pagination"><?php echo $show_page; ?></div></td>
            </tr>
            <?php endif; ?>
            </tfoot>
        </table>
    </form>

</div>

<script type="text/javascript">
    $(document).ready(function(){
        //表格移动变色
        $(".inform_detail").hide();
        $(".show_detail").click(function(){
            $(".inform_detail").hide();
            $(this).parents("tr").next(".inform_detail").show();
        });
        $(".close_detail").click(function(){
            $(this).parents(".inform_detail").hide();
        });

        $("tbody .line").hover(
            function()
            {
                $(this).addClass("inform_highlight");
            },
            function()
            {
                $(this).removeClass("inform_highlight");
            });
        $('#time_from').datepicker({onSelect:function(dateText,inst){
            var year2 = dateText.split('-') ;
            $('#time_to').datepicker( "option", "minDate", new Date(parseInt(year2[0]),parseInt(year2[1])-1,parseInt(year2[2])) );
        }});
        $('#time_to').datepicker({onSelect:function(dateText,inst){
            var year1 = dateText.split('-') ;
            $('#time_from').datepicker( "option", "maxDate", new Date(parseInt(year1[0]),parseInt(year1[1])-1,parseInt(year1[2])) );
        }});
    });
</script>