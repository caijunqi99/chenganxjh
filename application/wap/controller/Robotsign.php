<?php

namespace app\wap\controller;
vendor('jpush.autoload');

use think\Lang;
use JPush\Client;
class Robotsign extends MobileMall
{
    public function _initialize()
    {
        $this->AppKey = '2e49a22da063884527d82e1c';
        $this->MaterSecret ='e18529474930976be6ef2007';
        parent::_initialize();
        // Lang::load(APP_PATH . 'wap\lang\zh-cn\login.lang.php');
    }

    public function memberpush(){

        $md= model('Jpush');
        $md->JPushInit();
        /**
         * member_id  用户id
         * alert 提示信息
         * title 提醒标题
         */
        $pushResult = $md->MemberPush($member_id,$alert,$title='打卡提醒');

        p($pushResult);
    }

    
    

 
}

?>
