<?php

namespace app\wap\controller;

use think\Lang;
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
        $data = $member_Model->getMemberInfo(array('member_id'=>$member_id),"member_id,available_predeposit");
        if(empty($data)){
            output_error('无此会员');
        }
        if(!empty($data['available_predeposit'])){
            $data['available_predeposit'] = sprintf("%.2f",$data['available_predeposit']);
        }
        output_data($data);
    }

    public function self_assets(){
        $self = array(
            'available_predeposit' =>ncPriceFormatb($this->member_info['available_predeposit']),
            'freeze_predeposit' =>ncPriceFormatb($this->member_info['freeze_predeposit']),
            'income' => '0.00',//收入金额
            'reflect' => '0.00',//提现金额
        );
        output_data($self);
    }


    /**
     * 统一发送身份验证码
     */
    public function send_auth_code()
    {
        $type = input('param.type');
        if (!in_array($type, array('email', 'mobile')))
            exit();

        $model_member = Model('member');
        $verify_code = rand(100, 999) . rand(100, 999);
        $data = array();
        $data['auth_code'] = $verify_code;
        $data['send_acode_time'] = time();
        $update = $model_member->editMemberCommon($data,array('member_id' =>$this->member_info['member_id']));

        if (!$update) {
            exit(json_encode(array('state' => 'false', 'msg' => '系统发生错误，如有疑问请与管理员联系')));
        }

        $model_tpl = Model('mailtemplates');
        $tpl_info = $model_tpl->getTplInfo(array('code' => 'authenticate'));

        $param = array();
        $param['send_time'] = date('Y-m-d H:i', TIMESTAMP);
        $param['verify_code'] = $verify_code;
        $param['site_name'] = config('site_name');
        $message = ncReplaceText($tpl_info['content'], $param);
        $sms = new \sendmsg\Sms();
        $result = $sms->send($this->member_info["member_mobile"], $message);
        if ($result) {
            exit(json_encode(array('state' => 'true', 'msg' => '验证码已发出，请注意查收')));
        }
        else {
            exit(json_encode(array('state' => 'false', 'msg' => '验证码发送失败')));
        }
    }


    /**
     * 银行卡列表
     * @创建时间 2018-12-04T14:51:15+0800
     */
    public function MemberBankCards(){

        $input = input();
        $card = input('post.card');
        import('Banks', EXTEND_PATH);
        $result = \Banks::bankInfo($card);
        output_data(array('result'=>$result));
    }

    /**
     * 添加银行卡
     * @创建时间 2018-12-04T14:51:23+0800
     */
    public function AddMemberBankCard(){

    }

    /**
     * 编辑银行卡信息
     * @创建时间 2018-12-04T14:51:30+0800
     */
    public function EditMemberBankCard(){

    }

    /**
     * 删除银行卡信息
     * @创建时间 2018-12-04T14:51:38+0800
     */
    public function DelMemberBankCard(){

    }

    /**
     * 设置默认银行卡
     * @创建时间 2018-12-04T14:51:47+0800
     */
    public function SetDefautOfBankCard(){

    }


}

?>
