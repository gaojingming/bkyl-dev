<?php
namespace Home\Controller;
use Think\Controller;

/**
 * 登录相关功能控制器
 */
class LoginController extends Controller {
	private function _validate($username, $password) {
		$user_info = M('admin_info')->where(array('email'=>$username))->field('password')->select();

		// 如果用户不存在
		if (empty($user_info)) {
			return false;
		}

		// 比较用户名和密码是否正确
		$password_encode = md5(crypt($password, C('CRYPT_SALT')));
		if ($password_encode === $user_info[0]['password']) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * 显示登录页面
	 */
	public function index() {
		$this->display();
	}

	public function check_user() {
		$username = I('post.username');
		$password = I('post.password');

		// 检查用户名密码是否正确
		if ($this->_validate($username, $password)) {
			$user_info = M('admin_info')->where(array('email'=>$username))->field('id, permission')->select()[0];

			session('admin_id', $user_info['id']);
			session('admin_username', $username);
			session('admin_permission', $user_info['permission']);
			header_json_message(200, 'success');
		} else {
			header_json_message(200, 'failed');
			return;
		}
	}
}