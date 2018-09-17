<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:87:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\link\index.html";i:1514877306;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>友情链接</h3>
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
                    <th><?php echo \think\Lang::get('link_index_title'); ?></th>
                    <td><input type="text" value="<?php echo \think\Request::instance()->get('search_link_title'); ?>" name="search_link_title" class="txt"></td>
                    <td>
                        <a href="javascript:document.formSearch.submit();" class="btn-search" title="<?php echo \think\Lang::get('ds_query'); ?>"></a>
                        <?php if(!(empty(\think\Request::instance()->get('search_link_title')) || ((\think\Request::instance()->get('search_link_title') instanceof \think\Collection || \think\Request::instance()->get('search_link_title') instanceof \think\Paginator ) && \think\Request::instance()->get('search_link_title')->isEmpty()))): ?>
                        <a href="<?php echo url('Link/index'); ?>" class="btns tooltip" title="<?php echo \think\Request::instance()->get('nc_cancel_search'); ?>"><span><?php echo \think\Request::instance()->get('nc_cancel_search'); ?></span></a>
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
            <li>通过合作伙伴管理你可以，编辑、查看、删除合作伙伴信息</li>
            <li>在搜索处点击图片则表示将搜索图片标识仅为图片的相关信息，点击文字则表示将搜索图片标识仅为文字的相关信息，点击全部则搜索所有相关信息</li>
        </ul>
    </div>

    <table class="ds-default-table">
        <thead>
            <tr>
                <th><?php echo \think\Lang::get('link_sort'); ?></th>
                <th><?php echo \think\Lang::get('link_title'); ?></th>
                <th><?php echo \think\Lang::get('link_pic'); ?></th>
                <th><?php echo \think\Lang::get('link_url'); ?></th>
                <th><?php echo \think\Lang::get('ds_handle'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if(!(empty($link_list) || (($link_list instanceof \think\Collection || $link_list instanceof \think\Paginator ) && $link_list->isEmpty()))): if(is_array($link_list) || $link_list instanceof \think\Collection || $link_list instanceof \think\Paginator): $i = 0; $__LIST__ = $link_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$link): $mod = ($i % 2 );++$i;?>
            <tr>
                <td><?php echo $link['link_sort']; ?></td>
                <td><?php echo $link['link_title']; ?></td>
                <td><img class="img-responsive" src="<?php echo \think\Config::get('url_domain_root'); ?>/uploads/admin/link/<?php echo $link['link_pic']; ?>" width="31px" height="31px"></td>
                <td><?php echo $link['link_url']; ?></td>
                <td>
                    <a href="<?php echo url('/admin/link/edit',['link_id'=>$link['link_id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a>
                    <a href="javascript:if(confirm('<?php echo \think\Lang::get('sure_drop'); ?>'))window.location ='<?php echo url('/admin/link/drop',['link_id'=>$link['link_id']]); ?>'" ><?php echo \think\Lang::get('ds_del'); ?></a>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr class="no_data">
                <td colspan="20"><?php echo \think\Lang::get('no_record'); ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php echo $page; ?>
</div>