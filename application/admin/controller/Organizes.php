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
        if(session('admin_is_super') !=1 && !in_array(4,$this->action)){
            $this->error(lang('ds_assign_right'));
        }
        $admin_id = $this->admin_info['admin_id'];
        if(session('admin_is_super') == 1){
            $where = ' a.admin_del_status=1';
        }else{
            $where = 'a.create_uid='.$admin_id.' AND a.admin_del_status=1';
        }

        $account = '';$role = '';
        if (request()->isPost()) {
            if(!empty($_POST['account'])){
                $where .= ' AND (a.admin_name LIKE "%'.$_POST["account"].'%" || a.admin_phone LIKE "%'.$_POST["account"].'%") ';
                $account = trim($_POST['account']);
            }
            if(!empty($_POST['role'])){
                $where .= ' AND a.admin_gid = '.intval($_POST["role"]);
                $role = intval($_POST['role']);
            }
        }

        $admin_list = db('admin')->alias('a')->join('__GADMIN__ g', 'g.gid = a.admin_gid', 'LEFT')->join('__COMPANY__ o', 'o.o_id = a.admin_company_id', 'LEFT')->where($where)->paginate(10,false,['query' => request()->param()]);

//        halt($admin_list);
        //获取所创建的角色
        $gadmin_list = db('gadmin')->field('gid,create_uid,gname')->where('create_uid= '.$admin_id.' ')->select();

        $this->assign('gadmin_list',$gadmin_list);
        $this->assign('admin_list', $admin_list->items());
        $this->assign('account',$account);
        $this->assign('role',$role);
        $this->assign('page', $admin_list->render());
        $this->setAdminCurItem('admin');
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