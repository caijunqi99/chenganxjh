<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Classes extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/school.lang.php');
        Lang::load(APP_PATH . 'admin/lang/zh-cn/admin.lang.php');
        //获取当前角色对当前子目录的权限
        $class_name = strtolower(end(explode('\\',__CLASS__)));
        $perm_id = $this->get_permid($class_name);
        $this->action = $action = $this->get_role_perms(session('admin_gid') ,$perm_id);
        $this->assign('action',$action);
    }

    public function index() {
        if(session('admin_is_super') !=1 && !in_array(4,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
        $model_class = model('Classes');
        $condition = array();
        $admininfo = $this->getAdminInfo();
        if($admininfo['admin_id']!=1){
            $admin = db('admin')->where(array('admin_id'=>$admininfo['admin_id']))->find();
            $condition['a.admin_company_id'] = $admin['admin_company_id'];
        }
        $classname = input('param.school_index_classname');//学校名称
        if ($classname) {
            $condition['classname'] = array('like', "%" . $classname . "%");
        }
        $school_type = input('param.school_type');//学校类型
        if ($school_type) {
            $condition['typeid'] = $school_type;
        }
        $class_name = input('param.class_name');
        if ($class_name) {
            $condition['classid'] = $class_name;
        }
        $school_name = input('param.school_name');
        if ($school_name) {
            $condition['schoolid'] = $school_name;
        }
        $area_id = input('param.area_id');//地区
        if($area_id){
            $region_info = db('area')->where('area_id',$area_id)->find();
            if($region_info['area_deep']==1){
                $condition['school_provinceid'] = $area_id;
            }elseif($region_info['area_deep']==2){
                $condition['school_cityid'] = $area_id;
            }else{
                $condition['school_areaid'] = $area_id;
            }
        }
        $condition['isdel'] = 1;
        $class_list = $model_class->getClasslList($condition, 15);
        // 地区信息
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
        $model_schooltype = model('Schooltype');
        $schooltype = $model_schooltype->get_sctype_List(array('sc_enabled'=>1));
        $this->assign('schooltype', $schooltype);

        foreach ($class_list as $k=>$v){
            $schooltype = db('schooltype')->where('sc_id',$v['typeid'])->find();
            $class_list[$k]['typename'] = $schooltype['sc_type'];
            $school = db('school')->where('schoolid',$v['schoolid'])->find();
            $class_list[$k]['schoolname'] = $school['name'];
        }
        //全部学校
        if($admininfo['admin_id']!=1){
            $admin = db('admin')->where(array('admin_id'=>$admininfo['admin_id']))->find();
            $condition_school['a.admin_company_id'] = $admin['admin_company_id'];
        }
        $condition_school['isdel'] = 1;
        $model_school = model('School');
        $school_list = $model_school->getAllAchool($condition_school);
        $this->assign('page', $model_class->page_info->render());
        $this->assign('schoolList', $school_list);
        $this->assign('class_list', $class_list);
        $classname = $model_class->getAllClasses($condition_school);
        foreach ($classname as $k=>$v){
            $schooltype = db('schooltype')->where('sc_id',$v['typeid'])->find();
            $classname[$k]['typename'] = $schooltype['sc_type'];
        }
        $this->assign('classname', $classname);
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    public function add() {
        if(session('admin_is_super') !=1 && !in_array(1,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
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
            $model_schooltype = model('Schooltype');
            $schooltype = $model_schooltype->get_sctype_List(array('sc_enabled'=>1));
            $this->assign('schooltype', $schooltype);
            $this->setAdminCurItem('add');
            return $this->fetch();
        } else {
            $admininfo = $this->getAdminInfo();
            $model_classes = model('Classes');
            $data = array(
                'school_areaid' => input('post.area_id'),
                'school_region' => input('post.area_info'),
                'typeid' => input('post.classtype'),
                'schoolid' => input('post.order_state'),
                'classname' => input('post.school_class_name'),
                'desc' => input('post.class_desc'),
                'option_id' => $admininfo['admin_id'],
                'createtime' => date('Y-m-d H:i:s',time())
            );
            $city_id = db('area')->where('area_id',input('post.area_id'))->find();
            $data['school_cityid'] = $city_id['area_parent_id'];
            $province_id = db('area')->where('area_id',$city_id['area_parent_id'])->find();
            $data['school_provinceid'] = $province_id['area_parent_id'];
            //学校识别码
            $schoolInfo = db('school')->where('schoolid',input('post.order_state'))->find();
            $data['classCard'] = $schoolInfo['schoolCard'].($model_classes -> getNumber($schoolInfo['schoolCard']));
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
        if(session('admin_is_super') !=1 && !in_array(3,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
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
            $schoolid = isset($schoolname)?input('post.order_state'):input('post.school_name');
            $data = array(
                'school_areaid' => input('post.area_id'),
                'school_region' => input('post.area_info'),
                'typeid' => input('post.school_type'),
                'schoolid' => $schoolid,
                'classname' => input('post.school_class_name'),
                'desc' => input('post.class_desc'),
                'createtime' => date('Y-m-d H:i:s',time())
            );
            $city_id = db('area')->where('area_id',input('post.area_id'))->find();
            $data['school_cityid'] = $city_id['area_parent_id'];
            $province_id = db('area')->where('area_id',$city_id['area_parent_id'])->find();
            $data['school_provinceid'] = $province_id['area_parent_id'];
            //学校识别码
            $schoolInfo = db('school')->where('schoolid',$schoolid)->find();
            $data['classCard'] = $schoolInfo['schoolCard'].($model_class -> getNumber($schoolInfo['schoolCard']));
            //验证数据  END
            $result = $model_class->editClass($data,array('classid'=>$class_id));
            if ($result) {
                $this->success('编辑成功', 'Classes/index');
            } else {
                $this->error('编辑失败');
            }
        }
    }

    public function addstudent(){
        if(session('admin_is_super') !=1 && !in_array(11,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
        $class_id = input('get.class_id');
        if (!request()->isPost()) {
            $this->setAdminCurItem('addstudent');
            $this->assign('class_id', $class_id);
            return $this->fetch();
        } else {
            $admininfo = $this->getAdminInfo();
            $model_student = model('Student');
            $data = array(
                's_name' => input('post.student_name'),
                's_sex' => input('post.student_sex'),
                's_birthday' => input('post.student_birthday'),
                's_card' => input('post.student_idcard'),
                's_remark' => input('post.student_desc'),
                'option_id' => $admininfo['admin_id'],
                's_createtime' => date('Y-m-d H:i:s',time())
            );
            $classinfo = db('class')->where('classid',$class_id)->find();
            $data['s_provinceid'] = $classinfo['school_provinceid'];
            $data['s_cityid'] = $classinfo['school_cityid'];
            $data['s_areaid'] = $classinfo['school_areaid'];
            $data['s_region'] = $classinfo['school_region'];
            $data['s_schoolid'] = $classinfo['schoolid'];
            $data['s_sctype'] = $classinfo['typeid'];
            $data['s_classid'] = $classinfo['classid'];
            //验证数据  END
            $result = $model_student->addStudent($data);
            if ($result) {
                $this->success(lang('school_class_studentadd_succ'), 'Classes/index');
            } else {
                $this->error(lang('school_class_studentadd_fail'));
            }
        }
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
        if(session('admin_is_super') !=1 && !in_array(2,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
        $class_id = input('param.class_id');
        if (empty($class_id)) {
            $this->error(lang('param_error'));
        }
        $students = db('student')->where(['s_classid'=>$class_id,'s_del'=>1])->limit(1)->find();
        if($students){
            $this->error('该班级下有学生在使用，不能删除，请将使用的学生移除后进行删除');
        }
        $model_class = Model('classes');
        $result = $model_class->editClass(array('isdel'=>2),array('classid'=>$class_id));
        if ($result) {
            $this->success(lang('ds_common_del_succ'), 'Classes/index');
        } else {
            $this->error('删除失败');
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
                'url' => url('Admin/Classes/index')
            ),
        );
        if(session('admin_is_super') ==1 || in_array(1,$this->action )){
            if (request()->action() == 'add' || request()->action() == 'index') {
                $menu_array[] = array(
                    'name' => 'add',
                    'text' => '添加班级',
                    'url' => url('Admin/Classes/add')
                );
            }
        }

        if (request()->action() == 'edit') {
            $menu_array[] = array(
                'name' => 'edit',
                'text' => '编辑',
                'url' => url('Admin/Classes/edit')
            );
        }
        if (request()->action() == 'addstudent') {
            $menu_array[] = array(
                'name' => 'addstudent',
                'text' => '添加学生',
                'url' => url('Admin/Classes/addstudent')
            );
        }
        return $menu_array;
    }

}

?>
