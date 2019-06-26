<?php

namespace app\school\controller;

use think\Lang;
use think\Validate;

class Count extends AdminControl {

    const EXPORT_SIZE = 1000;

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
        $admininfo = $this->getAdminInfo();
        $schoolid = $admininfo['admin_school_id'];
        //机器人个数
        $robot = db("robot")->where(array("schoolid"=>$admininfo['admin_school_id'],"isdel"=>1))->count();
        $this->assign('robot', $robot);
        //摄像头个数
        $positionids = db("position")->field("position_id")->where(array("school_id"=>$schoolid))->select();
        $ids = array_column($positionids, 'position_id');
        $new_ids = implode(",",$ids);
        $camera = db("camera")->where(array("position_id"=>array("in",$new_ids)))->count();
        $this->assign('camera', $camera);
        //学生总人数
        $students = db("student")->where(array("s_schoolid"=>$schoolid,"s_del"=>1))->count();
        $this->assign('students', $students);
        //家长总人数（绑定学生的家长账号）
        $studentInfo = db('student')->field('s_ownerAccount')->where(array('s_schoolid'=>$admininfo['admin_school_id'],'s_del'=>1))->select();
        foreach($studentInfo as $key=>$item){
            if(!empty($item['s_ownerAccount'])){
                $member_ids[] = $item['s_ownerAccount'];
            }
        }
        $member_ids = array_unique($member_ids);
        if(!empty($member_ids)){
            $fu = db('member')->field("member_id")->where("member_id in (".implode(',',$member_ids).")")->select();
            foreach($fu as $F=>$it){
                $fu_ac[] = $it['member_id'];
            }
        }
        $member_ids = !empty($fu_ac)&&!empty($member_ids)?array_merge($member_ids,$fu_ac):"";
        $member = db("member")->where(array("member_id"=>array('in',$member_ids)))->count();
        $this->assign('member', $member);
        $this->setAdminCurItem('index');
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
                'url' => url('School/Count/index')
            ),
        );
        return $menu_array;
    }

}

?>
