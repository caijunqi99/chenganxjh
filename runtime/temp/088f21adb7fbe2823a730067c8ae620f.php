<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:92:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\snsmalbum\index.html";i:1514867099;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>会员相册</h3>
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
                <th><label for="class_name"><?php echo \think\Lang::get('snsalbum_class_name'); ?></label></th>
                <td><input type="text" value="<?php echo \think\Request::instance()->get('class_name'); ?>" name="class_name" id="class_name" class="txt"></td>
                <th><label for="user_name"><?php echo \think\Lang::get('snsalbum_member_name'); ?></label></th>
                <td><input type="text" value="<?php echo \think\Request::instance()->get('user_name'); ?>" name="user_name" id="user_name" class="txt"></td>
                <td><a href="javascript:document.formSearch.submit();" class="btn-search " title="<?php echo \think\Lang::get('ds_query'); ?>"></a></td>
            </tr>
            </tbody>
        </table>
    </form>
    <form method='post' id="form_goods">
        <input type="hidden" name="type" id="type" value="" />
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th class="w24"></th>
                <th class="w60" colspan="2"><?php echo \think\Lang::get('snsalbum_class_name'); ?></th>
                <th class=""><?php echo \think\Lang::get('snsalbum_member_name'); ?></th>
                <th><?php echo \think\Lang::get('snsalbum_add_time'); ?></th>
                <th class=""><?php echo \think\Lang::get('snsalbum_pic_count'); ?></th>
                <th class="w48 align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!(empty($ac_list) || (($ac_list instanceof \think\Collection || $ac_list instanceof \think\Paginator ) && $ac_list->isEmpty()))): if(is_array($ac_list) || $ac_list instanceof \think\Collection || $ac_list instanceof \think\Paginator): $i = 0; $__LIST__ = $ac_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr class="hover edit">
                <td></td>
                <td class="w60 picture">
                    <div class="size-44x44">
                        <span class="thumb size-44x44"><i></i>
                            <img src="<?php echo \think\Config::get('url_domain_root'); if($v['ac_cover'] != ''){echo UPLOAD_SITE_URL.DS.ATTACH_MALBUM.DS.$v['member_id'].DS.$v['ac_cover'];}else{echo '/uploads/home/common/default_goods_image.gif';}?>"  onload="javascript:DrawImage(this,44,44);"/>
                        </span>
                    </div>
                </td>
                <td><?php echo $v['ac_name']; ?></td>
                <td><?php echo $v['member_name']; ?></td>
                <td><?php echo date('Y-m-d',$v['upload_time']); ?></td>
                <td><?php echo $v['count']; ?></td>
                <td class="align-center"><a href="<?php echo url('snsmalbum/pic_list',['id'=>$v['ac_id']]); ?>"><?php echo \think\Lang::get('ds_view'); ?></a></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="15"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </tr>
           <?php endif; ?>
            </tbody>
        </table>
         <tfoot>
            <tr class="tfoot">
                <td colspan="16"><div class="pagination"><?php echo $showpage; ?> </div></td>
            </tr>
            </tfoot>
    </form>
</div>