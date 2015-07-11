<?php
namespace Home\Controller;
use Think\Controller;

/**
 * 登录相关功能控制器
 */
class LoginController extends Controller {
	/**
	 * 验证用户名密码是否正确
	 * @param  string $username 用户名
	 * @param  string $password 密码（未加密）
	 * @return boolean 正确/错误
	 */
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
	 * 获取用户ip所在地
	 * @param  string $ip ip地址
	 * @return string 所在地址
	 */
	private function _get_ip_location($ip) {
		$url = "http://ip.taobao.com/service/getIpInfo2.php";
		$post_data = array('ip' => $ip);
		$location = '';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

		$output = curl_exec($ch);
		$ip_info = json_decode($output)->data;

		$location = $ip_info->country . $ip_info->region;
		if ($ip_info->region != $ip_info->city) {
			$location .= $ip_info->city;
		}
		$location .= $ip_info->county . ' ' . $ip_info->isp;

		return $location;
	}

	/**
	 * 显示登录页面
	 */
	public function index() {
		$this->display();
	}

	/**
	 * AJAX提交检查用户名密码是否正确地址
	 */
	public function check_user() {
		$username = I('post.username');
		$password = I('post.password');

		// 检查用户名密码是否正确
		if ($this->_validate($username, $password)) {
			$user_info = M('admin_info')->where(array('email'=>$username))->field('id, permission')->select()[0];

			session('admin_id', $user_info['id']);
			session('admin_username', $username);
			session('admin_ip', get_client_ip());
			session('location', $this->_get_ip_location(get_client_ip()));
			session('admin_permission', $user_info['permission']);
			header_json_message(200, 'success');
		} else {
			header_json_message(200, 'failed');
			return;
		}
	}

	/**
	 * 退出登录
	 */
	public function logout() {
		session(null);
		$this->redirect('Login/index');
	}
}