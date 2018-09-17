<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:88:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\wechat\menu.html";i:1514877313;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>微信菜单</h3>
                <h5></h5>
            </div>
            <?php if($admin_item): ?>
<ul class="tab-base ds-row">
    <?php if(is_array($admin_item) || $admin_item instanceof \think\Collection || $admin_item instanceof \think\Paginator): if( count($admin_item)==0 ) : echo "" ;else: foreach($admin_item as $key=>$item): ?>
    <li><a href="<?php echo $item['url']; ?>" <?php if($item['name'] == $curitem): ?>class="current"<?php endif; ?>><span><?php echo $item['text']; ?></span></a></li>
    <?php endforeach; endif; else: echo "" ;endif; ?>
</ul>
<?php endif; ?>
            <a href="<?php echo url('Wechat/pub_menu'); ?>"  class="btn btn-small" style="float: right">更新菜单</a>
        </div>
    </div>
     <div class="explanation" id="explanation">
        <div class="title" id="checkZoom">
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示" class="arrow"></span>
        </div>
        <ul>
            <li>1.自定义菜单最多包括3个一级菜单，每个一级菜单最多包含5个二级菜单</li>
            <li>2.一级菜单最多4个汉字，二级菜单最多7个汉字，多出来的部分将会以“...”代替。</li>
            <li>3.创建自定义菜单后，菜单的刷新策略是，在用户进入公众号会话页或公众号profile页时，如果发现上一次拉取菜单的请求在5分钟以前，就会拉取一下菜单，如果菜单有更新，就会刷新客户端的菜单。测试时可以尝试取消关注公众账号后再次关注，则可以看到创建后的效果。</li>
            <li>4.目前支持的菜单类型的命令：hot点击率高的商品 commend店铺推荐商品 sale销售量 colleect收藏量</li>
            <li>5.菜单添加完成后点击右上角更新菜单按钮同步菜单到公众号</li>
        </ul>
    </div>
            <table class="ds-default-table">
                <thead>
                <tr>
                    <th><?php echo \think\Lang::get('menu_name'); ?></th>
                    <th><?php echo \think\Lang::get('menu_type'); ?></th>
                    <th><?php echo \think\Lang::get('menu_value'); ?></th>
                    <th><?php echo \think\Lang::get('menu_sort'); ?></th>
                    <th><?php echo \think\Lang::get('ds_handle'); ?></th>
                </tr>
                </thead>
                <?php if(!(empty($p_menu) || (($p_menu instanceof \think\Collection || $p_menu instanceof \think\Paginator ) && $p_menu->isEmpty()))): ?>
                <tbody>
                <?php if(is_array($p_menu) || $p_menu instanceof \think\Collection || $p_menu instanceof \think\Paginator): $i = 0; $__LIST__ = $p_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pmenu): $mod = ($i % 2 );++$i;?>
                <tr>
                    <td><?php echo $pmenu['name']; ?></td>
                    <td><?php echo $menu_type[$pmenu['type']]; ?></td>
                    <td><?php echo $pmenu['value']; ?></td>
                    <td><?php echo $pmenu['sort']; ?></td>
                    <td>
                        <a href="<?php echo url('/admin/Wechat/menu_edit',['id'=>$pmenu['id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a>|
                        <a href="javascript:if(confirm('是否确认删除？'))window.location ='<?php echo url('/admin/Wechat/menu_drop',['id'=>$pmenu['id']]); ?>'" ><?php echo \think\Lang::get('ds_del'); ?></a>
                    </td>
                </tr>
                <?php if(is_array($c_menu[$pmenu['id']]) || $c_menu[$pmenu['id']] instanceof \think\Collection || $c_menu[$pmenu['id']] instanceof \think\Paginator): $i = 0; $__LIST__ = $c_menu[$pmenu['id']];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cmenu): $mod = ($i % 2 );++$i;if($cmenu['pid'] == $pmenu['id']): ?>
                <tr>
                    <td>|___<?php echo $cmenu['name']; ?></td>
                    <td><?php echo $menu_type[$pmenu['type']]; ?></td>
                    <td><?php echo $cmenu['value']; ?></td>
                    <td><?php echo $cmenu['sort']; ?></td>
                    <td>
                        <a href="<?php echo url('/admin/Wechat/menu_edit',['id'=>$cmenu['id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a>|
                        <a href="javascript:if(confirm('是否确认删除？'))window.location ='<?php echo url('/admin/Wechat/menu_drop',['id'=>$cmenu['id']]); ?>'" ><?php echo \think\Lang::get('ds_del'); ?></a>
                    </td>
                </tr>
                <?php endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
                <?php else: ?>
                <tbody>
            <tr class="no_data">
                <td colspan="20"><?php echo \think\Lang::get('no_record'); ?></td>
            </tr>
        </tbody>
                <?php endif; ?>
            </table>
        </div>