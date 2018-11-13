<?php

namespace app\common\model;
vendor('jpush.autoload');
use JPush\Client;
use think\Model;
class Jpush extends Model {

    private $AppKey;
    private $MaterSecret;
    private $Client;

    public function __construct()
    {
        $this->AppKey = '2e49a22da063884527d82e1c';
        $this->MaterSecret ='e18529474930976be6ef2007';
        
    }

    public function JPushInit(){
        $this->Client =new Client($this->AppKey,$this->MaterSecret);
    }

    public function MemberPush($platform='all'){
        if ($platform=='all') {
            $platform= array('ios', 'android');
        }
        // $ios_notification = array(
        //     'content-available' => true,
        //     'category' => 'jiguang',
        //     'extras' => array(
        //         'pro_id' => $pro_id,
        //         'content'=>$content,
        //     ),
        // );
        // $android_notification = array(
        //     'extras' => array(
        //         'pro_id' => $pro_id,
        //         'content'=>$content,
        //     ),
        // );

        $RegistrationId = db('memberjpush')->where('member_id',$member_id)->value('registrationID');
        $push_payload = $this->Client
                        ->push()
                        ->setPlatform($platform)
                        ->addAllAudience()
                        ->setNotificationAlert('新系统正在研发中，敬请期待！');
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
    
    
}

?>
