<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:88:"C:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\store\store.html";i:1536983892;s:90:"C:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1536983892;s:95:"C:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1536983892;}*/ ?>
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
                <h3>店铺管理</h3>
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
                    <th><label><?php echo \think\Lang::get('belongs_level'); ?></label></th>
                    <td>
                        <select name="grade_id">
                            <option value=""><?php echo \think\Lang::get('ds_please_choose'); ?>...</option>
                            <?php if(!empty($grade_list) && is_array($grade_list)){ foreach($grade_list as $k => $v){ ?>
                            <option value="<?php echo $v['sg_id'];?>" <?php if(\think\Request::instance()->get('grade_id') == $v['sg_id']): ?>selected<?php endif; ?>><?php echo $v['sg_name'];?></option>
                            <?php } } ?>
                        </select>
                    </td>
                    <th><label for="owner_and_name"><?php echo \think\Lang::get('store_user'); ?></label></th>
                    <td><input type="text" value="<?php echo \think\Request::instance()->get('owner_and_name'); ?>" name="owner_and_name" id="owner_and_name" class="txt"></td><td></td><th><label>店铺类型</label></th>
                    <td>
                        <select name="store_type">
                            <option value=""><?php echo \think\Lang::get('ds_please_choose'); ?>...</option>
                            <?php if(!empty($store_type) && is_array($store_type)){ foreach($store_type as $k => $v){ ?>
                            <option value="<?php echo $k;?>" <?php if(\think\Request::instance()->get('store_type') == $k): ?>selected<?php endif; ?>><?php echo $v;?></option>
                            <?php } } ?>
                        </select>
                    </td>
                    <th><label for="store_name"><?php echo \think\Lang::get('store_name'); ?></label></th>
                    <td><input type="text" value="<?php echo \think\Request::instance()->get('store_name'); ?>" name="store_name" id="store_name" class="txt"></td>
                    <td>
                        <input type="submit" class="submit" value="搜索">
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    
    
    <table class="ds-default-table">
      <thead>
        <tr class="thead">
          <th><?php echo \think\Lang::get('store_name'); ?></th>
          <th><?php echo \think\Lang::get('store_user_name'); ?></th>
          <th>店主卖家账号</th>
          <th class="align-center"><?php echo \think\Lang::get('belongs_level'); ?></th>
          <th class="align-center"><?php echo \think\Lang::get('period_to'); ?></th>
          <th class="align-center"><?php echo \think\Lang::get('state'); ?></th>
          <th class="align-center"><?php echo \think\Lang::get('ds_handle'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($store_list) && is_array($store_list)){ foreach($store_list as $k => $v){ ?>
        <tr class="hover edit <?php echo getStoreStateClassName($v);?>">
          <td>
              <a href="<?php echo url('/Home/Store/index',['store_id'=>$v['store_id']]); ?>" >
                <?php echo $v['store_name'];?>
            </a>
          </td>
          <td><?php echo $v['member_name'];?></td>
          <td><?php echo $v['seller_name'];?></td>
          <td class="align-center"><?php echo $search_grade_list[$v['grade_id']];?></td>
          <td class="nowarp align-center"><?php echo $v['store_end_time']?date('Y-m-d', $v['store_end_time']):lang('no_limit');?></td>
          <td class="align-center w72"><?php echo $v['store_state']?lang('open'):lang('close');?></td>
        <td class="align-center w200">
            <a href="<?php echo url('/Admin/Store/store_joinin_detail',['member_id'=>$v['member_id']]); ?>">查看</a>&nbsp;&nbsp;
            <a href="<?php echo url('/Admin/Store/store_edit',['store_id'=>$v['store_id']]); ?>"><?php echo \think\Lang::get('ds_edit'); ?></a>&nbsp;&nbsp;
            <a href="<?php echo url('/Admin/Store/store_bind_class',['store_id'=>$v['store_id']]); ?>">经营类目</a>
            <?php if (getStoreStateClassName($v) != 'open' && cookie('remindRenewal'.$v['store_id']) == null) {?>
            <a href="<?php echo url('/Admin/Store/remind_renewal',['store_id'=>$v['store_id']]); ?>">提醒续费</a><?php }?>
            &nbsp;&nbsp; 
            <a href="<?php echo url('/Admin/Store/del',['id'=>$v['store_id'],'member_id'=>$v['member_id']]); ?>" onclick="return confirm('您确认要删除此店铺吗？');">删除</a>
            </td>
        </tr>
        <?php } }else { ?>
        <tr class="no_data">
          <td colspan="15"><?php echo \think\Lang::get('ds_no_record'); ?></td>
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
    </table>
    
</div>