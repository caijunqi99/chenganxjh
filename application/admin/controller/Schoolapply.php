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
        $admininfo = $this->getAdminInfo();
        $applyid = input('param.applyid');
        if (empty($applyid)) {
            $this->error(lang('param_error'));
        }
        $model_schoolapply = Model('schoolapply');
        $where = array(array('status'=>2,'auditor'=>$admininfo['admin_id'],'auditortime'=>date("Y-m-d H:i:s",time())));
        $result = $model_schoolapply->editSchoolapply($where,array('applyid'=>$applyid));
        if ($result) {
            $this->success(lang('school_index_applydealSuccess'), 'Schoolapply/index');
        } else {
            $this->error('处理失败');
        }
    }

    public function fand_schooltype(){
        $sc_id = intval(input('post.sc_id'));
        $Schooltype = model('Schooltype');
        $model_schoolapply = model('Schoolapply');
        $schoolapplyInfo = $model_schoolapply->getSchoolapplyById($sc_id);
        if($schoolapplyInfo){
            $Schooltype_list = $Schooltype->get_sctype_List(array('sc_id'=>array('in',$schoolapplyInfo['sc_type'])));
            echo json_encode($Schooltype_list);
        }
    }

    public function fand_classname(){
        $ty_id = intval(input('post.ty_id'));
        $sc_id = intval(input('post.sc_id'));
        $where = ['applyid'=>$sc_id,'sc_type'=>$ty_id];
        if($ty_id && $sc_id){
            $classList = db('schoolapply')->where($where)->select();
            echo json_encode($classList);
        }
    }

    /**
     * 导出
     */
    public function excel()
    {
        $dataResult = array();
        $title = "学校申请审核";
        ob_end_clean();

        ob_start();
        $headtitle= "<tr style='height:50px;border-style:none;><th border=\"0\" style='height:60px;width:270px;font-size:22px;' colspan='11' >{$headTitle}</th></tr>";
        $titlename = "<tr>
               <th style='width:70px;' >序号</th>
               <th style='width:170px;' >学校名称</th>
               <th style='width:170px;'>所在地区</th>
               <th style='width:120px;'>负责/联系人</th>
               <th style='width:130px;'>电话</th>
               <th style='width:170px;'>留言内容</th>
               <th style='width:150px;'>添加/修改时间</th>
               <th style='width:170px;'>状态</th>
               <th style='width:100px;'>审核人</th>
               <th style='width:150px;'>审核时间</th>
           </tr>";

        $filename = $title.".xlsx";
        $model_apply = Model('schoolapply');
        $condition = array();
//        if (!empty($_GET['o_name'])) {
//            $o_name=$_GET['o_name'];
//            $condition['o_name']=array('like', '%' . trim($o_name) . '%');
//            $this->assign('search_organize_name',$o_name);
//        }
//        if(!empty($_POST['area_info'])){
//
//            $area_info=$_POST['area_info'];
//            $condition['o_area']=array('like', '%' . trim($area_info) . '%');
//            $this->assign('o_area',$o_area);
//        }
        $dataResults = $model_apply->getSchoolapplyList($condition);
        foreach ($dataResults as $k=>$v){
            $dataResult[$k]['applyid'] = $v['applyid'];
            $dataResult[$k]['schoolname'] = $v['schoolname'];
            $dataResult[$k]['region'] = $v['region'];
            $dataResult[$k]['username'] = $v['username'];
            $dataResult[$k]['phone'] = $v['phone'];
            $dataResult[$k]['message'] = $v['message'];
            $dataResult[$k]['createtime'] = $v['createtime'];
            $dataResult[$k]['status'] = $v['status']==1?"处理中":"已处理";
            $memberinfo = db('member')->where(array('member_id'=>$v['auditor']))->find();
            $dataResult[$k]['auditor'] = $memberinfo['member_name'];
            $dataResult[$k]['auditortime'] = $v['auditortime'];
        }
        //print_r($dataResult);die;
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
