<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Student extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/school.lang.php');
    }

    public function index() {
        $model_student = model('Student');
        $condition = array();

//        $classname = input('param.school_index_classname');//学校名称
//        if ($classname) {
//            $condition['classname'] = array('like', "%" . $classname . "%");
//        }
//        $schooltype = input('param.school_type');//学校类型
//        if ($schooltype) {
//            $condition['common_phone'] = array('like', "%" . $schoolphone . "%");
//        }
//        $schoolusername = input('param.schoolusername');//联系/负责人
//        if ($schoolusername) {
//            $condition['username'] = array('like', "%" . $schoolusername . "%");
//        }
//        $school_type = input('param.school_type');//学校类型
//        if ($school_type) {
//            $condition['typeid'] = $school_type;
//        }
//        $area_id = input('param.area_id');//地区
//        if($area_id){
//            $region_info = db('area')->where('area_id',$area_id)->find();
//            if($region_info['area_deep']==1){
//                $condition['school_provinceid'] = $area_id;
//            }elseif($region_info['area_deep']==2){
//                $condition['school_cityid'] = $area_id;
//            }else{
//                $condition['school_areaid'] = $area_id;
//            }
//        }
//        $query_start_time = input('param.query_start_time');
//        $query_end_time = input('param.query_end_time');
//        if ($query_start_time || $query_end_time) {
//            $condition['createtime'] = array('between', array($query_start_time, $query_end_time));
//        }
        $condition['s_del'] = 1;
        $student_list = $model_student->getStudentList($condition, 10);
        // 地区信息
//        $region_list = db('area')->where('area_parent_id','0')->select();
//        $this->assign('region_list', $region_list);
//        $address = array(
//            'true_name' => '',
//            'area_id' => '',
//            'city_id' => '',
//            'address' => '',
//            'tel_phone' => '',
//            'mob_phone' => '',
//            'is_default' => '',
//            'area_info'=>''
//        );
//        $this->assign('address', $address);

        $schooltype = db('schooltype')->where('sc_enabled','1')->select();
        $this->assign('schooltype', $schooltype);

        foreach ($student_list as $k=>$v){
            $schooltype = db('schooltype')->where('sc_id',$v['s_sctype'])->find();
            $student_list[$k]['typename'] = $schooltype['sc_type'];
            $classinfo = db('class')->where('classid',$v['s_classid'])->find();
            $student_list[$k]['classname'] = $classinfo['classname'];
            $school = db('school')->where('schoolid',$v['s_schoolid'])->find();
            $student_list[$k]['schoolname'] = $school['name'];
        }
        $this->assign('page', $model_student->page_info->render());
        $this->assign('student_list', $student_list);
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    public function add() {

        if (!request()->isPost()) {
            //地区信息
            $region_list = db('area')->where('area_parent_id','0')->select();
            $this->assign('region_list', $region_list);
            $address = array(
                'true_name' => '',
                'area_id' => '',
                'city_id' => '',
                'address' => '',
                'tel_phone' => '',
                'mob_phone' => '',
                'is_default' => '',
                'area_info'=>''
            );
            $this->assign('address', $address);
            //学校类型
            $schooltype = db('schooltype')->where('sc_enabled','1')->select();
            $this->assign('schooltype', $schooltype);
            $this->setAdminCurItem('add');
            return $this->fetch();
        } else {
            $model_classes = model('Classes');
            $data = array(
                'school_areaid' => input('post.area_id'),
                'school_region' => input('post.area_info'),
                'typeid' => input('post.school_type'),
                'schoolid' => input('post.order_state'),
                'classname' => input('post.school_class_name'),
                'desc' => input('post.class_desc'),
                'createtime' => date('Y-m-d H:i:s',time())
            );
            $city_id = db('area')->where('area_id',input('post.area_id'))->find();
            $data['school_cityid'] = $city_id['area_parent_id'];
            $province_id = db('area')->where('area_id',$city_id['area_parent_id'])->find();
            $data['school_provinceid'] = $province_id['area_parent_id'];
            //验证数据  END
            $result = $model_classes->addClasses($data);
            if ($result) {
                $this->success(lang('school_class_add_succ'), 'Classes/index');
            } else {
                $this->error(lang('school_class_add_fail'));
            }
        }
    }

    public function edit() {
        $class_id = input('param.class_id');
        if (empty($class_id)) {
            $this->error(lang('param_error'));
        }
        $model_class = Model('classes');
        if (!request()->isPost()) {
            $condition['classid'] = $class_id;
            $class_array = $model_class->getClassInfo($condition);
            //地区信息
            $region_list = db('area')->where('area_parent_id','0')->select();
            $this->assign('region_list', $region_list);
            $address = array(
                'true_name' => '',
                'area_id' => '',
                'city_id' => '',
                'address' => '',
                'tel_phone' => '',
                'mob_phone' => '',
                'is_default' => '',
                'area_info'=>''
            );
            $this->assign('address', $address);
            $this->assign('class_array', $class_array);
            //学校类型
            $schooltype = db('schooltype')->where('sc_enabled','1')->select();
            $this->assign('schooltype', $schooltype);
            //学校名称
            $schoolname = db('school')->where('areaid',$class_array['school_areaid'])->select();
            $this->assign('schoolname', $schoolname);
            $this->setAdminCurItem('edit');
            return $this->fetch();
        } else {
            if(input('post.order_state')){
                $schoolname = input('post.order_state');
            }
            $data = array(
                'school_areaid' => input('post.area_id'),
                'school_region' => input('post.area_info'),
                'typeid' => input('post.school_type'),
                'schoolid' => isset($schoolname)?input('post.order_state'):input('post.school_name'),
                'classname' => input('post.school_class_name'),
                'desc' => input('post.class_desc'),
                'createtime' => date('Y-m-d H:i:s',time())
            );
            $city_id = db('area')->where('area_id',input('post.area_id'))->find();
            $data['school_cityid'] = $city_id['area_parent_id'];
            $province_id = db('area')->where('area_id',$city_id['area_parent_id'])->find();
            $data['school_provinceid'] = $province_id['area_parent_id'];
            //验证数据  END
            $result = $model_class->editClass($data,array('classid'=>$class_id));
            if ($result) {
                $this->success('编辑成功', 'Classes/index');
            } else {
                $this->error('编辑失败');
            }
        }
    }

    public function view(){
        $class_id = input('param.class_id');
        if (empty($class_id)) {
            $this->error(lang('param_error'));
        }
        $model_class = Model('classes');
        $class_array = $model_class->getClassInfo(array("classid"=>$class_id));
        $this->assign('class_array', $class_array);
        //学校类型
        $schooltype = db('schooltype')->where('sc_enabled','1')->select();
        $this->assign('schooltype', $schooltype);
        //学校名称
        $schoolinfo = db('school')->where(array("schoolid"=>$class_array['schoolid']))->find();
        $this->assign('schoolinfo', $schoolinfo);
        $this->setAdminCurItem('view');
        return $this->fetch();
    }

    /**
     * ajax操作
     */
    public function ajax() {
        $branch = input('param.branch');

        switch ($branch) {
            /**
             * 验证学校名是否重复
             */
            case 'check_user_name':
                $school_member = Model('school');
                $condition['name'] = input('param.school_name');
                $condition['schoolid'] = array('neq', intval(input('get.school_id')));
                $list = $school_member->getSchoolInfo($condition);
                if (empty($list)) {
                    echo 'true';
                    exit;
                } else {
                    echo 'false';
                    exit;
                }
                break;
        }
    }

    /**
     * 重要提示，删除会员 要先确定删除店铺,然后删除会员以及会员相关的数据表信息。这个后期需要完善。
     */
    public function drop() {
        $student_id = input('param.student_id');
        if (empty($student_id)) {
            $this->error(lang('param_error'));
        }
        $model_student = Model('student');
        $result = $model_student->editStudent(array('s_del'=>2),array('s_id'=>$student_id));
        if ($result) {
            $this->success(lang('ds_common_del_succ'), 'Student/index');
        } else {
            $this->error('删除失败');
        }
    }

    public function fand_schooltype(){
        $sc_id = intval(input('post.sc_id'));
        $Schooltype = model('Schooltype');
        $School = model('School');
        $schoolInfo = $School->getSchoolById($sc_id);
        if($schoolInfo){
            $Schooltype_list = $Schooltype->get_sctype_List(array('sc_id'=>array('in',$schoolInfo['typeid'])));
            exit(json_encode($Schooltype_list));
        }








    }

    /**
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'index',
                'text' => '管理',
                'url' => url('Admin/Student/index')
            ),
        );

        if (request()->action() == 'add' || request()->action() == 'index') {
            $menu_array[] = array(
                'name' => 'add',
                'text' => '添加学生',
                'url' => url('Admin/Student/add')
            );
        }
        if (request()->action() == 'edit') {
            $menu_array[] = array(
                'name' => 'edit',
                'text' => '编辑',
                'url' => url('Admin/Student/edit')
            );
        }
        if (request()->action() == 'view') {
            $menu_array[] = array(
                'name' => 'view',
                'text' => '查看',
                'url' => url('Admin/Student/view')
            );
        }
        return $menu_array;
    }

}

?>
