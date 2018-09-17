<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:90:"C:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\member\member.html";i:1536983892;s:90:"C:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1536983892;s:95:"C:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1536983892;}*/ ?>
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
                <h3>会员管理</h3>
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

    <form method="get" name="formSearch" id="formSearch">
        <table class="search-form">
            <tbody>
                <tr>
                    <td>
                        <select name="search_field_name" >
                            <option <?php if($search_field_name == 'member_name'){ ?>selected='selected'<?php } ?> value="member_name"><?php echo \think\Lang::get('member_index_name'); ?></option>
                            <option <?php if($search_field_name == 'member_email'){ ?>selected='selected'<?php } ?> value="member_email"><?php echo \think\Lang::get('member_index_email'); ?></option>
                            <option <?php if($search_field_name == 'member_mobile'){ ?>selected='selected'<?php } ?> value="member_mobile">手机号码</option>
                            <option <?php if($search_field_name == 'member_truename'){ ?>selected='selected'<?php } ?> value="member_truename"><?php echo \think\Lang::get('member_index_true_name'); ?></option>
                        </select>
                    </td>
                    <td><input type="text" value="<?php echo $search_field_value;?>" name="search_field_value" class="txt"></td>
                    <td>
                        <select name="search_sort" >
                            <option value=""><?php echo \think\Lang::get('ds_sort'); ?></option>
                            <option <?php if($search_sort == 'member_login_time desc'){ ?>selected='selected'<?php } ?> value="member_login_time desc"><?php echo \think\Lang::get('member_index_last_login'); ?></option>
                            <option <?php if($search_sort == 'member_login_num desc'){ ?>selected='selected'<?php } ?> value="member_login_num desc"><?php echo \think\Lang::get('member_index_login_time'); ?></option>
                        </select>
                    </td>
                    <td>
                        <select name="search_state" >
                            <option <?php if(\think\Request::instance()->get('search_state')): ?>selected='selected'<?php endif; ?> value=""><?php echo \think\Lang::get('member_index_state'); ?></option>
                            <option <?php if(\think\Request::instance()->get('search_state') == "no_informallow"): ?>selected='selected'<?php endif; ?> value="no_informallow"><?php echo \think\Lang::get('member_index_inform_deny'); ?></option>
                            <option <?php if(\think\Request::instance()->get('search_state') == "no_isbuy"): ?>selected='selected'<?php endif; ?> value="no_isbuy"><?php echo \think\Lang::get('member_index_buy_deny'); ?></option>
                            <option <?php if(\think\Request::instance()->get('search_state') == "no_isallowtalk"): ?>selected='selected'<?php endif; ?> value="no_isallowtalk"><?php echo \think\Lang::get('member_index_talk_deny'); ?></option>
                            <option <?php if(\think\Request::instance()->get('search_state') == "no_memberstate"): ?>selected='selected'<?php endif; ?> value="no_memberstate"><?php echo \think\Lang::get('member_index_login_deny'); ?></option>
                        </select>
                    </td>
                    <td>
                        <select name="search_grade" >
                            <option value='-1'>会员级别</option>
                            <?php if ($member_grade){foreach ($member_grade as $k=>$v){?>
                            <option <?php if(isset($_GET['search_grade']) && $_GET['search_grade'] == $k){ ?>selected='selected'<?php } ?> value="<?php echo $k;?>"><?php echo $v['level_name'];?></option>
                            <?php }}?>
                        </select>
                    </td>
                    <td>
                        <input type="submit" class="submit" value="搜索">
                        <?php if($search_field_value != '' or $search_sort != ''){?>
                        <a href="<?php echo url('Member/member'); ?>" class="btns"><span><?php echo \think\Lang::get('ds_cancel_search'); ?></span></a>
                        <?php }?>
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
            <li>系统平台全局设置,包括基础设置、购物、短信、邮件、水印和分销等相关模块。</li>
        </ul>
    </div>

    <table class="ds-default-table">
      <thead>
        <tr class="thead">
          <th>&nbsp;</th>
          <th colspan="2"><?php echo \think\Lang::get('member_index_name'); ?></th>
          <th class="align-center"><span fieldname="logins" nc_type="order_by"><?php echo \think\Lang::get('member_index_login_time'); ?></span></th>
          <th class="align-center"><span fieldname="last_login" nc_type="order_by"><?php echo \think\Lang::get('member_index_last_login'); ?></span></th>
          <th class="align-center"><?php echo \think\Lang::get('member_index_points'); ?></th>
          <th class="align-center"><?php echo \think\Lang::get('member_index_prestore'); ?></th>
          <th class="align-center">经验值</th>
          <th class="align-center">级别</th>
          <th class="align-center"><?php echo \think\Lang::get('member_index_login'); ?></th>
          <th class="align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
        </tr>
      <tbody>
        <?php if(!empty($member_list) && is_array($member_list)){ foreach($member_list as $k => $v){ ?>
        <tr class="hover member">
          <td class="w24"><input type="checkbox" name='del_id[]' value="<?php echo $v['member_id']; ?>" class="checkitem"></td>
          <td class="w48 picture">
              <div class="size-44x44">
              <span class="thumb"><i></i>
                  <img src="<?php echo getMemberAvatar($v['member_avatar']); ?>?<?php echo microtime(); ?>"  onload="javascript:DrawImage(this,44,44);"/>
              </span>
          </div>
          </td>
          <td><p class="name"><strong><?php echo $v['member_name']; ?></strong>(<?php echo \think\Lang::get('member_index_true_name'); ?>: <?php echo $v['member_truename']; ?>)</p>
            <p class="smallfont"><?php echo \think\Lang::get('member_index_reg_time'); ?>:&nbsp;<?php echo $v['member_add_time']; ?></p>
            
              <div class="im"><span class="email" >
                <?php if($v['member_email'] != ''){ ?>
                <a href="mailto:<?php echo $v['member_email']; ?>" class=" yes" title="<?php echo \think\Lang::get('member_index_email'); ?>:<?php echo $v['member_email']; ?>"><?php echo $v['member_email']; ?></a></span>
                <?php }else { ?>
                <a href="JavaScript:void(0);" class="" title="<?php echo \think\Lang::get('member_index_null'); ?>" ><?php echo $v['member_email']; ?></a></span>
                <?php } if($v['member_ww'] != ''){ ?>
                <a target="_blank" href="http://web.im.alisoft.com/msg.aw?v=2&uid=<?php echo $v['member_ww'];?>&site=cnalichn&s=11" class="" title="WangWang: <?php echo $v['member_ww'];?>"><img border="0" src="http://web.im.alisoft.com/online.aw?v=2&uid=<?php echo $v['member_ww'];?>&site=cntaobao&s=2&charset=utf-8" /></a>
                <?php } if($v['member_qq'] != ''){ ?>                
                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $v['member_qq'];?>&site=qq&menu=yes" class=""  title="QQ: <?php echo $v['member_qq'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $v['member_qq'];?>:52"/></a>
                <?php } ?>
                <!--v3-b11 显示手机号码-->
               <?php if($v['member_mobile'] != ''){ ?>
               <div style="font-size:13px; padding-left:10px">&nbsp;&nbsp;<?php echo $v['member_mobile']; ?></div>
               <?php } ?>
              </div></td>
          <td class="align-center"><?php echo $v['member_login_num']; ?></td>
          <td class="w150 align-center"><p><?php echo $v['member_login_time']; ?></p>
            <p><?php echo $v['member_login_ip']; ?></p></td>
          <td class="align-center"><?php echo $v['member_points']; ?></td>
          <td class="align-center"><p><?php echo \think\Lang::get('member_index_available'); ?>:&nbsp;<strong class="red"><?php echo $v['available_predeposit']; ?></strong>&nbsp;<?php echo \think\Lang::get('currency_zh'); ?></p>
            <p><?php echo \think\Lang::get('member_index_frozen'); ?>:&nbsp;<strong class="red"><?php echo $v['freeze_predeposit']; ?></strong>&nbsp;<?php echo \think\Lang::get('currency_zh'); ?></p>
          </td>
          <td class="align-center"><?php echo $v['member_exppoints'];?></td>
          <td class="align-center"><?php echo $v['member_grade'];?></td>
          <td class="align-center"><?php echo $v['member_state'] == 1?lang('member_edit_allow'):lang('member_edit_deny'); ?></td>
          <td class="align-center">
              <a href="<?php echo url('/admin/member/edit',['member_id'=>$v['member_id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a> | 
              <a href="<?php echo url('/admin/notice/notice',['member_name'=>$v['member_name']]); ?>"><?php echo \think\Lang::get('member_index_to_message'); ?></a> | 
              <a href="<?php echo url('/admin/member/drop',['member_id'=>$v['member_id']]); ?>"><?php echo \think\Lang::get('ds_del'); ?></a>
          </td>
        </tr>
        <?php } }else { ?>
        <tr class="no_data">
          <td colspan="11"><?php echo \think\Lang::get('ds_no_record'); ?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot class="tfoot">
        <?php if(!empty($member_list) && is_array($member_list)){ ?>
        <tr>
        <td class="w24"><input type="checkbox" class="checkall" id="checkallBottom"></td>
          <td colspan="16">
          <label for="checkallBottom"><?php echo \think\Lang::get('ds_select_all'); ?></label>
            &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="if(confirm('<?php echo \think\Lang::get('ds_ensure_del'); ?>')){$('#form_member').submit();}"><span><?php echo \think\Lang::get('ds_del'); ?></span></a>
          </td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
    <?php echo $page; ?>
    
</div>