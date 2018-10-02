<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;


class Company extends AdminControl {
    public function _initialize()
    {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/admin.lang.php');
        //获取当前角色对当前子目录的权限
        $class_name = strtolower(end(explode('\\',__CLASS__)));
        $perm_id = $this->get_permid($class_name);
//        halt($perm_id);
        $this->action = $action = $this->get_role_perms(session('admin_gid') ,$perm_id);
        $this->assign('action',$action);
    }
    /**
     *
     * 分子公司管理列表
     */
    public function index()
    {
        if(session('admin_is_super') !=1 && !in_array(4,$this->action)){
            $this->error(lang('ds_assign_right'));
        }
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
        //分子公司列表
        $model_organize = Model('company');
        $condition = array();
        $condition['o_del']=1;
        if (!empty($_POST['search_organize_name'])) {
            $o_name=input('param.search_organize_name');
            $condition['o_name']=array('like', '%' . trim($o_name) . '%');
            $this->assign('search_organize_name',$o_name);
        }
        if(!empty($_POST['o_provinceid'])){
            $o_provinceid=input('param.o_provinceid');
            $condition['o_provinceid']=$o_provinceid;
            $this->assign('o_provinceid',$o_provinceid);
        }
        if(!empty($_POST['o_cityid'])) {
            if ($_POST['dep'] == 2 || $_POST['dep'] == 3){
                $o_cityid = input('param.o_cityid');
                $condition['o_cityid'] =$o_cityid;
                $this->assign('o_cityid', $o_cityid);
            }
        }
        if(!empty($_POST['area_id'])){
            if ($_POST['dep'] == 3) {
                $area_id = input('param.area_id');
                $condition['o_areaid'] =$area_id;
                $this->assign('area_id', $area_id);
            }
        }
        $organize_list = $model_organize->getOrganizeList($condition, "*",15);
        $this->assign('page', $model_organize->page_info->render());
        $this->assign('organize_list', $organize_list);
        $this->setAdminCurItem('index');
        return $this->fetch();
    }
    /**
     * 获取分/子公司栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        if(session('admin_is_super') !=1 && !in_array(1,$this->action)){
            $menu_array = array(
                array(
                    'name' => 'index',
                    'text' => '分/子（代理）公司管理',
                    'url' => url('Admin/Company/index')
                )
            );
        }else{
            $menu_array = array(
                array(
                    'name' => 'index',
                    'text' => '分/子（代理）公司管理',
                    'url' => url('Admin/Company/index')
                ),
                array(
                    'name' => 'add',
                    'text' => '添加',
                    'url' => url('Admin/Company/add')
                )
            );
        }

        if (request()->action() == 'edit') {
            $oid=$_GET['organize_id'];
            $menu_array[1] = array(
                'name' => 'organize_edit', 'text' => '编辑', 'url' => url('Admin/Company/edit',array('organize_id'=>$oid))
            );
        }
        return $menu_array;
    }
    /**
     * 子公司新增
    */
    public function add() {
        if(session('admin_is_super') !=1 && !in_array(1,$this->action)){
            $this->error(lang('ds_assign_right'));
        }
        if (request()->isPost()) {
            //提交表单
            //保存
            $input = array();
            $input['o_name'] = trim($_POST['o_name']);
            $input['o_role'] = intval($_POST['o_role']);
            $input['o_provinceid'] = intval($_POST['o_provinceid']);
            $input['o_cityid'] = intval($_POST['o_cityid']);
            $input['o_areaid'] = intval($_POST['area_id']);
            $input['o_area'] = trim($_POST['area_info']);
            $input['o_address'] = trim($_POST['o_address']);
            $input['o_phone'] = trim($_POST['o_phone']);
            $input['o_leading'] = trim($_POST['o_leading']);
            $input['o_enddate'] = trim($_POST['activity_end_date']);
            $input['o_createtime']=date('Y-m-d H:i:s',time());
            $input['o_remark'] = trim($_POST['o_remark']);
            $input['o_del']=1;
            $activity = Model('company');
            $result = $activity->addOrganize($input);
            if ($result) {
                $this->log(lang('ds_add') . lang('ds_company') . '[' . $_POST['o_name'] . ']', 1);
                $this->success(lang('ds_common_save_succ'),'company/index');
            }
            else {
                $this->error(lang('ds_common_save_fail'));
            }
        } else {
            //地区信息
            $region_list = db('area')->where('area_parent_id','0')->select();
            $this->assign('region_list', $region_list);
            $this->setAdminCurItem('add');
            return $this->fetch();
        }
    }
    /**
     * 子公司编辑
     */
    public function edit()
    {
        if(session('admin_is_super') !=1 && !in_array(3,$this->action)){
            $this->error(lang('ds_assign_right'));
        }
        $model_organize = Model('company');
        if (request()->isPost()) {
            $where = array();
            $where['o_id'] = intval($_POST['o_id']);
            $update_array = array();
            $update_array['o_name'] = trim($_POST['o_name']);
            $update_array['o_role'] = intval($_POST['o_role']);
            $update_array['o_provinceid'] = intval($_POST['o_provinceid']);
            $update_array['o_cityid'] = intval($_POST['city_id']);
            $update_array['o_areaid'] = intval($_POST['area_id']);
            $update_array['o_area'] = trim($_POST['area_info']);
            $update_array['o_address'] = trim($_POST['o_address']);
            $update_array['o_phone'] = trim($_POST['o_phone']);
            $update_array['o_leading'] = trim($_POST['o_leading']);
            $update_array['o_enddate'] = trim($_POST['activity_end_date']);
            $update_array['o_createtime'] = date('Y-m-d H:i:s', time());
            $update_array['o_remark'] = trim($_POST['o_remark']);
            $result = $model_organize->editOrganize($where, $update_array);
            if ($result) {
                $this->log(lang('ds_edit') . lang('ds_company') . '[' . $_POST['o_name'] . ']', 1);
                $this->success(lang('ds_common_save_succ'), 'company/index');
            } else {
                $this->log(lang('ds_edit').lang('ds_company') . '[' . $_POST['o_name'] . ']', 0);
                $this->error(lang('ds_common_save_fail'));
            }
        } else {

            $organize_info = $model_organize->getOrganizeInfo(array('o_id' => intval(input('param.organize_id'))));
            //地区信息
            $region_list = db('area')->where('area_parent_id','0')->select();
            $this->assign('region_list', $region_list);
            if (empty($organize_info)) {
                $this->error(lang('param_error'));
            }
            $this->assign('organize_array', $organize_info);
            $this->setAdminCurItem('organize_edit');
            return $this->fetch('edit');
        }
    }
    /**
     * 子公司删除
    */
    public function del(){
        if(session('admin_is_super') !=1 && !in_array(2,$this->action)){
            $this->error(lang('ds_assign_right'));
        }
        $o_id = input('param.o_id');
        if (empty($o_id)) {
            $this->error(lang('param_error'));
        }
        $admin = db('admin')->where(['admin_company_id'=>$o_id,'admin_del_status'=>1])->limit(1)->find();
        if($admin){
            $this->error('该公司下存在正在使用的人员，不能删除，请将使用的人员移除后进行删除');
        }
        $where = array();
        $where['o_id'] =$o_id;
        $del_array = array();
        $del_array['o_del']=2;
        $model_organize = Model('company');
        $result = $model_organize->editOrganize($where, $del_array);
        if ($result) {
            $this->success(lang('ds_common_del_succ'), 'Company/index');
        } else {
            $this->error('删除失败');
        }
    }
    /**
     * 子公司列表导出
     */
    public function export_step1()
    {
        if(session('admin_is_super') !=1 && !in_array(7,$this->action)){
            $this->error(lang('ds_assign_right'));
        }
        $dataResult = array();
        //$headTitle = "分/子公司列表";
        $title = "分/子公司列表";
        //$headtitle= "<tr style='height:50px;border-style:none;><th border=\"0\" style='height:60px;width:270px;font-size:22px;' colspan='11' >{$headTitle}</th></tr>";
        $titlename = "<tr>
               <th style='width:70px;' >序号</th>
               <th style='width:170px;' >公司名称</th>
               <th style='width:70px;'>公司角色</th>
               <th style='width:150px;'>地区</th>
               <th style='width:170px;'>详细地址</th>
               <th style='width:100px;'>电话</th>
               <th style='width:70px;'>负责人</th>
               <th style='width:170px;'>合同截止日期</th>
               <th style='width:170px;'>创建时间</th>
               <th style='width:70px;'>备注</th>
           </tr>";

        $filename = $title.".xls";
        $model_organize = Model('company');
        $condition = array();
        $condition['o_del']=1;
        if (!empty($_GET['o_name'])) {
            $o_name=$_GET['o_name'];
            $condition['o_name']=array('like', '%' . trim($o_name) . '%');
            $this->assign('search_organize_name',$o_name);
        }
        if(!empty($_GET['o_provinceid'])){
            $o_provinceid=input('param.o_provinceid');
            $condition['o_provinceid']=$o_provinceid;
            $this->assign('o_provinceid',$o_provinceid);
        }
        if(!empty($_GET['o_cityid'])) {
                $o_cityid = input('param.o_cityid');
                $condition['o_cityid'] =$o_cityid;
                $this->assign('o_cityid', $o_cityid);
        }
        if(!empty($_GET['area_id'])){
                $area_id = input('param.area_id');
                $condition['o_areaid'] =$area_id;
                $this->assign('area_id', $area_id);
        }
        $dataResult = $model_organize->getOrganizeList($condition, "o_id,o_name,o_role,o_area,o_address,o_phone,o_leading,o_enddate,o_createtime,o_remark");
        foreach($dataResult as $key=>$v){
            if($v['o_role']==1){
                $dataResult[$key]['o_role']='总公司';
            }else if($v['o_role']==2){
                $dataResult[$key]['o_role']='省级代理';
            }else if($v['o_role']==3){
                $dataResult[$key]['o_role']='市级代理';
            }else{
                $dataResult[$key]['o_role']='特约代理';
            }
        }
        $this->excelData($dataResult,$titlename,$headtitle,$filename);
    }

    public function excelData($datas,$titlename,$title,$filename){
        $str = "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\"\r\nxmlns:x=\"urn:schemas-microsoft-com:office:excel\"\r\nxmlns=\"http://www.w3.org/TR/REC-html40\">\r\n<head>\r\n<meta http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n</head>\r\n<body>";
        $str .= $title;
        $str .="<table border=1><head>".$titlename."</head>";
        foreach ($datas as $key=> $rt )
        {
            $str .= "<tr>";
            foreach ( $rt as $k => $v )
            {
                $str .= "<td>{$v}</td>";
            }
            $str .= "</tr>\n";
        }
        header( "Content-Type: application/vnd.ms-excel; name='excel'" );
        header( "Content-type: application/octet-stream" );
        header( "Content-Disposition: attachment; filename=".$filename );
        header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
        header( "Pragma: no-cache" );
        header( "Expires: 0" );
        exit( $str );

    }
    /**
     * 管理员添加
     */
    public function admin_add() {
        if(session('admin_is_super') !=1 && !in_array(6,$this->action)){
            $this->error(lang('ds_assign_right'));
        }
        $admin_id = $this->admin_info['admin_id'];
            $model_admin = Model('admin');
            $param['admin_name'] = $_POST['admin_name'];
            $param['admin_gid'] = $_POST['gid'];
            $param['admin_password'] = md5($_POST['admin_password']);
            $param['create_uid'] = $admin_id;
            $param['admin_company_id']=$_POST['oid'];
            $param['admin_status']=1;
            $rs = $model_admin->addAdmin($param);
            if ($rs) {
                $this->log(lang('ds_add').lang('limit_admin') . '[' . $_POST['admin_name'] . ']', 1);
                echo json_encode(['m'=>true,'ms'=>lang('co_organize_succ')]);
            } else {
                echo json_encode(['m'=>true,'ms'=>lang('co_organize_succ')]);
            }

    }
}