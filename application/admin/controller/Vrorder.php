<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Vrorder extends AdminControl {

    /**
     * 每次导出订单数量
     * @var int
     */
    const EXPORT_SIZE = 1000;

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/vrorder.lang.php');
    }

    public function index() {
        $model_vr_order = Model('vrorder');
        $condition = array();

        $order_sn = input('get.order_sn');
        if ($order_sn) {
            $condition['order_sn'] = $order_sn;
        }
        $store_name = input('get.store_name');
        if ($store_name) {
            $condition['store_name'] = $store_name;
        }
        $order_state = input('get.order_state');
        if (!empty($order_state)) {
            $condition['order_state'] = intval($order_state);
        }
        $payment_code = input('get.payment_code');
        if ($payment_code) {
            $condition['payment_code'] = $payment_code;
        }
        $buyer_name = input('get.buyer_name');
        if ($buyer_name) {
            $condition['buyer_name'] = $buyer_name;
        }
        $query_start_time = input('get.query_start_time');
        $query_end_time = input('get.query_end_time');
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $query_start_time);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $query_end_time);
        $start_unixtime = $if_start_time ? strtotime($query_start_time) : null;
        $end_unixtime = $if_end_time ? strtotime($query_end_time) : null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('between', array($start_unixtime, $end_unixtime));
        }
        $order_list = $model_vr_order->getOrderList($condition, 30);

        foreach ($order_list as $k => $order_info) {
            //显示取消订单
            $order_list[$k]['if_cancel'] = $model_vr_order->getOrderOperateState('system_cancel', $order_info);
            //显示收到货款
            $order_list[$k]['if_system_receive_pay'] = $model_vr_order->getOrderOperateState('system_receive_pay', $order_info);
        }

        //显示支付接口列表(搜索)
        $payment_list = Model('payment')->getPaymentOpenList();
        $this->assign('payment_list', $payment_list);

        $this->assign('order_list', $order_list);
        $this->assign('show_page', $model_vr_order->page_info->render());
        $this->setAdminCurItem('index');
        return $this->fetch('vr_order_index');
    }

    /**
     * 平台订单状态操作
     *
     */
    public function change_state() {
        $model_vr_order = Model('vrorder');
        $condition = array();
        
        $condition['order_id'] = intval(input('param.order_id'));
        $order_info = $model_vr_order->getOrderInfo($condition);
        $state_type = input('param.state_type');
        if ($state_type == 'cancel') {
            $result = $this->_order_cancel($order_info);
        } elseif ($state_type == 'receive_pay') {
            $result = $this->_order_receive_pay($order_info, $_POST);
        }
        if (!isset($result['code'])) {
            $this->error('操作出错','index');
        } else {
            $this->success($result['msg'],'index');
        }
    }

    /**
     * 系统取消订单
     * @param unknown $order_info
     */
    private function _order_cancel($order_info) {
        $model_vr_order = Model('vrorder');
        $logic_vr_order = Logic('vr_order');
        $if_allow = $model_vr_order->getOrderOperateState('system_cancel', $order_info);
        if (!$if_allow) {
            return ds_callback(false, '无权操作');
        }
        $this->log('关闭了虚拟订单,' . lang('order_number') . ':' . $order_info['order_sn'], 1);
        return $logic_vr_order->changeOrderStateCancel($order_info, 'store', '管理员关闭虚拟订单');
    }

    /**
     * 系统收到货款
     * @param unknown $order_info
     * @throws Exception
     */
    private function _order_receive_pay($order_info, $post) {
        $model_vr_order = Model('vrorder');
        $logic_vr_order = model('vrorder','logic');
        $if_allow = $model_vr_order->getOrderOperateState('system_receive_pay', $order_info);
        if (!$if_allow) {
            return ds_callback(false, '无权操作');
        }

        if (!request()->post()) {
            $this->assign('order_info', $order_info);
            //显示支付接口
            $payment_list = Model('payment')->getPaymentOpenList();
            //去掉预存款和货到付款
            foreach ($payment_list as $key => $value) {
                if ($value['payment_code'] == 'predeposit' || $value['payment_code'] == 'offline') {
                    unset($payment_list[$key]);
                }
            }
            $this->assign('payment_list', $payment_list);
            $this->setAdminCurItem('submit');
            echo $this->fetch('receive_pay');
            exit();
        } else {
            $this->log('将虚拟订单改为已收款状态,' . lang('order_number') . ':' . $order_info['order_sn'], 1);
            return $logic_vr_order->changeOrderStatePay($order_info, 'system', $post);
        }
    }

    /**
     * 查看订单
     *
     */
    public function show_order() {
        $order_id = intval(input('param.order_id'));
        if ($order_id <= 0) {
            $this->error(lang('miss_order_number'));
        }
        $model_vr_order = Model('vrorder');
        $order_info = $model_vr_order->getOrderInfo(array('order_id' => $order_id));
        if (empty($order_info)) {
            $this->error('订单不存在');
        }

        //取兑换码列表
        $vr_code_list = $model_vr_order->getOrderCodeList(array('order_id' => $order_info['order_id']));
        $order_info['extend_vr_order_code'] = $vr_code_list;

        //显示取消订单
        $order_info['if_cancel'] = $model_vr_order->getOrderOperateState('buyer_cancel', $order_info);

        //显示订单进行步骤
        $order_info['step_list'] = $model_vr_order->getOrderStep($order_info);

        //显示系统自动取消订单日期
        if ($order_info['order_state'] == ORDER_STATE_NEW) {
            //$order_info['order_cancel_day'] = $order_info['add_time'] + ORDER_AUTO_CANCEL_DAY * 24 * 3600;
            $order_info['order_cancel_day'] = $order_info['add_time'] + ORDER_AUTO_CANCEL_DAY + 3 * 24 * 3600;
        }

        $this->assign('order_info', $order_info);
        $this->setAdminCurItem();
        $this->setMenuList();
        return $this->fetch('view');
    }

    /**
     * 导出
     *
     */
    public function export_step1() {

        $model_vr_order = Model('vrorder');
        $condition = array();
        if ($_GET['order_sn']) {
            $condition['order_sn'] = $_GET['order_sn'];
        }
        if ($_GET['store_name']) {
            $condition['store_name'] = $_GET['store_name'];
        }
        if (in_array($_GET['order_state'], array('0', '10', '20', '30', '40'))) {
            $condition['order_state'] = $_GET['order_state'];
        }
        if ($_GET['payment_code']) {
            $condition['payment_code'] = $_GET['payment_code'];
        }
        if ($_GET['buyer_name']) {
            $condition['buyer_name'] = $_GET['buyer_name'];
        }
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_GET['query_start_time']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_GET['query_end_time']);
        $start_unixtime = $if_start_time ? strtotime($_GET['query_start_time']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['query_end_time']) : null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('between', array($start_unixtime, $end_unixtime));
        }

        if (!is_numeric($_GET['curpage'])) {
            $count = $model_vr_order->getOrderCount($condition);
            $array = array();
            if ($count > self::EXPORT_SIZE) { //显示下载链接
                $page = ceil($count / self::EXPORT_SIZE);
                for ($i = 1; $i <= $page; $i++) {
                    $limit1 = ($i - 1) * self::EXPORT_SIZE + 1;
                    $limit2 = $i * self::EXPORT_SIZE > $count ? $count : $i * self::EXPORT_SIZE;
                    $array[$i] = $limit1 . ' ~ ' . $limit2;
                }
                $this->assign('list', $array);
                $this->assign('murl', url('vrorder/index'));
                return $this->fetch('export.excel');
            } else { //如果数量小，直接下载
                $data = $model_vr_order->getOrderList($condition, '', '*', 'order_id desc', self::EXPORT_SIZE);
                $this->createExcel($data);
            }
        } else { //下载
            $limit1 = ($_GET['curpage'] - 1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $data = $model_vr_order->getOrderList($condition, '', '*', 'order_id desc', "{$limit1},{$limit2}");
            $this->createExcel($data);
        }
    }

    /**
     * 生成excel
     *
     * @param array $data
     */
    private function createExcel($data = array()) {
        $excel_obj = new \excel\Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id' => 's_title', 'Font' => array('FontName' => '宋体', 'Size' => '12', 'Bold' => '1')));
        //header
        $excel_data[0][] = array('styleid' => 's_title', 'data' => lang('exp_od_no'));
        $excel_data[0][] = array('styleid' => 's_title', 'data' => lang('exp_od_store'));
        $excel_data[0][] = array('styleid' => 's_title', 'data' => lang('exp_od_buyer'));
        $excel_data[0][] = array('styleid' => 's_title', 'data' => lang('exp_od_xtimd'));
        $excel_data[0][] = array('styleid' => 's_title', 'data' => lang('exp_od_count'));
        $excel_data[0][] = array('styleid' => 's_title', 'data' => lang('exp_od_paytype'));
        $excel_data[0][] = array('styleid' => 's_title', 'data' => lang('exp_od_state'));
        $excel_data[0][] = array('styleid' => 's_title', 'data' => lang('exp_od_storeid'));
        $excel_data[0][] = array('styleid' => 's_title', 'data' => lang('exp_od_buyerid'));
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '接收手机');
        //data
        foreach ((array) $data as $k => $v) {
            $tmp = array();
            $tmp[] = array('data' => 'DS' . $v['order_sn']);
            $tmp[] = array('data' => $v['store_name']);
            $tmp[] = array('data' => $v['buyer_name']);
            $tmp[] = array('data' => date('Y-m-d H:i:s', $v['add_time']));
            $tmp[] = array('format' => 'Number', 'data' => dsPriceFormat($v['order_amount']));
            $tmp[] = array('data' => orderPaymentName($v['payment_code']));
            $tmp[] = array('data' => $v['state_desc']);
            $tmp[] = array('data' => $v['store_id']);
            $tmp[] = array('data' => $v['buyer_id']);
            $tmp[] = array('data' => $v['buyer_phone']);
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data, CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset(lang('exp_od_order'), CHARSET));
        $excel_obj->generateXML($excel_obj->charset(lang('exp_od_order'), CHARSET) . $_GET['curpage'] . '-' . date('Y-m-d-H', time()));
    }

    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'index', 'text' => lang('manage'), 'url' => url('vrorder/index')
            )
        );
        if(request()->action() == 'change_state') {
            $menu_array[] = array(
                'name' => 'submit', 'text' => '确认收款', 'url' => ''
            );
        }
        return $menu_array;
    }

}
