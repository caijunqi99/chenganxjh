<?php

namespace app\wap\controller;

use think\Lang;
use process\Process;
use cloud\RongCloud;
use think\Model;

class Memberwallet extends MobileMember
{

    public function _initialize()
    {
        parent::_initialize();
        Lang::load(APP_PATH . 'wap\lang\zh-cn\login.lang.php');
    }

    /**
     * 获取钱包余额
     */
    function wallet(){
        $member_id = input('post.member_id');
        if(empty($member_id)){
            output_error('会员id不能为空');
        }
        $member_Model = Model("Member");
        $data = $member_Model->getMemberInfo(array('member_id'=>$member_id),"member_id,total_predeposit");
        if(!empty($data['total_predeposit'])){
            $data['total_predeposit'] = sprintf("%.2f",$data['total_predeposit']);
        }
        output_data($data);
    }

}

?>
