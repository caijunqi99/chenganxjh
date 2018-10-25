<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Camera extends AdminControl
{

    public function _initialize()
    {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/look.lang.php');
        //获取当前角色对当前子目录的权限
        $class_name = strtolower(end(explode('\\',__CLASS__)));
        $perm_id = $this->get_permid($class_name);
        $this->action = $action = $this->get_role_perms(session('admin_gid') ,$perm_id);
        $this->assign('action',$action);
        //获取省份
        $province = db('area')->field('area_id,area_parent_id,area_name')->where('area_parent_id=0')->select();
        //获取学校
        $school = db('school')->field('schoolid,name')->select();
        $this->assign('school',$school);
        $this->assign('province',$province);
    }

    /**
     * @desc 摄像头管理
     * @author 郎志耀
     * @time 20180926
     */
    public function camera(){
        if(session('admin_is_super') !=1 && !in_array('4',$this->action)){
            $this->error(lang('ds_assign_right'));
        }
        $where = ' status=2 ';
        if(!empty($_GET)){
            if(!empty($_GET['name'])){
                $where .= ' AND camera_name LIKE "%'.trim($_GET["name"]).'%" ';
            }
            if(!empty($_GET['province'])){
                $where .= ' AND province_id = "'.intval($_GET["province"]).'"';
            }
            if(!empty($_GET['city'])){
                $where .= ' AND city_id = "'.intval($_GET["city"]).'"';
            }
            if(!empty($_GET['area'])){
                $where .= ' AND area_id = "'.intval($_GET["area"]).'"';
            }
            if(!empty($_GET['school'])){
                $where .= ' AND school_id = "'.intval($_GET["school"]).'"';
            }
            if(!empty($_GET['grade'])){
                $where .= ' AND class_area LIKE "%'.trim($_GET["grade"]).'%"';
            }
            if(!empty($_GET['class'])){
                $where .= ' AND class_area LIKE "%'.trim($_GET["class"]).'%"';
            }
        }


        $page_count = intval(input('get.page_count')) ? intval(input('get.page_count')) : 1;//每页的条数
        $start = intval(input('get.page')) ? (intval(input('get.page'))-1)*$page_count : 0;//开始页数

        //查询未绑定的摄像头
//        $list = db('camera')->where($where)->limit($start,$page_count)->order('sq_time DESC')->select();
        $list_count = db('camera')->where($where)->count();

//        halt($list);

//        $this->assign('list',$list);
        $this->assign('list_count',$list_count);
        $this->setAdminCurItem('camera');
        return $this->fetch('camera');
    }
    /**
 * @desc 获取分页数据
 * @author langzhiyao
 * @time 20190929
 */
    public function get_list(){

        $where = ' status=2 ';
        if(!empty($_POST)){
            if(!empty($_POST['name'])){
                $where .= ' AND camera_name LIKE "%'.trim($_POST["name"]).'%" ';
            }
            if(!empty($_POST['province'])){
                $where .= ' AND province_id = "'.intval($_POST["province"]).'"';
            }
            if(!empty($_POST['city'])){
                $where .= ' AND city_id = "'.intval($_POST["city"]).'"';
            }
            if(!empty($_POST['area'])){
                $where .= ' AND area_id = "'.intval($_POST["area"]).'"';
            }
            if(!empty($_POST['school'])){
                $where .= ' AND school_id = "'.intval($_POST["school"]).'"';
            }
            if(!empty($_POST['grade'])){
                $where .= ' AND class_area LIKE "%'.trim($_POST["grade"]).'%"';
            }
            if(!empty($_POST['class'])){
                $where .= ' AND class_area LIKE "%'.trim($_POST["class"]).'%"';
            }
        }

        $page_count = intval(input('post.page_count')) ? intval(input('post.page_count')) : 1;//每页的条数
        $start = intval(input('post.page')) ? (intval(input('post.page'))-1)*$page_count : 0;//开始页数

//        halt($start);
        //查询未绑定的摄像头
        $list = db('camera')->where($where)->limit($start,$page_count)->order('sq_time DESC')->select();
        $list_count = db('camera')->where($where)->count();

        $html = '';
        if(!empty($list)){
            foreach($list as $key=>$value){
                $html .= '<tr class="hover">';
                $html .= '<td class="align-center">'.$value["camera_name"].'</td>';
                $html .= '<td class="align-center">'.$value["class_area"].'</td>';
                if($value['is_public_area'] == 1){
                    $html .= '<td class="align-center">是</td>';
                }else if($value['is_public_area'] == 2){
                    $html .= '<td class="align-center">否</td>';
                }
                $html .= '<td class="align-center">'.$value["school_name"].'</td>';
//                $html .= '<td class="align-center">'.$value["address"].'</td>';
                $html .= '<td class="align-center">'.date('Y-m-d H:i:s',$value["sq_time"]).'</td>';
                $html .= '<td class="align-center">'.$value["sn"].'</td>';
                $html .= '<td class="align-center">'.$value["key"].'</td>';
                $html .= '<td class="align-center">'.$value["agent_name"].'</td>';
                $html .= '<td class="align-center">'.$value["content"].'</td>';
                $html .= '<td class="align-center" style="color:#0FB700;">待绑定</td>';
                $html .= '<td class="w150 align-center">
                        <div class="layui-table-cell laytable-cell-9-8">
                           <a href="javascript:void(0)"  class="layui-btn  layui-btn-sm" lay-event="reset">绑定设备信息</a>';
                $html .=  '</div></td>';

                $html .= '</tr>';
            }
        }
        if($html == ''){
            $html .= '<tr class="no_data">
                    <td colspan="11">没有符合条件的记录</td>
                </tr>';
        }

        exit(json_encode(array('html'=>$html,'count'=>$list_count)));

    }


    /**
     * @desc 确认导入excel表和学校
     * @author langzhiyao
     * @time 20180926
     */
    public function download(){
        if(session('admin_is_super') !=1 && !in_array('8',$this->action)){
            $this->error(lang('ds_assign_right'));
        }
        $this->setAdminCurItem('camera');
        return $this->fetch('download');
    }

    /**
     * @desc 再次确认插入数据表的内容
     * @author langzhiyao
     * @time 20180926
     */
    public function excelTrue(){
        if(session('admin_is_super') !=1 && !in_array('8',$this->action)){
            $this->error(lang('ds_assign_right'));
        }

        $this->setAdminCurItem('camera');
        return $this->fetch('excelTrue');
    }

    /**
     * @desc 将exce数据插入表中
     * @author langzhiyao
     * @time 20180928
     */
    public function insert_excel(){
        if(session('admin_is_super') !=1 && !in_array('8',$this->action)){
            $this->error(lang('ds_assign_right'));
        }
//        halt($_SESSION['excel']);
        if(!empty($_SESSION['excel'])){
            $excel = $_SESSION['excel']['excel_data'];
            if(empty($excel)){
                exit(json_encode(array('code'=>1,'msg'=>'没有符合的数据，请重新上传')));
            }
            foreach($excel as $key=>$value){
                $res = db('camera')->field('id,sn,key')->where(" `key` = '".$value["G"]."'")->find();

                if($value['C'] == '是'){$value['C'] =1;}else{$value['C'] = 2;}

                if(!$res){
                    $data = array(
                        'camera_name' => $value['A'],
                        'class_area' => $value['B'],
                        'is_public_area' => $value['C'],
                        'school_id' => $_SESSION['excel']['school']['schoolid'],
                        'school_name' => $_SESSION['excel']['school']['name'],
                        'province_id' => $_SESSION['excel']['school']['provinceid'],
                        'city_id' => $_SESSION['excel']['school']['cityid'],
                        'area_id' => $_SESSION['excel']['school']['areaid'],
                        'address' => $_SESSION['excel']['school']['address'],
                        'sq_time' => time(),
                        'sn' => $value['F'],
                        'key' => $value['G'],
                        'content' => $value['I'],
                        'agent_id' => $_SESSION['excel']['agent']['agent_id'],
                        'agent_name' => $_SESSION['excel']['agent']['agent_name'],
                    );
                    db('camera')->insert($data);
                }
            }
            exit(json_encode(array('code'=>0,'msg'=>'导入成功，请前往绑定设备')));
        }else{
            exit(json_encode(array('code'=>1,'msg'=>'上传的文件数据失效，请重新上传')));
        }

    }



    /**
     * @desc 摄像头 已录入管理
     * @author 郎志耀
     * @time 20180926
     */
    public function entered(){
        if(session('admin_is_super') !=1 && !in_array('4',$this->action)){
            $this->error(lang('ds_assign_right'));
        }
        $where = ' status=1 ';
        if(!empty($_GET)){
            if(!empty($_GET['name'])){
                $where .= ' AND camera_name LIKE "%'.trim($_GET["name"]).'%" ';
            }
            if(!empty($_GET['province'])){
                $where .= ' AND province_id = "'.intval($_GET["province"]).'"';
            }
            if(!empty($_GET['city'])){
                $where .= ' AND city_id = "'.intval($_GET["city"]).'"';
            }
            if(!empty($_GET['area'])){
                $where .= ' AND area_id = "'.intval($_GET["area"]).'"';
            }
            if(!empty($_GET['school'])){
                $where .= ' AND school_id = "'.intval($_GET["school"]).'"';
            }
            if(!empty($_GET['grade'])){
                $where .= ' AND class_area LIKE "%'.trim($_GET["grade"]).'%"';
            }
            if(!empty($_GET['class'])){
                $where .= ' AND class_area LIKE "%'.trim($_GET["class"]).'%"';
            }
        }

        $list_count = db('camera')->where($where)->count();

        $this->assign('list_count',$list_count);
        $this->setAdminCurItem('entered');
        return $this->fetch('entered');
    }
    /**
     * @desc 获取分页数据
     * @author langzhiyao
     * @time 20190929
     */
    public function get_entered_list(){

        $where = ' status=1 ';
        if(!empty($_POST)){
            if(!empty($_POST['name'])){
                $where .= ' AND camera_name LIKE "%'.trim($_POST["name"]).'%" ';
            }
            if(!empty($_POST['province'])){
                $where .= ' AND province_id = "'.intval($_POST["province"]).'"';
            }
            if(!empty($_POST['city'])){
                $where .= ' AND city_id = "'.intval($_POST["city"]).'"';
            }
            if(!empty($_POST['area'])){
                $where .= ' AND area_id = "'.intval($_POST["area"]).'"';
            }
            if(!empty($_POST['school'])){
                $where .= ' AND school_id = "'.intval($_POST["school"]).'"';
            }
            if(!empty($_POST['grade'])){
                $where .= ' AND class_area LIKE "%'.trim($_POST["grade"]).'%"';
            }
            if(!empty($_POST['class'])){
                $where .= ' AND class_area LIKE "%'.trim($_POST["class"]).'%"';
            }
        }

        $page_count = intval(input('post.page_count')) ? intval(input('post.page_count')) : 1;//每页的条数
        $start = intval(input('post.page')) ? (intval(input('post.page'))-1)*$page_count : 0;//开始页数

//        halt($start);
        //查询未绑定的摄像头
        $list = db('camera')->where($where)->limit($start,$page_count)->order('sq_time DESC')->select();
        $list_count = db('camera')->where($where)->count();

        $html = '';
        if(!empty($list)){
            foreach($list as $key=>$value){
                $html .= '<tr class="hover">';
                $html .= '<td class="align-center">'.$value["camera_name"].'</td>';
                $html .= '<td class="align-center">'.$value["class_area"].'</td>';
                if($value['is_public_area'] == 1){
                    $html .= '<td class="align-center">是</td>';
                }else if($value['is_public_area'] == 2){
                    $html .= '<td class="align-center">否</td>';
                }
                $html .= '<td class="align-center">'.$value["school_name"].'</td>';
//                $html .= '<td class="align-center">'.$value["address"].'</td>';
                $html .= '<td class="align-center">'.date('Y-m-d H:i:s',$value["sq_time"]).'</td>';
                $html .= '<td class="align-center">'.$value["sn"].'</td>';
                $html .= '<td class="align-center">'.$value["key"].'</td>';
                $html .= '<td class="align-center">'.$value["agent_name"].'</td>';
                $html .= '<td class="align-center">'.$value["content"].'</td>';
                $html .= '<td class="align-center" style="color:#E00515;">已录入</td>';
                $html .= '<td class="w150 align-center">
                        <div class="layui-table-cell laytable-cell-9-8">
                           <a href="javascript:void(0)" onclick="return edit('.$value["id"].');" class="layui-btn  layui-btn-sm" lay-event="reset">修改设备信息</a>';
                $html .=  '</div></td>';

                $html .= '</tr>';
            }
        }
        if($html == ''){
            $html .= '<tr class="no_data">
                    <td colspan="11">没有符合条件的记录</td>
                </tr>';
        }

        exit(json_encode(array('html'=>$html,'count'=>$list_count)));

    }

    /**
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'camera',
                'text' => '待绑定',
                'url' => url('Admin/Camera/camera')
            ),
            array(
                'name' => 'entered',
                'text' => '已录入',
                'url' => url('Admin/Camera/entered')
            )
        );
        return $menu_array;
    }

}