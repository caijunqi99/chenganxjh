<?php

function p($arr){
	echo "<pre>";
	print_r($arr);
	echo "</pre>";
}

function axisFomat($str){
	$time_list = config('pkgs_list');
	return $time_list[$str];
}

/**
 * 生成随机数字字符串组合
 * @param  integer $len   [description]
 * @param  [type]  $chars [description]
 * @return [type]         [description]
 */
function getRandomString($len=6, $chars=null,$t = 'n'){
    if (is_null($chars)){
        $chars = "abcdefghjkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789";
    }  
    mt_srand(10000000*(double)microtime());
    for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++){
        $str .= $chars[mt_rand(0, $lc)];  
    }
    if ($t=='u') return strtoupper($str);
    if ($t=='l') return strtolower($str);
    return $str;
}