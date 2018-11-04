<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;
use vomont\Vomont;

class Monitor extends AdminControl
{
    public function _initialize()
    {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/look.lang.php');
        //获取省份
        $province = db('area')->field('area_id,area_parent_id,area_name')->where('area_parent_id=0')->select();
        //获取学校
        $school = db('school')->field('schoolid,name')->select();
//获取当前角色对当前子目录的权限
        $class_name = strtolower(end(explode('\\',__CLASS__)));
        $perm_id = $this->get_permid($class_name);
        $this->action = $action = $this->get_role_perms(session('admin_gid') ,$perm_id);
        $this->assign('action',$action);

        $this->assign('school',$school);
        $this->assign('province',$province);
    }

    /**
     * @desc 网络监控
     * @author 郎志耀
     * @time 20180926
     */
    public function monitor(){
        if(session('admin_is_super') !=1 && !in_array('4',$this->action)){
            $this->error(lang('ds_assign_right'));
        }
        $where = '1=1';
        if(!empty($_POST)){


            $cond = array();
            foreach ($_POST as $key => $p) {
                if(!in_array($key, ['page','page_count']) && !empty($p))$cond[$key]=$p;
            }
            if ($cond) {
                $where = $this->_conditions($cond);
            }
            $page_count = intval(input('post.page_count')) ? intval(input('post.page_count')) : 15;//每页的条数
            $start = intval(input('post.page')) ? (intval(input('post.page'))-1)*$page_count : 0;//开始页数

        //查询已安装的摄像头
            $camera= db('camera')->where($where)->limit($start,$page_count)->order('cid DESC')->select();
            if(!empty($_POST['schoolid'])){
                $schoolid=$_POST['schoolid'];
                $model_school = Model('school');
                $schoolres=$model_school->getSchoolById($schoolid);
                $this->assign('schoolname',$schoolres['name']);
            }
            $this->assign('video', $camera);
        }
        $this->setAdminCurItem('monitor');
        return $this->fetch('monitor');
    }
    /**
     * 摄像头查询过滤
     * @创建时间   2018-11-03T00:39:28+0800
     * @param  [type]                   $where [description]
     * @return [type]                          [description]
     */
    public function _conditions($where){
        $res = array();
        $name = false;
        if (isset($where['class']) && !empty($where['class']) ) {
            $class = $this->getResGroupIds(array('classid'=>$where['class']));
            if ($class) {
                $res=array_merge($res, $class);
            }
            unset($where);
            $name = 'true';
        }
        if (isset($where['grade']) && !empty($where['grade'])) {
            $grade = $this->getResGroupIds(array('sc_type'=>$where['grade']));
            unset($where['school']);
            unset($where['area']);
            unset($where['city']);
            unset($where['province']);
            $name = 'true';
            if ($grade) {
                $res=array_merge($res, $grade);
            }
        }
        if (isset($where['school']) && $where['school'] != 0 ) {
            $school = $this->getResGroupIds(array('schoolid'=>$where['school']));
            unset($where['area']);
            unset($where['city']);
            unset($where['province']);
            $name = 'true';
            if ($school) {
                $res=array_merge($res, $school);
            }
        }
        if (isset($where['area']) && $where['area'] != 0 ) {
            $area = $this->getResGroupIds(array('areaid'=>$where['area']));
            unset($where['city']);
            unset($where['province']);
            $name = 'true';
            if ($area) {
                $res=array_merge($res, $area);
            }
        }
        if (isset($where['city']) && $where['city'] != 0 ) {
            $city = $this->getResGroupIds(array('cityid'=>$where['city']));
            unset($where['province']);
            $name = 'true';
            if ($city) {
                $res=array_merge($res, $city);
            }
        }
        if (isset($where['province']) && $where['province'] != 0 ) {
            $province = $this->getResGroupIds(array('provinceid'=>$where['province']));
            $name = 'true';
            if ($province) {
                $res=array_merge($res, $province);
            }
        }
        if ($name == 'true') {
            $condition['parentid'] = array('in',$res);
        }
        return $condition;
    }
    /**
     * 查询学校和班级摄像头
     * @创建时间   2018-11-03T00:39:48+0800
     * @param  [type]                   $where [description]
     * @return [type]                          [description]
     */
    public function getResGroupIds($where){
        $School = model('School');
        $Class = model('Classes');

        if (isset($where['sc_type']) && !empty($where['sc_type'])) {
            $sc_id = db('schooltype')->where($where)->value('sc_id');
            unset($where['sc_type']);
            $where[]=['exp','FIND_IN_SET('.$sc_id.',typeid)'];
        }
        $classid = '';
        if (isset($where['classid']) && !empty($where['classid']) ) {
            $classid = $where['classid'];
            unset($where['classid']);
        }
        $where['res_group_id'] =array('gt',0);
        $Schoollist = $School->getAllAchool($where,'res_group_id');
        // p($where);exit;
        if (isset($where['provinceid']) && !empty($where['provinceid'])) {
            $where['school_provinceid'] =$where['provinceid'];
            unset($where['provinceid']);
        }
        if (isset($where['cityid']) && !empty($where['cityid'])) {
            $where['school_cityid'] =$where['cityid'];
            unset($where['cityid']);
        }
        if (isset($where['areaid']) && !empty($where['areaid'])) {
            $where['school_areaid'] =$where['areaid'];
            unset($where['areaid']);
        }
        if (isset($where['areaid']) && !empty($where['areaid'])) {
            $where['school_areaid'] =$where['areaid'];
            unset($where['areaid']);
        }
        if (!empty($classid)) {
            $where['classid'] = $classid;
        }
        $res = array();
        $Classlist = $Class->getAllClasses($where,'res_group_id');
        $sc_resids=array_column($Schoollist, 'res_group_id');
        if ($sc_resids) {
            array_push($res, $sc_resids);
        }
        $cl_resids=array_column($Classlist, 'res_group_id');
        if ($cl_resids) {
            array_push($res, $cl_resids);
        }
        $ids = array_merge($sc_resids,$cl_resids);
        if ($ids) {
            return $ids;
        }else{
            return $res;
        }
    }

}