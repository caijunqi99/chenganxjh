<?php

namespace app\wap\controller;

use think\captcha\Captcha;

class Arrangement {


    /**
     * app宝宝课程
     */
    public function index() {
        $school_id  = intval(input('post.school_id'));
        $type  = input('post.type');
        $day  = isset($type)?$type:"1";
        if (empty($school_id)) {
            output_error('参数有误');
        }

        $model_arrangement = Model('arrangement');
        $data = $model_arrangement->getnowweek("schoolid=".$school_id,$day);
        foreach ($data as $k=>$v){
            $week = date('w',strtotime($v['date']));
            $array = array(0=>"周日",1=>"周一",2=>"周二",3=>"周三",4=>"周四",5=>"周五",6=>"周六");
            $data[$k]['week'] = $array[$week];
            $today = date('w');
            if($today==$week){
                $data[$k]['today'] = 1;
                $data[$k]['week'] = "今日";
            }
        }

        if($data){
            output_data($data);
        }else{
            output_data(array());
        }

    }



}