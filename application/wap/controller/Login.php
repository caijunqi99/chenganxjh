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
        $password = input('post.password');
        $client   = input('post.client');
        $log_type = input('post.log_type');        
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
            default:
                output_error('验证类型错误!');
                break;
        }
        if (empty($phone) || !in_array($client, $this->client_type_array)) {
            output_error($type.'失败!',array('type'=>input('post.log_type')));
        }
        if (!preg_match('/^0?(13|15|16|17|18|14)[0-9]{9}$/i', $phone)) {//根据会员名没找到时查手机号
            output_error('请输入正确的手机号码！',array('type'=>input('post.log_type')));
            
        }
        $model_member = Model('member');
        $array = array();
        $array['member_mobile'] = $phone;     
        if ($is_pass==2) { // 验证码判断
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
            $member['member_password'] = md5(trim($password));;
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
            if ($password && $is_pass == 1){
                if ($member_info['member_password'] != md5(trim($password))) {//密码对比
                    output_error('密码填写错误！',array('type'=>input('post.log_type')));
                }
            }            
        }
        $member = $model_member->getMemberInfo(array('member_mobile' => $phone));
        

        if (is_array($member) && !empty($member)) {
            $token = $this->_get_token($member['member_id'], $member['member_name'], $client);
            if ($token) {
                $logindata = array();
                $logindata['key']=$token;
                if(!empty($member['member_avatar'])){
                    $logindata['member_avatar'] = $member['member_avatar'];
                    $logindata['rel_member_avatar'] = UPLOAD_SITE_URL.$member['member_avatar'];
                }else{
                    $logindata['member_avatar'] = '/' . ATTACH_COMMON . '/' . 'default_user_portrait.png';
                    $logindata['rel_member_avatar'] = UPLOAD_SITE_URL . '/' . ATTACH_COMMON . '/' . 'default_user_portrait.png';
                }
                $logindata['user_name'] = $member['member_name'];
                $logindata['member_mobile'] = $member['member_mobile'];
                $logindata['member_identity'] = $member['member_identity'];
                $logindata['uid'] = $member['member_id'];                
                $logindata['is_owner'] = $member['is_owner']==0?true:false;                
                $logindata['viceAccount'] = $model_member->getMemberViceAccount($member['member_id']); 

                // $logindata['favorites_store'] = Model('favorites')->getStoreFavoritesCountByMemberId($this->member_info['member_id']);
                // $logindata['favorites_goods'] = Model('favorites')->getGoodsFavoritesCountByMemberId($this->member_info['member_id']);
                // $logindata['student'] = $Student->getMemberStudentInfoById('m.member_id='.$member['member_id']);

                // $logindata['sql'] = $Student->getLastSql();
                
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
        $password    = input('post.password');
        $re_password = input('post.re_password');
        $client      = input('post.client');
        $log_type    = input('post.log_type');        
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
            $new_password = md5($password);
            $model_member->editMember(array('member_id'=> $member['member_id']),array('member_password'=> $new_password));
            $token = $this->_get_token($member['member_id'], $member['member_name'], $client);
            if($token) {
                $logindata = array();
                $logindata['key']=$token;
                $logindata['avator'] = getMemberAvatarForID($member['member_id']);
                $logindata['user_name'] = $member['member_name'];
                $logindata['member_mobile'] = $member['member_mobile'];
                $logindata['member_identity'] = $member['member_identity'];
                $logindata['uid'] = $member['member_id'];                
                $logindata['is_owner'] = $member['is_owner']==0?true:false;                
                $logindata['viceAccount'] = $model_member->getMemberViceAccount($member['member_id']); 
                
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
        // $condition['client_type'] = $client;
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
