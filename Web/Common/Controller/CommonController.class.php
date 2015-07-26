<?php
namespace Common\Controller;
use Think\Controller;

/**
 * 首页Common控制器
 */
class CommonController extends Controller {

	public function _initialize() {
		// 访问量+1
		$db = M('statistic');
		$views = $db->where(array('date'=>date('Y-m-d')))->select();

		if (empty($views)) {
			$db->add(array('date'=>date('Y-m-d'), 'view_num'=>'1'));
		} else {
			$views = $views[0]['view_num'];
			++ $views;
			$db->where(array('date'=>date('Y-m-d')))->save(array('view_num'=>$views));
		}

	}

}