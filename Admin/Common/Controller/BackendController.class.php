<?php
namespace Common\Controller;
use Think\Controller;

/**
 * 后台登录管理控制器
 * 所有需要登录后才能访问的页面都应该继承自这个控制器
 */
class BackendController extends Controller {
	/**
	 * 创建这个控制器的时候首先运行的函数
	 * 在这里检查是否已经登录
	 */
	public function _initialize() {
		// 检查是否登录
		if (session('admin_id') === null) {
			$this->redirect('Login/index');
		}
		$this->assign('admin_username', session('admin_username'));
		$this->assign('admin_ip', session('admin_ip'));
		$this->assign('location', session('location'));
		$this->assign('permission', session('admin_permission'));
	}
}