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

    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'index',
                'text' => '资金明细',
                'url' => url('Admin/Platform/index')
            )
        );

        return $menu_array;
    }

}
