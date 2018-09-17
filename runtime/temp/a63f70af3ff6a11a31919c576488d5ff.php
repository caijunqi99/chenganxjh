<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:88:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\wechat\list.html";i:1514877313;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;}*/ ?>
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
                <h3>消息推送</h3>
            </div>
            <a href="<?php echo url('Wechat/SendGroup'); ?>"  class="btn" style="float: right">消息群发</a>
        </div>
    </div>
            <table class="ds-default-table">
                <thead>
                <tr>
                    <th style="width: 10%"><?php echo \think\Lang::get('tomember'); ?></th>
                    <th style="width: 10%"><?php echo \think\Lang::get('totype'); ?></th>
                    <th style="width: 40%"><?php echo \think\Lang::get('content'); ?></th>
                    <th style="width: 20%"><?php echo \think\Lang::get('totime'); ?></th>
                    <th style="width: 10%"><?php echo \think\Lang::get('state'); ?></th>
                    <th style="width: 10%"><?php echo \think\Lang::get('ds_handle'); ?></th>
                </tr>
                </thead>
                <?php if(!(empty($lists) || (($lists instanceof \think\Collection || $lists instanceof \think\Paginator ) && $lists->isEmpty()))): ?>
                <tbody>
                <?php if(is_array($lists) || $lists instanceof \think\Collection || $lists instanceof \think\Paginator): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?>
                <tr>
                    <td><?php echo $list['member_name']; ?></td>
                    <td><?php echo $list['type']; ?></td>
                   <td><?php echo $list['content']; ?></td>
                   <td><?php echo date('Y-m-d H:i:s',$list['createtime']); ?></td>
                  <td><?php if($list['issend']): ?>已发送<?php else: ?><span style="color: red">未发送</span><?php endif; ?></td>
                    <td>
                        <a href="<?php echo url('/admin/Wechat/text_form',['id'=>$list['id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a>|
                        <a href="javascript:if(confirm('是否确认删除？'))window.location ='<?php echo url('/admin/Wechat/del_text',['id'=>$list['id']]); ?>'" ><?php echo \think\Lang::get('ds_del'); ?></a>
                    </td>
                </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
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

      <div class="ncap-form-default show_new" id="dialog" style="display: none">
        <form method="post">
            <dl>
                <dt>标题</dt>
                <dd>
                    <input type="text" name="title" id="Title" style="width: 300px" value="">
                </dd>
            </dl>
            <dl>
                <dt>描述</dt>
                <dd>
                    <input type="text" name="description" id="Description" style="width: 300px">
                </dd>
            </dl>
            <dl>
                <dt>图片</dt>
                <dd>
                    <input type="file" name="s_pic">
                </dd>
            </dl>
            <dl>
                <dt>跳转链接</dt>
                <dd>
                <input type="text" name="url" id="Url" style="width: 300px">
                </dd>
            </dl>
        </form>
      </div>
 <div class="pagination"><?php echo $show_page; ?></div>
<script>
    $('.news').click(function() {
        $( "#dialog" ).dialog("open");
        });
    $( "#dialog" ).dialog({
        autoOpen: false,
        modal: true,
        width: 500,
        height: 400,
        show: {
            effect: "explode",
            duration: 1000
        },
        hide: {
            effect: "puff",
            duration: 1000
        }
    });

    var content= $.attr('content');

</script>