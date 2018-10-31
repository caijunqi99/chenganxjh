<?php
/**
 * 售后统计分析
 */

namespace app\school\controller;

use think\Lang;
use think\Loader;

class Stataftersale extends AdminControl
{
    private $search_arr;//处理后的参数

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Lang::load(APP_PATH.'school/lang/zh-cn/stat.lang.php');
        Loader::import('mall.statistics');
        Loader::import('mall.datehelper');
        $model = Model('stat');
        //存储参数
        $this->search_arr = $_REQUEST;
        //处理搜索时间
        if (in_array(request()->action(),array('refund'))){
            $this->search_arr = $model->dealwithSearchTime($this->search_arr);
            //获得系统年份
            $year_arr = getSystemYearArr();
            //获得系统月份
            $month_arr = getSystemMonthArr();
            //获得本月的周时间段
            $week_arr = getMonthWeekArr($this->search_arr['week']['current_year'], $this->search_arr['week']['current_month']);
            $this->assign('year_arr', $year_arr);
            $this->assign('month_arr', $month_arr);
            $this->assign('week_arr', $week_arr);
        }
        $this->assign('search_arr', $this->search_arr);
    }

    /**
     * 退款统计
     */
    public function refund(){
        $where = array();
        if(!isset($this->search_arr['search_type'])){
            $this->search_arr['search_type'] = 'day';
        }
        $model = Model('stat');

        //获得搜索的开始时间和结束时间
        $searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);

        $field = ' SUM(refund_amount) as amount ';
        if($this->search_arr['search_type'] == 'day'){
            //构造横轴数据
            for($i=0; $i<24; $i++){
                $stat_arr['xAxis']['categories'][] = "$i";
                $statlist[$i] = 0;
            }
            $field .= ' ,HOUR(FROM_UNIXTIME(add_time)) as timeval ';
        }
        if($this->search_arr['search_type'] == 'week'){
            //构造横轴数据
            for($i=1; $i<=7; $i++){
                $tmp_weekarr = getSystemWeekArr();
                //横轴
                $stat_arr['xAxis']['categories'][] = $tmp_weekarr[$i];
                unset($tmp_weekarr);
                $statlist[$i] = 0;
            }
            $field .= ' ,WEEKDAY(FROM_UNIXTIME(add_time))+1 as timeval ';
        }
        if($this->search_arr['search_type'] == 'month'){
            //计算横轴的最大量（由于每个月的天数不同）
            $dayofmonth = date('t',$searchtime_arr[0]);
            //构造横轴数据
            for($i=1; $i<=$dayofmonth; $i++){
                //横轴
                $stat_arr['xAxis']['categories'][] = $i;
                $statlist[$i] = 0;
            }
            $field .= ' ,day(FROM_UNIXTIME(add_time)) as timeval ';
        }
        $where = array();
        $where['add_time'] = array('between',$searchtime_arr);
        $statlist_tmp = $model->statByRefundreturn($where, $field, 0, 0);
        if ($statlist_tmp){
            foreach((array)$statlist_tmp as $k=>$v){
                $statlist[$v['timeval']] = floatval($v['amount']);
            }
        }
        //得到统计图数据
        $stat_arr['legend']['enabled'] = false;
        $stat_arr['series'][0]['name'] = '退款金额';
        $stat_arr['series'][0]['data'] = array_values($statlist);
        $stat_arr['title'] = '退款金额统计';
        $stat_arr['yAxis'] = '金额';
        $stat_json = getStatData_LineLabels($stat_arr);
        $this->assign('stat_json',$stat_json);
        $this->assign('searchtime',implode('|',$searchtime_arr));
        $this->setAdminCurItem('refund');
        return $this->fetch('aftersale_refund');
    }
    /**
     * 退款统计
     */
    public function refundlist(){
        $model = Model('refundreturn');
        $refundstate_arr = $this->getRefundStateArray();
        $where = array();
        $statlist= array();
        $searchtime_arr_tmp = explode('|',$this->search_arr['t']);
        foreach ((array)$searchtime_arr_tmp as $k=>$v){
            $searchtime_arr[] = intval($v);
        }
        $where['add_time'] = array('between',$searchtime_arr);
        if (isset($this->search_arr['exporttype']) == 'excel'){
            $refundlist_tmp = $model->getRefundReturnList($where, 0);
        } else {
            $refundlist_tmp = $model->getRefundReturnList($where, 10);
        }
        $statheader = array();
        $statheader[] = array('text'=>'订单编号','key'=>'order_sn');
        $statheader[] = array('text'=>'退款编号','key'=>'refund_sn');
        $statheader[] = array('text'=>'店铺名','key'=>'store_name','class'=>'alignleft');
        $statheader[] = array('text'=>'商品名称','key'=>'goods_name','class'=>'alignleft');
        $statheader[] = array('text'=>'买家会员名','key'=>'buyer_name');
        $statheader[] = array('text'=>'申请时间','key'=>'add_time');
        $statheader[] = array('text'=>'退款金额','key'=>'refund_amount');
        $statheader[] = array('text'=>'卖家审核','key'=>'seller_state');
        $statheader[] = array('text'=>'平台确认','key'=>'refund_state');
        foreach ((array)$refundlist_tmp as $k=>$v){
            $tmp = $v;
            foreach ((array)$statheader as $h_k=>$h_v){
                $tmp[$h_v['key']] = $v[$h_v['key']];
                if ($h_v['key'] == 'add_time'){
                    $tmp[$h_v['key']] = @date('Y-m-d',$v['add_time']);
                }
                if ($h_v['key'] == 'refund_state'){
                    $tmp[$h_v['key']] = $v['seller_state']==2 ? $refundstate_arr['admin'][$v['refund_state']]:'无';
                }
                if ($h_v['key'] == 'seller_state'){
                    $tmp[$h_v['key']] = $refundstate_arr['seller'][$v['seller_state']];
                }
                if ($h_v['key'] == 'goods_name'){
                    $tmp[$h_v['key']] = '<a href="'.url('goods/index', array('goods_id' => $v['goods_id'])).'" target="_blank">'.$v['goods_name'].'</a>';
                }
            }
            $statlist[] = $tmp;
        }
        if (isset($this->search_arr['exporttype']) == 'excel'){
            //导出Excel
            $excel_obj = new \excel\Excel();
            $excel_data = array();
            //设置样式
            $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
            //header
            foreach ((array)$statheader as $k=>$v){
                $excel_data[0][] = array('styleid'=>'s_title','data'=>$v['text']);
            }
            //data
            foreach ((array)$statlist as $k=>$v){
                foreach ((array)$statheader as $h_k=>$h_v){
                    $excel_data[$k+1][] = array('data'=>$v[$h_v['key']]);
                }
            }
            $excel_data = $excel_obj->charset($excel_data,CHARSET);
            $excel_obj->addArray($excel_data);
            $excel_obj->addWorksheet($excel_obj->charset('退款记录',CHARSET));
            $excel_obj->generateXML($excel_obj->charset('退款记录',CHARSET).date('Y-m-d-H',time()));
            exit();
        } else {
            $this->assign('statheader',$statheader);
            $this->assign('statlist',$statlist);
            $this->assign('show_page',$model->page_info->render());
            $this->assign('searchtime',input('param.t'));
            $this->assign('actionurl',url('Stataftersale/refundlist',['t'=>$this->search_arr['t']]));
            echo $this->fetch('stat_listandorder');
        }
    }
    /**
     * 店铺动态评分统计
     */
    public function evalstore(){
        //店铺分类
        $this->assign('class_list', rkcache('store_class', true));

        $model = Model('stat');
        $where = array();
        $statlist=array();
        if(intval(input('param.store_class')) > 0){
            $where['sc_id'] = intval($_GET['store_class']);
        }
        if (isset($this->search_arr['storename'])){
            $where['seval_storename'] = array('like',"%".trim($this->search_arr['storename'])."%");
        }
        $field = ' seval_storeid, seval_storename';
        $field .= ' ,(SUM(seval_desccredit)/COUNT(*)) as avgdesccredit';
        $field .= ' ,(SUM(seval_servicecredit)/COUNT(*)) as avgservicecredit';
        $field .= ' ,(SUM(seval_deliverycredit)/COUNT(*)) as avgdeliverycredit';

        $orderby_arr = array('avgdesccredit asc','avgdesccredit desc','avgservicecredit asc','avgservicecredit desc','avgdeliverycredit asc','avgdeliverycredit desc');
        if (!isset($this->search_arr['orderby'])||!in_array(trim($this->search_arr['orderby']),$orderby_arr)){
            $this->search_arr['orderby'] = 'avgdesccredit desc';
        }
        $orderby = trim($this->search_arr['orderby']).',seval_storeid';
        //查询评论的店铺总数
        $count_arr = $model->statByStoreAndEvaluatestore($where, 'count(DISTINCT evaluatestore.seval_storeid) as countnum');
        $countnum = intval($count_arr[0]['countnum']);
        if (isset($this->search_arr['exporttype']) == 'excel'){
            $statlist_tmp = $model->statByStoreAndEvaluatestore($where, $field, 0, 0, $orderby, 'seval_storeid');
        } else {
            $statlist_tmp = $model->statByStoreAndEvaluatestore($where, $field, array(10,$countnum), 0, $orderby, 'seval_storeid');
        }
        foreach((array)$statlist_tmp as $k=>$v){
            $tmp = $v;
            $tmp['avgdesccredit'] = round($v['avgdesccredit'],2);
            $tmp['avgservicecredit'] = round($v['avgservicecredit'],2);
            $tmp['avgdeliverycredit'] = round($v['avgdeliverycredit'],2);
            $statlist[] = $tmp;
        }
        //导出Excel
        if (isset($this->search_arr['exporttype']) == 'excel'){
            //导出Excel
            $excel_obj = new \excel\Excel();
            $excel_data = array();
            //设置样式
            $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
            //header
            $excel_data[0][] = array('styleid'=>'s_title','data'=>'店铺名称');
            $excel_data[0][] = array('styleid'=>'s_title','data'=>'描述相符度');
            $excel_data[0][] = array('styleid'=>'s_title','data'=>'服务态度');
            $excel_data[0][] = array('styleid'=>'s_title','data'=>'发货速度');
            //data
            foreach ((array)$statlist as $k=>$v){
                $excel_data[$k+1][] = array('data'=>$v['seval_storename']);
                $excel_data[$k+1][] = array('data'=>$v['avgdesccredit']);
                $excel_data[$k+1][] = array('data'=>$v['avgservicecredit']);
                $excel_data[$k+1][] = array('data'=>$v['avgdeliverycredit']);
            }
            $excel_data = $excel_obj->charset($excel_data,CHARSET);
            $excel_obj->addArray($excel_data);
            $excel_obj->addWorksheet($excel_obj->charset('店铺动态评分统计',CHARSET));
            $excel_obj->generateXML($excel_obj->charset('店铺动态评分统计',CHARSET).date('Y-m-d-H',time()));
            exit();
        }
        $this->assign('statlist',$statlist);
        $this->assign('orderby',$this->search_arr['orderby']);
        $this->assign('show_page',$model->page_info->render());
        $this->setAdminCurItem('evalstore');
        return $this->fetch('aftersale_evalstore');
    }
    function getRefundStateArray($type = 'all') {
        $state_array = array(
            '1' => lang('refund_state_confirm'),
            '2' => lang('refund_state_yes'),
            '3' => lang('refund_state_no')
        ); //卖家处理状态:1为待审核,2为同意,3为不同意
        $this->assign('state_array', $state_array);

        $admin_array = array(
            '1' => '处理中',
            '2' => '待处理',
            '3' => '已完成'
        ); //确认状态:1为买家或卖家处理中,2为待平台管理员处理,3为退款退货已完成
        $this->assign('admin_array', $admin_array);

        $state_data = array(
            'seller' => $state_array,
            'admin' => $admin_array
        );
        if ($type == 'all') {
            return $state_data; //返回所有
        }
        return $state_data[$type];
    }

    protected function getAdminItemList()
    {
       $menu_array=array(
           array('name'=>'refund','text'=>lang('stat_refund'),'url'=>url('Stataftersale/refund')),
           array('name'=>'evalstore','text'=>lang('stat_evalstore'),'url'=>url('Stataftersale/evalstore')),
       );
       return $menu_array;
    }
}