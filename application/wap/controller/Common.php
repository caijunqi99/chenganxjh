<?php

namespace app\wap\controller;

use think\Lang;
use process\Process;

class Common extends MobileMall
{

    public function _initialize()
    {
        parent::_initialize();
        Lang::load(APP_PATH . 'wap\lang\zh-cn\login.lang.php');
    }

    /**
     * @desc 地区获取
     * @author langzhiyao
     * @time 20181002
     */
    public function address(){
        $token = trim(input('post.key'));
        if(empty($token)){
            output_error('缺少参数token');
        }
        $where = ' area_parent_id = 0';
        $area = db('area')->field('area_id as code,area_name as name')->where($where)->select();
        if(!empty($area)){
            foreach($area as $key=>$value){
                $area[$key]['sub'] = db('area')->field('area_id as code,area_name as name')->where(' area_parent_id = "'.$value['code'].'"')->select();
                if(!empty($area[$key]['sub'])){
                    foreach($area[$key]['sub'] as $k=>$v){
                        $area[$key]['sub'][$k]['sub'] = db('area')->field('area_id as code,area_name as name')->where(' area_parent_id = "'.$v['code'].'"')->select();
                    }
                }
            }
        }


        output_data($area);

    }

    /**
     * @desc 行业获取
     * @author langzhiyao
     * @time 20181002
     */
    public function industry(){

        $token = trim(input('post.key'));
        if(empty($token)){
            output_error('缺少参数token');
        }
        $where = '1=1';
        $industry = db('industry')->where($where)->select();
        output_data($industry);
    }

    /**
     * @desc 学校获取
     * @author langzhiyao
     * @time 20181002
     */
    public function school(){
        $token = trim(input('post.key'));
        if(empty($token)){
            output_error('缺少参数token');
        }
        $province = intval(input('post.province'));
        $city = intval(input('post.city'));
        $area = intval(input('post.area'));
        $where = 'isdel =1 ';
        if(!empty($province)){
            $where .= ' AND provinceid = "'.$province.'"';
            if(!empty($city)){
                $where .= ' AND cityid = "'.$city.'"';
                if(!empty($area)){
                    $where .= ' AND areaid = "'.$area.'"';
                }
            }
        }
        $school = db('school')->field('schoolid,name')->where($where)->select();

        output_data($school);

    }

}