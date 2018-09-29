<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Vrsorder extends AdminControl {

    /**
     * 每次导出订单数量
     * @var int
     */
    const EXPORT_SIZE = 1000;

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/vrorder.lang.php');
        Lang::load(APP_PATH . 'admin/lang/zh-cn/school.lang.php');
    }

    public function index() {
        $order = Model('Packagesorder');
        $condition = array();
        $condition['order_type'] = 1;
        $buyer_name = input('get.buyer_name');
        if ($buyer_name) {
            $condition['buyer_name'] = $buyer_name;
        }
        $order_state = input('get.order_state');
        if ($order_state!="") {
            $condition['order_state'] = intval($order_state);
        }
        $payment_code = input('get.payment_code');
        if (!empty($payment_code)) {
            $condition['payment_code'] = $payment_code;
        }
        $order_list = $order->getOrderList($condition, 15);
        foreach ($order_list as $key=>$item) {
            $studentinfo = db('student')->where(array('s_id'=>$item['student_id']))->find();
            $order_list[$key]['student_name'] = $studentinfo['s_name'];
        }
        $payment = db('payment')->select();
        $this->assign('payment', $payment);
        $this->assign('order_list', $order_list);
        $this->assign('page', $order->page_info->render());
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    public function teach(){
        $order = Model('Packagesorder');
        $condition = array();
        $condition['order_type'] = 2;
        $buyer_name = input('get.buyer_name');
        if ($buyer_name) {
            $condition['buyer_name'] = array('like', "%" . $buyer_name . "%");
        }
        $order_state = input('get.order_state');
        if ($order_state!="") {
            $condition['order_state'] = intval($order_state);
        }
        $payment_code = input('get.payment_code');
        if (!empty($payment_code)) {
            $condition['payment_code'] = $payment_code;
        }
        $order_list = $order->getOrderList($condition, 15);
        foreach ($order_list as $key=>$item) {
            $studentinfo = db('student')->where(array('s_id' => $item['student_id']))->find();
            $order_list[$key]['student_name'] = $studentinfo['s_name'];
        }
        $payment = db('payment')->select();
        $this->assign('payment', $payment);
        $this->assign('order_list', $order_list);
        $this->assign('page', $order->page_info->render());
        $this->setAdminCurItem('teach');
        return $this->fetch();
    }

    public function revisit(){
        $order = Model('Packagesorder');
        $condition = array();
        $condition['order_type'] = 3;
        $buyer_name = input('get.buyer_name');
        if ($buyer_name) {
            $condition['buyer_name'] = array('like', "%" . $buyer_name . "%");
        }
        $order_state = input('get.order_state');
        if ($order_state!="") {
            $condition['order_state'] = intval($order_state);
        }
        $payment_code = input('get.payment_code');
        if (!empty($payment_code)) {
            $condition['payment_code'] = $payment_code;
        }
        $order_list = $order->getOrderList($condition, 15);
        foreach ($order_list as $key=>$item) {
            $studentinfo = db('student')->where(array('s_id' => $item['student_id']))->find();
            $order_list[$key]['student_name'] = $studentinfo['s_name'];
        }
        $payment = db('payment')->select();
        $this->assign('payment', $payment);
        $this->assign('order_list', $order_list);
        $this->assign('page', $order->page_info->render());
        $this->setAdminCurItem('revisit');
        return $this->fetch();
    }

    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'index',
                'text' => '看孩订单',
                'url' => url('Admin/Vrsorder/index')
            ),
            array(
                'name' => 'teach',
                'text' => '教孩订单',
                'url' => url('Admin/Vrsorder/teach')
            ),
            array(
                'name' => 'revisit',
                'text' => '重温课堂订单',
                'url' => url('Admin/Vrsorder/revisit')
            ),
        );
        return $menu_array;
    }

}
