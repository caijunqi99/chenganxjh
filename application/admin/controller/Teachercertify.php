<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Teachercertify extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/teacher.lang.php');
        Lang::load(APP_PATH . 'admin/lang/zh-cn/admin.lang.php');
        //获取当前角色对当前子目录的权限
//        $class_name = strtolower(end(explode('\\',__CLASS__)));
//        $perm_id = $this->get_permid($class_name);
//        $this->action = $action = $this->get_role_perms(session('admin_gid') ,$perm_id);
//        $this->assign('action',$action);
    }

    public function index() {
//        if(session('admin_is_super') !=1 && !in_array(4,$this->action )){
//            $this->error(lang('ds_assign_right'));
//        }
        $model_teacher = model('Teachercertify');
        $condition = array();
//        $admininfo = $this->getAdminInfo();
//        if($admininfo['admin_id']!=1){
//            $admin = db('admin')->where(array('admin_id'=>$admininfo['admin_id']))->find();
//            $condition['a.admin_company_id'] = $admin['admin_company_id'];
//        }
        $user = input('param.user');//会员账户
        if ($user) {
            $condition['member_mobile'] = array('like', "%" . $user . "%");
        }
        $teacher_status = input('param.teacher_status');//状态
        if ($teacher_status) {
            $condition['status'] = $teacher_status;
        }
        $username = input('param.username');//姓名
        if ($username) {
            $condition['username'] = array('like', "%" . $username . "%");
        }
        $query_start_time = input('param.query_start_time');
        $query_end_time = input('param.query_end_time');
        if ($query_start_time && $query_end_time) {
            $condition['createtime'] = array('between', array(strtotime($query_start_time), strtotime($query_end_time)));
        }elseif($query_start_time){
            $condition['createtime'] = array('>',strtotime($query_start_time));
        }elseif($query_end_time){
            $condition['createtime'] = array('<',strtotime($query_end_time));
        }
        $teacher_list = $model_teacher->getTeacherList($condition, 15);
        foreach($teacher_list as $k=>$v){
            $memberinfo = db('member')->where(array('member_id'=>$v['member_id']))->find();
            $teacher_list[$k]['member_add_time'] = $memberinfo['member_add_time'];
        }
        $path = "http://vip.xiangjianhai.com:8001/uploads/";
        $this->assign('path', $path);
        $this->assign('page', $model_teacher->page_info->render());
        $this->assign('teacher_list', $teacher_list);
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    public function pass() {
//        if(session('admin_is_super') !=1 && !in_array(3,$this->action )){
//            $this->error(lang('ds_assign_right'));
//        }
        $teacher_id = input('param.teacher_id');
        if (empty($teacher_id)) {
            $this->error(lang('param_error'));
        }
        $model_teacher = model('Teachercertify');
        if (!request()->isPost()) {
            $teachinfo = $model_teacher->getOneById($teacher_id);
            $memberinfo = db('member')->where(array('member_id'=>$teachinfo['member_id']))->find();
            $teachinfo['member_add_time'] = $memberinfo['member_add_time'];
            $path = "http://vip.xiangjianhai.com:8001/uploads/";
            $this->assign('path', $path);
            $this->assign('teachinfo', $teachinfo);
            $this->setAdminCurItem('pass');
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
                $this->success('编辑成功', 'School/member');
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
//        if(session('admin_is_super') !=1 && !in_array(2,$this->action )){
//            $this->error(lang('ds_assign_right'));
//        }
        $teacher_id = input('param.teacher_id');
        $status = input('param.status');
        if (empty($teacher_id)) {
            $this->error(lang('param_error'));
        }

        $model_teacher = model('Teachercertify');
        $result = $model_teacher->teacher_update(array('status'=>$status),array('id'=>$teacher_id));
        if ($result) {
            if($status==2){
                $this->success(lang('teacher_index_noapass'), 'Teachercertify/index');
            }else{
                $this->success(lang('teacher_index_apass'), 'Teachercertify/index');
            }
        } else {
            $this->error('审核失败');
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
                'url' => url('Admin/Teachercertify/index')
            ),
        );
        if (request()->action() == 'pass') {
            $menu_array[] = array(
                'name' => 'pass',
                'text' => '审核',
                'url' => url('Admin/Teachercertify/pass')
            );
        }
        return $menu_array;
    }

}

?>
