<?php
function header_message($sta_code, $message = '') {
	$status = array(
		200 => 'OK',
		406 => 'Not Acceptable',
		404 => 'Not Found',
	);

	header('HTTP/1.1 '.$sta_code.' '.$status[$sta_code]);
	echo $message;
}

function header_json_message($sta_code, $message = '') {
	$status = array(
		200 => 'OK',
		406 => 'Not Acceptable',
		404 => 'Not Found',
	);

	header('HTTP/1.1 '.$sta_code.' '.$status[$sta_code]);
	echo json_encode($message);
}