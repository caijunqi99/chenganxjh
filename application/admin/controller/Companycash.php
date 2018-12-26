<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Companycash extends AdminControl {

    //代理商提现
    public function index(){
        $condition = array();
        $status = input('param.status');//状态
        if ($status!="") {
            $condition['status'] = $status;
        }
        $user = input('param.user');//会员账号
        if($user!=""){
            $condition['pdc_member_name'] = array('like', "%" . $user . "%");
        }
        $number = input('param.number');//提现编号
        if($number!=""){
            $condition['pdc_sn'] = array('like', "%" . $number . "%");
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
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    //代理商提现，后台标识
    public function company_option(){
        $pdc_id = input('param.pdc_id');
        $status = input('param.status');
        $id = input('param.id');
        $name = input('param.name');
        $price = input('param.price');
        $number = input('param.number');
        $company_cash = Model("Companycash");
        $result = $company_cash->editCpdCash(array('status'=>$status),array('pdc_id'=>$pdc_id));
        $log_model = Model("Adminpdlog");
        $member_data = [
            "lg_member_id" => $id,
            "lg_member_name" => $name,
            "lg_type" => "cash_pay",
            "lg_av_amount" => $price,
            "lg_add_time" => time(),
            "lg_desc" => "代理商提现。提现编号：".$number
        ];
        if($result&&$status==2){
            $member_data['status'] = 1;
            $log_model->addLog($member_data);
            $this->success("提现成功", 'Companycash/company_cash');
        }else{
            $member_data['status'] = 2;
            $log_model->addLog($member_data);
            $this->success("提现失败", 'Companycash/company_cash');
        }
    }

    //提现
    public function cash(){
        $admininfo = $this->getAdminInfo();
        $admininfo['admin_company_id']=2;
        $admininfo['admin_id']=2;
        $admininfo['admin_name']="bjfgs";
        if (!request()->isPost()) {
            $companyInfo = db("company")->field("o_id,total_amount")->where("o_id",$admininfo['admin_company_id'])->find();
            $companyBanks = db("companybanks")->field("bank_id,bank_name,bank_card,true_name")->where("company_id",$admininfo['admin_company_id'])->find();
            $this->assign('companyInfo', $companyInfo);
            $this->assign('companyBanks', $companyBanks);
            $this->setAdminCurItem('cash');
            return $this->fetch();
        } else {
            //冻结申请提现金额
            $company = model('Company');
            $o_id = input('post.o_id');
            $freeze_amount = input('post.s_money');
            $total_amount = input('post.total_amount_new');
            $total_amount_new = $total_amount-$freeze_amount;
            $company->editOrganize(array('o_id'=>$o_id),array('total_amount'=>$total_amount_new,'freeze_amount'=>$freeze_amount));
            //提现申请记录
            $model_pd = Model('predeposit');
            $sn = $model_pd->makeSn();
            $company_cash = model('Companycash');
            $data = array(
                'pdc_amount' => $freeze_amount,
                'pdc_bank_name' => input('post.bank_name'),
                'pdc_bank_no' => input('post.bank_card'),
                'pdc_bank_user' => input('post.true_name'),
                'pdc_add_time' => time(),
                'pdc_payment_state' => 0,
                'available_predeposit' => $total_amount_new,
                'status' => "1",
                'pdc_member_id' => $admininfo['admin_id'],
                'pdc_member_name' => $admininfo['admin_name'],
                'pdc_sn' => $sn
            );
            $adminInfo = db("admin")->field("admin_name")->where(array('admin_id'=>$admininfo['admin_id']))->find();
            $data['pdc_member_name'] = $adminInfo['admin_name'];
            //验证数据  END
            $result = $company_cash->addCpdCash($data);
            if ($result) {
                $this->success("提现申请成功", 'Companycash/company_cash');
            } else {
                $this->error("提现申请失败");
            }
        }
    }

    /**
     * ajax操作
     */
    public function ajax() {
        $branch = input('param.branch');

        switch ($branch) {
            //金额验证
            case 'check_count':
                $total_amount = input('get.total_amount');
                $s_money = input('get.s_money');
                $is = is_numeric($s_money);
                if(empty($is)){
                    exit('false');
                }else{
                    if ($s_money>$total_amount) {
                        exit('false');
                    } else {
                        exit('true');
                    }
                }
                break;
        }
    }

    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'index',
                'text' => '管理',
                'url' => url('Admin/Companycash/index')
            )
        );
        //        if(session('admin_is_super') !=1){
        $menu_array[] = array(
            'name' => 'cash',
            'text' => '提现',
            'url' => url('Admin/Companycash/cash')
        );
//        }

        return $menu_array;
    }

}
