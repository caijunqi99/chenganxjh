<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Revisitclass extends AdminControl
{
    public function _initialize()
    {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/look.lang.php');
        //获取省份
        $province = db('area')->field('area_id,area_parent_id,area_name')->where('area_parent_id=0')->select();
        //获取学校
        $school = db('school')->field('schoolid,name')->select();


        $this->assign('school',$school);
        $this->assign('province',$province);
    }

    /**
     * @desc 重温课堂回放
     * @author 郎志耀
     * @time 20180926
     */
    public function revisitclass(){


        $this->setAdminCurItem('revisitclass');
        return $this->fetch('revisitclass');
    }


}