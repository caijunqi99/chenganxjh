<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/16
 * Time: 20:35
 */

namespace app\wap\controller;


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
                $this->assign('message', '支付失败');
            }
        }
        else {
            //验证失败
            $this->assign('result', 'fail');
            $this->assign('message', '支付失败');
        }
        return $this->fetch('payment_message');
    }

    public function notify_url(){
        $this->payment_code = 'wxpay_h5';
        $api = $this->_get_payment_api();
        $params = $this->_get_payment_config();
        // $api->setConfigs($params);

        // $data= $api->onNotify();
        $d = $this->xmlToArray(file_get_contents('php://input'));
        $input = input();
        $insert = array(
            'content'=>json_encode(array(
                'InsertTime'=>date('Y-m-d H:i:s',time()),
                'input' =>$input,
                'data' =>$d
            ))
        );
        db('testt')->insert($insert);
        echo 'success';
    }

    public function xmlToArray($xml)
    {
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * 支付宝支付提醒
     */
    /**
     * 支付宝支付异步提醒
     */
    public function alipay_notify_app()
    {
        $this->payment_code = 'alipay_app';
        $input = input();
        $payment_config = $this->_get_payment_config();
        $payment_api = $this->_get_payment_api();
        $pay_sn = explode('-', $input['out_trade_no']);
        $data['pay_sn'] = $pay_sn['0'];
        $data['order_amount'] =$input['total_amount'];
        $Package = model('Packagesorder');
        $order_info = $Package->getOrderInfo($data);
        if (!empty($order_info)) {
            $callback_info = $payment_api->verify_notify($input);
            if ($callback_info['trade_status'] == '1') {
                //验证成功
                $update = array(
                    'out_pay_sn' => $input['trade_no'],
                    'payment_time' => strtotime($input['gmt_create']),
                    'finnshed_time' => time(),
                    'pd_amount' => 0, //预存款支付金额
                    'evaluation_state' => 0, //评价状态 0未评价，1已评价，2已过期未评价
                    'order_state' => 40 ,//订单状态：0(已取消)10(默认):未付款;20:已付款;40:已完成;
                    'over_amount' => $input['buyer_pay_amount'], //最终支付金额
                );
                $result = $this->_update_order($update, $order_info);
                if ($result['code']) {
                    echo 'success';
                    exit;
                }
            }
        }
        //验证失败
        echo "fail";
        exit;
    }

    /**
     *微信h5支付回调
     */
    public function wx_notify_h5()
    {
        $this->payment_code = 'wxpay_h5';
        $api = $this->_get_payment_api();
        $params = $this->_get_payment_config();
        $input = $this->xmlToArray(file_get_contents('php://input'));
        if (is_array($input) && !empty($input)) {
            $Package = model('Packagesorder');
            $order_info = $Package->getOrderInfo($input['out_trade_no']);
            if ($order_info && $input['result_code']=="SUCCESS") {
                //验证订单
                
                //验证成功
                $update = array(
                    'out_pay_sn' => $input['transaction_id'],
                    'payment_time' => strtotime($input['time_end']),
                    'finnshed_time' => time(),
                    'pd_amount' => 0, //预存款支付金额
                    'evaluation_state' => 0, //评价状态 0未评价，1已评价，2已过期未评价
                    'order_state' => 40, //订单状态：0(已取消)10(默认):未付款;20:已付款;40:已完成;
                    'over_amount' => $input['total_fee']/100, //最终支付金额
                );
                $result = $this->_update_order($update, $order_info);
                if ($result['code']) {
                    echo 'success';
                    exit;
                }
            }
        }
        echo 'fail';
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

        if ($result['trade_status'] == '1') {
            $internalSn = $result['out_trade_no'] . '_' . $result['attach'];
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
    private function _update_order($input, $orderInfo)
    {
        $model_order = model('Packagesorder');
        $logic_payment = model('payment', 'logic');
        $paymentCode = $this->payment_code;
        if ($orderInfo) {

            $result = $logic_payment->updatePackageOrder($input, $orderInfo, $paymentCode);

            
            $log_buyer_id = $orderInfo['buyer_id'];
            $log_buyer_name = $orderInfo['buyer_name'];
            $log_desc = '套餐购买' . orderPaymentName($paymentCode) . '成功支付，支付单号：' . $orderInfo['pay_sn'];

        }
        if ($result['code']) {
            //记录消费日志
            \mall\queue\QueueClient::push('addConsume', array(
                'member_id' => $log_buyer_id, 'member_name' => $log_buyer_name,
                'consume_amount' => dsPriceFormat($orderInfo['order_amount']), 'consume_time' => TIMESTAMP,
                'consume_remark' => $log_desc
            ));
        }

        return $result;
    }
}