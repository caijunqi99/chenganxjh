<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:97:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\ownshop\ownshop_list.html";i:1514867097;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>自营店铺</h3>
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
            <li>平台在此处统一管理自营店铺，可以新增、编辑、删除平台自营店铺</li>
            <li>可以设置未绑定全部商品类目的平台自营店铺的经营类目</li>
            <li>已经发布商品的自营店铺不能被删除</li>
            <li>删除平台自营店铺将会同时删除店铺的相关图片以及相关商家中心账户，请谨慎操作！</li>
        </ul>
    </div>
    

    <form method="get" name="formSearch" id="formSearch">
        <table class="search-form">
            <tbody>
                <tr>
                    <th><label for="store_name">店铺</label></th>
                    <td><input type="text" value="<?php echo \think\Request::instance()->get('store_name'); ?>" name="store_name" id="store_name" class="txt" /></td>
                    <td>
                        <input type="submit" class="submit" value="搜索">
                    </td>
                </tr>
            </tbody>
        </table>
    </form>


    <form method="post" id="store_form">
        <input type="hidden" name="form_submit" value="ok" />
        <table class="ds-default-table">
            <thead>
                <tr class="thead">
                    <th>店铺名称</th>
                    <th>店主账号</th>
                    <th>店主卖家账号</th>
                    <th class="align-center">状态</th>
                    <th class="align-center">绑定所有类目</th>
                    <th class="align-center">操作</th>
                </tr>
            </thead>
            <?php if (empty($store_list)) { ?>
            <tbody>
                <tr class="no_data">
                    <td colspan="15"><?php echo \think\Lang::get('ds_no_record'); ?></td>
                </tr>
            </tbody>
            <?php } else { ?>
            <tbody>
                <?php foreach($store_list as $k => $v) { ?>
                <tr class="">
                    <td>
                        <a href="<?php echo url('/Home/store/index',['store_id'=>$v['store_id']]); ?>" >
                            <?php echo $v['store_name']; ?>
                        </a>
                    </td>
                    <td><?php echo $v['member_name']; ?></td>
                    <td><?php echo $v['seller_name']; ?></td>
                    <td class="align-center w72">
                        <?php echo $v['store_state'] ? lang('open') : lang('close'); ?>
                    </td>
                    <td class="align-center w120"><?php echo $v['bind_all_gc'] ? '是' : '否'; ?></td>
                    <td class="align-center w200">
                        <a href="<?php echo url('/Admin/ownshop/edit',['id'=>$v['store_id']]); ?>">编辑</a>
                        <?php if (!$v['bind_all_gc']) { ?>
                        |
                        <a href="<?php echo url('/Admin/ownshop/bind_class',['id'=>$v['store_id']]); ?>">经营类目</a>
                        <?php } if (empty($storesWithGoods[$v['store_id']])) { ?>
                        |
                        <a href="<?php echo url('/Admin/ownshop/del',['id'=>$v['store_id']]); ?>" onclick="return confirm('此操作不可逆转！确定删除？');">删除</a>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr class="tfoot">
                    <td></td>
                    <td colspan="16">
                        <div class="pagination"><?php echo $page; ?></div></td>
                </tr>
            </tfoot>
            <?php } ?>
        </table>
    </form>
</div>
