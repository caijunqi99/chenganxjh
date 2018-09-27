<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Schoolapply extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/school.lang.php');
        Lang::load(APP_PATH . 'admin/lang/zh-cn/admin.lang.php');
    }

    public function index() {
        $model_schoolapply = model('Schoolapply');
        $condition = array();

        $schoolname = input('param.schoolname');//学校名称
        if ($schoolname) {
            $condition['schoolname'] = array('like', "%" . $schoolname . "%");
        }
        $school_type = input('param.school_type');//学校类型
        if ($school_type) {
            $condition['typeid'] = array('like', "%" . $school_type . "%");
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
        $school_status = input('param.school_status');//状态
        if ($school_status) {
            $condition['status'] = $school_status;
        }
        $schoolapply_list = $model_schoolapply->getSchoolapplyList($condition, 10);
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
        $this->assign('page', $model_schoolapply->page_info->render());
        $this->assign('schoolapply_list', $schoolapply_list);
        $this->setAdminCurItem('index');
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
     * 处理
     */
    public function deal() {
        $applyid = input('param.applyid');
        if (empty($applyid)) {
            $this->error(lang('param_error'));
        }
        $model_schoolapply = Model('schoolapply');
        $result = $model_schoolapply->editSchoolapply(array('status'=>2),array('applyid'=>$applyid));
        if ($result) {
            $this->success(lang('school_index_applydealSuccess'), 'Schoolapply/index');
        } else {
            $this->error('处理失败');
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
