<?php

namespace app\wap\controller;

use think\Lang;
use process\Process;

class Login extends MobileMall
{

    public function _initialize()
    {
        parent::_initialize();
        Lang::load(APP_PATH . 'wap\lang\zh-cn\login.lang.php');
    }

    public function dologin(){
        $phone    = input('post.mobile');
        $password = input('param.password');
        $client   = input('param.client');
        $log_type = input('param.log_type');        
        $captcha  = input('post.captcha');
        $is_pass  = intval(input('post.is_pass'));
        switch ($log_type) {
            case 'sms_register':
                $log_type=1;
                $type='注册';
                break;
            case 'sms_login':
                $log_type=2;
                $type='登陆';
                break;
            case 'sms_password_reset':
                $log_type=3;
                $type='重置密码';
                break;            
            default:
                output_error('验证类型错误!');
                break;
        }
        if (empty($phone) || !in_array($client, $this->client_type_array)) {
            output_error($type.'失败!',array('type'=>input('post.log_type')));
        }
        if (!preg_match('/^0?(13|15|17|18|14)[0-9]{9}$/i', $phone)) {//根据会员名没找到时查手机号
            output_error('请输入正确的手机号码！',array('type'=>input('post.log_type')));
            
        }
        $model_member = Model('member');
        $array = array();
        $array['member_mobile'] = $phone;     
        if ($is_pass==2) {
            if (empty($captcha))output_error('请输入正确的验证码',array('type'=>input('post.log_type')));
            $state = 'true';
            $condition = array();
            $condition['log_phone'] = $phone;
            $condition['log_captcha'] = $captcha;
            $condition['log_type'] = $log_type;
            $model_sms_log = Model('smslog');
            $sms_log = $model_sms_log->getSmsInfo($condition);
            // output_error($condition);
            if(empty($sms_log) || ($sms_log['add_time'] < TIMESTAMP-1800)) {//半小时内进行验证为有效
                output_error('动态码错误或已过期，重新输入',array('type'=>input('post.log_type'),'t'=>1));
            }
        }
        if ($is_pass == 1) {
            if(empty($password))output_error('非法登陆',array('type'=>input('post.log_type') ) );
        }
        $member_info = $model_member->getMemberInfo($array,'member_password,member_name,member_id');
        if (!$member_info) {//注册
            // $this->register($register_info)
            $member = array();
            $member['member_name'] = $phone;
            $member['member_password'] = $password;
            $member['member_mobile'] = $phone;
            $member['member_email'] = '';
            $member['member_mobile_bind'] = 1;
            $result = $model_member->addMember($member);
            if (!$result) {                
                output_error('注册失败',array('type'=>input('post.log_type')));
            }else{
                //添加会员积分--前期可以不使用
                // if (config('points_isuse')) {
                //     Model('points')->savePointsLog('regist', array(
                //         'pl_memberid' => $insert_id, 'pl_membername' => $register_info['member_name']
                //     ), false);
                //     //添加邀请人(推荐人)会员积分
                //     $inviter_name = db('member')->where('member_id', $member_info['inviter_id'])->find();
                //     if ($inviter_name) {
                //         Model('points')->savePointsLog('inviter', array(
                //             'pl_memberid' => $register_info['inviter_id'], 'pl_membername' => $inviter_name['member_name'],
                //             'invited' => $member_info['member_name']
                //         ));
                //     }
                // }
            }
        }else{//登陆
            if ($password){
                if ($member_info['member_password'] != md5($password)) {//密码对比
                    output_error('密码填写错误！',array('type'=>input('post.log_type')));
                }
            }            
        }
        $member = $model_member->getMemberInfo(array('member_mobile' => $phone));
        if (is_array($member) && !empty($member)) {
            $token = $this->_get_token($member['member_id'], $member['member_name'], $client);
            if ($token) {
                $logindata = array(
                    'member_mobile' => $member['member_mobile'], 'uid' => $member['member_id'], 'key' => $token
                    //头像，用户名，副账号数量，本身账号属性，角色属性， 是否已绑定孩子，
                );
                output_data($logindata);
            }else {
                output_error('登录失败');
            }
        }else{
            output_error('网络错误！');
        }

    }


    public function password_reset(){
        if(config('sms_password') != 1) {
            output_error('系统没有开启手机找回密码功能','','error');
        }
        $phone       = input('post.mobile');
        $password    = input('param.password');
        $re_password = input('param.re_password');
        $client      = input('param.client');
        $log_type    = input('param.log_type');        
        switch ($log_type) {
            case 'sms_password':
                $log_type=3;
                $type='重置密码';
                break;            
            default:
                output_error('验证类型错误!');
                break;
        }
        $model_member = Model('member');
        $member = $model_member->getMemberInfo(array('member_mobile'=> $phone));
        if(!empty($member)) {
            $new_password = md5($_POST['password']);
            $model_member->editMember(array('member_id'=> $member['member_id']),array('member_password'=> $new_password));
            $token = $this->_get_token($member['member_id'], $member['member_name'], $client);
            if($token) {
                $logindata = array(
                    'member_mobile' => $member['member_mobile'], 'uid' => $member['member_id'], 'key' => $token
                );
                output_data($logindata);
            }else {
                output_error('网络错误');
            }
        }else{
           output_error('没有此用户!'); 
        }

        output_error($state);
    }
    public function get_inviter(){
        $inviter_id=intval(input('get.inviter_id'));
        $member=db('member')->where('member_id',$inviter_id)->field('member_id,member_name')->find();
        
        output_data(array('member' => $member));
    }
    /**
     * 登录生成token
     */
    private function _get_token($member_id, $member_name, $client)
    {
        $model_mb_user_token = Model('mbusertoken');

        //重新登录后以前的令牌失效
        
        $condition = array();
        $condition['member_id'] = $member_id;
        $condition['client_type'] = $client;
        $model_mb_user_token->delMbUserToken($condition);
        //生成新的token
        $mb_user_token_info = array();
        $token = md5($member_name . strval(TIMESTAMP) . strval(rand(0, 999999)));
        $mb_user_token_info['member_id'] = $member_id;
        $mb_user_token_info['member_name'] = $member_name;
        $mb_user_token_info['token'] = $token;
        $mb_user_token_info['login_time'] = TIMESTAMP;
        $mb_user_token_info['client_type'] = $client;

        $result = $model_mb_user_token->addMbUserToken($mb_user_token_info);

        if ($result) {
            return $token;
        }
        else {
            return null;
        }
    }

 
}

?>
