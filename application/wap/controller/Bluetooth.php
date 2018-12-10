<?php

namespace app\wap\controller;

use think\Lang;

class Bluetooth extends MobileMember
{

    public function _initialize()
    {
        parent::_initialize();
        Lang::load(APP_PATH . 'wap\lang\zh-cn\login.lang.php');
    }

    /**
     * @desc 绑定蓝牙
     * @author langzhiyao
     */
    public function bind_blueTooth(){
        $member_id  = intval(input('post.member_id'));
        $append_id  = intval(input('post.append_id'));
        $name  = trim(input('post.name'));
        $distance  = trim(input('post.distance'));
        $voice  = trim(input('post.voice'));
        $openVibrator  = intval(input('post.openVibrator'));
        $status  = intval(input('post.status'));
        if (empty($member_id) || empty($append_id) || empty($name)) {
            output_error('参数有误');
        }
        //判断是否已绑定
        $model_blueTooth = Model('blueTooth');

        $result = $model_blueTooth->isset_blueTooth(array('userId'=>$member_id,'appendId'=>$append_id));
        if(!$result){
            $data = array(
                'userId'=>$member_id,
                'appendId'=>$append_id,
                'name'=>$name,
                'distance'=>$distance,
                'voice'=>$voice,
                'openVibrator'=>$openVibrator,
                'status'=>$status,
                'add_time'=>time()
            );
            $res = $model_blueTooth->blueTooth_add($data);
            if($res){
                output_data(array('message'=>'连接成功'));
            }else{
             output_error('连接失败，请重新连接');
            }
        }else{
            output_error('已连接');
        }


    }

    public function getBlueTooth(){
        $member_id  = intval(input('post.member_id'));
        if (empty($member_id)) {
            output_error('参数有误');
        }
        $model_blueTooth = Model('blueTooth');

        $result = $model_blueTooth->getList(array('userId'=>$member_id));

        output_data($result);

    }

}