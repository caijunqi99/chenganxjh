<?php

namespace app\school\controller;

use think\Lang;
use think\Validate;

class Recording extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'school/lang/zh-cn/member.lang.php');
        //获取当前角色对当前子目录的权限
        $class_name=explode('\\',__CLASS__);
        $class_name = strtolower(end($class_name));
        $perm_id = $this->get_permid($class_name);
//        halt($class_name);
        $this->action = $action = $this->get_role_perms(session('school_admin_gid') ,$perm_id);
        $this->assign('action',$action);
    }

    public function index() {
        if(session('admin_is_super') !=1 && !in_array(4,$this->action)){
            $this->error(lang('ds_assign_right'));
        }
        $condition = array();
        $studentname = input('param.studentname');//学生姓名
        if ($studentname) {
            $condition['s.s_name'] = array('like', "%" . $studentname . "%");
        }
        $schooltype = input('param.school_type');//学校类型
        if ($schooltype) {
            $condition['sc.sc_id'] = $schooltype;
        }
        $query_start_time = input('param.query_start_time');
        $query_end_time = input('param.query_end_time');
        if ($query_start_time && $query_end_time) {
            $condition['ioTime'] = array('between', array(strtotime($query_start_time), strtotime($query_end_time)));
        }elseif($query_start_time){
            $condition['ioTime'] = array('>',strtotime($query_start_time));
        }elseif($query_end_time){
            $condition['ioTime'] = array('<',strtotime($query_end_time));
        }else{
            $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
            $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
            $condition['ioTime'] = array('between', array($beginToday, $endToday));
        }

        $robot_model = Model('Robotreport');
        $admininfo = $this->getAdminInfo();
        $condition['school_id'] = $admininfo['admin_school_id'];
        $RecordInfo = $robot_model->getReportList($condition);
        $path = "http://".$_SERVER['HTTP_HOST']."/uploads/home/robotvideo/";
        //年级
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
        //班级
        $allclass = db("class")->field("classid,classname")->where(array("schoolid"=>$admininfo['admin_school_id'],"isdel"=>1))->select();
        $this->assign('classList', $allclass);
        $this->assign('schooltype', $new_schooltype);
        $this->assign('recordInfo', $RecordInfo);
        $this->assign('path', $path);
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    /**
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        if(session('admin_is_super') !=1 && !in_array(1,$this->action)){
            $menu_array = array(
                array(
                    'name' => 'robot',
                    'text' => '管理',
                    'url' => url('School/Recording/index')
                ),
            );
        }else{
            $menu_array = array(
                array(
                    'name' => 'robot',
                    'text' => '管理',
                    'url' => url('School/Robot/robot')
                ),
            );

            if (request()->action() == 'add' || request()->action() == 'robot') {
                $menu_array[] = array(
                    'name' => 'add',
                    'text' => '新增',
                    'url' => url('School/Robot/add')
                );
            }
        }

        if (request()->action() == 'edit') {
            $menu_array[] = array(
                'name' => 'edit',
                'text' => '编辑',
                'url' => url('School/Robot/edit')
            );
        }
        return $menu_array;
    }

}

?>
