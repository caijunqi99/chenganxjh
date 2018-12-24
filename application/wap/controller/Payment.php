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

    public function notify_url($input,$other=''){
        // $d = $this->xmlToArray(file_get_contents('php://input'));
        // $input = input();
        $insert = array(
            'content'=>json_encode(array(
                'InsertTime'=>date('Y-m-d H:i:s',time()),
                'PaymentCode'=>$this->payment_code,
                'input' =>$input,
                // 'data' =>$d,
                'other'=>$other
            ))
        );
        db('testt')->insert($insert);
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
        $payment_api = $this->_get_payment_api();

        $input = input();
        $pay_sn = explode('-', $input['out_trade_no']);
        $data['pay_sn'] = $pay_sn['0'];
        $Package = model('Packagesorder');
        $order_info = $Package->getOrderInfo($data);

        $this->notify_url($input,$order_info);

        if (!empty($order_info) && $input) {
            $callback_info = $payment_api->verify_notify($input);

            if ($callback_info['trade_status'] == '1') {
                //验证成功
                $update = array(
                    'out_pay_sn' => $callback_info['trade_no'],
                    'payment_time' => strtotime($input['gmt_create']),
                    'finnshed_time' => time(),
                    'pd_amount' => 0, //预存款支付金额
                    'evaluation_state' => 0, //评价状态 0未评价，1已评价，2已过期未评价
                    'order_state' => 40 ,//订单状态：0(已取消)10(默认):未付款;20:已付款;40:已完成;
                    'over_amount' => $callback_info['total_fee'], //最终支付金额
                );

                $result = $this->_update_order($update, $order_info);

                if ($result['code']) {
                    if($order_info['pkg_type'] == 2){
                        //分成
                        $this->money($callback_info['total_fee'],$order_info['order_id']);
                    }
                    echo 'SUCCESS';
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
        $input = $this->xmlToArray(file_get_contents('php://input'));
        $Package = model('Packagesorder');
        $data =array();
        $data['pay_sn'] = $input['out_trade_no'];
        $order_info = $Package->getOrderInfo($data);
        $this->notify_url($input,$order_info);

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
                if($order_info['pkg_type'] == 2){
                    //分成
                    $this->money($input['total_fee']/100,$order_info['order_id']);
                }

                echo 'SUCCESS';
                exit;
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
                echo 'fail';
                exit;
            }else{
                echo 'success';
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
        $inc_file = APP_PATH . 'wap' . DS . 'api' . DS . 'payment' . DS . $this->payment_code . DS . $this->payment_code . '.php';

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


        return $result;
    }



    /*
    * 重温课堂支付成功 给特约，县/区代，市代，总后台分成
    * 分成比例在后台设置 config表-》code=re_class_pay_scale
    * 如果没有特约，特约应得的分成给总后台
    * 如果没有县/区，县/区应得的分成给总后台
    * 如果没有市代，省代应得的分成给总后台
    * 代理商分成金额存储company表
    * 每次分成，金额增加日志存pdlog表
    *
    * */
    public function money($price,$order_id){
        //订单信息
        $Package = model('Packagesorder');
        $order_info = $Package->getOrderInfo(array('order_id'=>$order_id));
        $log_model = Model("Pdlog");
        $member_model = Model("Member");
        $memberInfo = $member_model->getMemberInfoByID($order_info['buyer_id']);
        $reClass_data = [
            "lg_member_id" => $order_info['buyer_id'],
            "lg_member_name" => $memberInfo['member_mobile'],
            "lg_type" => "order_pay",
            "lg_av_amount" => $price,
            "lg_add_time" => time(),
            "lg_desc" => "重温课堂套餐，用户支付成功。订单编号：".$order_info['order_sn']
        ];
        $log_model->addLog($reClass_data);

        if(!empty($order_info['cityid']) && !empty($order_info['areaid']) && !empty($order_info['admin_company_id'])){
            //分成比例
            $proportion = db('config')->where(array('code'=>"re_class_pay_scale"))->find();
            $proportion['value'] = json_decode($proportion['value'],true);
            $alw =0;
            //特约分成金额
            $agent_price = sprintf('%.4f', $price*$proportion['value']['agent']/100);
            $company_model = Model("Company");
            $agent = $company_model->getOrganizeInfo(array('o_role'=>4,'o_id'=>$order_info['admin_company_id']));
            if($agent){
                $new_price = $agent['total_amount'] + $agent_price;
                $ty = $company_model->editOrganize(array("o_id"=>$agent['o_id']),array("total_amount"=>$new_price));
                if(empty($ty)){
                    output_error('特约分成失败');
                }
                $agent_data = [
                    "lg_member_id" => $agent['o_id'],
                    "lg_member_name" => $agent['o_name'],
                    "lg_type" => "share_ty_payment",
                    "lg_av_amount" => $agent_price,
                    "lg_add_time" => time(),
                    "lg_desc" => "重温课堂套餐，用户支付成功给特约代理商分成。订单编号：".$order_info['order_sn']
                ];
                $companylog_model = Model("Companylog");
                $companylog_model->addLog($agent_data);
                $alw =$alw+$proportion['value']['agent'];
            }
            //县区代理商分成金额
            $area_price = sprintf('%.4f', $price*$proportion['value']['area_agent']/100);
            $company_model = Model("Company");
            $area_agent = $company_model->getOrganizeInfo(array('o_role'=>1,'o_provinceid'=>$order_info['provinceid'],'o_cityid'=>$order_info['cityid'],'o_areaid'=>$order_info['areaid']));
            if($area_agent){
                $area_new_price = $area_agent['total_amount'] + $area_price;
                $area = $company_model->editOrganize(array("o_id"=>$area_agent['o_id']),array("total_amount"=>$area_new_price));
                if(empty($area)){
                    output_error('县区代分成失败');
                }
                $city_data = [
                    "lg_member_id" => $area_agent['o_id'],
                    "lg_member_name" => $area_agent['o_name'],
                    "lg_type" => "share_area_payment",
                    "lg_av_amount" => $area_price,
                    "lg_add_time" => time(),
                    "lg_desc" => "重温课堂套餐，用户支付成功给县区代理商分成。订单编号：".$order_info['order_sn']
                ];
                $companylog_model = Model("Companylog");
                $companylog_model->addLog($city_data);
                $alw = $alw+$proportion['value']['area_agent'];
            }
            //市代理商分成金额
            $city_price = sprintf('%.4f', $price*$proportion['value']['city_agent']/100);
            $company_model = Model("Company");
            $city_agent = $company_model->getOrganizeInfo(array('o_role'=>3,'o_provinceid'=>$order_info['provinceid'],'o_cityid'=>$order_info['cityid']));
            if($city_agent){
                $city_new_price = $city_agent['total_amount'] + $city_price;
                $city = $company_model->editOrganize(array("o_id"=>$city_agent['o_id']),array("total_amount"=>$city_new_price));
                if(empty($city)){
                    output_error('市代分成失败');
                }
                $city_data = [
                    "lg_member_id" => $city_agent['o_id'],
                    "lg_member_name" => $city_agent['o_name'],
                    "lg_type" => "share_city_payment",
                    "lg_av_amount" => $city_price,
                    "lg_add_time" => time(),
                    "lg_desc" => "重温课堂套餐，用户支付成功给市代理商分成。订单编号：".$order_info['order_sn']
                ];
                $companylog_model = Model("Companylog");
                $companylog_model->addLog($city_data);
                $alw = $alw+$proportion['value']['city_agent'];
            }

            //总后台分成金额
            $admin_price = sprintf('%.4f', $price*(100-$alw)/100);
            $admininfo = db("admin")->where(array("admin_gid"=>0))->find();
            $admin_new_price = $admininfo['admin_total_count'] + $admin_price;
            $admin_model = Model("Admin");
            $admin = $admin_model->updateAdmin(array("admin_total_count"=>$admin_new_price),$admininfo['admin_id']);
            if(empty($admin)){
                output_error('分成给总后台，分成失败');
            }
            $admin_data = [
                "lg_member_id" => $admininfo['admin_id'],
                "lg_member_name" => $admininfo['admin_name'],
                "lg_type" => "share_admin_payment",
                "lg_av_amount" => $admin_price,
                "lg_add_time" => time(),
                "lg_desc" => "重温课堂套餐，用户支付成功,给总后台分成。订单编号：".$order_info['order_sn']
            ];
            $adminpdlog_model = Model("Adminpdlog");
            $adminpdlog_model->addLog($admin_data);
        }
    }
}                  