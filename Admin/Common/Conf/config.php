<?php
return array(
	// 单一模块结构
	'MULTI_MODULE' => false,

	// URL模式
	'URL_MODEL' => 1,

	// 伪静态
	'URL_HTML_SUFFIX' => '',

	// 默认控制器
	'DEFAULT_CONTROLLER' => 'Dashboard',

	// 后台PUBLIC文件夹地址
	'TMPL_PARSE_STRING' => array(
		'__PUBLIC__' => __ROOT__.'/Admin/Public',
	),

	// CRYPT SALT
	'CRYPT_SALT' => '_D5/.XbXy',
);