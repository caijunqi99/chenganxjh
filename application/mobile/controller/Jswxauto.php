<?php
/**
 * 小程序处理
 */

namespace app\mobile\controller;


class Jswxauto extends MobileMall
{
    public $jsapi;
    public $jsappsecret;
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }
    //小程序获取code

    public function getsessionkey()
    {
        $code = input('code');
        if (!empty($code)) {
            $this->jsapi = 'wx7c160594b6ce37ec';
            $this->jsappsecret = '77d9b903ef5c6cd1a3ed0d151b4bf4e8';
            $url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . $this->jsapi . "&secret=" . $this->jsappsecret . "&js_code=" . $code . "&grant_type=authorization_code";
            $res = json_decode($this->httpGet($url), true);
            if (!isset($res['unionid'])) {
                $res['unionid'] = $res['openid'];
            }
            $sevalue=$res['openid'].'|'.$res['session_key'].'|'.$res['unionid'];
            $sessionkey=encrypt($sevalue,$this->jsapi);
            output_data(array('sessionkey' => $sessionkey));
        }
    }

    /**
     *微信小程序登录
     *unionid
     */
    public function jswxlogin()
    {
        $post = $_POST;
        $this->jsapi = 'wx7c160594b6ce37ec';
        $data=decrypt($post['sessionkey'],$this->jsapi);
        list($openid,$session_key,$unionid)=explode('|',$data);
        $res=array(
            'openid' =>$openid,
            'unionid' =>$unionid,
            'nickName' =>$post['nickName'],
            'avatarUrl' =>$post['avatarUrl']
        );
        $model_member = Model('member');
        $member_info = $model_member->getMemberInfo(array('weixin_unionid' => $res['unionid']));
        if (!empty($member_info)) {
            //更新信息
            if (!empty($res['nickName']) && $res['nickName'] != $member_info['member_name']) {
                $model_member->editMember(array('weixin_unionid' => $res['unionid']), array('member_name' => $res['nickName']));
            }
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], 'jswechat');
            $logindata = array(
                'username' => $member_info['member_name'], 'userid' => $member_info['member_id'], 'key' => $token
            );
            session('username', $member_info["member_name"], time() + 3600 * 24, '/');
            session('key', $token, time() + 3600 * 24, '/');
            output_data($logindata);
        }else{
            $this->register($res);
        }
    }

    private function register($user_info)
    {
        $unionid = $user_info['unionid'];
        $nickname = $user_info['nickName'];
        if (!empty($unionid)) {
            $rand = rand(100, 899);
            if (empty($nickname))
                $nickname = 'name_' . $rand;
            if (strlen($nickname) < 3)
                $nickname = $nickname . $rand;
            $member_name = $nickname;
            $model_member = Model('member');
            $member_info = $model_member->getMemberInfo(array('member_name' => $member_name));
            $password = rand(100000, 999999);
            $member = array();
            $member['member_password'] = $password;
            $member['member_email'] = '';
            $member['weixin_unionid'] = $unionid;
            $member['member_wxopenid'] = $user_info['openid'];

            $weixin_info = array();
            $weixin_info['unionid'] = $user_info['unionid'];
            $weixin_info['nickname'] = $user_info['nickName'];
            $weixin_info['openid'] = $user_info['openid'];
            $member['weixin_info'] = serialize($weixin_info);

            if (empty($member_info)) {
                $member['member_name'] = $member_name;
                $result = $model_member->addMember($member);
            }
            else {
                for ($i = 1; $i < 999; $i++) {
                    $rand += $i;
                    $member_name = $nickname . $rand;
                    $member_info = $model_member->getMemberInfo(array('member_name' => $member_name));
                    if (empty($member_info)) {//查询为空表示当前会员名可用
                        $member['member_name'] = $member_name;
                        $result = $model_member->addMember($member);
                        break;
                    }
                }
            }
            $headimgurl = $user_info['avatarUrl'];//用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像）
            $headimgurl = substr($headimgurl, 0, -1) . '132';
            $avatar = @copy($headimgurl, BASE_UPLOAD_PATH . '/' . ATTACH_AVATAR . "/avatar_$result.jpg");
            if ($avatar) {
                $model_member->editMember(array('member_id' => $result), array('member_avatar' => "avatar_$result.jpg"));
            }
            $member = $model_member->getMemberInfo(array('member_name' => $member_name));
            if (!empty($member)) {
                    $token = $this->_get_token($result, $member_name, 'jswechat');
                    $logindata = array(
                        'username' => $member_info['member_name'], 'userid' => $member_info['member_id'], 'key' => $token
                    );
                    output_data($logindata);
                }
                else {
                    output_error('网络通信错误');
                }
            }
    }

    /**
     * 登录生成token
     */
    private function _get_token($member_id, $member_name, $client)
    {
        $model_mb_user_token = Model('mbusertoken');
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