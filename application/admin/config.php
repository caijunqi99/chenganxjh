<?php

return [
    //默认错误跳转对应的模板文件
    'dispatch_error_tmpl' => 'public:dispatch_jump',
    //默认成功跳转对应的模板文件
    'dispatch_success_tmpl' => 'public:dispatch_jump',
    // URL普通方式参数 用于自动生成
    'url_common_param' => true,
    'pkgs_list'=>[
		'day'     =>'天',
		'week'    =>'周',
		'mouth'   =>'月',
		'quarter' =>'季度',
		'half'    =>'半年',
		'year'    =>'年',
		'hour'    =>'小时',
	],
];

