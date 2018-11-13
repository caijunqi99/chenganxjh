<?php

namespace app\admin\controller;

use think\Lang;
use think\Model;
use think\Validate;
use vomont\Vomont;

class Import extends AdminControl
{

    public function _initialize()
    {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/student.lang.php');
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
     * @desc 导入失败
     * @author 郎志耀
     * @time 20180926
     */
    public function index(){

        if(session('admin_is_super') !=1 && !in_array('4',$this->action)){
            $this->error(lang('ds_assign_right'));
        }
        $where = ' status=2 ';
        if(!empty($_GET)){
            if(!empty($_GET['name'])){
                $where .= ' AND (m_name LIKE "%'.trim($_GET["name"]).'%" OR s_name LIKE "%'.trim($_GET["name"]).'%")';
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
                $where .= ' AND class_name LIKE "%'.trim($_GET["grade"]).'%"';
            }
            if(!empty($_GET['class'])){
                $where .= ' AND class_name LIKE "%'.trim($_GET["class"]).'%"';
            }
        }
        //查询绑定总数
        $list_count = db('import_student')->where($where)->count();
        $this->assign('list_count',$list_count);
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    /**
     * @desc 导入成功
     * @author 郎志耀
     * @time 20180926
     */
    public function success(){

        if(session('admin_is_super') !=1 && !in_array('4',$this->action)){
            $this->error(lang('ds_assign_right'));
        }
        $where = ' status=1 ';
        if(!empty($_GET)){
            if(!empty($_GET['name'])){
                $where .= ' AND (m_name LIKE "%'.trim($_GET["name"]).'%" OR s_name LIKE "%'.trim($_GET["name"]).'%")';
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
                $where .= ' AND class_name LIKE "%'.trim($_GET["grade"]).'%"';
            }
            if(!empty($_GET['class'])){
                $where .= ' AND class_name LIKE "%'.trim($_GET["class"]).'%"';
            }
        }
        //查询绑定总数
        $list_count = db('import_student')->where($where)->count();
        $this->assign('list_count',$list_count);
        $this->setAdminCurItem('success');
        return $this->fetch();
    }
    /**
     * @desc 获取分页数据
     * @author langzhiyao
     * @time 20190929
     */
    public function get_list(){
        $status = intval(input('get.status'));
        $where = ' status="'.$status.'" ';
        if(!empty($_POST)){
            if(!empty($_POST['name'])){
                $where .= ' AND (m_name LIKE "%'.trim($_GET["name"]).'%" OR s_name LIKE "%'.trim($_GET["name"]).'%")';
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
                $where .= ' AND class_name LIKE "%'.trim($_POST["grade"]).'%"';
            }
            if(!empty($_POST['class'])){
                $where .= ' AND class_name LIKE "%'.trim($_POST["class"]).'%"';
            }
        }

        $page_count = intval(input('post.page_count')) ? intval(input('post.page_count')) : 1;//每页的条数
        $start = intval(input('post.page')) ? (intval(input('post.page'))-1)*$page_count : 0;//开始页数

//        halt($start);
        //查询未绑定的摄像头
        $list = db('import_student')->where($where)->limit($start,$page_count)->order('time DESC')->select();
        $list_count = db('import_student')->where($where)->count();

        $html = '';
        if(!empty($list)){
            foreach($list as $key=>$value){
                $html .= '<tr class="hover">';
                if($value['reason_id'] == 1){
                    $html .= '<td class="align-center" style="color: red;" >'.$value["m_mobile"].'</td>';
                }else{
                    $html .= '<td class="align-center" >'.$value["m_mobile"].'</td>';
                }
                $html .= '<td class="align-center">'.$value["m_name"].'</td>';
                if($value['m_sex'] == 1){
                    $html .= '<td class="align-center">男</td>';
                }else if($value['m_sex'] == 2){
                    $html .= '<td class="align-center">女</td>';
                }else{
                    $html .= '<td class="align-center">保密</td>';
                }
                if($value['reason_id'] == 2){
                    $html .= '<td class="align-center" style="color: red;" >'.$value["s_name"].'</td>';
                }else{
                    $html .= '<td class="align-center">'.$value["s_name"].'</td>';
                }

                if($value['s_sex'] == 1){
                    $html .= '<td class="align-center">男</td>';
                }else if($value['_sex'] == 2){
                    $html .= '<td class="align-center">女</td>';
                }else{
                    $html .= '<td class="align-center">保密</td>';
                }
                if($value['reason_id'] == 3){
                    $html .= '<td class="align-center" style="color: red;" >'.$value["s_card"].'</td>';
                }else{
                    $html .= '<td class="align-center">'.$value["s_card"].'</td>';
                }
                $html .= '<td class="align-center">'.$value["school_name"].'</td>';
                if($value['reason_id'] == 4){
                    $html .= '<td class="align-center" style="color: red;" >'.$value["school_type"].'</td>';
                }else{
                    $html .= '<td class="align-center">'.$value["school_type"].'</td>';
                }
                if($value['reason_id'] == 5){
                    $html .= '<td class="align-center" style="color: red;" >'.$value["class_name"].'</td>';
                }else{
                    $html .= '<td class="align-center">'.$value["class_name"].'</td>';
                }
                $html .= '<td class="align-center">'.$value["address"].'</td>';
                $html .= '<td class="align-center">'.date('Y-m-d H:i:s',$value["time"]).'</td>';
                if($value['reason_id'] == 6){
                    $html .= '<td class="align-center" style="color: red;" >'.$value["t_name"].'</td>';
                }else{
                    $html .= '<td class="align-center">'.$value["t_name"].'</td>';
                }
                $html .= '<td class="align-center">'.round($value["t_price"],2).'元</td>';
                $html .= '<td class="align-center">'.intval($value["t_day"]).'天</td>';
                $html .= '<td class="w150 align-center">
                        <div class="layui-table-cell laytable-cell-9-8">
                           <a href="javascript:void(0)"  class="layui-btn  layui-btn-sm" data-id="'.$value["id"].'" lay-event="reset">修改</a>';
                $html .=  '</div></td>';

                $html .= '</tr>';
            }
        }
        if($html == ''){
            $html .= '<tr class="no_data">
                    <td colspan="15">没有符合条件的记录</td>
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
            $excel_success = $_SESSION['excel']['excel_success_data'];
            $excel_fail = $_SESSION['excel']['excel_fail_data'];
            /*if(empty($_SESSION['excel'])){
                exit(json_encode(array('code'=>1,'msg'=>'没有符合的数据，请重新上传')));
            }*/
            //插入成功的数据
            //开启事务
            $model = Model('import_student');
            $model_member = Model('member');
            $model_student = Model('student');
            $model_order = Model('packagesorder');
            $model_order_time = Model('packagetime');
            $model->startTrans();
            $flag = false;
            if(!empty($excel_success)){
                foreach($excel_success as $key=>$value){
                    if($value['C'] == '男'){$value['C'] =1;}else{$value['C'] = 2;}//家长性别
                    if($value['E'] == '男'){$value['E'] =1;}else{$value['E'] = 2;}//学生性别
                    //套餐ID
                    switch ($value['J']){
                        case '看孩套餐':
                            $t_id = 1;
                            break;
                        case '重温课堂套餐':
                            $t_id = 2;
                            break;
                        case '教孩套餐':
                            $t_id = 3;
                            break;
                        default:
                            $t_id = 4;
                            break;
                    }
                    $res = db('member')->field('member_id')->where(" `member_mobile` = '".$value["A"]."'")->find();
                    if(!$res){
                        $data = array(
                            'm_mobile' => $value['A'],
                            'm_name' => $value['B'],
                            'm_sex' => $value['C'],
                            's_name' => $value['D'],
                            's_sex' => $value['E'],
                            's_card' => $value['F'],
                            'school_id' => $_SESSION['excel']['school']['schoolid'],
                            'school_name' => $_SESSION['excel']['school']['name'],
                            'province_id' => $_SESSION['excel']['school']['provinceid'],
                            'city_id' => $_SESSION['excel']['school']['cityid'],
                            'area_id' => $_SESSION['excel']['school']['areaid'],
                            'address' => $_SESSION['excel']['school']['address'],
                            'sc_id' => $value['sc_id'],
                            'school_type' => $value['H'],
                            'classid' => $value['classid'],
                            'class_name' => $value['I'],
                            't_id' => $t_id,
                            't_name' => $value['J'],
                            't_price' => $value['K'],
                            't_day' => $value['L'],
                            'content' => $value['M'],
                            'status' => 1,
                            'time' => time(),
                        );
                        $import_data = $model->insert($data);
                        if($import_data){
                            //为家长开账户并发送短信通知
                            $pass = getRandomString(6,null,'n');
                            $member = array();
                            $member['member_name'] = $value['A'];
                            $member['member_nickname'] = $value['A'];
                            $member['member_password'] = md5(trim($pass));;
                            $member['member_mobile'] = $value['A'];
                            $member['member_mobile_bind'] = 1;
                            $member_id = $model_member->insertGetId($member);
                            if ($member_id) {
                                //添加学生信息
                                $student_array=array(
                                    's_name' => $value['D'],
                                    's_sex' => $value['E'],
                                    's_card' => $value['F'],
                                    's_schoolid' => $_SESSION['excel']['school']['schoolid'],
                                    's_sctype' => $_SESSION['excel']['sc_id'],//学校类型id
                                    's_classid' => $_SESSION['excel']['classid'],//班级id
                                    's_ownerAccount' => $member_id,
                                    's_provinceid' => $_SESSION['excel']['school']['provinceid'],
                                    's_cityid' => $_SESSION['excel']['school']['cityid'],
                                    's_areaid' => $_SESSION['excel']['school']['areaid'],
                                    's_region' => $_SESSION['excel']['school']['address'],
                                    'admin_company_id' => $_SESSION['excel']['school']['admin_company_id'],
                                    'option_id' => session('admin_id'),
                                    's_createtime' => date('Y-m-d',time()),

                                );
                                $student_id = $model_student->insertGetId($student_array);
                                if($student_id){

                                    //添加订单信息
                                    $this->_logic_buy_1 = \model('buy_1','logic');
                                    switch ($t_id){
                                        case 1://看孩套餐
                                            $pay_sn = $this->_logic_buy_1->makePaySn($member_id);
                                            //到期时间
                                            $endTime = time()+$value['L']*24*3600;
                                            $see_array = array(
                                                'pay_sn'=>$pay_sn,
                                                'buyer_id'=>intval($member_id),
                                                'buyer_name'=>trim($member['member_mobile']),
                                                'buyer_mobile'=>trim($member['member_mobile']),
                                                'add_time'=>time(),
                                                'payment_code'=>'offline',
                                                'payment_time'=>time(),
                                                'finnshed_time'=>time(),
                                                'pkg_name'=>trim($value['J']).'（线下）',
                                                'pkg_price'=>ncPriceFormatb($value['K']),
                                                'pkg_length'=>intval($value['L']),
                                                's_id'=>intval($student_id),
                                                's_name'=>trim($value['D']),
                                                'schoolid'=>intval($_SESSION['excel']['school']['schoolid']),
                                                'name'=>trim($_SESSION['excel']['school']['name']),
                                                'classid'=>intval($value['classid']),
                                                'classname'=>trim($value['I']),
                                                'order_amount'=>ncPriceFormatb($value['K']),
                                                'order_state'=>'40',
                                                'order_dieline'=>$endTime,
                                                'option_id'=>intval($_SESSION['excel']['school']['option_id']),
                                                'over_amount'=>ncPriceFormatb($value['K']),
                                                'admin_company_id'=>intval($_SESSION['excel']['school']['admin_company_id']),
                                            );
                                            $order_pay_id =$model_order->insertGetId($see_array);
                                            if($order_pay_id){
                                                $order_sn = $this->_logic_buy_1->makeOrderSn($order_pay_id);
                                                $order_pay = $model_order->where('order_id="'.$order_pay_id.'"')->update(array('order_sn'=>$order_sn));
                                                if($order_pay){
                                                    $desc = date('Y-m-d H:i',time()).'第一次购买看孩套餐,套餐到期时间:'.date('Y-m-d H:i',$endTime);
                                                    $see_end = array(
                                                        'member_id'=>intval($member_id),
                                                        'member_name'=>trim($member['member_mobile']),
                                                        's_id'=>intval($student_id),
                                                        's_name'=>trim($value['D']),
                                                        'start_time'=>time(),
                                                        'end_time'=>$endTime,
                                                        'up_time'=>time(),
                                                        'up_desc'=>$desc,
                                                    );
                                                    $order_pay_time = $model_order_time->insert($see_end);
                                                    if($order_pay_time){

                                                        $flag = true;
                                                    }else{
                                                        $flag = false;
                                                    }

                                                }else{
                                                    $flag = false;
                                                }
                                            }else{
                                                $flag = false;
                                            }
                                            break;
                                        case 2://重温课堂套餐
                                            break;
                                        case 3://教孩套餐
                                            break;
                                    }
                                }else{
                                    $flag = false;
                                }
                            }else{
                                $flag=false;
                            }
                        }else{
                            $flag = false;
                        }
                        if($flag){
                            //发送随机密码
                            //生成数字字符随机 密码
                            $sms_tpl = config('sms_tpl');
                            $tempId = $sms_tpl['sms_password_reset'];
                            $sms = new \sendmsg\Sms();
                            $pass = '您于'.date('Y-m-d H:i:s',time()).'开通想见孩账号，您的账号是:'.$member['member_mobile'].'密码是：'.$pass;
                            $send = $sms->send($member['member_mobile'],$pass,$tempId);
                            if($send){
                                $model->commit();
                            }else{
                                $model->rollback();
                            }
                        }else{
                            $model->rollback();
                        }
                    }
                }
            }
            //插入失败的数据
            if(!empty($excel_fail)){
                foreach($excel_fail as $key=>$value){
                    if($value['C'] == '男'){$value['C'] =1;}else{$value['C'] = 2;}//家长性别
                    if($value['E'] == '男'){$value['E'] =1;}else{$value['E'] = 2;}//学生性别
                    //套餐ID
                    switch ($value['J']){
                        case '看孩套餐':
                            $t_id = 1;
                            break;
                        case '重温课堂套餐':
                            $t_id = 2;
                            break;
                        case '教孩套餐':
                            $t_id = 3;
                            break;
                        default:
                            $t_id = 4;
                            break;
                    }
                    $data = array(
                        'm_mobile' => $value['A'],
                        'm_name' => $value['B'],
                        'm_sex' => $value['C'],
                        's_name' => $value['D'],
                        's_sex' => $value['E'],
                        's_card' => $value['F'],
                        'school_id' => $_SESSION['excel']['school']['schoolid'],
                        'school_name' => $_SESSION['excel']['school']['name'],
                        'province_id' => $_SESSION['excel']['school']['provinceid'],
                        'city_id' => $_SESSION['excel']['school']['cityid'],
                        'area_id' => $_SESSION['excel']['school']['areaid'],
                        'address' => $_SESSION['excel']['school']['address'],
                        'sc_id' => '',
                        'school_type' => $value['H'],
                        'classid' => '',
                        'class_name' => $value['I'],
                        't_id' => $t_id,
                        't_name' => $value['J'],
                        't_price' => $value['K'],
                        't_day' => $value['L'],
                        'content' => $value['M'],
                        'status' => 2,
                        'time' => time(),
                        'reason' => $value['error'],
                        'reason_id' => $value['error_id'],
                    );
                    $import_data = $model->insert($data);
                    if($import_data){
                        $model->commit();
                    }else{
                        $model->rollback();
                    }
                }
            }
            exit(json_encode(array('code'=>0,'msg'=>'导入成功')));
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
        $where = '';
        if(!empty($_GET)){
            $where = $this->_conditions($_GET);
        }

        $list_count = db('camera')->where($where)->count();

        $this->assign('list_count',$list_count);
        $this->setAdminCurItem('entered');
        return $this->fetch('entered');
    }
    /**
     * 摄像头查询过滤
     * @创建时间   2018-11-03T00:39:28+0800
     * @param  [type]                   $where [description]
     * @return [type]                          [description]
     */
    public function _conditions($where){
        if (isset($where['name']) && !empty($where['name'])) {
            $condition['name'] = array('LIKE','%'.$where['name'].'%');
        }
        $res = array();
        $name = false;
        if (isset($where['class']) && !empty($where['class']) ) {
            $class = $this->getResGroupIds(array('classname'=>$where['class']));
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
        $classname = '';
        if (isset($where['classname']) && !empty($where['classname']) ) {
            $classname = $where['classname'];
            unset($where['classname']);
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
        if (!empty($classname)) {
            $where['classname'] = array('like','%'.$classname.'%');
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
    /**
     * @desc 获取分页数据
     * @author langzhiyao
     * @time 20190929
     */
    public function get_entered_list(){

        $where = ' 1=1 ';
        if(!empty($_POST)){
            // p($_POST);exit;
            $cond = array();
            foreach ($_POST as $key => $p) {
                if(!in_array($key, ['page','page_count']) && !empty($p))$cond[$key]=$p;
            }
            if ($cond) {
                $where = $this->_conditions($cond);
            }
        }

        $page_count = intval(input('post.page_count')) ? intval(input('post.page_count')) : 1;//每页的条数
        $start = intval(input('post.page')) ? (intval(input('post.page'))-1)*$page_count : 0;//开始页数

//        halt($start);
        //查询已安装的摄像头
        $list = db('camera')->where($where)->limit($start,$page_count)->order('cid DESC')->select();
        $date=date('H:i',time());
        foreach($list as $k=>$v){
            if($v['online']==0){
                $list[$k]['statuses']=2;
            }else{
                if($v['status']==1){
                    if(!empty($v['begintime'])&&!empty($v['endtime'])){
                        $begintime=date('H:i',$v['begintime']);
                        $endtime=date('H:i',$v['endtime']);
                        if($date<$begintime||$date>$endtime){
                            $list[$k]['statuses']=2;
                        }else{
                            $list[$k]['statuses']=1;
                        }
                    }else{
                        $list[$k]['statuses']=1;
                    }
                }else{
                    $list[$k]['statuses']=2;
                }
            }
        }
        //return $list;exit;
        $list_count = db('camera')->where($where)->count();
        $html = '';
        if(!empty($list)){
            foreach($list as $key=>$v){
                $datainfo = json_encode($v);
                $html .= "<tr class='hover' id='tr_".$v['cid']."' datainfo='".$datainfo."'>";
                $html .= '<td class="align-center">'.$v["name"].'</td>';
                $html .= '<td class="align-center">'.$v["channelid"].'</td>';
                $html .= '<td class="align-center">'.$v["deviceid"].'</td>';
                $html .= '<td class="align-center">'.$v["id"].'</td>';
                if($v['statuses'] == 1){
                    $html .= '<td class="align-center"><b style="color:green;">在线</b></td>';
                }else if($v['statuses'] == 2){
                    $html .= '<td class="align-center"><b style="color:red;">离线</b></td>';
                }
                $html .= '<td class="align-center">'.$v["parentid"].'</td>';
                $html .= '<td class="align-center"><img src="'.$v["imageurl"].'" width="120" height="50"></td>';
                $html .= '<td id="rmt_'.$v['cid'].'" class="align-center"><img onClick="rtmplay('.$v['cid'].')" src="'.$v["imageurl"].'" width="120" height="50"></td>';
                if($v['is_classroom'] == 1){
                    $html .= '<td class="align-center"><b style="color:red;">否</b></td>';
                }else if($v['is_classroom'] == 2){
                    $html .= '<td class="align-center"><b style="color:green;">是</b></td>';
                }
                if($v['status'] == 1){
                    $html .= '<td class="align-center">开启</td>';
                }else if($v['status'] == 2){
                    $html .= '<td class="align-center">关闭</td>';
                }
                $html .= '<td class="align-left">'.date('Y-m-d H:i:s',$v["sq_time"]).'</td>';
                if(!empty($v['begintime'])){
                    $html .= '<td class="align-center">开启时间：'.date('H:i',$v["begintime"]);
                }else{
                    $html .= '<td class="align-center">开启时间：未设置';
                }
                if(!empty($v['endtime'])) {
                    $html .= '<hr>关闭时间：' . date('H:i', $v['endtime']) . '</td>';
                }else{
                    $html .= '<hr>关闭时间：未设置</td>';
                }
                $html .= '</tr>';
            }
        }
        if($html == ''){
            $html .= '<tr class="no_data">
                    <td colspan="12">没有符合条件的记录</td>
                </tr>';
        }

        exit(json_encode(array('html'=>$html,'count'=>$list_count)));

    }
    /**
     * 自动导入摄像头
     */
    public function get_camera(){
        $model_school = Model('school');
        $condition=array();
        $condition['isdel']=1;
        $school=$model_school->getSchoolList($condition);
        $shu=array();
        foreach($school as $v){
            if($v['res_group_id']!=0){
                $shu[] = $v['res_group_id'];
            }
        }
        $model_class=Model('classes');
        $where=array();
        $where['isdel']=1;
        $class=$model_class->getAllClasses($where);
        foreach($class as $v){
            if($v['res_group_id']!=0){
                $shu[]=$v['res_group_id'];
            }
        }
        $vlink = new Vomont();
        $res= $vlink->SetLogin();
        $accountid=$res['accountid'];
        $data='';
        foreach($shu as $v){
            $datas=$vlink->SetPlay($accountid,$v);
            if(empty($data)) {
                $data = $datas['resources'];
            }else{
                $data = array_merge($data,$datas['resources']);
            }
        }
        foreach($data as $k=>$v){
            $play=$v['deviceid'].'-'.$v['channelid'].',';
            $video=$vlink->Resources($accountid,$play);
            $data[$k]['imageurl']=$video['channels'][0]['imageurl'];
            $data[$k]['rtmpplayurl']=$video['channels'][0]['rtmpplayurl'];
            $data[$k]['sq_time']=time();
            $data[$k]['status']=1;
            $data[$k]['is_classroom']=1;
        }
        $model_camera=Model('camera');
        $result=$model_camera->getCameraList('','','id');
        $ret=$this->get_diff_array_by_pk($data,$result);
        $sult=$model_camera->cameras_add($ret);
        if($sult){
            echo json_encode(array('count'=>$sult));
        }else{
            echo json_encode(array('count'=>0));
        }
    }
    function get_diff_array_by_pk($arr1,$arr2,$pk='id'){
        try{
            $res=[];
            foreach($arr2 as $item) $tmpArr[$item[$pk]] = $item;
            foreach($arr1 as $v) if(! isset($tmpArr[$v[$pk]])) $res[] = $v;
            return $res;
        }catch (\Exception $exception){
            return $arr1;
        }
    }

    /**
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'index',
                'text' => '导入失败列表',
                'url' => url('Admin/Import/index')
            ),
            array(
                'name' => 'success',
                'text' => '导入成功列表',
                'url' => url('Admin/Import/success')
            )
        );
        return $menu_array;
    }

}