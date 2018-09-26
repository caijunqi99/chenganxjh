<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Look extends AdminControl
{

    public function _initialize()
    {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/look.lang.php');
    }

    /**
     * @desc 摄像头管理
     * @author 郎志耀
     * @time 20180926
     */
    public function camera(){



        $this->setAdminCurItem('look');
        return $this->fetch('camera');
    }
    /**
     * @desc
     * @author
     * @time 20180926
     */
    public function download(){
        $this->setAdminCurItem('look');
        return $this->fetch('download');
    }

    /**
     * @desc 网络监控
     * @author 郎志耀
     * @time 20180926
     */
    public function monitor(){


        $this->setAdminCurItem('look');
        return $this->fetch('monitor');
    }


    /**
     * @desc 重温课堂回放
     * @author 郎志耀
     * @time 20180926
     */
    public function revisitClass(){


        $this->setAdminCurItem('look');
        return $this->fetch('revisitClass');
    }



}