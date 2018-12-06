<?php

namespace app\common\model;

use think\Model;

class Adminpdlog extends Model {

    public $page_info;
    /**
     * 增加帮助类型
     *
     * @param
     * @return int
     */
    public function addLog($type_array) {
        $log_id = db('adminpdlog')->insertGetId($type_array);
        return $log_id;
    }

}
