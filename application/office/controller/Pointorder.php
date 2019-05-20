<?php

namespace app\office\controller;
use think\Lang;
use think\Validate;

class Pointorder extends AdminControl
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Lang::load(APP_PATH . 'office/lang/zh-cn/pointorder.lang.php');
    }
    /**
     * 积分兑换列表
     */
    public function pointorder_list(){
        $model_pointorder = Model('pointorder');
        //获取兑换订单状态
        $pointorderstate_arr = $model_pointorder->getPointOrderStateBySign();
        $where = array();
        //兑换单号
        $pordersn = trim(input('param.pordersn'));
        if ($pordersn){
            $where['point_ordersn'] = array('like',"%{$pordersn}%");
        }
        //兑换会员名称
        $pbuyname = trim(input('param.pbuyname'));
        if (trim(input('param.pbuyname'))){
            $where['point_buyername'] = array('like',"%{$pbuyname}%");
        }
        //订单状态
        if (trim(input('param.porderstate'))){
            $where['point_orderstate'] = $pointorderstate_arr[input('param.porderstate')][0];
        }
        //查询兑换订单列表
        $order_list = $model_pointorder->getPointOrderList($where, '*', 10, 0, 'point_orderid desc');

        //信息输出
       $this->assign('pointorderstate_arr',$pointorderstate_arr);
       $this->assign('order_list',$order_list);
       $this->assign('show_page',$model_pointorder->page_info->render());
       $this->setAdminCurItem('pointorder_list');
        return $this->fetch();
    }

    /**
     * 删除兑换订单信息
     */
    public function order_drop(){
        $data = Model('pointorder')->delPointOrderByOrderID(input('param.order_id'));
        if ($data['state']){
            showDialog(lang('admin_pointorder_del_success'),url('pointorder/pointorder_list'),'succ');
        } else {
            showDialog($data['msg'],url('pointorder/pointorder_list'),'error');
        }
    }

    /**
     * 取消兑换
     */
    public function order_cancel(){
        $model_pointorder = Model('pointorder');
        //取消订单
        $data = $model_pointorder->cancelPointOrder(input('param.id'));
        if ($data['state']){
            showDialog(lang('admin_pointorder_cancel_success'),url('pointorder/pointorder_list'),'succ');
        }else {
            showDialog($data['msg'],url('pointorder/pointorder_list'),'error');
        }
    }

    /**
     * 发货
     */
    public function order_ship(){
        $order_id = intval(input('param.id'));
        if ($order_id <= 0){
            showDialog(lang('admin_pointorder_parameter_error'),url('pointorder/pointorder_list'),'error');
        }
        $model_pointorder = Model('pointorder');
        //获取订单状态
        $pointorderstate_arr = $model_pointorder->getPointOrderStateBySign();

        //查询订单信息
        $where = array();
        $where['point_orderid'] = $order_id;
        $where['point_orderstate'] = array('in',array($pointorderstate_arr['waitship'][0],$pointorderstate_arr['waitreceiving'][0]));//待发货和已经发货状态
        $order_info = $model_pointorder->getPointOrderInfo($where);
        if (!$order_info){
            showDialog(lang('admin_pointorderd_record_error'),url('pointorder/pointorder_list'),'error');
        }
        if (request()->isPost()){
            $obj_validate = new Validate();
            $data=[
                'shippingcode'=>$_POST["shippingcode"]
            ];
            $rule=[
                ['shippingcode','require',lang('admin_pointorder_ship_code_nullerror')]
            ];

            $error = $obj_validate->check($data,$rule);
            if (!$error){
                showDialog(lang('ds_common_op_fail').$obj_validate->getError(),url('pointorder/pointorder_list'),'error');
            }
            //发货
            $data = $model_pointorder->shippingPointOrder($order_id, $_POST, $order_info);
            if ($data['state']){
                showDialog('发货修改成功',url('pointorder/pointorder_list'),'succ');
            }else {
                showDialog($data['msg'],url('pointorder/pointorder_list'),'error');
            }
        } else {
            $express_list = Model('express')->getExpressList();
           $this->assign('express_list',$express_list);
           $this->assign('order_info',$order_info);
           $this->setAdminCurItem('order_ship');
            return $this->fetch();
        }
    }
    /**
     * 兑换信息详细
     */
    public function order_info(){
        $order_id = intval(input('param.order_id'));
        if ($order_id <= 0){
            showDialog(lang('admin_pointorder_parameter_error'),url('pointorder/pointorder_list'),'error');
        }
        //查询订单信息
        $model_pointorder = Model('pointorder');
        $order_info = $model_pointorder->getPointOrderInfo(array('point_orderid'=>$order_id));
        if (!$order_info){
            showDialog(lang('admin_pointorderd_record_error'),url('pointorder/pointorder_list'),'error');
        }
        $orderstate_arr = $model_pointorder->getPointOrderState($order_info['point_orderstate']);
        $order_info['point_orderstatetext'] = $orderstate_arr[1];

        //查询兑换订单收货人地址
        $orderaddress_info = $model_pointorder->getPointOrderAddressInfo(array('point_orderid'=>$order_id));
       $this->assign('orderaddress_info',$orderaddress_info);

        //兑换商品信息
        $prod_list = $model_pointorder->getPointOrderGoodsList(array('point_orderid'=>$order_id));
       $this->assign('prod_list',$prod_list);

        //物流公司信息
        if ($order_info['point_shipping_ecode'] != ''){
            $data = Model('express')->getExpressInfoByECode($order_info['point_shipping_ecode']);
            if ($data['state']){
                $express_info = $data['data']['express_info'];
            }
           $this->assign('express_info',$express_info);
        }

       $this->assign('order_info',$order_info);
        $this->setAdminCurItem('order_info');
        return $this->fetch();
    }
    protected function getAdminItemList()
    {
        $menu_array = array(
            array(
                'name' => 'index', 'text' => '礼品列表', 'url' => url('Pointprod/index')
            ), array(
                'name' => 'prod_add', 'text' => '新增礼品', 'url' => url('Pointprod/prod_add')
            ), array(
                'name' => 'pointorder_list', 'text' => '兑换列表', 'url' => url('pointorder/pointorder_list')
            ),
        );
        return $menu_array;
    }
}