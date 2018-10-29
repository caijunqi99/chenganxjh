<?php

namespace app\vlinker\Controller;
use app\vlinker\Core\CommandSDK;
use think\Controller;
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

    public function __construct()
    {
        parent::__construct();
        

    }

}