<?php


namespace app\wap\controller;


class Totalarea extends MobileMall
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * 地区列表
     */
    public function index() {
        $area_id = intval(input('param.area_id'));

        $model_area = Model('area');

        $condition = array();
        if(isset($area_id) && $area_id > 0) {
            $condition['area_parent_id'] = $area_id;
        } else {
            $condition['area_deep'] = 1;
        }
        $area_list = $model_area->getAreaList($condition, 'area_id,area_name');
        output_data(array('area_list' => $area_list));
    }

}