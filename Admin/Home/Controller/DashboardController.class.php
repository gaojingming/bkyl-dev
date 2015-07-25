<?php
namespace Home\Controller;
use Common\Controller\BackendController;

class DashboardController extends BackendController {

	/**
	 * 后台首页
	 */
	public function index() {
		$this->display();
	}

}