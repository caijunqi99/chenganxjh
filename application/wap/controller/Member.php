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

    }


}

?>
