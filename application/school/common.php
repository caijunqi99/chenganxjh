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

