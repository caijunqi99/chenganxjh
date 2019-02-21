<?php

function p($arr,$m='p'){
	if ($m=='p') {
		echo "<pre>";
		print_r($arr);
		echo "</pre>";
	}else{
		echo "<pre>";
		var_dump($arr);
		echo "</pre>";
	}
	
}

function axisFomat($str){
	$time_list = config('pkgs_list');
	return $time_list[$str];
}

function CalculationTime($order_info,$packagetime){
    $nowTime = !empty($packagetime['end_time'])?$packagetime['end_time']:$order_info['finnshed_time'];
    $pkg_length = $order_info['pkg_length'];    
    switch ($order_info['pkg_axis']) {
        case 'hour':
            // $endTime = strtotime("+{$pkg_length} hour",$nowTime);
            break;
        case 'day':
            // $endTime = strtotime("+{$pkg_length} day",$nowTime);
            break;
        case 'week':
            // $endTime = strtotime("+{$pkg_length} week",$nowTime);
            break;
        case 'mouth':
            // $endTime = strtotime("+{$pkg_length} month",$nowTime);
            $endTime = date('Y-m-d H:i:s',strtotime("{$nowTime}+{$pkg_length}month"));
            break;
        case 'quarter':
            $pkg_length*=3;
            // $endTime = strtotime("+{$pkg_length} month",$nowTime);
            $endTime = date('Y-m-d H:i:s',strtotime("{$nowTime}+{$pkg_length}month"));
            break;
        case 'half':
            $pkg_length*=6;
            // $endTime = strtotime("+{$pkg_length} month",$nowTime);
            break;
        case 'year':
            // $endTime = strtotime("+{$pkg_length} year",$nowTime);
            $endTime = date('Y-m-d H:i:s',strtotime("{$nowTime}+{$pkg_length}year"));

            break;
    }
    return $endTime;
}
