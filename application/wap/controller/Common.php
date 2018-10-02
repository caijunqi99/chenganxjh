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
        $industry = db('industry')->field('name as title,id as value')->where($where)->select();
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
        $school = '';
        if(!empty($province)){
            $where .= ' AND provinceid = "'.$province.'"';
            if(!empty($city)){
                $where .= ' AND cityid = "'.$city.'"';
                if(!empty($area)){
                    $where .= ' AND areaid = "'.$area.'"';
                }
            }
            $school = db('school')->field('schoolid,name')->where($where)->select();
        }

        output_data($school);

    }
    /**
     * @desc 获取年级接口
     * @author langzhiyao
     * @time 20181002
     */
    public function grade(){
        $token = trim(input('post.key'));
        if(empty($token)){
            output_error('缺少参数token');
        }
        $school_id = intval(input('post.school_id'));
        if(empty($school_id)){
            output_error('缺少参数school_id');
        }
        $where = 'isdel =1 AND schoolid="'.$school_id.'"';

        $school = db('school')->field('schoolid,name,typeid')->where($where)->find();
        if(empty($school)){
           output_error('该学校不存在或已被删除，请联系管理员');
        }
        $arr = '';
        if(!empty($school['typeid'])){
            $type = explode(',',$school['typeid']);
            foreach($type as $key=>$value){
                $name =db('schooltype')->field('sc_id,sc_type')->where('sc_id  = "'.$value.'"')->find();
                $arr['id'] = $name['sc_id'];
                $arr['name'] = $name['sc_type'];
                $arr['child'] = db('class')->field('classid,classname')->where('schoolid = "'.$school_id.'" AND typeid = "'.$value.'"')->select();
            }
        }

        output_data($arr);


    }

}