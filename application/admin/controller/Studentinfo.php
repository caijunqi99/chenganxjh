<?php

namespace app\admin\controller;

use think\Lang;
use think\Validate;

class Studentinfo extends AdminControl {

    public function _initialize() {
        parent::_initialize();
        Lang::load(APP_PATH . 'admin/lang/zh-cn/school.lang.php');
    }

    public function index(){
        $student_id = input('param.student_id');
        if (empty($student_id)) {
            $this->error(lang('param_error'));
        }
        $model_student = Model('Student');
        $student_array = $model_student->getStudentInfo(array("s_id"=>$student_id));
        $this->assign('student_array', $student_array);
        //学校名称
        $schoolname = db('school')->where('schoolid',$student_array['s_schoolid'])->find();
        $this->assign('schoolname', $schoolname['name']);
        //学校类型
        $schooltype = db('schooltype')->where('sc_enabled','1')->select();
        $typeids = explode(',',$schoolname['typeid']);
        foreach ($schooltype as $k=>$v){
            foreach ($typeids as $key=>$item){
                if($item ==$v['sc_id']){
                    $type[$item] = $v['sc_type'];
                }
            }
        }
        $this->assign('schooltype', $type);
        //班级名称
        $classname = db('class')->where('classid',$student_array['s_classid'])->find();
        $this->assign('classname', $classname['classname']);
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    public function lists() {
        $model_student = model('Student');
        $condition = array();
        $condition['s_id'] = input('param.student_id');
        $studentInfo = $model_student->getStudentInfo($condition);
        //主账号
        $member = db('member')->where(['member_id'=>$studentInfo['s_ownerAccount']])->select();
        $member[0]['member_add_time']=date('Y-m-d H:i:s',$member[0]['member_add_time']);
        //副账户
        if(!empty($studentInfo['s_viceAccount'])){
            $viceAccountids = explode(',',$studentInfo['s_viceAccount']);
            foreach ($viceAccountids as $key=>$item){
                $member_vice = db('member')->where(['member_id'=>$item])->find();
                $viceAccount[$key]['member_id'] = $member_vice['member_id'];
                $viceAccount[$key]['member_name'] = $member_vice['member_name'];
                $viceAccount[$key]['member_desc'] = $member_vice['member_desc'];
                $viceAccount[$key]['member_add_time'] = date('Y-m-d H:i:s',$member_vice['member_add_time']);
            }
            $member = array_merge($member,$viceAccount);
        }
        $this->assign('member', $member);
        $this->setAdminCurItem('lists');
        return $this->fetch();
    }

    //看孩订单
    public function order() {
        $student_id = input('param.student_id');
        $lookOrder = db('packagesorder')->where(array('student_id'=>$student_id,'order_type'=>1))->select();
//        $class_list = $model_class->getClasslList($condition, 10);
//        $this->assign('page', $model_class->page_info->render());
        $this->assign('lookOrder', $lookOrder);
        $this->setAdminCurItem('order');
        return $this->fetch();
    }

    //教孩订单
    public function teachorder() {
        $student_id = input('param.student_id');
        $lookOrder = db('packagesorder')->where(array('student_id'=>$student_id,'order_type'=>2))->select();
//        $class_list = $model_class->getClasslList($condition, 10);
//        $this->assign('page', $model_class->page_info->render());
        $this->assign('lookOrder', $lookOrder);
        $this->setAdminCurItem('teachorder');
        return $this->fetch();
    }

    //重温课堂订单
    public function reviveorder() {
        $student_id = input('param.student_id');
        $lookOrder = db('packagesorder')->where(array('student_id'=>$student_id,'order_type'=>3))->select();
//        $class_list = $model_class->getClasslList($condition, 10);
//        $this->assign('page', $model_class->page_info->render());
        $this->assign('lookOrder', $lookOrder);
        $this->setAdminCurItem('reviveorder');
        return $this->fetch();
    }

    //商城订单
    public function shopping() {
        $student_id = input('param.student_id');
        $lookOrder = db('order')->where(array('student_id'=>$student_id))->select();
//        $class_list = $model_class->getClasslList($condition, 10);
//        $this->assign('page', $model_class->page_info->render());
        $this->assign('lookOrder', $lookOrder);
        $this->setAdminCurItem('shopping');
        return $this->fetch();
    }

    /**
     * 获取卖家栏目列表,针对控制器下的栏目
     */
    protected function getAdminItemList() {
        $student_id = $_GET['student_id'];
        $menu_array = array(
            array(
                'name' => 'index',
                'text' => '学生信息',
                'url' => url('Admin/Studentinfo/index',array('student_id'=>$student_id))
            ),
            array(
                'name' => 'lists',
                'text' => '绑定家长账号',
                'url' => url('Admin/Studentinfo/lists',array('student_id'=>$student_id))
            ),
            array(
                'name' => 'order',
                'text' => '看孩订单',
                'url' => url('Admin/Studentinfo/order',array('student_id'=>$student_id))
            ),
            array(
                'name' => 'teachorder',
                'text' => '教孩订单',
                'url' => url('Admin/Studentinfo/teachorder',array('student_id'=>$student_id))
            ),
            array(
                'name' => 'reviveorder',
                'text' => '重温课堂订单',
                'url' => url('Admin/Studentinfo/reviveorder',array('student_id'=>$student_id))
            ),
            array(
                'name' => 'shopping',
                'text' => '商城订单',
                'url' => url('Admin/Studentinfo/shopping',array('student_id'=>$student_id))
            )
//            array(
//                'name' => 'order',
//                'text' => '已购套餐',
//                'url' => url('Admin/Studentinfo/order',array('student_id'=>$student_id))
//            ),

        );
        return $menu_array;
    }

}

?>
