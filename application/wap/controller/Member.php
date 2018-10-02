<?php

namespace app\wap\controller;

use think\Lang;
use process\Process;

class Member extends MobileMall
{

    public function _initialize()
    {
        parent::_initialize();
        Lang::load(APP_PATH . 'wap\lang\zh-cn\login.lang.php');
    }

    /**
     * @desc 个人信息
     * @author langzhiyao
     * @time 20181001
     */
    public function info(){
        $token = trim(input('post.key'));
        $member_id = intval(input('post.member_id'));
        $where = ' member_id = "'.$member_id.'"';
        if(empty($token)){
            output_error('缺少参数token');
        }
        if(empty($member_id)){
            output_error('缺少参数id');
        }
        $member = db('member')->field('member_id,member_paypwd')->where($where)->find();
        if(empty($member)){
            output_error('会员不存在，请联系管理员');
        }
        if(!empty($member_id)){
            $result = db('member')->field('member_id,member_nickname,member_avatar,member_identity,member_age,member_sex,member_email,member_provinceid,member_cityid,member_areaid,member_jobid')->where($where)->find();
            if(!empty($result)){
                output_data($result);
            }else{
                output_error('该用户不存在');
            }
        }else{
            output_error('缺少参数id');
        }

    }
    /**
     * @desc 修改个人信息
     * @author langzhiyao
     * @time 20181001
     */
    public function addInfo(){
        $token = trim(input('post.key'));
        $member_id = intval(input('post.member_id'));

        if(empty($token)){
            output_error('缺少参数token');
        }
        if(empty($member_id)){
            output_error('缺少参数id');
        }
        $where = ' member_id = "'.$member_id.'"';
        $member = db('member')->field('member_id,member_paypwd')->where($where)->find();
        if(empty($member)){
            output_error('会员不存在，请联系管理员');
        }
        //判断是否有传的数据
        if(!empty($_POST)){
            $member_nickname = trim(input('post.member_nickname'));//昵称
            $member_identity = intval(input('post.member_identity'));//角色
            $member_age = trim(input('post.member_age'));//年龄
            $member_sex = intval(input('post.member_sex'));//性别
            $member_email = trim(input('post.member_email'));//邮箱
            $member_provinceid = intval(input('post.member_provinceid'));//省
            $member_cityid = intval(input('post.member_cityid'));//市
            $member_areaid = intval(input('post.member_areaid'));//区
            $member_jobid = intval(input('post.member_jobid'));//行业
            $member_avatar = trim(input('post.member_avatar'));//头像
            if(!empty($member_avatar)){
                $data = array(
                    'member_nickname' => $member_nickname,
                    'member_age' => $member_age,
                    'member_identity' => $member_identity,
                    'member_sex' => $member_sex,
                    'member_email' => $member_email,
                    'member_provinceid' => $member_provinceid,
                    'member_cityid' => $member_cityid,
                    'member_areaid' => $member_areaid,
                    'member_jobid' => $member_jobid,
                    'member_avatar' => $member_avatar,
                    'member_edit_time'=>time()
                );
            }else{
                $data = array(
                    'member_nickname' => $member_nickname,
                    'member_age' => $member_age,
                    'member_identity' => $member_identity,
                    'member_sex' => $member_sex,
                    'member_email' => $member_email,
                    'member_provinceid' => $member_provinceid,
                    'member_cityid' => $member_cityid,
                    'member_areaid' => $member_areaid,
                    'member_jobid' => $member_jobid,
                    'member_edit_time'=>time()
                );
            }

            //修改个人信息
            $result = db('member')->where($where)->update($data);
            if($result){
                output_data(array('message'=>'修改成功'));
            }else{
                output_error('修改失败');
            }
        }else{
            output_error('网络错误');
        }
    }

    /**
     * @desc 我的订单
     * @author langzhiyao
     * @time 20181001
     */
    public function order(){
        $token = trim(input('post.key'));
        if(empty($token)){
            output_error('缺少参数token');
        }
        $member_id = intval(input('post.member_id'));
        if(empty($member_id)){
            output_error('缺少参数id');
        }
        $member_where = ' member_id = "'.$member_id.'"';
        $member = db('member')->field('member_id,member_paypwd')->where($member_where)->find();
        if(empty($member)){
            output_error('会员不存在，请联系管理员');
        }
        $type_id = intval(input('post.type_id'));//1.看孩 2.教孩  3.商城
        if(empty($type_id)){
            output_error('缺少参数type_id');
        }
        $where = ' o.buyer_id = "'.$member_id.'" AND o.delete_state = 0 ';
        $order ='';
        switch ($type_id){
            case 1:
                $order = db('packagesorder')->alias('o')->field('o.order_name,o.add_time,o.order_state,o.order_amount,FROM_UNIXTIME(o.add_time,\'%Y-%m-%d\') as add_time')->where($where)->order('order_id DESC')->select();
                break;
            case 2:
                $order = db('packagesorderteach')->alias('o')->field('o.order_name,o.add_time,o.order_state,o.order_amount,FROM_UNIXTIME(o.add_time,\'%Y-%m-%d\') as add_time')->where($where)->order('order_id DESC')->select();
                break;
            case 3:
                $order = db('order')->alias('o')->field('g.goods_name as order_name,o.add_time,o.order_state,o.order_amount,FROM_UNIXTIME(o.add_time,\'%Y-%m-%d\') as add_time')->join('__ORDERGOODS__ g','g.order_id=o.order_id','LEFT')->where($where)->order('o.order_id DESC')->select();
                break;
        }
        output_data($order);


    }

    /**
     * @desc 我的收藏
     * @author langzhiyao
     * @time 20181001
     */
    public function collect(){
        $token = trim(input('post.key'));
        if(empty($token)){
            output_error('缺少参数token');
        }
        $member_id = intval(input('post.member_id'));
        if(empty($member_id)){
            output_error('缺少参数id');
        }
        $member_where = ' member_id = "'.$member_id.'"';
        $member = db('member')->field('member_id,member_paypwd')->where($member_where)->find();
        if(empty($member)){
            output_error('会员不存在，请联系管理员');
        }
        $type_id = intval(input('post.type_id'));//1.课件 2.商城
        if(empty($type_id)){
            output_error('缺少参数type_id');
        }
        $where = ' c.member_id = "'.$member_id.'" AND c.type_id = "'.$type_id.'" ';
        $collect ='';
        switch ($type_id){
            case 1:
                $collect = db('membercollect')->alias('c')->field('c.time,FROM_UNIXTIME(c.time,\'%Y-%m-%d\') as time,t.t_title,t.t_picture,t.t_userid,t.t_profile,t.t_price,m.member_nickname')->join('__TEACHCHILD__ t','t.t_id=c.collect_id','LEFT')->join('__MEMBER__ m','m.member_id=t.t_userid','LEFT')->where($where)->order('c.id DESC')->select();
                break;
            case 2:
                $collect = db('membercollect')->alias('c')->field('c.time,FROM_UNIXTIME(c.time,\'%Y-%m-%d\') as time,g.goods_name,g.goods_price,g.goods_image,g.evaluation_count')->join('__GOODS__ g','g.goods_id=c.collect_id','LEFT')->where($where)->order('c.id DESC')->select();
                break;
        }
        output_data($collect);

    }

    /**
     * @desc 修改密码
     * @author langzhiyao
     * @time 20181001
     */
    public function editPwd(){
        $token = trim(input('post.key'));
        if(empty($token)){
            output_error('缺少参数token');
        }
        $member_id = intval(input('post.member_id'));
        if(empty($member_id)){
            output_error('缺少参数id');
        }
        $oldPwd = trim(input('post.oldPwd'));
        $newPwd = trim(input('post.newPwd'));
        $reNewPwd = trim(input('post.reNewPwd'));
        if(empty($oldPwd) || empty($newPwd) || empty($reNewPwd)){
            output_error('传参不能为空');
        }
        $where = ' member_id = "'.$member_id.'"';
        $member = db('member')->field('member_id,member_password')->where($where)->find();
        if(!empty($member)){
            if($member['member_password'] != md5($oldPwd)){
                output_error('旧密码不正确，请重新填写');
            }
            if(strlen($newPwd) <6 || strlen($newPwd) >12){
                output_error('新密码长度必须在6~12位中');
            }

            if($newPwd != $reNewPwd){
                output_error('两次密码不一致，请重新填写');
            }
            $data = array(
                'member_password' => md5($newPwd)
            );
            $result = db('member')->where($where)->update($data);
            if($result){
                output_data(array('message'=>'修改成功'));
            }else{
                output_error('新密码和原密码一致，请重新修改');
            }
        }else{
            output_error('该会员不存在，请联系管理员');
        }

    }

    /**
     * @desc  支付密码
     * @author langzhiyao
     * @time 20181002
     */
    public function payPwd(){
        $token = trim(input('post.key'));
        if(empty($token)){
            output_error('缺少参数token');
        }
        $member_id = intval(input('post.member_id'));
        if(empty($member_id)){
            output_error('缺少参数id');
        }
        $where = ' member_id = "'.$member_id.'"';

        $member = db('member')->field('member_id,member_paypwd')->where($where)->find();
        if(empty($member)){
            output_error('会员不存在，请联系管理员');
        }

        output_data($member);

    }
    /**
     * @desc 支付密码修改
     * @author langzhiyao
     * @time 20181002
     */
    public function editPayPwd(){
        $token = trim(input('post.key'));
        if(empty($token)){
            output_error('缺少参数token');
        }
        $member_id = intval(input('post.member_id'));
        if(empty($member_id)){
            output_error('缺少参数id');
        }
        $where = ' member_id = "'.$member_id.'"';

        $member = db('member')->field('member_id,member_paypwd')->where($where)->find();
        if(empty($member)){
            output_error('会员不存在，请联系管理员');
        }

        $payPwd = trim(input('post.payPwd'));
        if(empty($payPwd)){
            output_error('密码不能为空');
        }
        if(strlen($payPwd) != 6){
            output_error('密码不符合要求，只能为6为数字');
        }
        $rePayPwd = trim(input('post.rePayPwd'));
        if($payPwd != $rePayPwd){
            output_error('两次密码不一致，请重新输入');
        }
        $data = array(
            'member_paypwd'=>md5($payPwd)
        );

        $result = db('member')->where($where)->update($data);

        if($result){
            output_data(array('message'=>'设置成功'));
        }else{
            output_error('新密码不能和原密码一样，请重新输入');
        }

    }

    /**
     * @desc 绑定学生
     * @author langzhiyao
     * @time 20181002
     */
    public function studentBind(){
        $token = trim(input('post.key'));
        if(empty($token)){
            output_error('缺少参数token');
        }
        $member_id = intval(input('post.member_id'));
        if(empty($member_id)){
            output_error('缺少参数id');
        }
        $where = ' member_id = "'.$member_id.'"';

        $member = db('member')->field('member_id,member_paypwd')->where($where)->find();
        if(empty($member)){
            output_error('会员不存在，请联系管理员');
        }

        $name = trim(input('post.name'));//姓名
        $sex = intval(input('post.sex'));//性别
        $birthday = strtotime(trim(input('post.birthday')));//出生日期
        $province_id = intval(input('post.province'));//省ID
        $city_id = intval(input('post.city'));//市ID
        $area_id = intval(input('post.area'));//区ID
        $school_id = intval(input('post.school'));//学校ID
        $grade_id = intval(input('post.grade'));//年级ID
        $class_id = intval(input('post.class'));//班级ID
        $classCard = trim(input('post.class_code'));//班级识别码
        $card = trim(input('post.card'));//学生身份证ID
        if(empty($name) || empty($school_id) || empty($grade_id) || empty($class_id) || empty($classCard)){
            output_error('传的参数不完整');
        }
        //判断该学生是否有绑定人
        $student = db('student')->field('s_ownerAccount')->where(' s_card = "'.$card.'"')->find();
        if(!empty($student) && !empty($student['s_ownerAccount'])){
            output_error('该学生已有绑定人，请联系管理员');
        }
        //判断识别码是否存在 并是不是这个班级的识别码
        $class = db('class')->field('classCard,classid')->where(' classid =  "'.$class_id.'"')->find();
        if(empty($class)){
            output_error('班级不存在');
        }
        if($class['classCard'] != $classCard){
            output_error('选择班级和填写的班级识别码不一致');
        }

        $data = array(
            's_ownerAccount' => $member_id,
            's_name' => $name,
            's_sex' => $sex,
            's_classid' => $class_id,
            's_schoolid' => $school_id,
            's_sctype' => $grade_id,
            's_birthday' => $birthday,
            's_provinceid' => $province_id,
            's_cityid' => $city_id,
            's_areaid' => $area_id,
            's_card' => $card,
            'classCard' =>$classCard
        );

    $student = db('student')->insert($data);

    if($student){
        output_data(array('message'=>'绑定成功'));
    }else{
        output_error('绑定失败');
    }

    }

    /**
     * @desc 副账号列表
     * @author langzhiyao
     * @time 20181002
     */
    public function account(){
        $token = trim(input('post.key'));
        if(empty($token)){
            output_error('缺少参数token');
        }
        $member_id = intval(input('post.member_id'));
        if(empty($member_id)){
            output_error('缺少参数id');
        }
        $member_where = ' member_id = "'.$member_id.'"';

        $member = db('member')->field('member_id,member_paypwd')->where($member_where)->find();
        if(empty($member)){
            output_error('会员不存在，请联系管理员');
        }
        $where = ' is_owner = "'.$member_id.'"';

        $account = db('member')->field('member_id,member_aboutname,member_mobile')->where($where)->select();
        output_data($account);




    }
    /**
     * @desc 绑定副账号
     * @author langzhiyao
     * @time 20181002
     */
    public function accountBind(){
        $token = trim(input('post.key'));
        if(empty($token)){
            output_error('缺少参数token');
        }
        $member_id = intval(input('post.member_id'));
        if(empty($member_id)){
            output_error('缺少参数id');
        }
        $member_where = ' member_id = "'.$member_id.'"';

        $member = db('member')->field('member_id,member_paypwd')->where($member_where)->find();
        if(empty($member)){
            output_error('会员不存在，请联系管理员');
        }
        //查询该会员绑定的孩子
        $member_student = db('member')->alias('m')->join('__STUDENT__ s','s.s_ownerAccount = m.member_id','LEFT')->field('m.member_id,s.classCard,s.s_card')->where($member_where)->select();

        $member_aboutname = trim(input('post.member_aboutname'));//关系名称
        $member_mobile = trim(input('post.member_mobile'));//手机号

        if(empty($member_aboutname) || empty($member_mobile)){
            output_error('传参数不正确');
        }
        $member_mobile_where = ' member_mobile = "'.$member_mobile.'" ';
        $member_about = db('member')->where($member_mobile_where)->find();
        $data = array(
            'is_owner' => $member_id,
            'member_aboutname' => $member_aboutname,
            'member_mobile' => $member_mobile
        );
        if(!empty($member_about)){
            if($member_about['is_owner'] != 0){
                output_error('该手机号已有副账号，不能绑定');
            }else{
                //判断该手机号绑定的孩子
                $student = db('member')->alias('m')->join('__STUDENT__ s','s.s_ownerAccount = m.member_id','LEFT')->field('m.member_id,s.classCard,s.s_card')->where($member_mobile_where)->select();
                if(!empty($student[0]['classCard'])){
                    if(count($student) != 1){
                        output_error('该手机号绑定有多个孩子，不能绑定');
                    }else{
                        if(!empty($member_student[0]['classCard'])){
                            $is_bind = 0;
                            foreach($member_student as $key=>$value){
                                if($value['classCard'] == $student[0]['classCard'] && $value['s_card'] == $student[0]['s_card']){
                                    $is_bind = 1;
                                }
                            }
                            if($is_bind == 1){
                                $res = db('member')->where($member_where)->update($data);
                            }else{
                                output_error('该手机号绑定孩子和会员绑定孩子不一致，不能绑定');
                            }
                        }else{
                            output_error('该手机号绑定孩子和会员绑定孩子不一致，不能绑定');
                        }

                    }
                }else{
                    $res = db('member')->where($member_where)->update($data);
                }
            }

        }else{
            $res = db('member')->insert($data);
        }

        output_data(array('message'=>'绑定成功'));



    }
    /**
     * @desc 解绑
     * @author langzhiyao
     * @time 20181002
     */
    public function accountDel(){
        $token = trim(input('post.key'));
        if(empty($token)){
            output_error('缺少参数token');
        }
        $member_id = intval(input('post.member_id'));
        if(empty($member_id)){
            output_error('缺少参数id');
        }
        $jb_id = intval(input('post.jb_id'));
        if(empty($jb_id)){
            output_error('缺少参数jb_id');
        }
        $where = ' member_id = "'.$member_id.'"';

        $member = db('member')->field('member_id')->where($where)->find();
        if(empty($member)){
            output_error('会员不存在，请联系管理员');
        }

        $jb_where = ' member_id = "'.$jb_id.'"';

        $jb_account = db('member')->field('member_id,is_owner')->where($jb_where)->find();
        if(empty($jb_account)){
            output_error('副账号不存在，请联系管理员');
        }

        if($jb_account['is_owner'] == 0){
            output_error('已解绑，无需重复操作');
        }
        if($member_id == $jb_account['is_owner']){
            $data = array(
                'is_owner'=>0,
                'member_aboutname' => ''
            );
            $res = db('member')->where($jb_where)->update($data);
            if($res){
                output_data(array('message'=>'解绑成功'));
            }else{
                output_error('已解绑，无需重复操作');
            }
        }else{
            output_error('解绑失败，该账号不属于该会员，请联系管理员解除');
        }




    }


}

?>
