<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\wechat\member.html";i:1514867103;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;}*/ ?>
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
                <h3>绑定列表</h3>
            </div>
        </div>
    </div>
 <table class="ds-default-table">
      <thead>
        <tr class="thead">
           <th class="align-center"> 微信OpenId</th>
           <th class="align-center"> 微信UnionID</th>
           <th class="align-center"> 用户名</th>
           <th class="align-center"> 绑定时间</th>
           <th class="align-center"> 操作</th>
        </tr>
      <tbody>
 <?php if(!(empty($member) || (($member instanceof \think\Collection || $member instanceof \think\Paginator ) && $member->isEmpty()))): if(is_array($member) || $member instanceof \think\Collection || $member instanceof \think\Paginator): $i = 0; $__LIST__ = $member;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
  <tr class="member">
    <td class="align-center"><?php echo $val['member_wxopenid']; ?></td>
    <td class="align-center"><?php echo $val['weixin_unionid']; ?></td>
    <td class="align-center"><?php echo $val['member_name']; ?></td>
    <td class="align-center"><?php echo date('Y_m_d H:i:s',$val['member_add_time']); ?></td>
    <td class="align-center">
       <a href="<?php echo url('/admin/member/edit',['member_id'=>$val['member_id']]); ?>">详细</a> |
       <a href="<?php echo url('/admin/Wechat/msend',['member_id'=>$val['member_id'],'openid'=>$val['member_wxopenid']]); ?>">推送消息</a>
    </td>
  </tr>
 <?php endforeach; endif; else: echo "" ;endif; else: ?>
  <tr class="no_data">
          <td colspan="11"><?php echo \think\Lang::get('ds_no_record'); ?></td>
        </tr>
 <?php endif; ?>
      </tbody>
   </thead>
 </table>

</div>