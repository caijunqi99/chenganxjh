<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:108:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/admin\view\stataftersale\stat_listandorder.html";i:1514528337;}*/ ?>
<div class="w100pre close_float" style="text-align:right;">
  	<input type="hidden" id="export_type" name="export_type" data-param='{"url":"<?php echo $actionurl;?>?t=<?php echo $searchtime; ?>&exporttype=excel"}' value="excel"/>
  	<a class="btns" href="javascript:void(0);" id="export_btn"><span>导出Excel</span></a>
  </div>

  <table class="ds-default-table">
    <thead>
      <tr class="thead sortbar-array">
        <?php if(is_array($statheader) || $statheader instanceof \think\Collection || $statheader instanceof \think\Paginator): $i = 0; $__LIST__ = $statheader;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;if(isset($v['isorder'])): if($v['isorder'] == 1): ?>
        <th class="align-center">
            <a nc_type="orderitem" href="" class="selected"><?php echo $v['text']; ?><i></i></a>
        </th>
       <?php else: ?>
        <th class="align-center"><?php echo $v['text']; ?></th>
        <?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
    </tr>
    </thead>
    <tbody id="datatable">
    <?php if(!(empty($statlist) || (($statlist instanceof \think\Collection || $statlist instanceof \think\Paginator ) && $statlist->isEmpty()))): if(is_array($statlist) || $statlist instanceof \think\Collection || $statlist instanceof \think\Paginator): $i = 0; $__LIST__ = $statlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
          <tr class="hover">
            <?php if(is_array($statheader) || $statheader instanceof \think\Collection || $statheader instanceof \think\Paginator): $i = 0; $__LIST__ = $statheader;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$h_v): $mod = ($i % 2 );++$i;?>
          	<td class="<?php echo !empty($h_v['class'])?$h_v['class'] : 'align-center'; ?>"><?php echo $v[$h_v['key']]; ?></td>
            <?php endforeach; endif; else: echo "" ;endif; ?>
          </tr>
   <?php endforeach; endif; else: echo "" ;endif; else: ?>
    <tr class="no_data">
        	<td colspan="11"><?php echo lang('no_record'); ?></td>
        </tr>
   <?php endif; ?>
    </tbody>
    <?php if(!(empty($statlist) || (($statlist instanceof \think\Collection || $statlist instanceof \think\Paginator ) && $statlist->isEmpty()))): ?>
    <tfoot>
      <tr class="tfoot">
        <td colspan="15" id="dataFuncs">
          <div class="pagination"><?php echo $show_page; ?></div>
        </td>
      </tr>
    </tfoot>
    <?php endif; ?>
  </table>
<script>

    jQuery.browser={};(function(){jQuery.browser.msie=false; jQuery.browser.version=0;if(navigator.userAgent.match(/MSIE ([0-9]+)./)){ jQuery.browser.msie=true;jQuery.browser.version=RegExp.$1;}})();
</script>
  <script type="text/javascript" src="<?php echo config('url_domain_root'); ?>static/plugins/jquery.poshytip.min.js"></script>
<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/jquery.ajaxContent.pack.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    //Ajax提示
    $('.tip').poshytip({
        className: 'tip-yellowsimple',
        showTimeout: 1,
        alignTo: 'target',
        alignX: 'center',
        alignY: 'top',
        offsetY: 5,
        allowTipHover: false
    });

    $('#statlist').find('.demo').ajaxContent({
        event:'click', //mouseover
        loaderType:"img",
        loadingMsg:"<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/treetable/transparent.gif",
        target:'#statlist'
    });
    $("[nc_type='orderitem']").ajaxContent({
        event:'click',
        loaderType:"img",
        loadingMsg:"<?php echo \think\Config::get('url_domain_root'); ?>static/admin/images/treetable/transparent.gif",
        target:'#statlist'
    });

    //导出图表
    $("#export_btn").click(function(){
        var item = $("#export_type");
        var type = $(item).val();
        if(type == 'excel'){
            download_excel(item);
        }
    });
});
</script>