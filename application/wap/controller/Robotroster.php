<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/16
 * Time: 20:12
 */

namespace app\wap\controller;


class Robotroster extends MobileMall
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    //机器人认证
    public function auth(){
        $input = input();
        //db('testt')->insertGetId(['content'=>json_encode(['input'=>$input,'InputTime'=>date('Y-m-d H:i:s'),'method'=>'Robotroster_auth'])]);
        $SNNumber = $input['SNNumber'];
        if(empty($SNNumber)){
            $ret = array('ret'=>"00001","data"=>'','msg'=>"fail");
            return json_encode($ret);
        }
        $model_robot = Model("Robot");
        $data = $model_robot->getOne(array('r.SNNumber'=>$SNNumber,'r.isdel'=>1));
        $datas = array();
        $datas['schoolId'] = !empty($data['schoolid'])?$data['schoolid']:"";
        $datas['schoolName'] = !empty($data['name'])?$data['name']:"";
        $ret = array('ret'=>"00000","data"=>!empty($datas)?$datas:"",'msg'=>"success");
        return json_encode($ret);
    }

    //获取花名册
    public function roster(){
        $school_id = input('post.school_id');
        if(empty($school_id)){
            output_error('school_id参数有误');
        }
        $model_student = Model("Student");
        $data = $model_student->getAllStudent(array('s_schoolid'=>$school_id));
        $insert = [
            'school_id' => $school_id,
            'SNNumber' => input('post.SNNumber')?input('post.SNNumber'):"",
            'create_time' => time()
        ];
        $model_roster = Model("Robotroster");
        $model_roster->rosterAdd($insert);
        output_data($data);
    }

}