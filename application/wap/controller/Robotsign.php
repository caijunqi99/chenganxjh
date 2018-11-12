<?php

namespace app\wap\controller;
vendor('jpush.autoload');

use think\Lang;
use JPush\Client;
class Robotsign extends MobileMall
{

    public function _initialize()
    {
        parent::_initialize();
        // Lang::load(APP_PATH . 'wap\lang\zh-cn\login.lang.php');
    }

    public function index(){
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
