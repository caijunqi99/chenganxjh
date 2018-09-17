<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\chatlog\index.html";i:1514867095;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>聊天记录</h3>
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


    <form method="get" action="" name="formSearch" id="formSearch">
        
        <table class="search-form">
            <tbody>
            <tr>
                <th>发送人</th>
                <td><input type="text" class="text" name="f_name" value="<?php echo \think\Request::instance()->get('f_name'); ?>" /></td>
                <th>回复人</th>
                <td><input type="text" class="text" name="t_name" value="<?php echo \think\Request::instance()->get('t_name'); ?>" /></td>
                <th><label for="add_time_from">起止日期</label></th>
                <td><input class="txt date" type="text" value="<?php echo \think\Request::instance()->get('add_time_from'); ?>" id="add_time_from" name="add_time_from">
                    <label for="add_time_to">~</label>
                    <input class="txt date" type="text" value="<?php echo \think\Request::instance()->get('add_time_to'); ?>" id="add_time_to" name="add_time_to"/></td>
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
            <li>发送人即发出消息的会员，回复人为收到消息的会员，如果是店铺的客服或管理员可显示所属店铺名称。</li>
            <li>为使查询信息更准确，请输入聊天双方的完整会员名——登录账号。</li>
            <li>可查询“<?php echo $minDate; ?>”到“<?php echo $maxDate; ?>”的90天内聊天记录。</li>
        </ul>
    </div>
    

    <div class="chat-log">
        <ul class="chat-log-list">
            <?php if(!(empty($log_list) || (($log_list instanceof \think\Collection || $log_list instanceof \think\Paginator ) && $log_list->isEmpty()))): if(is_array($log_list) || $log_list instanceof \think\Collection || $log_list instanceof \think\Paginator): $i = 0; $__LIST__ = $log_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;if($val['f_id'] == $f_member['member_id']): ?>
            <li class="from_log"><span class="avatar"><img src="<?php echo getMemberAvatarForID($val['f_id']); ?>"/></span>
               <?php if($f_member['store_id'] > 0): ?>
                <dl>
                    <dt class="store_log"><?php echo $f_member['store_name']; ?>客服：<?php echo $f_member['seller_name']; ?> <span>(会员ID：<?php echo $val['f_name']; ?>)</span>
                    </dt>
                    <dd class="time"><?php echo date('Y-m-d H:i:s',$val['add_time']); ?></dd>
                    <dd class="content"><?php echo parsesmiles($val['t_msg']); ?></dd>
                </dl>
                <?php else: ?>
                <dl>
                    <dt class="user_log">会员：<?php echo $val['f_name']; ?></dt>
                    <dd class="time"><?php echo date('Y-m-d H:i:s',$val['add_time']); ?></dd>
                    <dd class="content"><?php echo parsesmiles($val['t_msg']); ?></dd>
                </dl>
               <?php endif; ?>
            </li>
            <?php else: ?>
            <li class="to_log"><span class="avatar"><img src="<?php echo getMemberAvatarForID($val['f_id']); ?>"/></span>
                <?php if($t_member['store_id'] >0): ?>
                <dl>
                    <dt class="store_log"><?php echo $t_member['store_name']; ?>客服：<?php echo $t_member['seller_name']; ?> <span>(会员ID：<?php echo $val['f_name']; ?>)</span>
                    </dt>
                    <dd class="time"><?php echo date('Y-m-d H:i:s',$val['add_time']); ?></dd>
                    <dd class="content"><?php echo parsesmiles($val['t_msg']); ?></dd>
                </dl>
                <?php else: ?>
                <dl>
                    <dt class="user_log">会员：<?php echo $val['f_name']; ?></dt>
                    <dd class="time"><?php echo date('Y-m-d H:i:s',$val['add_time']); ?></dd>
                    <dd class="content"><?php echo parsesmiles($val['t_msg']); ?></dd>
                </dl>
                <?php endif; ?>
            </li>
           <?php endif; endforeach; endif; else: echo "" ;endif; else: ?>
             <li class="no_data">
                <td colspan="15"><?php echo \think\Lang::get('ds_no_record'); ?></td>
            </li>
           <?php endif; ?>
        </ul>
        <?php if(!(empty($log_list) || (($log_list instanceof \think\Collection || $log_list instanceof \think\Paginator ) && $log_list->isEmpty()))): ?>
        <div class="pagination"><?php echo $show_page; ?></div>
        <?php endif; ?>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('#add_time_from').datepicker({dateFormat: 'yy-mm-dd',minDate: '<?php echo $minDate; ?>'});
        $('#add_time_to').datepicker({dateFormat: 'yy-mm-dd',maxDate: '<?php echo $maxDate; ?>'});
    });
</script>