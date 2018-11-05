<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/16
 * Time: 20:35
 */

namespace app\mobile\controller;


class Payment extends MobileMall
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * Alipay支付回调
     */
    public function alipay_return_url()
    {
        $this->payment_code = 'alipay';
        $payment_config = $this->_get_payment_config();
        $payment_api = $this->_get_payment_api($payment_config);
        $pay_sn = explode('-', input('param.out_trade_no'));
        $data['pay_sn'] = $pay_sn['0'];
        trace('pay_sn' . $data['pay_sn'], 'debug');
        $data['order_amount'] = input('param.total_amount');
        $order_model = model('order');
        $order_info = $order_model->getOrderInfo($data);
        if (!empty($order_info)) {
            $callback_info = $payment_api->getReturnInfo($payment_config);
            if ($callback_info) {
                $this->assign('result', 'success');
                $this->assign('message', '支付成功');
            }
            else {
                //验证失败
                $this->assign('result', 'fail');
                $this->assign('message', '支付配置参数错误');
            }
        }
        else {
            //验证失败
            $this->assign('result', 'fail');
            $this->assign('message', '支付失败,订单金额与订单号错误！！');
        }
        return $this->fetch('payment_message');
    }

    /**
     * 支付宝支付提醒
     */
    public function notify()
    {
        $this->payment_code = 'alipay';

        $payment_config = $this->_get_payment_config();
        $payment_api = $this->_get_payment_api($payment_config);
        $pay_sn = explode('-', input('param.out_trade_no'));
        $data['pay_sn'] = $pay_sn['0'];
        $data['order_amount'] = input('param.total_amount');
        $order_model = model('order');
        $order_info = $order_model->getOrderInfo($data);

        if (!empty($order_info)) {
            $callback_info = $payment_api->getNotifyInfo();
            if ($callback_info) {

                //验证成功
                $result = $this->_update_order($callback_info['out_trade_no'], $callback_info['trade_no']);

                if ($result['code']) {
                    echo 'success';
                    die;
                }
            }
        }
        //验证失败
        echo "fail";
        die;
    }

    /**
     *微信h5支付回调
     */
    public function wx_notify()
    {
        $this->payment_code = 'wxpay_h5';
        $api = $this->_get_payment_api();
        $params = $this->_get_payment_config();
        $api->setConfigs($params);

        list($result, $output) = $api->notify();
        if ($result) {
            $internalSn = $result['out_trade_no'] . '-' . $result['attach'];
            $externalSn = $result['transaction_id'];
            $updateSuccess = $this->_update_order($internalSn, $externalSn);

            if (!$updateSuccess) {
                // @todo
                // 直接退出 等待下次通知
                exit;
            }
        }

        echo $output;
        exit;
    }

    /**
     *微信app支付回调
     */
    public function wx_notify_app()
    {
        $this->payment_code = 'wxpay_app';
        $api = $this->_get_payment_api();
        $params = $this->_get_payment_config();

        $result = $api->verify_notify($params);
        $insert = array(
            'content'=>json_encode(array(
                'InsertTime'=>date('Y-m-d H:i:s',time()),
                'PaymentCode'=>$params,
                'input' =>$result,
                // 'data' =>$d,
                'other'=>$api
            ))
        );
        db('testt')->insert($insert);
        if ($result['trade_status'] == '1') {
            $internalSn = $result['out_trade_no'] . '-' . $result['attach'];
            $externalSn = $result['transaction_id'];
            $updateSuccess = $this->_update_order($internalSn, $externalSn);

            if (!$updateSuccess) {
                // @todo
                // 直接退出 等待下次通知
                exit;
            }
        }
        exit;
    }

    /**
     * 支付宝支付提醒
     */
    public function alipay_notify_app()
    {
        $this->payment_code = 'alipay_app';

        $payment_config = $this->_get_payment_config();
        $payment_api = $this->_get_payment_api();
        $pay_sn = explode('-', input('param.out_trade_no'));
        $data['pay_sn'] = $pay_sn['0'];
        $data['order_amount'] = input('param.total_amount');
        $order_model = model('order');
        $order_info = $order_model->getOrderInfo($data);
        if (!empty($order_info)) {
            $callback_info = $payment_api->verify_notify($payment_config);
            if ($callback_info['trade_status'] == '1') {
                //验证成功
                $result = $this->_update_order($callback_info['out_trade_no'], $callback_info['trade_no']);
                if ($result['code']) {
                    echo 'success';
                    die;
                }
            }
        }
        //验证失败
        echo "fail";
        die;
    }

    /**
     * 获取支付接口实例
     */
    private function _get_payment_api($payment_config=array())
    {
        $inc_file = APP_PATH . DIR_MOBILE . DS . 'api' . DS . 'payment' . DS . $this->payment_code . DS . $this->payment_code . '.php';

        if (is_file($inc_file)) {
            require($inc_file);
        }
        $payment_api = new $this->payment_code($payment_config);
        return $payment_api;
    }

    /**
     * 获取支付接口信息
     */
    private function _get_payment_config()
    {
        $model_mb_payment = Model('mbpayment');

        //读取接口配置信息
        $condition = array();
        if ($this->payment_code == 'wxpay_h5') {
            $condition['payment_code'] = 'wxpay_jsapi';
        }
        else {
            $condition['payment_code'] = $this->payment_code;
        }
        $payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);

        return $payment_info['payment_config'];
    }

    /**
     * 更新订单状态
     */
    private function _update_order($out_trade_no, $trade_no)
    {
        $model_order = model('order');
        $logic_payment = model('payment', 'logic');

        $tmp = explode('-', $out_trade_no);
        $out_trade_no = $tmp[0];
        if (!empty($tmp[1])) {
            $order_type = $tmp[1];
        }
        else {
            $order_pay_info = $model_order->getOrderPayInfo(array('pay_sn' => $out_trade_no));
            if (empty($order_pay_info)) {
                $order_type = 'v';
            }
            else {
                $order_type = 'r';
            }
        }


        $paymentCode = $this->payment_code;

        if ($paymentCode == 'wxpay_jsapi'||$paymentCode == 'wxpay_app' ||$paymentCode == 'wxpay_h5') {
            $paymentCode = 'wxpay';
        }

        if ($order_type == 'r') {
            $result = $logic_payment->getRealOrderInfo($out_trade_no);
            if (intval($result['data']['api_pay_state'])) {
                return array('state' => true);
            }
            $order_list = $result['data']['order_list'];
            $result = $logic_payment->updateRealOrder($out_trade_no, $paymentCode, $order_list, $trade_no);

            $api_pay_amount = 0;
            if (!empty($order_list)) {
                foreach ($order_list as $order_info) {
                    $api_pay_amount += $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
                }
            }
            $log_buyer_id = $order_list[0]['buyer_id'];
            $log_buyer_name = $order_list[0]['buyer_name'];
            $log_desc = '实物订单使用' . orderPaymentName($paymentCode) . '成功支付，支付单号：' . $out_trade_no;

        }
        elseif ($order_type == 'v') {
            $result = $logic_payment->getVrOrderInfo($out_trade_no);
            $order_info = $result['data'];
            if (!in_array($result['data']['order_state'], array(ORDER_STATE_NEW, ORDER_STATE_CANCEL))) {
                return array('state' => true);
            }
            $result = $logic_payment->updateVrOrder($out_trade_no, $paymentCode, $result['data'], $trade_no);

            $api_pay_amount = $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
            $log_buyer_id = $order_info['buyer_id'];
            $log_buyer_name = $order_info['buyer_name'];
            $log_desc = '虚拟订单使用' . orderPaymentName($paymentCode) . '成功支付，支付单号：' . $out_trade_no;
        }
        if ($result['code']) {
            //记录消费日志
            \mall\queue\QueueClient::push('addConsume', array(
                'member_id' => $log_buyer_id, 'member_name' => $log_buyer_name,
                'consume_amount' => dsPriceFormat($api_pay_amount), 'consume_time' => TIMESTAMP,
                'consume_remark' => $log_desc
            ));
        }

        return $result;
    }
}