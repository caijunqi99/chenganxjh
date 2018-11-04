<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Classesinfo extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/school.lang.php');
    }

    public function index(){
        $class_id = input('param.class_id');
        if (empty($class_id)) {
            $this->error(lang('param_error'));
        }
        $model_class = Model('Classes');
        $class_array = $model_class->getClassInfo(array("classid"=>$class_id));
        $this->assign('class_array', $class_array);
        //学校名称
        $schoolname = db('school')->where('schoolid',$class_array['schoolid'])->find();
        $this->assign('schoolname', $schoolname['name']);
        //学校类型
        $schooltype = db('schooltype')->where('sc_enabled','1')->select();
        $typeids = explode(',',$schoolname['typeid']);
        foreach ($schooltype as $k=>$v){
            foreach ($typeids as $key=>$item){
                if($item ==$v['sc_id']){
                    $type[$item] = $v['sc_type'];
                }
            }
        }
        $this->assign('schooltype', $type);
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    public function lists() {
        $model_student = model('Student');
        $condition = array();
        $condition['s_classid'] = input('param.class_id');
        $condition['s_del'] = 1;
        $student_list = $model_student->getStudentList($condition, 10);
        //家长主账号
        foreach ($student_list as $key=>$item) {
            $memberinfo = db('member')->where(array('member_id'=>$item['s_ownerAccount']))->find();
            $student_list[$key]['member'] = $memberinfo['member_name'];
        }
        $this->assign('page', $model_student->page_info->render());
        $this->assign('student_list', $student_list);
        $this->setAdminCurItem('lists');
        return $this->fetch();
    }

    public function camera() {
        $class_id = input('param.class_id');
        $model_camera = model('Camera');
        $where['classid']=$class_id;
        $data=$this->_conditions($where);
        print_r($data);die;
        $cameraList = $model_camera->getCameraList($data, 10);
        $this->assign('page', $model_camera->page_info->render());
        $this->assign('camera_list', $cameraList);
        $this->setAdminCurItem('camera');
        return $this->fetch();
    }

    /**
     * 摄像头查询过滤
     * @创建时间   2018-11-03T00:39:28+0800
     * @param  [type]                   $where [description]
     * @return [type]                          [description]
     */
    public function _conditions($where){
        $res = array();
        $name = false;
        if (isset($where['classid']) && !empty($where['classid']) ) {
            $class = $this->getResGroupIds(array('classid'=>$where['classid']));
            if ($class) {
                $res=array_merge($res, $class);
            }
            unset($where);
            $name = 'true';
        }
        if ($name == 'true') {
            $condition['parentid'] = array('in',$res);
        }
        return $condition;
    }
    /**
     * 查询学校和班级摄像头
     * @创建时间   2018-11-03T00:39:48+0800
     * @param  [type]                   $where [description]
     * @return [type]                          [description]
     */
    public function getResGroupIds($where){
        $School = model('School');
        $Class = model('Classes');
        $classname = '';
        if (isset($where['classid']) && !empty($where['classid']) ) {
            $classname = $where['classid'];
            unset($where['classid']);
        }
        $where['res_group_id'] =array('gt',0);
//        $Schoollist = $School->getAllAchool($where,'res_group_id');
//        if (!empty($classid)) {
//            $where['classid'] = $classid;
//        }
        $res = array();
        $Classlist = $Class->getAllClasses($where,'res_group_id');
        print_r($Classlist);die;
//        $sc_resids=array_column($Schoollist, 'res_group_id');
//        if ($sc_resids) {
//            array_push($res, $sc_resids);
//        }
        $cl_resids=array_column($Classlist, 'res_group_id');
        if ($cl_resids) {
            array_push($res, $cl_resids);
        }
        //$ids = array_merge($sc_resids,$cl_resids);
        $ids = $cl_resids;
        if ($ids) {
            return $ids;
        }else{
            return $res;
        }
    }


    /**
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        $class_id = $_GET['class_id'];
        $menu_array = array(
            array(
                'name' => 'index',
                'text' => '班级信息',
                'url' => url('Admin/Classesinfo/index',array('class_id'=>$class_id))
            ),
            array(
                'name' => 'lists',
                'text' => '班级所属的学生',
                'url' => url('Admin/Classesinfo/lists',array('class_id'=>$class_id))
            ),
            array(
                'name' => 'camera',
                'text' => '班级所属的摄像头',
                'url' => url('Admin/Classesinfo/camera',array('class_id'=>$class_id))
            ),

        );
        return $menu_array;
    }

}

?>
