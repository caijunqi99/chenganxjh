<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:92:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\storejoin\index.html";i:1514867102;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>开店首页</h3>
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
            <li>可以传三张图片，在开店首页头部显示，建议使用1920px * 350px</li>
            <li>“置空”会删除图片，提交保存后生效</li>
            <li>所填写的“贴心提示”会出现在开店首页图片下方</li>
        </ul>
    </div>
    
    <form method="post" enctype="multipart/form-data" name="form1">
        <input type="hidden" name="form_submit" value="ok" />
        <table class="ds-default-table">
            <tbody>
            <tr class="space">
                <th colspan="2">图片上传:</th>
            </tr>
            <?php $__FOR_START_14323__=1;$__FOR_END_14323__=$size;for($i=$__FOR_START_14323__;$i <= $__FOR_END_14323__;$i+=1){ ?>
            <tr class="noborder">
                <td colspan="2"><label>IMG<?php echo $i; ?>:</label>
                    <a href="JavaScript:void(0);" onclick="clear_pic(<?php echo $i; ?>)"><span>置空</span></a></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <?php if(!(empty($pic[$i]) || (($pic[$i] instanceof \think\Collection || $pic[$i] instanceof \think\Paginator ) && $pic[$i]->isEmpty()))): ?>
                    <span class="type-file-show" id="show<?php echo $i; ?>"><a class="nyroModal" rel="gal" href="<?php echo \think\Config::get('url_domain_root'); ?>uploads/admin/Storejion/<?php echo $pic[$i]; ?>">
            <img class="show_image" title="点击打开" src="<?php echo \think\Config::get('url_domain_root'); ?>/static/admin/images/preview.png"></a>
            </span>
                    <?php endif; ?>
                    <span class="type-file-box">
            <input type="text" name="textfield" id="textfield<?php echo $i; ?>" class="type-file-text" />
            <input type="button" name="button" id="button<?php echo $i; ?>" value="" class="type-file-button" />
            <input name="pic<?php echo $i; ?>" type="file" class="type-file-file" id="pic<?php echo $i; ?>" size="30" hidefocus="true">
            <input type="hidden" name="show_pic<?php echo $i; ?>" id="show_pic<?php echo $i; ?>" value="<?php if(isset($pic[$i])): ?><?php echo $pic[$i]; endif; ?>" />
            </span></td>
                <td class="vatop tips"></td>
            </tr>
            <?php } ?>
            <tr class="space">
                <th colspan="2"><label for="show_txt">贴心提示:</label></th>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><textarea name="show_txt" rows="6" class="tarea" id="show_txt" ><?php echo $show_txt; ?></textarea></td>
                <td class="vatop tips"><span class="vatop rowform"></span></td>
            </tr>
            </tbody>
            <tfoot>
            <tr class="tfoot">
                <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.form1.submit()"><span><?php echo \think\Lang::get('ds_submit'); ?></span></a></td>
            </tr>
            </tfoot>
        </table>
    </form>

</div>

<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/js/jquery.nyroModal/custom.min.js"></script>
<link rel="stylesheet" href="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/js/jquery.nyroModal/styles/nyroModal.css">

<script type="text/javascript">
    $(function(){
        $('input[class="type-file-file"]').change(function(){
            var pic=$(this).val();
            var extStart=pic.lastIndexOf(".");
            var ext=pic.substring(extStart,pic.lengtd).toUpperCase();
            $(this).parent().find(".type-file-text").val(pic);
            if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
                alert("<?php echo \think\Lang::get('default_img_wrong'); ?>");
                $(this).attr('value','');
                return false;
            }
        });
        $('.nyroModal').nyroModal();
    });
    function clear_pic(n){
        $("#show"+n+"").remove();
        $("#textfield"+n+"").val("");
        $("#pic"+n+"").val("");
        $("#show_pic"+n+"").val("");
    }
</script>