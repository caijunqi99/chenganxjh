<?php

namespace app\wap\controller;

use think\Lang;
class Robotsign extends MobileMall
{
    public function _initialize()
    {
        parent::_initialize();
        // Lang::load(APP_PATH . 'wap\lang\zh-cn\login.lang.php');
    }

    public function memberpush(){

        //使用极光推送
        $md= model('Jpush');
        $md->JPushInit();
        /**
         * member_id  用户id
         * alert 提示信息
         * title 提醒标题
         */
        $input = input();
        $phone = $input['member_mobile'];
        $member_id = db('member')->where('member_mobile',$phone)->value('member_id');
        $alert = '推送测试abcdefg';
        $pushResult = $md->MemberPush($member_id,$alert,$title='打卡提醒');

        p($pushResult);
    }

    
    

 
}

?>
