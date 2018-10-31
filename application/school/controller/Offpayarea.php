<?php

namespace app\school\controller;


class Offpayarea extends AdminControl
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    public function index()
    {
        $model_parea = Model('offpayarea');
        $model_area = Model('area');
       /* if (!defined('DEFAULT_PLATFORM_STORE_ID')) {
            $this->error('请系统管理员配置完自营店铺后再设置货到付款', 'dashboard/aboutus');
        }*/

        $store_id = 1;
        if (request()->isPost()) {
            if (!preg_match('/^[\d,]+$/', $_POST['county'])) {
                $_POST['county'] = '';
            }
            //内置自营店ID
            $area_info = $model_parea->getAreaInfo(array('store_id' => $store_id));
            $data = array();
            $county = trim($_POST['county'], ',');
            //v3-b12 地区修改
            $county_array = explode(',', $county);

            $all_array = array();

            if (!empty($_POST['province']) && is_array($_POST['province'])) {
                foreach ($_POST['province'] as $v) {
                    $all_array[$v] = $v;
                }
            }

            if (!empty($_POST['city']) && is_array($_POST['city'])) {
                foreach ($_POST['city'] as $v) {
                    $all_array[$v] = $v;
                }
            }


            if(is_array($county_array)) {
                foreach ($county_array as $pid) {
                    if($pid=='') $pid=0;
                    $all_array[$pid] = $pid;
                    $temp = $model_area->getChildsByPid($pid);
                    if (!empty($temp) && is_array($temp)) {
                        foreach ($temp as $v) {
                            $all_array[$v] = $v;
                        }
                    }
                }
            }
//halt($all_array);
            $all_array = array_values($all_array);
            $data['area_id'] = serialize($all_array);
            if (!$area_info) {
                $data['store_id'] = $store_id;
                $result = $model_parea->addArea($data);
            } else {
                $result = $model_parea->updateArea(array('store_id' => $store_id), $data);
            }
            if ($result) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }
        //取出支持货到付款的县ID及上级市ID
        $parea_info = $model_parea->getAreaInfo(array('store_id' => $store_id));
        if (!empty($parea_info['area_id'])) {
            $parea_ids = @unserialize($parea_info['area_id']);
        }
        if (empty($parea_ids)) {
            $parea_ids = array();
        }

        //取出支持货到付款县ID的上级市ID
        $city_checked_child_array = array();
        //v3-b12 地区修改
        $county_array = $model_area->getAreaList(array('area_deep' => 3), 'area_id,area_parent_id');
        foreach ($county_array as $v) {
            if (in_array($v['area_id'], $parea_ids)) {
                $city_checked_child_array[$v['area_parent_id']][] = $v['area_id'];
            }
        }
        //halt($city_checked_child_array);
        $this->assign('city_checked_child_array', $city_checked_child_array);
        //市级下面的县是不是全部支持货到付款，如果全部支持，默认选中，如果其中部分县支持货到付款，默认不选中但显示一个支付到付县的数量

        //格式 city_id => 下面支持到付的县ID数量
        $city_count_array = array();
        //格式 city_id => 是否选中true/false
        $city_checked_array = array();
        $list = $model_area->getAreaList(array('area_deep' => 3), 'area_parent_id,count(area_id) as child_count', 'area_parent_id');
        foreach ($list as $k => $v) {
            $city_count_array[$v['area_parent_id']] = $v['child_count'];
        }
        foreach ($city_checked_child_array as $city_id => $city_child) {
            if (count($city_child) > 0) {
                if (count($city_child) == $city_count_array[$city_id]) {
                    $city_checked_array[$city_id] = true;
                }
            }
        }
        //dump($city_checked_array);
        $this->assign('city_checked_array', $city_checked_array);

        //取得省级地区及直属子地区(循环输出)
        require(EXTEND_PATH . '/area.php');
        //v3-b12 地区修改 修改地区从3级变成5级，以及N级引发的错误
        $province_array = array();
        foreach ($area_array as $k => $v) {
            if ($v['area_parent_id'] == '0') {
                $province_array[$k] = $k;
            }
        }

        foreach ($area_array as $k => $v) {
            if ($v['area_parent_id'] != '0') {
                if (in_array($v['area_parent_id'], $province_array)) {
                    $area_array[$v['area_parent_id']]['child'][$k] = $v['area_name'];
                }
                unset($area_array[$k]);
            }
        }

        $this->assign('province_array', $area_array);

        //计算哪些省需要默认选中(即该省下面的所有县都支持到付，即所有市都是选中状态)
        $province_array = $area_array;
        foreach ($province_array as $pid => $value) {
            if (is_array($value['child'])) {
                foreach ($value['child'] as $k => $v) {
                    if (!array_key_exists($k, $city_checked_array)) {
                        unset($province_array[$pid]);
                        break;
                    }
                }
            }
        }
        $this->assign('province_checked_array', $province_array);
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    protected function getAdminItemList() {
        $menu_array = array(
            array(
                'name' => 'index',
                'text' => '配送地区',
                'url' => url('Offpayarea/index')
            )
        );
        return $menu_array;
    }
}