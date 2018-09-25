<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Schoolinfo extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/school.lang.php');
    }

    public function index(){
        $school_id = input('param.school_id');
        if (empty($school_id)) {
            $this->error(lang('param_error'));
        }
        $model_school = Model('school');
        $school_array = $model_school->getSchoolInfo(array("schoolid"=>$school_id));
        $this->assign('school_array', $school_array);
        //学校类型
        $schooltype = db('schooltype')->where('sc_enabled','1')->select();
        $this->assign('schooltype', $schooltype);
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    public function lists() {
        $model_class = model('Classes');
        $condition = array();
        $condition['schoolid'] = input('param.school_id');
        $condition['isdel'] = 1;
        $class_list = $model_class->getClasslList($condition, 10);
        //学校类型
        $schooltype = db('schooltype')->where('sc_enabled','1')->select();
        $this->assign('schooltype', $schooltype);
        $this->assign('page', $model_class->page_info->render());
        $this->assign('class_list', $class_list);
        $this->setAdminCurItem('list');
        return $this->fetch();
    }

    public function camera() {
        $model_class = model('Classes');
        $condition = array();
        $condition['schoolid'] = input('param.school_id');
        $condition['isdel'] = 1;
        $class_list = $model_class->getClasslList($condition, 10);
        $this->assign('page', $model_class->page_info->render());
        $this->assign('class_list', $class_list);
        //学校类型
        $schooltype = db('schooltype')->where('sc_enabled','1')->select();
        $this->assign('schooltype', $schooltype);
        $this->setAdminCurItem('camera');
        return $this->fetch();
    }


    /**
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        $schoolid = $_GET['school_id'];
        $menu_array = array(
            array(
                'name' => 'index',
                'text' => '学校信息',
                'url' => url('Admin/Schoolinfo/index',array('school_id'=>$schoolid))
            ),
            array(
                'name' => 'list',
                'text' => '所属班级',
                'url' => url('Admin/Schoolinfo/lists',array('school_id'=>$schoolid))
            ),
            array(
                'name' => 'camera',
                'text' => '班级所属的摄像头',
                'url' => url('Admin/Schoolinfo/camera',array('school_id'=>$schoolid))
            ),
        );
        return $menu_array;
    }

}

?>
