<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;


class Organizes extends AdminControl
{
    public function _initialize()
    {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/admin.lang.php');
    }
    /**
     * 获取分/子公司查看列表
     */
    protected function getAdminItemList() {
        $oid=$_GET['o_id'];
        $menu_array = array(
            array(
                'name' => 'company',
                'text' => '公司信息',
                'url' => url('Admin/Organizes/company',array('o_id'=>$oid))
            ),
            array(
                'name' => 'personnel',
                'text' => '公司人员信息',
                'url' => url('Admin/Organizes/personnel',array('o_id'=>$oid))
            ),
            array(
                'name' => 'schoolnum',
                'text' => '发展学校个数',
                'url' => url('Admin/Organizes/schoolnum',array('o_id'=>$oid))
            ),
            array(
                'name' => 'cameranum',
                'text' => '摄像头个数',
                'url' => url('Admin/Organizes/cameranum',array('o_id'=>$oid))
            ),
            array(
                'name' => 'membernum',
                'text' => '所属会员总数',
                'url' => url('Admin/Organizes/membernum',array('o_id'=>$oid))
            ),
            array(
                'name' => 'studentnum',
                'text' => '绑定学生总数',
                'url' => url('Admin/Organizes/studentnum',array('o_id'=>$oid))
            ),
        );
        return $menu_array;
    }
    //分子公司信息
    public function company()
    {
        //分子公司列表
        $model_organize = Model('company');
        $oid=$_GET['o_id'];
        $organize_info = $model_organize->getOrganizeInfo(array('o_id' => $oid));
        $this->assign('organize_info', $organize_info);
        $this->setAdminCurItem('company');
        return $this->fetch();
    }
    //公司人员信息
    public function personnel(){
        $where = ' a.admin_del_status=1';
        $where .=' and a.admin_company_id='.$_GET['o_id'] ;
        $admin_list = db('admin')->alias('a')->join('__GADMIN__ g', 'g.gid = a.admin_gid', 'LEFT')->join('__COMPANY__ o', 'o.o_id = a.admin_company_id', 'LEFT')->where($where)->paginate(10,false,['query' => request()->param()]);
        $this->assign('admin_list', $admin_list->items());
        $this->assign('page', $admin_list->render());
        $this->setAdminCurItem('personnel');
        return $this->fetch();
    }
    //发展学校个数
    public function schoolnum(){
        $oid=$_GET['o_id'];
        $model_admin = Model('admin');
        $condition = array();
        $condition['admin_company_id']=$oid;
        $admin=$model_admin->getAdminList($condition);
        $model_school = model('School');
        $conditions = array();
        $list=array();
        foreach($admin as $v){
            $conditions['option_id']=$v['admin_id'];
            $conditions['isdel']=1;
            $list+=$model_school->getSchoolList($condition);
        }
        $num=count($list);
        $this->assign('num',$num);
        $this->assign('school', $list);
        $this->setAdminCurItem('schoolnum');
        return $this->fetch();
    }
    //所属摄像头个数
    public function cameranum(){
        $oid=$_GET['o_id'];
        $model_admin = Model('admin');
        $condition = array();
        $condition['admin_company_id']=$oid;
        $admin=$model_admin->getAdminList($condition);
        $model_school = model('School');
        $conditions = array();
        $list=array();
        foreach($admin as $v){
            $conditions['option_id']=$v['admin_id'];
            $conditions['isdel']=1;
            $list+=$model_school->getSchoolList($condition);
        }
        $model_camera = model('Camera');
        foreach($list as $k=>$v){
            $where['schoolid']=$v['schoolid'];
            $datas=$this->_conditions($where);
            if(empty($data)){
                $data=!empty($datas)?$datas:'';
            }else{
                if(!empty($datas['parentid'][1])) {
                    foreach ($datas['parentid'][1] as $v1) {
                        $data['parentid'][1][] = $v1;
                    }
                }
            }
        }
        if(!empty($data)){
            $cameraList = $model_camera->getCameraList($data, 10);
            $this->assign('page', $model_camera->page_info->render());
            $this->assign('cameraList', $cameraList);
        }

        $this->setAdminCurItem('cameranum');
        return $this->fetch();
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
        if (isset($where['schoolid']) && $where['schoolid'] != 0 ) {
            $school = $this->getResGroupIds(array('schoolid'=>$where['schoolid']));
            $name = 'true';
            if ($school) {
                $res=array_merge($res, $school);
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


        $classname = '';
        if (isset($where['classname']) && !empty($where['classname']) ) {
            $classname = $where['classname'];
            unset($where['classname']);
        }
        $where['res_group_id'] =array('gt',0);
        $Schoollist = $School->getAllAchool($where,'res_group_id');
        // p($where);exit;
        if (!empty($classname)) {
            $where['classname'] = array('like','%'.$classname.'%');
            unset($Schoollist);
        }
        $res = array();
        $Classlist = $Class->getAllClasses($where,'res_group_id');
        $sc_resids=array_column($Schoollist, 'res_group_id');
        if ($sc_resids) {
            $res = $sc_resids;
//            array_push($res, $sc_resids);
        }
        $cl_resids=array_column($Classlist, 'res_group_id');
        if ($cl_resids) {
//            array_push($res, $cl_resids);
            if(empty($res)){
                $res = $cl_resids;
            }else{
                $res = array_merge($res,$cl_resids);
            }
        }
        $ids = array_merge($sc_resids,$cl_resids);
        if ($ids) {
            return $ids;
        }else{
            return $res;
        }
    }
    //所属会员数
    public function membernum(){
        $this->setAdminCurItem('membernum');
        return $this->fetch();
    }
    //绑定学生数
    public function studentnum(){
        $oid=$_GET['o_id'];
        $model_admin = Model('admin');
        $condition = array();
        $condition['admin_company_id']=$oid;
        $admin=$model_admin->getAdminList($condition);
        $model_school = model('School');
        $conditions = array();
        $list=array();
        foreach($admin as $v){
            $conditions['option_id']=$v['admin_id'];
            $conditions['isdel']=1;
            $list+=$model_school->getAllAchool($conditions);
        }
        foreach($list as $v){
            $schoolid.=$v['schoolid'].',';
        }
        $schoolid=substr($schoolid, 0, -1);
        $where['s_del'] = 1;
        $where['s_schoolid']=array('in',$schoolid);
        $model_student = model('Student');
        $student_list = $model_student->getStudentList($where, 15);
        $students= $model_student->getStudentList($where);
        foreach ($student_list as $k=>$v){
            $schooltype = db('schooltype')->where('sc_id',$v['s_sctype'])->find();
            $student_list[$k]['typename'] = $schooltype['sc_type'];
            $classinfo = db('class')->where('classid',$v['s_classid'])->find();
            $student_list[$k]['classname'] = $classinfo['classname'];
            $school = db('school')->where('schoolid',$v['s_schoolid'])->find();
            $student_list[$k]['schoolname'] = $school['name'];
            $member=db('member')->where('member_id',$v['s_ownerAccount'])->find();
            $student_list[$k]['member_name']=$member['member_name'];
        }
        $this->assign('page', $model_student->page_info->render());
        $this->assign('student_list', $student_list);
        $this->assign('count',count($students));
        $this->setAdminCurItem('studentnum');
        return $this->fetch();
    }
    //分配管理员账号
    public function admin(){
        $role=$_GET['role_id'];
        $this->assign('role',$role);
        $this->setAdminCurItem('admin');
        return $this->fetch();
    }
}