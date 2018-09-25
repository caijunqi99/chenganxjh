<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:92:"C:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\operation\index.html";i:1536983892;s:90:"C:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1536983892;s:95:"C:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1536983892;}*/ ?>
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
                <h3>基本设置</h3>
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

    <form method="post" name="settingForm" id="settingForm">
        <table class="ds-default-table">
            <tbody>
             <!-- 开启商品发布审核 -->
             <tr class="noborder">
                <td colspan="2" class="required"><label><?php echo \think\Lang::get('is_goods_verify'); ?>:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform onoff">
                    <label for="goods_verify_1" class="cb-enable <?php if($list_setting['goods_verify'] == '1'): ?>selected<?php endif; ?>" title="<?php echo \think\Lang::get('open'); ?>"><span><?php echo \think\Lang::get('open'); ?></span></label>
                    <label for="goods_verify_0" class="cb-disable <?php if($list_setting['goods_verify'] == '0'): ?>selected<?php endif; ?>" title="<?php echo \think\Lang::get('close'); ?>"><span><?php echo \think\Lang::get('close'); ?></span></label>
                    <input type="radio" id="goods_verify_1" name="goods_verify" value="1"  <?php if($list_setting['goods_verify']=='1'): ?>checked=checked<?php endif; ?>>>
                    <input type="radio" id="goods_verify_0" name="goods_verify" value="0" <?php if($list_setting['goods_verify']=='0'): ?>checked=checked<?php endif; ?>>
                <td class="vatop tips"><?php echo \think\Lang::get('goods_verify_notice'); ?></td>
            </tr>
            <!-- 开启闲置市场 -->
            <tr class="noborder">
                <td colspan="2" class="required"><label><?php echo \think\Lang::get('flea_isuse'); ?>:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform onoff">
                    <label for="flea_isuse_1" class="cb-enable <?php if($list_setting['flea_isuse'] == '1'): ?>selected<?php endif; ?>" title="<?php echo \think\Lang::get('open'); ?>"><span><?php echo \think\Lang::get('open'); ?></span></label>
                    <label for="flea_isuse_0" class="cb-disable <?php if($list_setting['flea_isuse'] == '0'): ?>selected<?php endif; ?>" title="<?php echo \think\Lang::get('close'); ?>"><span><?php echo \think\Lang::get('close'); ?></span></label>
                    <input type="radio" id="flea_isuse_1" name="flea_isuse" value="1"  <?php if($list_setting['flea_isuse']=='1'): ?>checked=checked<?php endif; ?>>
                    <input type="radio" id="flea_isuse_0" name="flea_isuse" value="0" <?php if($list_setting['flea_isuse']=='0'): ?>checked=checked<?php endif; ?>>
                </td>
                <td class="vatop tips"><?php echo \think\Lang::get('flea_isuse_notice'); ?></td>
            </tr>
            <!-- 促销开启 -->
            <tr class="noborder">
                <td colspan="2" class="required"><label><?php echo \think\Lang::get('promotion_allow'); ?>:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform onoff">
                    <label for="promotion_allow_1" class="cb-enable <?php if($list_setting['promotion_allow'] == '1'): ?>selected<?php endif; ?>" title="<?php echo \think\Lang::get('open'); ?>"><span><?php echo \think\Lang::get('open'); ?></span></label>
                    <label for="promotion_allow_0" class="cb-disable <?php if($list_setting['promotion_allow'] == '0'): ?>selected<?php endif; ?>" title="<?php echo \think\Lang::get('close'); ?>"><span><?php echo \think\Lang::get('close'); ?></span></label>
                    <input type="radio" id="promotion_allow_1" name="promotion_allow" value="1" <?php if($list_setting['promotion_allow']== '1'): ?>checked=checked<?php endif; ?>>
                    <input type="radio" id="promotion_allow_0" name="promotion_allow" value="0" <?php if($list_setting['promotion_allow']== '0'): ?>checked=checked<?php endif; ?>>
                </td>
                <td class="vatop tips"><?php echo \think\Lang::get('promotion_notice'); ?></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><?php echo \think\Lang::get('groupbuy_allow'); ?>:</td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform onoff">
                    <label for="groupbuy_allow_1" class="cb-enable <?php if($list_setting['groupbuy_allow'] == '1'): ?>selected<?php endif; ?>" title="<?php echo \think\Lang::get('open'); ?>"><span><?php echo \think\Lang::get('open'); ?></span></label>
                    <label for="groupbuy_allow_0" class="cb-disable <?php if($list_setting['groupbuy_allow'] == '0'): ?>selected<?php endif; ?>" title="<?php echo \think\Lang::get('close'); ?>"><span><?php echo \think\Lang::get('close'); ?></span></label>
                    <input id="groupbuy_allow_1" name="groupbuy_allow" <?php if($list_setting['groupbuy_allow']== '1'): ?> checked=checked<?php endif; ?> value="1" type="radio">
                    <input id="groupbuy_allow_0" name="groupbuy_allow" <?php if($list_setting['groupbuy_allow']== '0'): ?> checked=checked<?php endif; ?> value="0" type="radio">
                </td>
                <td class="vatop tips"><?php echo \think\Lang::get('groupbuy_isuse_notice'); ?></td>
            </tr>

            <tr class="noborder">
                <td colspan="2" class="required"><label><?php echo \think\Lang::get('points_isuse'); ?>:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform onoff">
                    <label for="points_isuse_1" class="cb-enable <?php if($list_setting['points_isuse'] == '1'): ?>selected<?php endif; ?>" title="<?php echo \think\Lang::get('gold_isuse_open'); ?>"><span><?php echo \think\Lang::get('points_isuse_open'); ?></span></label>
                    <label for="points_isuse_0" class="cb-disable <?php if($list_setting['points_isuse'] == '0'): ?>selected<?php endif; ?>" title="<?php echo \think\Lang::get('gold_isuse_close'); ?>"><span><?php echo \think\Lang::get('points_isuse_close'); ?></span></label>
                    <input type="radio" id="points_isuse_1" name="points_isuse" value="1" <?php if($list_setting['points_isuse']=='1'): ?>checked=checked<?php endif; ?>>
                    <input type="radio" id="points_isuse_0" name="points_isuse" value="0" <?php if($list_setting['points_isuse']=='0'): ?> checked=checked <?php endif; ?>>
                </td>
                <td class="vatop tips"><?php echo \think\Lang::get('points_isuse_notice'); ?></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><?php echo \think\Lang::get('open_pointshop_isuse'); ?>:</td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform onoff">
                    <label for="pointshop_isuse_1" class="cb-enable <?php if($list_setting['pointshop_isuse'] == '1'): ?>selected<?php endif; ?>" title="<?php echo \think\Lang::get('ds_open'); ?>"><span><?php echo \think\Lang::get('ds_open'); ?></span></label>
                    <label for="pointshop_isuse_0" class="cb-disable <?php if($list_setting['pointshop_isuse'] == '0'): ?>selected<?php endif; ?>" title="<?php echo \think\Lang::get('ds_close'); ?>"><span><?php echo \think\Lang::get('ds_close'); ?></span></label>
                    <input id="pointshop_isuse_1" name="pointshop_isuse" <?php if($list_setting['pointshop_isuse']== '1'): ?> checked=checked <?php endif; ?> value="1" type="radio">
                    <input id="pointshop_isuse_0" name="pointshop_isuse" <?php if($list_setting['pointshop_isuse']== '0'): ?> checked=checked <?php endif; ?> value="0" type="radio">
                </td>
                <td class="vatop tips"><?php echo \think\Lang::get('open_pointshop_isuse_notice'); ?></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><?php echo \think\Lang::get('open_pointprod_isuse'); ?>:</td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform onoff">
                    <label for="pointprod_isuse_1" class="cb-enable <?php if($list_setting['pointprod_isuse'] == '1'): ?>selected<?php endif; ?>" title="<?php echo \think\Lang::get('open'); ?>"><span><?php echo \think\Lang::get('open'); ?></span></label>
                    <label for="pointprod_isuse_0" class="cb-disable <?php if($list_setting['pointprod_isuse'] == '0'): ?>selected<?php endif; ?>" title="<?php echo \think\Lang::get('close'); ?>"><span><?php echo \think\Lang::get('close'); ?></span></label>
                    <input id="pointprod_isuse_1" name="pointprod_isuse" <?php if($list_setting['pointprod_isuse']== '1'): ?> checked=checked <?php endif; ?> value="1" type="radio">
                    <input id="pointprod_isuse_0" name="pointprod_isuse" <?php if($list_setting['pointprod_isuse']== '0'): ?> checked=checked <?php endif; ?>} value="0" type="radio">
                </td>
                <td class="vatop tips"><?php echo \think\Lang::get('open_pointprod_isuse_notice'); ?></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><?php echo \think\Lang::get('voucher_allow'); ?>:</td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform onoff">
                    <label for="voucher_allow_1" class="cb-enable <?php if($list_setting['voucher_allow'] == '1'): ?>selected<?php endif; ?>" title="<?php echo \think\Lang::get('open'); ?>"><span><?php echo \think\Lang::get('open'); ?></span></label>
                    <label for="voucher_allow_0" class="cb-disable <?php if($list_setting['voucher_allow'] == '0'): ?>selected<?php endif; ?>" title="<?php echo \think\Lang::get('close'); ?>"><span><?php echo \think\Lang::get('close'); ?></span></label>
                    <input id="voucher_allow_1" name="voucher_allow" <?php echo $list_setting['voucher_allow']== '1'; ?> checked=checked {/if} value="1" type="radio">
                    <input id="voucher_allow_0" name="voucher_allow" <?php echo $list_setting['voucher_allow']== '0'; ?> checked=checked {/if} value="0" type="radio">
                </td>
                <td class="vatop tips"><?php echo \think\Lang::get('voucher_allow_notice'); ?></td>
            </tr>
            </tbody>
        </table>

        <table class="ds-default-table">
            <thead>
            <tr class="space">
                <th colspan="16"><?php echo \think\Lang::get('points_ruletip'); ?>:</th>
            </tr>
            <tr class="thead">
                <th><?php echo \think\Lang::get('points_item'); ?></th>
                <th><?php echo \think\Lang::get('points_number'); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr class="hover">
                <td class="w200"><?php echo \think\Lang::get('points_number_reg'); ?></td>
                <td><input id="points_reg" name="points_reg" value="<?php echo $list_setting['points_reg']; ?>" class="txt" type="text" style="width:60px;"></td>
            </tr>
            <tr class="hover">
                <td><?php echo \think\Lang::get('points_number_login'); ?></td>
                <td><input id="points_login" name="points_login" value="<?php echo $list_setting['points_login']; ?>" class="txt" type="text" style="width:60px;"></td>
            </tr>
            <tr class="hover">
                <td><?php echo \think\Lang::get('points_number_comments'); ?></td>
                <td><input id="points_comments" name="points_comments" value="<?php echo $list_setting['points_comments']; ?>" class="txt" type="text" style="width:60px;"></td>
            </tr>
            <tr class="hover">
                <td>签到积分</td>
                <td><input id="points_signin" name="points_signin" value="<?php echo $list_setting['points_signin']; ?>" class="txt" type="text" style="width:60px;"></td>
            </tr>
            <tr class="hover">
                <td>邀请注册</td>
                <td><input id="points_invite" name="points_invite" value="<?php echo $list_setting['points_invite']; ?>" class="txt" type="text" style="width:60px;">邀请非会员注册时给邀请人的积分数
                </td>
            </tr>
            <tr class="hover">
                <td>返利比例</td>
                <td><input id="points_rebate" name="points_rebate" value="<?php echo $list_setting['points_rebate']; ?>" class="txt" type="text" style="width:35px;">% &nbsp;&nbsp;&nbsp;被邀请会员购买商品时给邀请人返的积分数(例如设为5%，被邀请人购买100元商品，返给邀请人5积分)
                </td>
            </tr>
            </tbody>
        </table>
        <table class="ds-default-table">
            <thead>
            <tr class="thead">
                <th colspan="2"><?php echo \think\Lang::get('points_number_order'); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr class="hover">
                <td class="w200"><?php echo \think\Lang::get('points_number_orderrate'); ?></td>
                <td><input id="points_orderrate" name="points_orderrate" value="<?php echo $list_setting['points_orderrate']; ?>" class="txt" type="text" style="width:60px;">
                    <?php echo \think\Lang::get('points_number_orderrate_tip'); ?>
                </td>
            </tr>
            <tr class="hover">
                <td><?php echo \think\Lang::get('points_number_ordermax'); ?></td>
                <td><input id="points_ordermax" name="points_ordermax" value="<?php echo $list_setting['points_ordermax']; ?>" class="txt" type="text" style="width:60px;">
                    <?php echo \think\Lang::get('points_number_ordermax_tip'); ?>
                </td>
            </tr>


            </tbody>
            <tfoot>
            <tr class="tfoot">
                <td colspan="2"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo \think\Lang::get('ds_submit'); ?></span></a></td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>

<script>

    $(function(){$("#submitBtn").click(function(){
        if($("#settingForm").valid()){
            $("#settingForm").submit();
        }
    });
    });
    //
    $(document).ready(function(){
        $("#settingForm").validate({
            errorPlacement: function(error, element){
                error.appendTo(element.parent().parent().prev().find('td:first'));
            },
            rules : {
            },
            messages : {
            }
        });
    });
</script>