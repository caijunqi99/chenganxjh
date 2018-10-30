<?php
/**
 * 物盟
 */
namespace vomont;

use vomont\Core\VomontTestA;
use vomont\Lib\VomontTestB;
class Vomont
{   


    private $appid;

    /**
     * 参数初始化
     * @param $appKey
     * @param $appSecret
     * @param string $format
     */
    public function __construct($format = 'json') {

        $this->appid='aaaa';
    }
    
    public function test(){
        return $this->appid;
    }

    public function testa(){
        $testa = new VomontTestA();
        return $testa->testa();
    }
    public function testb(){
        $testb = new VomontTestB();
        return $testb->testb();
    }
    
}