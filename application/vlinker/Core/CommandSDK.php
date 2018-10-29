<?php

namespace app\vlinker\Core;

class CommandSDK
{
    const KEY = 12;
    const BASE = HTTP_URL;
    const EquipmentList = 133;//获取设备列表
    const dayData = 134;//获取当天数据
    const currentData=136;//获取当前数据
    const dayDataType = 139;//获取类型信息
    const historyData = 138;//获取历史数据
    const videoList = 130;//获取播放资源列表
    protected $key = null;
    const error_code = [   // 错误code
        '111'=>'日期错误',

    ];
    public function __construct()
    {
        $this->key = config('KEY');

    }
    public function getKey()
    {
        return $this->key;
    }
}