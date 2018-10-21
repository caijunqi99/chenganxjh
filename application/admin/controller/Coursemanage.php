<?php

namespace app\admin\controller;

use think\Lang;
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
        foreach($course_list as $key=>$value){
            if(!empty($value['content'])){
                $course_list[$key]['content'] = json_decode($value['content'],true);
            }
        }
        //print_r($course_list);die;
        $this->assign('course_list', $course_list);
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    public function add() {
        if(session('admin_is_super') !=1 && !in_array(1,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
        if (!request()->isPost()) {
            //类型
            $model_schooltype = model('Schooltype');
            $schoolType = $model_schooltype->get_sctype_List(array('sc_enabled'=>1));
            $this->assign('schoolType', $schoolType);
            $this->setAdminCurItem('add');
            return $this->fetch();
        } else {
            $schoolid = 8;
            //$schoolid = input('post.schoolid');
            $date = input('post.date');
            $mor_course = input('post.mor_txt1');
            $mor_starttime = input('post.mor_starttime');
            $mor_endtime = input('post.mor_endtime');
            $after_course = input('post.after_txt1');
            $after_starttime = input('post.after_starttime');
            $after_endtime = input('post.after_endtime');
            $content = array(
                "上午"=>array(
                    array("time"=>$mor_starttime."-".$mor_endtime,"content"=>$mor_course)
                ),
                "下午"=>array(
                    array("time"=>$after_starttime."-".$after_endtime,"content"=>$after_course)
                )
            );

            $data = array(
                'schoolid' => $schoolid,
                'date' => $date,
                'content' => json_encode($content),
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
    /**
     * 管理员添加
     */
    public function admin_add() {
        if(session('admin_is_super') !=1 && !in_array(6,$this->action )){
            $this->error(lang('ds_assign_right'));
        }
        $admin_id = $this->admin_info['admin_id'];
            $model_admin = Model('admin');
            $param['admin_name'] = $_POST['admin_name'];
            $param['admin_gid'] = 5;
            $param['admin_password'] = md5($_POST['admin_password']);
            $param['create_uid'] = $admin_id;
            $rs = $model_admin->addAdmin($param);
            if ($rs) {
                $this->log(lang('ds_add').lang('limit_admin') . '[' . $_POST['admin_name'] . ']', 1);
                echo json_encode(['m'=>true,'ms'=>lang('co_organize_succ')]);
            } else {
                echo json_encode(['m'=>true,'ms'=>lang('co_organize_succ')]);
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
