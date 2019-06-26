<?php

namespace app\school\controller;

use think\Lang;
use think\Validate;

class Classes extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'school/lang/zh-cn/school.lang.php');
        Lang::load(APP_PATH . 'school/lang/zh-cn/admin.lang.php');
        //获取当前角色对当前子目录的权限
        $class_name = strtolower(end(explode('\\',__CLASS__)));
        $perm_id = $this->get_permid($class_name);
        $this->action = $action = $this->get_role_perms(session('school_admin_gid') ,$perm_id);
        $this->assign('action',$action);
    }

    public function index() {
        if(session('school_admin_is_super') !=1 && !in_array(4,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
        $model_class = model('Classes');
        $condition = array();
        $admininfo = $this->getAdminInfo();
        if($admininfo['admin_id']!=1){
            if(!empty($admininfo['admin_school_id'])){
                $condition['schoolid'] = $admininfo['admin_school_id'];
            }else{
                $model_company = Model("Company");
                $condition = $model_company->getCondition($admininfo['admin_company_id'],"class");
            }
        }
        $school_index_classname = input('param.school_index_classname');//班级名称
        if ($school_index_classname) {
            $condition['classname'] = array('like', "%" . $school_index_classname . "%");
        }
        $type_name = input('param.type_name');//所属年级
        if ($type_name) {
            $condition['typeid'] = $type_name;
        }
        $classCard = input('param.classCard');//班级识别码
        if ($classCard) {
            $condition['classCard'] = $classCard;
        }
        $condition['isdel'] = 1;
        $class_list = $model_class->getClasslList($condition, 15);
        //全部学校
        if($admininfo['admin_id']!=1){
            if(!empty($admininfo['admin_school_id'])){
                $condition_school['schoolid'] = $admininfo['admin_school_id'];
            }else{
                $model_company = Model("Company");
                $condition_school = $model_company->getCondition($admininfo['admin_company_id']);
            }
        }

        $model_school = model('School');
        $school_list = $model_school->getAllAchool($condition_school,'schoolid,name');
        $left_menu = array_column($school_list,NULL, 'schoolid');

        $schooltypeList  = db('schooltype')->field('sc_id,sc_type')->select();
        $schooltypeList=array_column($schooltypeList,NULL,'sc_id');
        
        $condition_school['isdel'] = 1;
        foreach ($class_list as $k=>$v){
            $class_list[$k]['typename'] = $schooltypeList[$v['typeid']]['sc_type'];
            $class_list[$k]['schoolname'] = $left_menu[$v['schoolid']]['name'];
        }

        $this->assign('page', $model_class->page_info->render());
        $this->assign('schoolList', $school_list);
        $this->assign('class_list', $class_list);
        //全部班级
        if($admininfo['admin_id']!=1){
            if(!empty($admininfo['admin_school_id'])){
                $condition_class['schoolid'] = $admininfo['admin_school_id'];
            }else{
                $model_company = Model("Company");
                $condition_class = $model_company->getCondition($admininfo['admin_company_id'],"class");
            }
        }
        $condition_class['isdel'] = 1;
        $classname = $model_class->getAllClasses($condition_class);
        foreach ($classname as $k=>$v){
            $classname[$k]['typename'] =$schooltypeList[$v['typeid']]['sc_type'];
        }
        $this->assign('classname', $classname);
        //所属年级
        $schoolInfo = db('school')->where('schoolid',$admininfo['admin_school_id'])->find();
        $typeids = explode(",",$schoolInfo['typeid']);
        $model_schooltype = model('Schooltype');
        $schooltype = $model_schooltype->get_sctype_List(array('sc_enabled'=>1));
        foreach($schooltype as $k=>$v){
            foreach($typeids as $key=>$item){
                if($item == $v['sc_id']){
                    $new_schooltype[] = $v;
                }
            }
        }
        $this->assign('schooltype', $new_schooltype);
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    public function add() {
        if(session('school_admin_is_super') !=1 && !in_array(1,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
        $admininfo = $this->getAdminInfo();
        $schoolInfo = db('school')->where('schoolid',$admininfo['admin_school_id'])->find();
        if (!request()->isPost()) {
            $typeids = explode(",",$schoolInfo['typeid']);
            //学校类型
            $model_schooltype = model('Schooltype');
            $schooltype = $model_schooltype->get_sctype_List(array('sc_enabled'=>1));
            foreach($schooltype as $k=>$v){
                foreach($typeids as $key=>$item){
                    if($item == $v['sc_id']){
                        $new_schooltype[] = $v;
                    }
                }
            }
            //学校房间
            $rooms = db('position')->field("position_id,position")->where(array('school_id'=>$admininfo['admin_school_id'],'is_bind'=>1))->order("position_id desc")->select();
            $this->assign('schooltype', $new_schooltype);
            $this->assign('rooms', $rooms);
            $this->setAdminCurItem('add');
            return $this->fetch();
        } else {
            $position_id = input('post.class_room');
            if(!empty($position_id)){
                db('position')->where(array("position_id"=>$position_id))->update(array("is_bind"=>2));
            }
            $model_classes = model('Classes');
            $data = array(
                'school_areaid' => $schoolInfo['areaid'],
                'school_region' => $schoolInfo['region'],
                'school_cityid' => $schoolInfo['cityid'],
                'school_provinceid' => $schoolInfo['provinceid'],
                'typeid' => input('post.classtype'),
                'position_id' => $position_id,
                'schoolid' => $schoolInfo['schoolid'],
                'classname' => input('post.school_class_name'),
                'desc' => input('post.class_desc'),
                'option_id' => $admininfo['admin_id'],
                'admin_company_id' => $schoolInfo['admin_company_id'],
                'createtime' => date('Y-m-d H:i:s',time())
            );
            //学校识别码
            $classcard=$schoolInfo['schoolCard'].($model_classes -> getNumber($schoolInfo['schoolCard']));
            $data['classCard'] =$classcard;
            //生成二维码
            import('qrcode.index',EXTEND_PATH);
            $PhpQRCode = new \PhpQRCode();
            $PhpQRCode->set('pngTempDir', BASE_UPLOAD_PATH . DS . ATTACH_STORE . DS . 'class' .DS. $schoolInfo['schoolCard'].DS);
            // 生成商品二维码
            $PhpQRCode->set('date', $classcard);
            $PhpQRCode->set('pngTempName', $classcard . '.png');
            $qr=$PhpQRCode->init();
            $data['qr']='/home/store/class/'.$schoolInfo['schoolCard'].'/'.$qr;
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
        if(session('school_admin_is_super') !=1 && !in_array(3,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
        $admininfo = $this->getAdminInfo();
        $schoolInfo = db('school')->where('schoolid',$admininfo['admin_school_id'])->find();
        $class_id = input('param.class_id');
        if (empty($class_id)) {
            $this->error(lang('param_error'));
        }
        $model_class = Model('classes');
        if (!request()->isPost()) {
            $condition['classid'] = $class_id;
            $class_array = $model_class->getClassInfo($condition);
            $this->assign('class_array', $class_array);
            //所属年级
            $typeids = explode(",",$schoolInfo['typeid']);
            $model_schooltype = model('Schooltype');
            $schooltype = $model_schooltype->get_sctype_List(array('sc_enabled'=>1));
            foreach($schooltype as $k=>$v){
                foreach($typeids as $key=>$item){
                    if($item == $v['sc_id']){
                        $new_schooltype[] = $v;
                    }
                }
            }
            //学校房间
            $rooms = db('position')->field("position_id,position")->where(array('school_id'=>$admininfo['admin_school_id']))->order("position_id desc")->select();
            $this->assign('rooms', $rooms);
            $this->assign('schooltype', $new_schooltype);
            $this->setAdminCurItem('edit');
            return $this->fetch();
        } else {
            //查看是否已绑定
            $position_id = input('post.class_room');
            if(!empty($position_id)){
                $rooms = db('class')->field("classid,position_id")->where(array('schoolid'=>$admininfo['admin_school_id'],'position_id'=>$position_id))->find();
                if(!empty($rooms)){
                    db('class')->where(array("classid"=>$rooms['classid']))->update(array("position_id"=>0));
                }
                db('position')->where(array("position_id"=>$position_id))->update(array("is_bind"=>2));
            }
            $data = array(
                'school_areaid' => $schoolInfo['areaid'],
                'school_region' => $schoolInfo['region'],
                'school_cityid' => $schoolInfo['cityid'],
                'school_provinceid' => $schoolInfo['provinceid'],
                'typeid' => input('post.school_type'),
                'position_id' => $position_id,
                'schoolid' => $schoolInfo['schoolid'],
                'classname' => input('post.school_class_name'),
                'desc' => input('post.class_desc'),
                'createtime' => date('Y-m-d H:i:s',time())
            );
            //学校识别码
//            $schoolInfo = db('school')->where('schoolid',$schoolid)->find();
//            $data['classCard'] = $schoolInfo['schoolCard'].($model_class -> getNumber($schoolInfo['schoolCard']));
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
        if(session('school_admin_is_super') !=1 && !in_array(11,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
        $class_id = input('get.class_id');
        if (!request()->isPost()) {
            $this->setAdminCurItem('addstudent');
            $this->assign('class_id', $class_id);
            return $this->fetch();
        } else {
            $admininfo = $this->getAdminInfo();
            $classinfo = db('class')->where('classid',$class_id)->find();
            $model_student = model('Student');
            $data = array(
                's_name' => input('post.student_name'),
                's_sex' => input('post.student_sex'),
                's_birthday' => input('post.student_birthday'),
                's_card' => input('post.student_idcard'),
                's_remark' => input('post.student_desc'),
                'option_id' => $admininfo['admin_id'],
                'admin_company_id' => $classinfo['admin_company_id'],
                's_createtime' => date('Y-m-d H:i:s',time())
            );
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
             * 验证班级名是否重复
             */
            case 'check_user_name':
                $model_class = Model('classes');
                $condition['classname'] = input('param.class_name');
                $condition['schoolid'] = input('param.school_id');
                $condition['isdel'] = 1;
                $list = $model_class->getClassInfo($condition);
                if (empty($list)) {
                    echo 'true';
                    exit;
                } else {
                    echo 'false';
                    exit;
                }
                break;
            /**
             * 验证身份证是否重复（同一班级）
             */
            case 'check_user_cards':
                $model_student = model('Student');
                $condition['s_card'] = input('param.student_idcard');
                $condition['s_classid'] = input('param.class_name');
                $condition['s_del'] = 1;
                $list = $model_student->getStudentInfo($condition);
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
        if(session('school_admin_is_super') !=1 && !in_array(2,$this->action )){
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
                'url' => url('School/Classes/index')
            ),
        );
        if(session('school_admin_is_super') ==1 || in_array(1,$this->action )){
            if (request()->action() == 'add' || request()->action() == 'index') {
                $menu_array[] = array(
                    'name' => 'add',
                    'text' => '添加班级',
                    'url' => url('School/Classes/add')
                );
            }
        }

        if (request()->action() == 'edit') {
            $menu_array[] = array(
                'name' => 'edit',
                'text' => '编辑',
                'url' => url('School/Classes/edit')
            );
        }
        if (request()->action() == 'addstudent') {
            $menu_array[] = array(
                'name' => 'addstudent',
                'text' => '添加学生',
                'url' => url('School/Classes/addstudent')
            );
        }
        return $menu_array;
    }

}

?>
