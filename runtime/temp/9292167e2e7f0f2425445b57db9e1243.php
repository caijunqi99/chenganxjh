<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:82:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\db\db.html";i:1514528339;s:90:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\header.html";i:1514528328;s:95:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\public\admin_items.html";i:1514541233;}*/ ?>
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
                <h3>数据备份</h3>
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
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom">
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示" class="arrow"></span>
        </div>
        <ul>
            <li>数据备份功能根据你的选择备份全部数据或指定数据，导出的数据文件可用“数据恢复”功能或 phpMyAdmin 导入</li>
            <li>建议定期备份数据库</li>
        </ul>
    </div>

    
    <div class="mDiv">
        <div class="ftitle">
            <h3>数据库表列表</h3>
            <h5>(共<?php echo $tableNum; ?>张记录，共计<?php echo $total; ?>)</h5>
        </div>
        <a id="export" class="btn">数据备份</a>
    </div>

    <table class="ds-default-table">
        <thead>
            <tr>
                <th><input type="checkbox" onclick="javascript:$('input[name*=tables]').prop('checked', this.checked);"></th>
                <th>数据库表</th>
                <th>记录条数</th>
                <th>占用空间</th>
                <th>编码</th>
                <th>创建时间</th>
                <th>备份状态</th>
                <th>操作</th>
            </tr>
        </thead>
        
        <form  method="post" id="export-form" action="<?php echo url('Admin/Db/export'); ?>">
            <tbody>
                <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $k=>$db): ?>
                <tr data-id="<?php echo $db['Name']; ?>">
                    <td class="sign">
                        <div style="width: 24px;"><input type="checkbox" name="tables[]" value="<?php echo $db['Name']; ?>"></div>
                    </td>
                    <td align="left" class="">
                        <div style="text-align: left; width: 200px;"><?php echo $db['Name']; ?></div>
                    </td>
                    <td align="center" class="">
                        <div style="text-align: center; width: 50px;"><?php echo $db['Rows']; ?></div>
                    </td>
                    <td align="center" class="">
                        <div style="text-align: center; width: 50px;"><?php echo format_bytes($db['Data_length']); ?></div>
                    </td>
                    <td align="center" class="">
                        <div style="text-align: center; width: 100px;"><?php echo $db['Collation']; ?></div>
                    </td>
                    <td align="center" class="">
                        <div style="text-align: center; width: 120px;"><?php echo $db['Create_time']; ?></div>
                    </td>
                    <td align="center" class="">
                        <div style="text-align: center; width: 200px;" class="info">未备份</div>
                    </td>
                    <td align="center" class="handle">
                        <div style="text-align: center; width: 170px; max-width:170px;">
                            <a href="<?php echo url('Admin/Db/optimize',array('tablename'=>$db['Name'])); ?>"><i class="fa fa-magic"></i>优化</a> | 
                            <a href="<?php echo url('Admin/Db/repair',array('tablename'=>$db['Name'])); ?>"><i class="fa fa-wrench"></i>修复</a>
                        </div>
                    </td>
                    <td align="" class="" style="width: 100%;">
                        <div>&nbsp;</div>
                    </td>
                </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </form>
    </table>
</div>
<script>
                    (function($) {
                        var $form = $("#export-form"), $export = $("#export"), tables;
                        $export.click(function() {
                            if ($("input[name^='tables']:checked").length == 0) {
                                alert('请选中要备份的数据表');
                                return false;
                            }
                            $export.addClass("disabled");
                            $export.html("正在发送备份请求...");
                            $.post(
                                    $form.attr("action"),
                                    $form.serialize(),
                                    function(data) {
                                        if (data.status) {
                                            tables = data.tables;
                                            $export.html(data.info + "开始备份，请不要关闭本页面！");
                                            backup(data.tab);
                                            window.onbeforeunload = function() {
                                                return "正在备份数据库，请不要关闭！"
                                            }
                                        } else {
                                            alert(data.info);
                                            $export.removeClass("disabled");
                                            $export.html("立即备份");
                                        }
                                    },
                                    "json"
                                    );
                            return false;
                        });

                        function backup(tab, status) {
                            status && showmsg(tab.id, "开始备份...(0%)");
                            $.get($form.attr("action"), tab, function(data) {
                                if (data.status) {
                                    showmsg(tab.id, data.info);
                                    if (!$.isPlainObject(data.tab)) {
                                        $export.removeClass("disabled");
                                        $export.html("备份完成，点击重新备份");
                                        window.onbeforeunload = function() {
                                            return null
                                        }
                                        return;
                                    }
                                    backup(data.tab, tab.id != data.tab.id);
                                } else {
                                    $export.removeClass("disabled");
                                    $export.html("立即备份");
                                }
                            }, "json");

                        }

                        function showmsg(id, msg) {
                            $("input[value=" + tables[id] + "]").closest("tr").find(".info").html(msg);
//                            $("input[value=" + tables[id] + "]").closest("tr").hide(3000);
                        }
                    })(jQuery);
</script>
</body>
</html>