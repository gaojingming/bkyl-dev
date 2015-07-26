<?php
return array(
	// 单一模块结构
	'MULTI_MODULE' => false,

	// URL模式
	'URL_MODEL' => 2,

	// 伪静态
	'URL_HTML_SUFFIX' => 'html',

	// 模板解析字符串
	'TMPL_PARSE_STRING'  => array(
		'__PUBLIC__'     => __ROOT__ . '/Public',
	),
);