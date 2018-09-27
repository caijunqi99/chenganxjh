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
        $model_organize = Model('organize');
        $oid=$_GET['o_id'];
        $organize_info = $model_organize->getOrganizeInfo(array('o_id' => $oid));
        $this->assign('organize_info', $organize_info);
        $this->setAdminCurItem('company');
        return $this->fetch();
    }
    //公司人员信息
    public function personnel(){
        $this->setAdminCurItem('personnel');
        return $this->fetch();
    }
    //发展学校个数
    public function schoolnum(){
        $this->setAdminCurItem('schoolnum');
        return $this->fetch();
    }
    //所属摄像头个数
    public function cameranum(){
        $this->setAdminCurItem('cameranum');
        return $this->fetch();
    }
    //所属会员数
    public function membernum(){
        $this->setAdminCurItem('membernum');
        return $this->fetch();
    }
    //绑定学生数
    public function studentnum(){
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