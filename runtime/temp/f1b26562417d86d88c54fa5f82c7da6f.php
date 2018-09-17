<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:106:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\statmarketing\stat_linelabels.html";i:1514528337;}*/ ?>
<div id="container_<?php echo $stattype; ?>"></div>
<script>
$(function () {
    $('#container_<?php echo $stattype; ?>').highcharts(<?php echo $stat_json; ?>);
});
</script>