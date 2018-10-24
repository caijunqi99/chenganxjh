<?php

namespace app\admin\controller;

use think\Lang;
use think\Model;
use think\Validate;

class Coursemanage extends AdminControl {

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
        $model_arrangement = model('Arrangement');
        $condition = array();
        $admininfo = $this->getAdminInfo();
        if($admininfo['admin_id']!=1){
            $admin = db('admin')->where(array('admin_id'=>$admininfo['admin_id']))->find();
            $condition['a.admin_company_id'] = $admin['admin_company_id'];
        }
        //$data = db('school')->alias('s')->join('__ADMIN__ a',' a.admin_id=s.option_id ','LEFT')->where(array('s.isdel'=>1,'a.admin_company_id'=>$a))->select();
        $schoolname = input('param.schoolname');//学校名称
        if ($schoolname) {
            $condition['name'] = array('like', "%" . $schoolname . "%");
        }
        $schoolphone = input('param.schoolphone');//电话
        if ($schoolphone) {
            $condition['common_phone'] = array('like', "%" . $schoolphone . "%");
        }
        $schoolusername = input('param.schoolusername');//联系/负责人
        if ($schoolusername) {
            $condition['username'] = array('like', "%" . $schoolusername . "%");
        }
        $school_type = input('param.school_type');//学校类型
        if ($school_type) {
            $condition['typeid'] = $school_type;
        }
        $area_id = input('param.area_id');//地区
        if($area_id){
            $region_info = db('area')->where('area_id',$area_id)->find();
            if($region_info['area_deep']==1){
                $condition['provinceid'] = $area_id;
            }elseif($region_info['area_deep']==2){
                $condition['cityid'] = $area_id;
            }else{
                $condition['areaid'] = $area_id;
            }
        }
        $query_start_time = input('param.query_start_time');
        $query_end_time = input('param.query_end_time');
        if ($query_start_time || $query_end_time) {
            $condition['createtime'] = array('between', array($query_start_time, $query_end_time));
        }
        $condition['isdel'] = 1;
        $course_list = $model_arrangement->getList($condition);
        $model_schooltype = model('Schooltype');
        $schooltype = $model_schooltype->get_sctype_List(array('sc_enabled'=>1));
        $school = db('class')->field("classid,classname")->select();
        foreach($course_list as $key=>$value){
            foreach($schooltype as $k=>$v){
                if($value['type']==$v['sc_id']){
                    $course_list[$key]['type'] = $v['sc_type'];
                }
            }
            foreach($school as $k2=>$v2){
                if($value['classid']==$v2['classid']){
                    $course_list[$key]['schoolname'] = $v2['classname'];
                }
            }
        }

        $this->assign('course_list', $course_list);
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    public function add() {
        $schoolid = 6;
        if(session('admin_is_super') !=1 && !in_array(1,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
        if (!request()->isPost()) {
            //地区信息
//            $region_list = db('area')->where('area_parent_id','0')->select();
//            $this->assign('region_list', $region_list);
//            $address = array(
//                'true_name' => '',
//                'area_id' => '',
//                'city_id' => '',
//                'address' => '',
//                'tel_phone' => '',
//                'mob_phone' => '',
//                'is_default' => '',
//                'area_info'=>''
//            );
//            $this->assign('address', $address);
            //学校类型
            $schoolInfo = db('school')->where(array('schoolid'=>$schoolid))->find();
            $type = explode(',',$schoolInfo['typeid']);
            $model_schooltype = model('Schooltype');
            $schooltype = $model_schooltype->get_sctype_List(array('sc_enabled'=>1));
            foreach($type as $key=>$val){
               foreach($schooltype as $k=>$v){
                    if($val==$v['sc_id']){
                        $types[$key]['typeid'] = $val;
                        $types[$key]['typename'] = $v['sc_type'];
                    }
               }
            }
            $this->assign('schooltype', $types);
            $this->assign('schoolid', $schoolid);
            $this->setAdminCurItem('add');
            return $this->fetch();
        } else {
            $data = $_POST;
            foreach($data['mor_txt'] as $k=>$v){
                if($data['mor_txt'][0]!=""){
                    $content['Monday']['morning'][$k]['content'] = $data['mor_txt'][$k];
                    $content['Monday']['morning'][$k]['time'] = $data['mor_startTime'][$k]."-".$data['mor_endTime'][$k];
                }
            }
            foreach($data['after_txt'] as $k=>$v){
                if($data['after_txt'][0]!=""){
                    $content['Monday']['afternoon'][$k]['content'] = $data['after_txt'][$k];
                    $content['Monday']['afternoon'][$k]['time'] = $data['after_startTime'][$k]."-".$data['after_endTime'][$k];
                }
            }
            foreach($data['mor_cls'] as $k=>$v){
                if($data['mor_cls'][0]!=""){
                    $content['Tuesday']['morning'][$k]['content'] = $data['mor_cls'][$k];
                    $content['Tuesday']['morning'][$k]['time'] = $data['mor_cls_startTime'][$k]."-".$data['mor_cls_endTime'][$k];
                }
            }
            foreach($data['after_cls'] as $k=>$v){
                if($data['after_cls'][0]!=""){
                    $content['Tuesday']['afternoon'][$k]['content'] = $data['after_cls'][$k];
                    $content['Tuesday']['afternoon'][$k]['time'] = $data['after_cls_startTime'][$k]."-".$data['after_cls_endTime'][$k];
                }
            }
            foreach($data['mor_wes_txt'] as $k=>$v){
                if($data['mor_wes_txt'][0]!=""){
                    $content['Wednesday']['morning'][$k]['content'] = $data['mor_wes_txt'][$k];
                    $content['Wednesday']['morning'][$k]['time'] = $data['mor_wes_startTime'][$k]."-".$data['mor_wes_endTime'][$k];
                }
            }
            foreach($data['after_wes_txt'] as $k=>$v){
                if($data['after_wes_txt'][0]!=""){
                    $content['Wednesday']['afternoon'][$k]['content'] = $data['after_wes_txt'][$k];
                    $content['Wednesday']['afternoon'][$k]['time'] = $data['after_wes_startTime'][$k]."-".$data['after_wes_endTime'][$k];
                }
            }
            foreach($data['mor_tus_txt'] as $k=>$v){
                if($data['mor_tus_txt'][0]!=""){
                    $content['Thursday']['morning'][$k]['content'] = $data['mor_tus_txt'][$k];
                    $content['Thursday']['morning'][$k]['time'] = $data['mor_tus_startTime'][$k]."-".$data['mor_tus_endTime'][$k];
                }
            }
            foreach($data['after_tus_txt'] as $k=>$v){
                if($data['after_tus_txt'][0]!=""){
                    $content['Thursday']['afternoon'][$k]['content'] = $data['after_tus_txt'][$k];
                    $content['Thursday']['afternoon'][$k]['time'] = $data['after_tus_startTime'][$k]."-".$data['after_tus_endTime'][$k];
                }
            }
            foreach($data['mor_fri_txt'] as $k=>$v){
                if($data['mor_fri_txt'][0]!=""){
                    $content['Friday']['morning'][$k]['content'] = $data['mor_fri_txt'][$k];
                    $content['Friday']['morning'][$k]['time'] = $data['mor_fri_startTime'][$k]."-".$data['mor_fri_endTime'][$k];
                }
            }
            foreach($data['after_fri_txt'] as $k=>$v){
                if($data['after_fri_txt'][0]!=""){
                    $content['Friday']['afternoon'][$k]['content'] = $data['after_fri_txt'][$k];
                    $content['Friday']['afternoon'][$k]['time'] = $data['after_fri_startTime'][$k]."-".$data['after_fri_endTime'][$k];
                }
            }
            foreach($data['mor_fes_txt'] as $k=>$v){
                if($data['mor_fes_txt'][0]!=""){
                    $content['Saturday']['morning'][$k]['content'] = $data['mor_fes_txt'][$k];
                    $content['Saturday']['morning'][$k]['time'] = $data['mor_fes_startTime'][$k]."-".$data['mor_fes_endTime'][$k];
                }
            }
            foreach($data['after_fes_txt'] as $k=>$v){
                if($data['after_fes_txt'][0]!=""){
                    $content['Saturday']['afternoon'][$k]['content'] = $data['after_fes_txt'][$k];
                    $content['Saturday']['afternoon'][$k]['time'] = $data['after_fes_startTime'][$k]."-".$data['after_fes_endTime'][$k];
                }
            }
            foreach($data['mor_sun_txt'] as $k=>$v){
                if($data['mor_sun_txt'][0]!=""){
                    $content['Sunday']['morning'][$k]['content'] = $data['mor_sun_txt'][$k];
                    $content['Sunday']['morning'][$k]['time'] = $data['mor_sun_startTime'][$k]."-".$data['mor_sun_endTime'][$k];
                }
            }
            foreach($data['after_sun_txt'] as $k=>$v){
                if($data['after_sun_txt'][0]!=""){
                    $content['Sunday']['afternoon'][$k]['content'] = $data['after_sun_txt'][$k];
                    $content['Sunday']['afternoon'][$k]['time'] = $data['after_sun_startTime'][$k]."-".$data['after_sun_endTime'][$k];
                }
            }
            $schoolInfo = db('school')->where('schoolid',$schoolid)->find();
            $admininfo = $this->getAdminInfo();
            $data = array(
                'schoolid' => $_POST['schoolid'],
                'classid' => input('post.class_name'),
                'type' => input('post.school_type'),
                'content' => json_encode($content),
                'option_id' => $admininfo['admin_id'],
                'admin_company_id' => $schoolInfo['admin_company_id'],
                'desc' => input('post.desc'),
                'addtime' => time()
            );
            $model_arrangement = model('Arrangement');
            //验证数据  END
            $result = $model_arrangement->arrangement_add($data);
            if ($result) {
                $this->success(lang('school_course_add_suss'), 'Coursemanage/index');
            } else {
                $this->error(lang('school_course_add_fail'));
            }
        }
    }

    public function edit() {
        if(session('admin_is_super') !=1 && !in_array(3,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
        $school_id = input('param.school_id');
        if (empty($school_id)) {
            $this->error(lang('param_error'));
        }
        $model_school = Model('school');
        if (!request()->isPost()) {
            $condition['schoolid'] = $school_id;
            $school_array = $model_school->getSchoolInfo($condition);
            $school_array['typeid']=explode(',',$school_array['typeid']);
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
            $this->assign('school_array', $school_array);
            //类型
            $model_schooltype = model('Schooltype');
            $schoolType = $model_schooltype->get_sctype_List(array('sc_enabled'=>1));
            $this->assign('schoolType', $schoolType);
            $this->setAdminCurItem('edit');
            return $this->fetch();
        } else {

            $data = array(

                'name' => input('post.school_name'),
                'areaid' => input('post.area_id'),
                'region' => input('post.area_info'),
                'typeid' => implode(",",$_POST['school_type']),
                'address' => input('post.school_address'),
                'common_phone' => input('post.school_phone'),
                'username' => input('post.school_username'),
                'dieline' => input('post.school_dieline'),
                'desc' => input('post.school_desc'),
                'createtime' => date('Y-m-d H:i:s',time())

            );
            $city_id = db('area')->where('area_id',input('post.area_id'))->find();
            $data['cityid'] = $city_id['area_parent_id'];
            $province_id = db('area')->where('area_id',$city_id['area_parent_id'])->find();
            $data['provinceid'] = $province_id['area_parent_id'];
            //print_r($school_id);die;
            //验证数据  END
            $result = $model_school->editSchool($data,array('schoolid'=>$school_id));
            if ($result) {
                $this->success('编辑成功', 'School/index');
            } else {
                $this->error('编辑失败');
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
                $condition['areaid'] = array('eq', intval(input('get.area_id')));
                $condition['isdel'] = 1;
                $list = $school_member->getSchoolInfo($condition);
                if (empty($list)) {
                    echo 'true';
                    exit;
                } else {
                    echo 'false';
                    exit;
                }
                break;
            /**
             * 验证班级名是否重复
             */
            case 'check_class_name':
                $class_member = Model('classes');
                $condition['classname'] = input('param.class_name');
                $condition['schoolid'] = input('param.school_id');
                $condition['isdel'] = 1;
                $list = $class_member->getClassInfo($condition);
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
        $school_id = input('param.school_id');
        if (empty($school_id)) {
            $this->error(lang('param_error'));
        }
        $classes = db('class')->where(['schoolid'=>$school_id,'isdel'=>1])->limit(1)->find();
        if($classes){
            $this->error('该学校下存在正在使用的班级，不能删除，请将使用的班级移除后进行删除');
        }
        $model_school = Model('school');
        $result = $model_school->editSchool(array('isdel'=>2),array('schoolid'=>$school_id));
        if ($result) {
            $this->success(lang('ds_common_del_succ'), 'School/member');
        } else {
            $this->error('删除失败');
        }
    }

    public function course(){
        $course_id = input('param.course_id');
        $model = Model('Arrangement');
        $course = $model->getOneById($course_id);
        $course['content'] = json_decode($course['content'],true);
        $this->assign('course', $course);
        $this->setAdminCurItem('course');
        return $this->fetch();
    }

    /**
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'index',
                'text' => '管理',
                'url' => url('Admin/Coursemanage/index')
            ),
        );
//        if(session('admin_is_super') ==1 || in_array(1,$this->action )){
            if (request()->action() == 'add' || request()->action() == 'index') {
                $menu_array[] = array(
                    'name' => 'add',
                    'text' => '添加课程',
                    'url' => url('Admin/Coursemanage/add')
                );
            }
//        }
        if (request()->action() == 'edit') {
            $menu_array[] = array(
                'name' => 'edit',
                'text' => '编辑',
                'url' => url('Admin/School/edit')
            );
        }
        return $menu_array;
    }

}

?>
