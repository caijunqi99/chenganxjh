<?php
/**
 * 物盟
 */
namespace vomont;

use vomont\Core\SDKConfig;
use vomont\Core\CommandSDK;
class Vomont
{   
    private $http;
    private $aipurl;
    protected $authkey;
    protected $username;
    protected $pswd;
    public $SDK = null;

    /**
     * 参数初始化
     * @param $appKey
     * @param $appSecret
     * @param string $format
     */
    public function __construct() {
        $this->http     = SDKConfig::HTTP_URL;
        $this->authkey  = SDKConfig::KEY;
        $this->username = SDKConfig::VomontUsername;
        $this->pswd     = SDKConfig::VomontPswd;
        $this->SDK      = new CommandSDK();
    }

    /**
     * 登录认证请求
     */
    public function SetLogin(){
        $msgid = $this->SDK::Login;
        $login = httpRequest($this->http."msgid={$msgid}&authkey={$this->authkey}&username={$this->username}&pswd={$this->pswd}");
        $login = json_decode($login,TRUE);
        $arrayName = array('accountid','companyname','encodekey','signature');
        foreach ($login as $k => $v) {
            if(in_array($k, $arrayName))$this->$k = $v;
        }
        //app使用的ip
        $this->aipurl = 'http://'.$login['vlinkerip'].':'.$login['vlinkerport'].'/?';
        return TRUE;
    }
    




    
}