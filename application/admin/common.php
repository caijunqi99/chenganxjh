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

function Fomat($time){
	return date('Y-m-d H:i:s',$time);
}

