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
        $area = db('area')->field('area_id,area_parent_id,area_name')->where($where)->select();
        if(!empty($area)){
            foreach($area as $key=>$value){
                    $area[$key]['child'] = db('area')->field('area_id,area_parent_id,area_name')->where(' area_parent_id = "'.$value['area_id'].'"')->select();
                    if(!empty($area[$key]['child'])){
                        foreach($area[$key]['child'] as $k=>$v){
                            $area[$key]['child'][$k]['child'] = db('area')->field('area_id,area_parent_id,area_name')->where(' area_parent_id = "'.$v['area_id'].'"')->select();
                        }
                    }
            }
        }


        output_data($area);

    }

}