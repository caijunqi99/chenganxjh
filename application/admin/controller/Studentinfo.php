<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Studentinfo extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/school.lang.php');
    }

    public function index(){
        $student_id = input('param.student_id');
        if (empty($student_id)) {
            $this->error(lang('param_error'));
        }
        $model_student = Model('Student');
        $student_array = $model_student->getStudentInfo(array("s_id"=>$student_id));
        $this->assign('student_array', $student_array);
        //学校类型
        $schooltype = db('schooltype')->where('sc_enabled','1')->select();
        $this->assign('schooltype', $schooltype);
        //学校名称
        $schoolname = db('school')->where('schoolid',$student_array['s_schoolid'])->find();
        $this->assign('schoolname', $schoolname['name']);
        //班级名称
        $classname = db('class')->where('classid',$student_array['s_classid'])->find();
        $this->assign('classname', $classname['classname']);
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    public function lists() {
        $model_student = model('Student');
        $condition = array();
        $condition['s_classid'] = input('param.class_id');
        $condition['s_del'] = 1;
        $student_list = $model_student->getStudentList($condition, 10);
        $this->assign('page', $model_student->page_info->render());
        $this->assign('student_list', $student_list);
//        //学校类型
//        $schooltype = db('schooltype')->where('sc_enabled','1')->select();
//        $this->assign('schooltype', $schooltype);
        $this->setAdminCurItem('lists');
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
        $student_id = $_GET['student_id'];
        $menu_array = array(
            array(
                'name' => 'index',
                'text' => '学生信息',
                'url' => url('Admin/Studentinfo/index',array('student_id'=>$student_id))
            ),
            array(
                'name' => 'lists',
                'text' => '绑定家长账号',
                'url' => url('Admin/Studentinfo/lists',array('student_id'=>$student_id))
            ),
            array(
                'name' => 'camera',
                'text' => '已购套餐',
                'url' => url('Admin/Studentinfo/camera',array('student_id'=>$student_id))
            ),

        );
        return $menu_array;
    }

}

?>
