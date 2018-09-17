<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:88:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\config\base.html";i:1514867089;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1536980988;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>想见孩系统后台</title>
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
                <h3>站点设置</h3>
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
    
    <form method="post" enctype="multipart/form-data" name="form1" action="">
        <div class="ncap-form-default">
            <dl>
                <dt><?php echo \think\Lang::get('site_name'); ?></dt>
                <dd>
                    <input id="site_name" name="site_name" value="<?php echo $list_config['site_name']; ?>" class="input-txt" type="text">
                    <span class="err"></span>
                    <p class="notic"><?php echo \think\Lang::get('web_name_notice'); ?></p>
                </dd>
            </dl>
            <dl>
                <dt><?php echo \think\Lang::get('site_logo'); ?></dt>
                <dd>
                    <span class="type-file-show"><img class="show_image" src="<?php echo \think\Config::get('url_domain_root'); ?>/static/admin/images/preview.png">
                        <div class="type-file-preview"><img src="<?php echo \think\Config::get('url_domain_root'); ?><?php echo '/uploads/'.ATTACH_COMMON;?>/<?php echo $list_config['site_logo']; ?>"></div>
                    </span>
                    <span class="type-file-box"><input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
                        <input name="site_logo" type="file" class="type-file-file" id="site_logo" size="30" hidefocus="true" nc_type="change_site_logo">
                    </span>
                    <p class="notic">默认网站LOGO,通用头部显示，最佳显示尺寸为240*60像素</p>
                </dd>
            </dl>
            <dl>
                <dt><?php echo \think\Lang::get('member_logo'); ?></dt>
                <dd>
                    <span class="type-file-show"><img class="show_image" src="<?php echo \think\Config::get('url_domain_root'); ?>/static/admin/images/preview.png">
                        <div class="type-file-preview"><img src="<?php echo \think\Config::get('url_domain_root'); ?><?php echo '/uploads/'.ATTACH_COMMON;?>/<?php echo $list_config['member_logo']; ?>"></div>
                    </span>
                    <span class="type-file-box"><input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
                        <input name="member_logo" type="file" class="type-file-file" id="member_logo" size="30" hidefocus="true" nc_type="change_member_logo">
                    </span>
                    <p class="notic">网站小尺寸LOGO，会员个人主页显示，最佳显示尺寸为200*40像素</p>
                </dd>
            </dl>
            <dl>
                <dt><?php echo \think\Lang::get('seller_center_logo'); ?></dt>
                <dd>
                    <span class="type-file-show"><img class="show_image" src="<?php echo \think\Config::get('url_domain_root'); ?>/static/admin/images/preview.png">
                        <div class="type-file-preview"><img src="<?php echo \think\Config::get('url_domain_root'); ?><?php echo '/uploads/'.ATTACH_COMMON;?>/<?php echo $list_config['seller_center_logo']; ?>"></div>
                    </span>
                    <span class="type-file-box"><input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
                        <input name="seller_center_logo" type="file" class="type-file-file" id="seller_center_logo" size="30" hidefocus="true" nc_type="change_seller_center_logo">
                    </span>
                    <p class="notic">商家中心LOGO，最佳显示尺寸为150*40像素，请根据背景色选择使用图片色彩</p>
                </dd>
            </dl>
            <dl>
                <dt><?php echo \think\Lang::get('site_mobile_logo'); ?></dt>
                <dd>
                    <span class="type-file-show"><img class="show_image" src="<?php echo \think\Config::get('url_domain_root'); ?>/static/admin/images/preview.png">
                        <div class="type-file-preview"><img src="<?php echo \think\Config::get('url_domain_root'); ?><?php echo '/uploads/'.ATTACH_COMMON;?>/<?php echo $list_config['site_mobile_logo']; ?>"></div>
                    </span>
                    <span class="type-file-box"><input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
                        <input name="site_mobile_logo" type="file" class="type-file-file" id="site_mobile_logo" size="30" hidefocus="true" nc_type="change_site_mobile_logo">
                    </span>
                    <p class="notic">WAP手机端LOGO,通用头部显示，最佳显示尺寸为116*43像素</p>
                </dd>
            </dl>
            <dl>
                <dt><?php echo \think\Lang::get('site_logowx'); ?></dt>
                <dd>
                    <span class="type-file-show"><img class="show_image" src="<?php echo \think\Config::get('url_domain_root'); ?>/static/admin/images/preview.png">
                        <div class="type-file-preview"><img src="<?php echo \think\Config::get('url_domain_root'); ?><?php echo '/uploads/'.ATTACH_COMMON;?>/<?php echo $list_config['site_logowx']; ?>"></div>
                    </span>
                    <span class="type-file-box"><input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
                        <input name="site_logowx" type="file" class="type-file-file" id="site_logowx" size="30" hidefocus="true" nc_type="change_site_logowx">
                    </span>
                    <p class="notic">放在网站右上角顶部及首页底部右下角,最佳显示尺寸为66*66像素</p>
                </dd>
            </dl>
              <dl>
                <dt><?php echo \think\Lang::get('hot_search'); ?></dt>
                <dd>
                    <textarea id="hot_search" name="hot_search"><?php echo $list_config['hot_search']; ?></textarea>
                    <span class="err"></span>
                    <p class="notic"><?php echo \think\Lang::get('field_notice'); ?></p>
                </dd>
            </dl>
            <dl>
                <dt><?php echo \think\Lang::get('icp_number'); ?></dt>
                <dd>
                    <input id="icp_number" name="icp_number" value="<?php echo $list_config['icp_number']; ?>" class="input-txt" type="text">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            
            <dl>
                <dt><?php echo \think\Lang::get('site_phone'); ?></dt>
                <dd>
                    <input id="site_phone" name="site_phone" value="<?php echo $list_config['site_phone']; ?>" class="input-txt" type="text">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            
            <dl>
                <dt><?php echo \think\Lang::get('site_tel400'); ?></dt>
                <dd>
                    <input id="site_tel400" name="site_tel400" value="<?php echo $list_config['site_tel400']; ?>" class="input-txt" type="text">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl>
                <dt><?php echo \think\Lang::get('site_email'); ?></dt>
                <dd>
                    <input id="site_email" name="site_email" value="<?php echo $list_config['site_email']; ?>" class="input-txt" type="text">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl>
                <dt><?php echo \think\Lang::get('node_site_url'); ?></dt>
                <dd>
                    <input id="node_site_url" name="node_site_url" value="<?php echo (isset($list_config['node_site_url']) && ($list_config['node_site_url'] !== '')?$list_config['node_site_url']:''); ?>" class="input-txt" type="text">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl>
                <dt><?php echo \think\Lang::get('flow_static_code'); ?></dt>
                <dd>
                    <textarea id="flow_static_code" name="flow_static_code"><?php echo $list_config['flow_static_code']; ?></textarea>
                    <span class="err"></span>
                    <p class="notic"><?php echo \think\Lang::get('flow_static_code_notice'); ?></p>
                </dd>
            </dl>
            
            
            <dl>
                <dt><?php echo \think\Lang::get('site_state'); ?></dt>
                <dd>
                    <div class="onoff">
                        <label for="article_show1" class="cb-enable <?php if($list_config['site_state'] == 1): ?>selected<?php endif; ?>"><?php echo lang('ds_open'); ?></label>
                        <label for="article_show0" class="cb-disable <?php if($list_config['site_state'] == 0): ?>selected<?php endif; ?>"><?php echo lang('ds_close'); ?></label>
                        <input id="article_show1" name="site_state" value="1" type="radio" <?php if($list_config['site_state'] == 1): ?> checked="checked"<?php endif; ?>>
                        <input id="article_show0" name="site_state" value="0" type="radio" <?php if($list_config['site_state'] == 0): ?> checked="checked"<?php endif; ?>>
                    </div>
                </dd>
            </dl>
            <dl>
                <dt><?php echo \think\Lang::get('closed_reason'); ?></dt>
                <dd>
                    <textarea id="closed_reason" name="closed_reason"><?php echo $list_config['closed_reason']; ?></textarea>
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl>
                <dt></dt>
                <dd><a href="JavaScript:void(0);" class="btn" onclick="document.form1.submit()">确认提交</a></dd>
            </dl>
        </div>
    </form>
    
</div>








