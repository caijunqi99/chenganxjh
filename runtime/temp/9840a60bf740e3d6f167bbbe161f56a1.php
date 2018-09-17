<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:94:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\notice\notice_add.html";i:1514867096;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>会员通知</h3>
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

<div class="fixed-empty"></div>
  <form id="notice_form" method="POST">
    <table class="ds-default-table">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label><?php echo lang('notice_index_send_type'); ?>: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><ul class="nofloat">
              <li>
                <label><input type="radio" checked="" value="1" name="send_type"><?php echo lang('notice_index_spec_member'); ?></label>
              </li>
              <li>
                <label><input type="radio" value="2" name="send_type" /><?php echo lang('notice_index_all_member'); ?></label>
              </li>
            </ul>
          </td>
          <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tbody id="user_list">
        <tr>
          <td colspan="2" class="required"><label class="validation" for="user_name"><?php echo lang('notice_index_member_list'); ?>: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><textarea id="user_name" name="user_name" rows="6" class="tarea" ><?php echo str_replace(' ','+',\think\Request::instance()->param('member_name')); ?></textarea></td>
          <td class="vatop tips"><?php echo lang('notice_index_member_tip'); ?></td>
        </tr>
      </tbody>
      <tbody id="msg">
        <tr>
          <td colspan="2" class="required"><label class="validation"><?php echo lang('notice_index_content'); ?>: </label></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="vatop rowform"><textarea name="content1" rows="6" class="tarea"></textarea></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo lang('ds_submit'); ?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>

</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#notice_form").valid()){
        $("#notice_form").submit();
    }
});
});
$(document).ready(function(){
    $('#notice_form').validate({
        errorPlacement: function(error, element){
            error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
            user_name : {
                required : check_user_name
            },
            content1 :{
                required : true
            }
        },
        messages : {
            user_name :{
                required     : '<?php echo lang('notice_index_member_error'); ?>'
            },
            content1 :{
                required : '<?php echo lang('notice_index_content_null'); ?>'
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    function check_user_name()
    {
        var rs = $(":input[name='send_type']:checked").val();
        return rs == 1 ? true : false;
    }

    $("input[name='send_type']").click(function(){
        var rs = $(this).val();
        switch(rs)
        {
            case '1':
                $('#user_list').show();
                break;
            case '2':
                $('#user_list').hide();
                break;
        }
    });
});
</script>