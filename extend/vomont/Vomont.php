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
    }

    /**
     * 登录认证请求
     */
    public function SetLogin(){
        $SDK      = new CommandSDK();
        $msgid = $SDK::Login;
        $login = httpRequest($this->http."msgid={$msgid}&authkey={$this->authkey}&username={$this->username}&pswd={$this->pswd}");
        $login = json_decode($login,TRUE);
        $arrayName = array('accountid','companyname','encodekey','signature');
        foreach ($login as $k => $v) {
            if(in_array($k, $arrayName))$this->$k = $v;
        }
        //app使用的ip
        $this->aipurl = 'http://'.$login['vlinkerip'].':'.$login['vlinkerport'].'/?';
        return $login;
    }
    /**
     * app获取资源列表
     */
    public function SetPlay($accpuntid,$parentid){
        $SDK      = new CommandSDK();
        $msgid = $SDK::ResourcesLists;
        $request = httpRequest($this->http."msgid={$msgid}&authkey={$this->authkey}&accountid={$accpuntid}&restype=1&parentid={$parentid}");
        $data = json_decode($request,TRUE);
        return $data;
    }
    /**
     * app获取用户学校，班级资源列表
     */
    public function SetPlays($accpuntid,$schoolid,$classid){
        $SDK      = new CommandSDK();
        $msgid = $SDK::ResourcesLists;
        $request = httpRequest($this->http."msgid={$msgid}&authkey={$this->authkey}&accountid={$accpuntid}&restype=1&parentid={$schoolid}");
        $res = json_decode($request,TRUE);
        $requestcl = httpRequest($this->http."msgid={$msgid}&authkey={$this->authkey}&accountid={$accpuntid}&restype=1&parentid={$classid}");
        $data=json_decode($requestcl,TRUE);
        foreach($res['resources'] as $v){
                    $data['resources'][]=$v;
                }
        return $data;
    }
    /**
     * 后台获取播放资源列表
     */
    public function Resources($accpuntid,$channels){
        $SDK      = new CommandSDK();
        $msgid = $SDK::getAllResources;
        $request = httpRequest($this->http."msgid={$msgid}&authkey={$this->authkey}&accountid={$accpuntid}&pageid=1&pagecount=6&channels={$channels}");
        $data = json_decode($request,TRUE);
        return $data;
    }
    /**
     * 创建直播计划
    */
    public function Livestatus($accpuntid){
        $SDK      = new CommandSDK();
        $mgsid = $SDK::livestatus;
        $request = httpRequest($this->http.'msgid='.$mgsid.'&authkey='.$this->authkey.'&accountid='.$accpuntid.'&resid=16876545&streamtype=1&time={"weektime":[{"day":0,"begintime":0,"livelong":86400},{"day":1,"begintime":0,"livelong":86400},{"day":2,"begintime":0,"livelong":86400},{"day":3,"begintime":0,"livelong":86400},{"day":4,"begintime":0,"livelong":86400},{"day":5,"begintime":0,"livelong":86400},{"day":6,"begintime":0,"livelong":86400}]}');
        $data = json_decode($request,TRUE);
        return $data;
    }





    




    
}