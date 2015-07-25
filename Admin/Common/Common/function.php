<?php
/**
 * 发送对应的header头并返回数据
 * @param  integer $sta_code HTTP头
 * @param  string $message  返回的数据
 */
function header_message($sta_code, $message = '') {
	$status = array(
		200 => 'OK',
		406 => 'Not Acceptable',
		404 => 'Not Found',
	);

	header('HTTP/1.1 '.$sta_code.' '.$status[$sta_code]);
	echo $message;
}

/**
 * 发送对应的HTTP状态码并返回json编码的数据
 * @param  integer $sta_code HTTP状态码
 * @param  string $message  需要编码的数据
 */
function header_json_message($sta_code, $message = '') {
	$status = array(
		200 => 'OK',
		406 => 'Not Acceptable',
		404 => 'Not Found',
	);

	header('HTTP/1.1 '.$sta_code.' '.$status[$sta_code]);
	echo json_encode($message);
}