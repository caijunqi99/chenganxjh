<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;


class Organize extends AdminControl {
    public function _initialize()
    {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/brand.lang.php');
    }
    /**
     *
     * 分子公司管理列表
     */
    public function index()
    {
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
        $model_organize = Model('organize');
        $condition = array();
        $condition['o_del']=1;
        if (!empty($_POST['search_organize_name'])) {
            $o_name=$_POST['search_organize_name'];
            $condition['o_name']=array('like', '%' . trim($o_name) . '%');
            $this->assign('search_organize_name',$o_name);
        }
        if(!empty($_POST['area_info'])){
            $area_info=$_POST['area_info'];
            $condition['o_area']=array('like', '%' . trim($area_info) . '%');
            $this->assign('o_area',$o_area);
        }
        $organize_list = $model_organize->getOrganizeList($condition, "*", 15);
        $this->assign('page', $model_organize->page_info->render());
        $this->assign('organize_list', $organize_list);
        $this->setAdminCurItem('index');
        return $this->fetch();
    }
    /**
     * 获取分/子公司栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'index',
                'text' => '分/子（代理）公司管理',
                'url' => url('Admin/Organize/index')
            ),
            array(
                'name' => 'add',
                'text' => '添加',
                'url' => url('Admin/Organize/add')
            )
        );
        if (request()->action() == 'edit') {
            $menu_array[1] = array(
                'name' => 'organize_edit', 'text' => '编辑', 'url' => url('Admin/Organize/edit')
            );
        }
        return $menu_array;
    }
    /**
     * 子公司新增
    */
    public function add() {
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
            $input['o_phone'] = intval($_POST['o_phone']);
            $input['o_leading'] = trim($_POST['o_leading']);
            $input['o_enddate'] = trim($_POST['activity_end_date']);
            $input['o_createtime']=date('Y-m-d H:i:s',time());
            $input['o_remark'] = trim($_POST['o_remark']);
            $input['o_del']=1;
            $activity = Model('organize');
            $result = $activity->addOrganize($input);
            if ($result) {
                //$this->log(lang('ds_add') . lang('brand_index_brand') . '[' . $_POST['brand_name'] . ']', 1);
                $this->success(lang('ds_common_save_succ'),'organize/index');
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
        $model_organize = Model('organize');
        if (request()->isPost()) {
            $where = array();
            $where['o_id'] = intval($_POST['o_id']);
            $update_array = array();
            $update_array['o_name'] = trim($_POST['o_name']);
            $update_array['o_role'] = intval($_POST['o_role']);
            //$update_array['o_provinceid'] = intval($_POST['o_provinceid']);
            //$update_array['o_cityid'] = intval($_POST['city_id']);
            $update_array['o_areaid'] = intval($_POST['area_id']);
            $update_array['o_area'] = trim($_POST['area_info']);
            $update_array['o_address'] = trim($_POST['o_address']);
            $update_array['o_phone'] = intval($_POST['o_phone']);
            $update_array['o_leading'] = trim($_POST['o_leading']);
            $update_array['o_enddate'] = trim($_POST['activity_end_date']);
            $update_array['o_createtime'] = date('Y-m-d H:i:s', time());
            $update_array['o_remark'] = trim($_POST['o_remark']);
            $result = $model_organize->editOrganize($where, $update_array);
            if ($result) {
                //$this->log(lang('ds_edit') . lang('brand_index_brand') . '[' . $_POST['brand_name'] . ']', 1);
                $this->success(lang('ds_common_save_succ'), 'organize/index');
            } else {
                //$this->log(lang('ds_edit').lang('brand_index_brand') . '[' . $_POST['brand_name'] . ']', 0);
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
        $where = array();
        $where['o_id'] = intval($_GET['o_id']);
        $del_array = array();
        $del_array['o_del']=2;
        $model_organize = Model('organize');
        $result = $model_organize->editOrganize($where, $del_array);
        $this->success(lang('ds_common_del_succ'));
    }
    /**
     * 子公司列表导出
     */
    public function export_step1()
    {
        $dataResult = array();
        $headTitle = "分/子公司列表";
        $title = "分/子公司列表";
        $headtitle= "<tr style='height:50px;border-style:none;><th border=\"0\" style='height:60px;width:270px;font-size:22px;' colspan='11' >{$headTitle}</th></tr>";
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
        $model_organize = Model('organize');
        $condition = array();
        $condition['o_del']=1;
        if (!empty($_GET['o_name'])) {
            $o_name=$_GET['o_name'];
            $condition['o_name']=array('like', '%' . trim($o_name) . '%');
            $this->assign('search_organize_name',$o_name);
        }
        if(!empty($_POST['area_info'])){
            $area_info=$_POST['area_info'];
            $condition['o_area']=array('like', '%' . trim($area_info) . '%');
            $this->assign('o_area',$o_area);
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
}