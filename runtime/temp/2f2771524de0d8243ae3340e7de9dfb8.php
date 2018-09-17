<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:100:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\statindustry\linelabels.html";i:1514528331;}*/ ?>
<div id="container_<?php echo $stattype; ?>"></div>
<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/highcharts/highcharts.js"></script>
<script>
$(function () {
    $('#container_<?php echo $stattype; ?>').highcharts(<?php echo $stat_json; ?>);
});
</script>