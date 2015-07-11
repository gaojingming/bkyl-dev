<?php
namespace Common\Controller;
use Think\Controller;

class BackendController extends Controller {
	public function _initialize() {
		// 检查是否登录
		if (session('admin_id') === null) {
			$this->redirect('Login/index');
		}
		$this->assign('admin_username', session('admin_username'));
		$this->assign('admin_ip', session('admin_ip'));
		$this->assign('location', session('location'));
	}
}