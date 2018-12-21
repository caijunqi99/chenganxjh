<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Platform extends AdminControl {

    //资金明细
    public function index(){
        $condition = array();
        $status = input('param.status');//交易类型
        if ($status!="") {
            $condition['lg_type'] = $status==1? array("in",array("share_admin_payment","order_pay")) : "cash_pay";
        }
        $user = input('param.user');//会员账号
        if($user!=""){
            $condition['lg_member_name'] = array('like', "%" . $user . "%");
        }
        $query_start_time = input('param.query_start_time');
        $query_end_time = input('param.query_end_time');
        if ($query_start_time && $query_end_time) {
            $condition['lg_add_time'] = array('between', array(strtotime($query_start_time), strtotime($query_end_time)));
        }elseif($query_start_time){
            $condition['lg_add_time'] = array('>',strtotime($query_start_time));
        }elseif($query_end_time){
            $condition['lg_add_time'] = array('<',strtotime($query_end_time));
        }
        $pdlog = Model("Adminpdlog");
        $result = $pdlog->getAllPdlog($condition,15);
        $sum = $pdlog->getAllCount();
        if($sum){
            $this->assign('sum', $sum);
        }
        $this->assign('pdlog', $result);
        $this->assign('page', $pdlog->page_info->render());
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    //会员提现
    public function member_cash(){
        $condition = array();
        $status = input('param.status');//状态
        if ($status!="") {
            $condition['status'] = $status;
        }
        $user = input('param.user');//会员账号
        if($user!=""){
            $condition['pdc_member_name'] = array('like', "%" . $user . "%");
        }
        $query_start_time = input('param.query_start_time');
        $query_end_time = input('param.query_end_time');
        if ($query_start_time && $query_end_time) {
            $condition['pdc_add_time'] = array('between', array(strtotime($query_start_time), strtotime($query_end_time)));
        }elseif($query_start_time){
            $condition['pdc_add_time'] = array('>',strtotime($query_start_time));
        }elseif($query_end_time){
            $condition['pdc_add_time'] = array('<',strtotime($query_end_time));
        }
        $member_cash = Model("Predeposit");
        $result = $member_cash->getPdCashList($condition,15);
        $sum = $member_cash->getAllCount();
        if($sum[0]['num']){
            $this->assign('sum', $sum[0]['num']);
        }
        $this->assign('result', $result);
        $this->assign('page', $member_cash->page_info->render());
        $this->setAdminCurItem('member_cash');
        return $this->fetch();
    }

    //代理商提现
    public function company_cash(){
        $condition = array();
        $status = input('param.status');//状态
        if ($status!="") {
            $condition['status'] = $status;
        }
        $user = input('param.user');//会员账号
        if($user!=""){
            $condition['pdc_member_name'] = array('like', "%" . $user . "%");
        }
        $query_start_time = input('param.query_start_time');
        $query_end_time = input('param.query_end_time');
        if ($query_start_time && $query_end_time) {
            $condition['pdc_add_time'] = array('between', array(strtotime($query_start_time), strtotime($query_end_time)));
        }elseif($query_start_time){
            $condition['pdc_add_time'] = array('>',strtotime($query_start_time));
        }elseif($query_end_time){
            $condition['pdc_add_time'] = array('<',strtotime($query_end_time));
        }
        $company_cash = Model("Companycash");
        $result = $company_cash->getCpdCashList($condition,15);
        $sum = $company_cash->getAllCount();
        if($sum[0]['num']){
            $this->assign('sum', $sum[0]['num']);
        }
        $this->assign('result', $result);
        $this->assign('page', $company_cash->page_info->render());
        $this->setAdminCurItem('company_cash');
        return $this->fetch();
    }

    //会员提现，后台标识
    public function member_option(){
        $pdc_id = input('param.pdc_id');
        $status = input('param.status');
        $id = input('param.id');
        $name = input('param.name');
        $price = input('param.price');
        $member_cash = Model("Predeposit");
        $result = $member_cash->editPdCash(array('status'=>$status),array('pdc_id'=>$pdc_id));
        if($result&&$status==2){
            $log_model = Model("Adminpdlog");
            $member_data = [
                "lg_member_id" => $id,
                "lg_member_name" => $name,
                "lg_type" => "cash_pay",
                "lg_av_amount" => $price,
                "lg_add_time" => time(),
                "lg_desc" => "会员提现。"
            ];
            $log_model->addLog($member_data);
            $this->success("提现成功", 'Platform/member_cash');
        }else{
            $this->success("提现失败", 'Platform/member_cash');
        }
    }

    //代理商提现，后台标识
    public function company_option(){
        $pdc_id = input('param.pdc_id');
        $status = input('param.status');
        $id = input('param.id');
        $name = input('param.name');
        $price = input('param.price');
        $company_cash = Model("Companycash");
        $result = $company_cash->editCpdCash(array('status'=>$status),array('pdc_id'=>$pdc_id));
        $log_model = Model("Adminpdlog");
        if($result&&$status==2){
            $member_data = [
                "lg_member_id" => $id,
                "lg_member_name" => $name,
                "lg_type" => "cash_pay",
                "lg_av_amount" => $price,
                "lg_add_time" => time(),
                "lg_desc" => "代理商提现。",
                "status" => 1
            ];
            $log_model->addLog($member_data);
            $this->success("提现成功", 'Platform/company_cash');
        }else{
            $member_data = [
                "lg_member_id" => $id,
                "lg_member_name" => $name,
                "lg_type" => "cash_pay",
                "lg_av_amount" => $price,
                "lg_add_time" => time(),
                "lg_desc" => "代理商提现。",
                "status" => 2
            ];
            $log_model->addLog($member_data);
            $this->success("提现失败", 'Platform/company_cash');
        }
    }

    public function company_card(){

    }

    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'index',
                'text' => '资金明细',
                'url' => url('Admin/Platform/index')
            ),
            array(
                'name' => 'member_cash',
                'text' => '会员提现',
                'url' => url('Admin/Platform/member_cash')
            ),
            array(
                'name' => 'company_cash',
                'text' => '代理商提现',
                'url' => url('Admin/Platform/company_cash')
            ),
            array(
                'name' => 'company_card',
                'text' => '添加或修改绑定银行卡',
                'url' => url('Admin/Platform/company_card')
            ),
            array(
                'name' => 'cash',
                'text' => '提现',
                'url' => url('Admin/Platform/cash')
            )
        );

        return $menu_array;
    }

}
