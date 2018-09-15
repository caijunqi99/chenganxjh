<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Payment extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/payment.lang.php');
    }

    /**
     * 支付方式
     */
    public function index() {
        $model_payment = model('payment');
        $payment_list = $model_payment->getPaymentList(array('payment_code' => array('neq', 'predeposit')));
        $this->assign('payment_list', $payment_list);
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    /**
     * 编辑
     */
    public function edit() {
        $model_payment = model('payment');
        $payment_id = intval(input('param.payment_id'));
        if (!(request()->isPost())) {
            $payment = $model_payment->getPaymentInfo(array('payment_id' => $payment_id));
            if ($payment['payment_config'] != '') {
                $this->assign('config_array', unserialize($payment['payment_config']));
            }
            $this->assign('payment', $payment);
            $this->setAdminCurItem('edit');
            return $this->fetch();
        } else {
            $data = array();
            $data['payment_state'] = intval($_POST["payment_state"]);
            $payment_config = '';
            $config_array=array();
            if(isset($_POST["config_name"])) {
                $config_array = explode(',', $_POST["config_name"]); //配置参数
            }
            if (is_array($config_array) && !empty($config_array)) {
                $config_info = array();
                foreach ($config_array as $k) {
                    $config_info[$k] = trim($_POST[$k]);
                }
                $payment_config = serialize($config_info);
            }
            $data['payment_config'] = $payment_config; //支付接口配置信息
            $model_payment->editPayment($data, array('payment_id' => $payment_id));
            $this->success('编辑成功', 'Payment/index');
        }
    }
    
    
    /**
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'index',
                'text' => '支付方式',
                'url' => url('Admin/Payment/index')
            ),
        );
        if (request()->action() == 'edit') {
            $menu_array[] = array(
                'name' => 'edit',
                'text' => '编辑',
                'url' => 'javascript:void(0)'
            );
        }
        return $menu_array;
    }

}

?>
