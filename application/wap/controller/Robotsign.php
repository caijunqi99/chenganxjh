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


    public function MemberPush(){
        //uid: 14972856178
        //registrationID:141fe1da9e8ea882e37
        $Jpush = model('Jpush');
        $Jpush->JPushInit();
        $Jpush->MemberPush();
        
        p($Jpush);exit;
        $platform = array('ios', 'android');
        $push_payload = $client->push()
            ->setPlatform($platform)
            ->addRegistrationId('141fe1da9e8ea882e37')
            ->setNotificationAlert('哈喽，保甲勇士！');
        try {
            $response = $push_payload->send();
            p(111);
            p($response);
        } catch (\JPush\Exceptions\APIConnectionException $e) {
            p(2222);
            p($e);
        } catch (\JPush\Exceptions\APIRequestException $e) {
            p(3333);
            p($e);
        }
    }

    public function index1(){
        $AppKey = '2e49a22da063884527d82e1c';
        $MaterSecret ='e18529474930976be6ef2007';
        $client=new Client($AppKey,$MaterSecret);
        
        $platform = array('ios', 'android');
        $ios_notification = array(
            'content-available' => true,
            'category' => 'jiguang',
            'extras' => array(
                'pro_id' => $pro_id,
                'content'=>$content,
            ),
        );
        $android_notification = array(
            'extras' => array(
                'pro_id' => $pro_id,
                'content'=>$content,
            ),
        );
        
        try {
            if($alias!=""){
                $response = $client
                ->push()
                ->setPlatform($platform)
                ->addAlias($alias)
                ->iosNotification($alert, $ios_notification)
                ->androidNotification($alert, $android_notification)
                ->send();

            }elseif($tag!=""){
                $response = $client
                ->push()
                ->setPlatform($platform)
                ->addTag($tag)
                ->iosNotification($alert, $ios_notification)
                ->androidNotification($alert, $android_notification)
                ->send();
            }else{
                $response = $client
                ->push()
                ->setPlatform($platform)
                ->addAllAudience()
                ->iosNotification($alert, $ios_notification)
                ->androidNotification($alert, $android_notification)
                ->send();
            }
            
            
            
        } catch (\JPush\Exceptions\APIConnectionException $e) {
            //try something here
          
           print $e;
        } catch (\JPush\Exceptions\APIRequestException $e) {
            // try something here
        
            print $e;
           
        }
        
       return true;
    }

    

 
}

?>
