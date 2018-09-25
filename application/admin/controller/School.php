<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class School extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/school.lang.php');
    }

    public function member() {
        $model_school = model('School');
        $condition = array();

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
        $school_list = $model_school->getSchoolList($condition, 10);
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
        $this->assign('page', $model_school->page_info->render());
        $this->assign('school_list', $school_list);
        $this->setAdminCurItem('member');
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
            $this->setAdminCurItem('add');
            return $this->fetch();
        } else {
            $model_school = model('School');
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
            //print_r($data);die;
            //验证数据  END
            $result = $model_school->addSchool($data);
            if ($result) {
                $this->success(lang('school_add_succ'), 'School/member');
            } else {
                $this->error(lang('school_add_fail'));
            }
        }
    }

    public function addclass() {
        $school_id = input('param.school_id');
        if (empty($school_id)) {
            $this->error(lang('param_error'));
        }
        if (!request()->isPost()) {
            $schooltype = db('schooltype')->where('sc_enabled','1')->select();
            $this->assign('schooltype', $schooltype);
            $this->assign('schoolid', $school_id);
            $this->setAdminCurItem('addclass');
            return $this->fetch();
        } else {
            $model_school = model('School');
            $model_class = model('Classes');
            $data = array(
                'typeid' => input('post.school_type'),
                'classname' => input('post.school_class_name'),
                'desc' => input('post.class_desc'),
                'createtime' => date('Y-m-d H:i:s',time())
            );
            $schoolinfo = $model_school->find(array("schoolid"=>$school_id));
            $data['schoolid'] = $schoolinfo['schoolid'];
            $data['school_provinceid'] = $schoolinfo['provinceid'];
            $data['school_cityid'] = $schoolinfo['cityid'];
            $data['school_areaid'] = $schoolinfo['areaid'];
            $data['school_region'] = $schoolinfo['region'];
            //验证数据  END
            $result = $model_class->addClasses($data);
            if ($result) {
                $this->success(lang('school_class_add_succ'), 'Classes/index');
            } else {
                $this->error(lang('school_class_add_fail'));
            }
        }
    }

    public function edit() {
        $school_id = input('param.school_id');
        if (empty($school_id)) {
            $this->error(lang('param_error'));
        }
        $model_school = Model('school');
        if (!request()->isPost()) {
            $condition['schoolid'] = $school_id;
            $school_array = $model_school->getSchoolInfo($condition);
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
                $this->success('编辑成功', 'School/member');
            } else {
                $this->error('编辑失败');
            }
        }
    }

    public function view(){
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
        $school_id = input('param.school_id');
        if (empty($school_id)) {
            $this->error(lang('param_error'));
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
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'member',
                'text' => '管理',
                'url' => url('Admin/School/member')
            ),
        );

        if (request()->action() == 'add' || request()->action() == 'member') {
            $menu_array[] = array(
                'name' => 'add',
                'text' => '添加学校',
                'url' => url('Admin/School/add')
            );
        }
        if (request()->action() == 'edit') {
            $menu_array[] = array(
                'name' => 'edit',
                'text' => '编辑',
                'url' => url('Admin/School/edit')
            );
        }
        if (request()->action() == 'view') {
            $menu_array[] = array(
                'name' => 'view',
                'text' => '查看',
                'url' => url('Admin/School/view')
            );
        }
        if (request()->action() == 'addclass') {
            $menu_array[] = array(
                'name' => 'addclass',
                'text' => '添加班级',
                'url' => url('Admin/School/addclass')
            );
        }
        return $menu_array;
    }

}

?>
