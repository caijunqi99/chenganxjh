<?php

namespace app\vlinker\Controller;
use think\Controller;
header("Access-Control-Allow-Origin: *");
class BaseController extends Controller
{
    const POST = 'POST';
    protected $code = null;
    protected $return = null;
    protected $base = null;
    protected $key = null;
    protected $param = null;
    protected $method = null;
    protected $sdk = null;
    protected $vmid= null;

    public function _initialize()
    {

        parent::_initialize();
        
        

    }

}