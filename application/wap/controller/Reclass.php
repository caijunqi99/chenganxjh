<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/16
 * Time: 20:12
 */

namespace app\wap\controller;
use think\Model;
use vomont\Vomont;


class Reclass extends MobileMember
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
        $condition['payment_code'] = $payment_code;
        $mb_payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);

        if (!$mb_payment_info) {
            output_error('支付方式未开启');
        }
        $this->payment_code = $payment_code;
        $this->payment_config = $mb_payment_info['payment_config'];
        $inc_file = APP_PATH . DIR_APP . DS . 'api' . DS . 'payment' . DS . $this->payment_code . DS . $this->payment_code . '.php';
        if (!is_file($inc_file)) {
            output_error('支付接口出错，请联系管理员！');
        }
        require_once($inc_file);
        $this->_logic_buy_1 = \model('buy_1','logic');
    }
    //重温课堂首页
    public function index(){
        $id = intval(input('post.id'));
        $begintime=intval(input('post.begintime'));
        $endtime=intval(input('post.endtime'));
        $time=strtotime("-4 month");
        $member_id=intval(input('post.member_id'));

        if((!empty($begintime) && $begintime<$time) || empty($begintime)){
            $begintime='';
        }else{
            $endtime=$begintime+24*3600;
        }
//        if((!empty($endtime) && $endtime<$time) || empty($endtime)){
//            $endtime='';
//        }
        $resid=$id.",";
        $vlink = new Vomont();
        $res= $vlink->SetLogin();
        $accountid=$res['accountid'];
        $res=$vlink->Videotape($accountid,$resid,$begintime,$endtime);
        $video = $res['videos'];
        if(!empty($video)){
            //判断是否购买套餐并且套餐没有过期
            $is_buy_tc= db('packagetime')->where('member_id="'.$member_id.'" AND pkg_type=2')->find();
            if(!empty($is_buy_tc) && $is_buy_tc['end_time'] >time()){
                foreach($video as $k=> $v){
                    //按日期分组
                    $video[$k]['date'] = date("Y-m-d",$v['begintime']);
                    $video[$k]['begin']=date('H:i',$v['begintime']);
                    $video[$k]['end']=date('H:i',$v['endtime']);
                    $video[$k]['is_buy'] = 1;
                    //判断是否购买片段
                    $re = db('packagesorderreclass')->where('order_resid="'.$id.'" AND start_time>="'.$v["begintime"].'" AND end_time<="'.$v["endtime"].'"  AND order_state=20  ')->find();
                    if(!empty($re)){
                        if($re['order_dieline']>$is_buy_tc['end_time']){
                            $video[$k]['Due_time'] = $re['order_dieline'];
                        }else{
                            $video[$k]['Due_time'] = $is_buy_tc['end_time'];
                        }
                    }else{
                        $video[$k]['Due_time'] = $is_buy_tc['end_time'];
                    }

                }
            }else{
                foreach($video as $k=> $v){
                    //按日期分组
                    $video[$k]['date'] = date("Y-m-d",$v['begintime']);
                    $video[$k]['begin']=date('H:i',$v['begintime']);
                    $video[$k]['end']=date('H:i',$v['endtime']);
                    //判断是否购买片段
                    $re = db('packagesorderreclass')->where('order_resid="'.$id.'" AND start_time>="'.$v["begintime"].'" AND end_time<="'.$v["endtime"].'" AND order_state=20 ')->find();
                    if(!empty($re) && $re['order_dieline']>time()){
                        $video[$k]['is_buy'] = 1;
                        $video[$k]['Due_time'] = $re['order_dieline'];
                    }else{
                        $video[$k]['is_buy'] = 2;
                        $video[$k]['Due_time'] = '';
                    }
                }
            }
            foreach($video as $key=>$item){
                $data[$item['date']][] = $item;
                $last_time = $item['begintime'];
            }
            foreach ($data as $ke=>$va){
                    $rr[$ke]['date'] = $ke;
                    $rr[$ke]['list']=$va;
            }
            $rr = array_reverse(array_values($rr));
            $res=array();
            $res['content'] = $rr;
            $res['time'] = !empty($last_time)?$last_time:"";
            $datas = !empty($res) ? [$res] : $res;
        }
        output_data($datas);
    }

    /**
     * 重温课堂购买片段页
     * 1，拿到需要购买的套餐信息
     *     --购买人信息
     *     --视频信息
     *     --支付类型
     * 2，根据得到的信息生成订单
     *     --生成订单流水号
     *     --生成支付流水号
     *     --存储到订单表-》包含套餐信息，购买人信息，支付类型，孩子，摄像头信息
     *     --返回
     * @return [type] [description]
     */
    public function buyOrder(){
        $resid = intval(input('post.resid'));
        $member_id = intval(input('post.member_id'));
        $begintime=intval(input('post.begintime'));
        $endtime=intval(input('post.endtime'));
        $time=strtotime("-4 month");
        if((!empty($begintime) && $begintime<$time) || empty($begintime)){
            output_error('片段的开始时间错误！');
        }
        if((!empty($endtime) && $endtime<$time) || empty($endtime)){
            output_error('片段的结束时间错误！');
        }
        $id=$resid.",";
        $vlink = new Vomont();
        $res= $vlink->SetLogin();
        $accountid=$res['accountid'];
        $res=$vlink->Videotape($accountid,$id,$begintime,$endtime);
        $video = $res['videos'];
        if(empty($video)){
            output_error('无此片段的信息！');
        }
        $model = Model('packagesorderreclass');
        //会员信息
        $memberinfo = db('member')->where(array('member_id'=>$member_id))->find();
        //获取摄像头表中的parentid  然后在获取class表中的schoolid  然后在获取学校信息
        $camera = db('camera')->field('parentid')->where('id="'.$resid.'"')->find();
        $school = '';
        if(!empty($camera)){
            $class = db('class')->field('schoolid')->where('res_group_id="'.$camera['parentid'].'"')->find();
            if(!empty($class)){
                $school= db('school')->where('schoolid="'.$class["schoolid"].'"')->find();
            }
        }
        //片段价格
        $site = db('config')->where(' id=723')->find();
        //生成基本订单信息
        $order = array();
        $order['buyer_id'] = $member_id;
        $order['order_sn'] = "111111";//初始
        $order['pay_sn'] = "111111";//初始
        $order['buyer_name'] = $memberinfo['member_name'];
        $order['buyer_mobile'] = $memberinfo['member_mobile'];
        $order['order_name'] = '（课堂片段）'.date('Y-m-d',$begintime).date('H:i:s',$begintime).date('H:i:s',$endtime);
        $order['order_amount'] = sprintf("%.2f",$site['value']);
        $order['add_time'] = TIMESTAMP;
        $order['order_resid'] = $resid;
        $order['start_time'] = $begintime;
        $order['end_time'] = $endtime;
        //学校地区及所属公司
        $order['provinceid'] = !empty($school)?$school['provinceid']:"";
        $order['cityid'] = !empty($school)?$school['cityid']:"";
        $order['areaid'] = !empty($school)?$school['areaid']:"";
        $order['admin_company_id'] = !empty($school)?$school['admin_company_id']:"";


        $order['payment_code'] = $this->payment_code;
        if ($order['payment_code'] == "") {
            $order['payment_code'] = "offline";
        }
        $order['order_from'] = $this->member_info['client_type'];
        $order['order_state'] = ORDER_STATE_NEW;
        //写入订单表
        $order_pay_id = $model->addOrder($order);
        $this->orderInfo = $order;
        $this->orderInfo['order_sn'] = $model->makeOrderSn($order_pay_id);
        $this->orderInfo['pay_sn'] = $model->makePaySn($member_id);
        $this->orderInfo['order_id'] = $order_pay_id;
        //写入平台流水号
        $model->editOrder(array('order_sn'=>$this->orderInfo['order_sn'],"pay_sn"=>$this->orderInfo['pay_sn']), array('order_id'=>$order_pay_id));
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
        //微信app支付
        if ($this->payment_code == 'wxpay_app') {
            $param['orderSn'] = $order_pay_info['pay_sn'];
            $param['orderFee'] = (100 * $order_pay_info['order_amount']);
            $param['orderInfo'] = config('site_name') . '订单' . $order_pay_info['pay_sn'];
            $param['orderAttach'] = $order_pay_info['pkg_type'] = "reclass";
            $param['notifyUrl'] = WAP_SITE_URL . '/Reclasspayment/wx_notify_h5';
            $api = new \wxpay_app();
            $api->get_payform($param);
            exit;
        }
        //支付宝
        if ($this->payment_code == 'alipay_app') {
            $param['orderSn'] = $order_pay_info['pay_sn'];;
            $param['orderFee'] = $order_pay_info['order_amount'];
            $param['orderInfo'] = config('site_name') . '订单' . $order_pay_info['pay_sn'];
            $param['order_type'] = $order_pay_info['pkg_type'] = "reclass";
            $param['notifyUrl'] = WAP_SITE_URL . '/Reclasspayment/alipay_notify_app';
            $api = new \alipay_app();
            $api->get_payform($param);

            exit;
        }
    }
}