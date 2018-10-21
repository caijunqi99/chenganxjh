<?php

namespace app\wap\controller;


class Teacherbuy extends MobileMember
{
    private $payment_code;
    private $payment_config;
    private $orderInfo;

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        if (request()->action() != 'payment_list' && !input('param.payment_code')) {
            $payment_code = 'alipay';
        } else {
            $payment_code = input('post.payment_code');
        }
        $model_mb_payment = Model('mbpayment');
        $condition = array();
        $condition['payment_code'] = $payment_code == 'wxpay_h5'?'wxpay_app':$payment_code;
        $mb_payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);
        
        if (!$mb_payment_info && $payment_code == 'wxpay_h5') {
            $mb_payment_info = $model_mb_payment->getMbPaymentOpenInfo(array('payment_code'=>'wxpay_h5'));
        }
        if ($mb_payment_info) {
            $this->payment_code = $payment_code;
            $this->payment_config = $mb_payment_info['payment_config'];
            $inc_file = APP_PATH . DIR_APP . DS . 'api' . DS . 'payment' . DS . $this->payment_code . DS . $this->payment_code . '.php';
            if (!is_file($inc_file)) {
                output_error('支付接口出错，请联系管理员！');
            }
            require_once($inc_file);
        }
        $this->_logic_buy_1 = \model('buy_1','logic');
    }

    //购买页
    public function buy(){
        $teachchild = model('Teachchild');
        $tid = intval(input('post.tid'));
        if(empty($tid)){
            output_error('t_id参数有误');
        }
        $teachInfo = $teachchild->getTeachchildInfo(array('t_id'=>$tid));
        if(emmpty($teachInfo)){
            output_error('暂无数据');
        }
        $time = db('config')->where(array('code'=>"teacher_children"))->find();
        $teachInfo['time_line'] = $time['value'];

        $payment_list = model('mbpayment')->getMbPaymentOpenList();
        if (!empty($payment_list)) {
            foreach ($payment_list as $k => $value) {
                unset($payment_list[$k]['payment_id']);
                unset($payment_list[$k]['payment_config']);
                unset($payment_list[$k]['payment_state']);
                unset($payment_list[$k]['payment_state_text']);
            }
        }
        if(in_array($this->member_info['client_type'],array('ios','android'))){
            foreach ($payment_list as $k => $value) {
                if(!strpos($payment_list[$k]['payment_code'],'app')){
                    unset($payment_list[$k]);
                }
            }
        }
        $teachInfo['payment'] = $payment_list;
        output_data([$teachInfo]);
    }

    /**
     * 计算套餐时间何时到期
     * @return [type] [description]
     */
    public function packages_time_reckon(){
        output_data($this->payment_config);

    }

    /**
     * 购买视频
     * 1，拿到需要购买的套餐信息
     *     --购买人信息
     *     --套餐信息
     *     --购买人孩子信息，摄像头信息
     *     --支付类型
     * 2，根据得到的信息生成订单
     *     --生成订单流水号
     *     --生成支付流水号
     *     --存储到订单表-》包含套餐信息，购买人信息，支付类型，孩子，摄像头信息
     *     --返回
     * @return [type] [description]
     */
    public function buyOrder(){
        $tid = input('post.tid');
        $child_id = input('post.student_id');
        if (!$child_id || !$tid) {
            output_error('缺少参数！');
        }
        $teachchild = model('Teachchild');
        $teachInfo = $teachchild->getTeachchildInfo(array('t_id'=>$tid));
        if(empty($teachInfo)){
            output_error('无此视频的信息！');
        }
        $Children = model('Student');
        $childinfo=$Children->getChildrenInfoById($child_id);
        if (!$childinfo) {
            output_error('没有当前孩子信息！');
        }

        $order = array();
        //生成基本订单信息
        $order['student_id'] = $child_id;
        $order['buyer_id'] = $this->member_info['member_id'];
        $order['buyer_name'] = $this->member_info['member_name'];
        $order['buyer_mobile'] = $this->member_info['member_mobile'];
        $order['order_name'] = $teachInfo['t_title'];
        $order['order_amount'] = $teachInfo['t_price'];
        //$order['pay_sn'] = '$pay_sn';
        //$order['order_dieline'] = $teachInfo['t_title'];
        //$order['payment_time'] = $teachInfo['t_title'];
        //$order['finnshed_time'] = $teachInfo['t_title'];
        $order['add_time'] = TIMESTAMP;
        $order['payment_code'] = $this->payment_code;
        if ($order['payment_code'] == "") {
            $order['payment_code'] = "offline";
        }
        $order['order_from'] = $this->member_info['client_type'];
        $order['order_state'] = ORDER_STATE_NEW;
        //写入订单表
        $model = Model('Packagesorderteach');
        $order_pay_id = $model->addOrder($order);
        $this->orderInfo = $order;
        $this->orderInfo['order_id'] = $order_pay_id;
//        try {
//
//            $model->startTrans();
//            //写入订单表
//            $order_pay_id = $model->addOrder($order);
//            echo $order_pay_id;die;
//            $this->orderInfo = $order;
//            $this->orderInfo['order_id'] = $order_pay_id;
//            $model->commit();
//
//        } catch (Exception $e) {
//            $model->rollback();
//            return ds_callback(false, $e->getMessage());
//        }
        //写入平台流水号
        $model->editOrder(array('order_sn'=>$this->orderInfo['order_sn']), array('order_id'=>$order_pay_id));
        //app支付
        $this->_app_pay($this->orderInfo);        
    }

    /**
     * app支付
     * @param  [type] $order_pay_info [description]
     * @return [type]                 [description]
     */
    private function _app_pay($order_pay_info){
        $param = $this->payment_config;
        // 使用h5支付 wxpay_html5
        if ($this->payment_code == 'wxpay_h5') {
            $param['orderSn'] = $order_pay_info['pay_sn'];
            $param['orderFee'] = (100 * $order_pay_info['order_amount']);
            $param['orderInfo'] = config('site_name') . '商品订单' . $order_pay_info['pay_sn'];
            $param['orderAttach'] = $order_pay_info['pkg_type']==1?'witching':'teaching';
            $api = new \wxpay_h5();
            $api->setConfigs($param);
            
            $mweburl = $api->get_payurl($this);
            output_data($mweburl);
            $url = $mweburl['mweb_url'];
            Header("Location: $url");
            exit;
        }
        
        //alipay and so on
        $param['order_type'] = $order_pay_info['pkg_type']==1?'witching':'teaching';
        $param['orderInfo'] = config('site_name') . '商品订单' . $order_pay_info['pay_sn'];
        $param['orderSn'] = $order_pay_info['pay_sn'];
        $param['orderFee'] = 0.01;//$order_pay_info['order_amount'];
        $param['orderAttach'] = $order_pay_info['pkg_type']==1?'witching':'teaching';

        
        $payment_api = new $this->payment_code($param);
        $return = $payment_api->getSubmitUrl($param);
        echo $return;
        exit;
    }

    public function notify_url(){
        echo 'success';
    }

    public function order_status_check(){
        $pay_sn = input('post.pay_sn');
        if(!$pay_sn)output_error('订单号错误！');
        $Order = model('Packagesorder');

        $orderInfo = $Order->getOrderInfo(array('pay_sn'=>$pay_sn,'buyer_mobile'=>$this->member_info['member_mobile']));
        if(!$orderInfo)output_error('没有此订单信息！');
        if($this->payment_code == 'wxpay_h5'){
            $payment = new $this->payment_code();
        }else if($this->payment_code == 'alipay_app'){
            $payment = new $this->payment_code();
        }else{
            output_error('支付类型错误！');
        }
        $Status = $payment->getOrderStateBysn($orderInfo['pay_sn']);
        p($Status);exit;
        output_data($this);
    }


    
    /**
     * 验证密码
     */
    public function check_password()
    {
        if (empty($_POST['password'])) {
            output_error('参数错误');
        }

        $model_member = Model('member');

        $member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        if ($member_info['member_paypwd'] == md5($_POST['password'])) {
            output_data('1');
        }
        else {
            output_error('密码错误');
        }
    }

    /**
     * AJAX验证支付密码
     */
    public function check_pd_pwd()
    {
        if (empty($_POST['password'])) {
            output_error('支付密码格式不正确');
        }
        $buyer_info = Model('member')->getMemberInfoByID($this->member_info['member_id'], 'member_paypwd');
        if ($buyer_info['member_paypwd'] != '') {
            if ($buyer_info['member_paypwd'] === md5($_POST['password'])) {
                output_data('1');
            }
        }
        output_error('支付密码验证失败');
    }

    
}