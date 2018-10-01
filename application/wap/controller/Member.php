<?php

namespace app\wap\controller;

use think\Lang;
use process\Process;

class Member extends MobileMall
{

    public function _initialize()
    {
        parent::_initialize();
        Lang::load(APP_PATH . 'wap\lang\zh-cn\login.lang.php');
    }



}

?>
