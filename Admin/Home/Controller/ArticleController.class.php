<?php
namespace Home\Controller;
use Common\Controller\BackendController;

/**
 * 文章相关操作控制器
 */
class ArticleController extends BackendController {
	/**
	 * 返回datatables插件指定格式的数据
	 * @param  array $data  需要改变结构的数据
	 * @param  array $order 更改结构后的顺序
	 * @return array        更改结构后的数据
	 */
	private function datatables_ajax_encode($data, $order) {
		$arr = array(
			'data' => array()
		);

		foreach ($data as $v) {
			$tmp = array();
			for ($i = 0; $i < count($order); $i ++) {
				$tmp[] = $v[$order[$i]];
			}
			$arr['data'][] = $tmp;
		}
		return $arr;
	}

	/**
	 * 默认重定向显示listing页面
	 */
	public function index() {
		$this->redirect('Article/listing');
	}

	/**
	 * 文章列表页面
	 */
	public function listing() {
		$this->display();
	}

	/**
	 * 添加文章页面
	 */
	public function add() {
		$this->display();
	}

	/**
	 * 修改文章页面
	 */
	public function update() {
		$id = I('get.id');
		if (empty($id)) {
			$this->error();
		}
		$db_instance = new \Home\Model\ArticleModel();
		
		$data = $db_instance->get_article($id);
		if (empty($data)) {
			$this->error();
		}

		$this->assign('data', $data[0]);
		$this->display();
	}

	/**
	 * 获取文章列表AJAX访问地址
	 * @return json_string 文章列表
	 */
	public function get_article_list() {
		$format = I('param.format');
		$category_id = I('param.category_id');

		$order_by = I('param.order_by');
		$order_arr = split(',', $order_by);
		foreach ($order_arr as &$v) {
			$v = trim($v);
		}

		$data = M('article')->where(array('category_id'=>$category_id))->field('id, title, istop, modify_time')->order('istop DESC, modify_time DESC')->select();

		switch ($format) {
			case 'datatables':
				$table_data = $this->datatables_ajax_encode($data, $order_arr);
				echo json_encode($table_data);
				break;

			default:
				echo json_encode($data);
		}
	}

	/**
	 * 添加文章ajax提交地址
	 */
	public function article_add() {
		if (!IS_POST)
			$this->error();

		$db_instance = new \Home\Model\ArticleModel();

		$data['category_id'] = I('post.category_select');
		if (empty($data['category_id'])) {
			header_message(406, '分类不能为空');
			return;
		}

		$data['title'] = I('post.title');
		if (empty($data['title'])) {
			header_message(406, '标题不能为空');
			return;
		}

		$data['content'] = I('post.content', '', false);
		if (empty($data['content'])) {
			header_message(406, '内容不能为空');
			return;
		}

		$data['modify_time'] = date('y-m-d H:i:s', time());
		$data['author_id'] = session('admin_id');

		if (I('post.istop') != '') {
			$data['istop'] = I('post.istop') == 'on' ? 1 : 0;
		}

		// 如果有上传文件
		if (!empty($_FILES['thumbnail']['tmp_name'])) {
			$upload_config = array(
				'maxSize'  => 1048576,								// 文件大小不超过1MB
				'rootPath' => './Uploads/',							// 文件上传根目录
				'savePath' => '',									// 上传子目录
				'mimes'    => array('image/jpeg', 'image/png'),		// 允许的MIME文件类型
				'exts'     => array('jpg', 'png'),					// 允许的后缀名
				'subName'  => false,								// 不适用子目录保存文件
			);
			$upload_ins = new \Think\Upload($upload_config);
			// 上传banner图
			$file_info = $upload_ins->upload();
			if (!$file_info) {
				header_message(406, $upload_ins->getError());
				return;
			}
			// 剪裁图片为414px * 176px
			$image = new \Think\Image();
			$image->open('./Uploads/'.$file_info['thumbnail']['savename']);
			$image->thumb(100, 70, \Think\Image::IMAGE_THUMB_FIXED)->save('./Uploads/'.$file_info['thumbnail']['savename']);
			$data['thumbnail'] = $file_info['thumbnail']['savename'];
		}

		// 添加数据
		if ($db_instance->addNewArticle($data)) {
			header_message(200, 'success');
		} else {
			header_message(200, $db_instance->getError());
		}
	}

	public function article_update() {
		if (!IS_POST) {
			$this->error();
		}

		$db_instance = new \Home\Model\ArticleModel();
		$data['id'] = I('post.id');
		if (empty($data['id'])) {
			header_message(406, 'id不能为空');
			return;
		}

		$data['category_id'] = I('post.category_select');
		if (empty($data['category_id'])) {
			header_message(406, '分类不能为空');
			return;
		}

		$data['title'] = I('post.title');
		if (empty($data['title'])) {
			header_message(406, '标题不能为空');
			return;
		}

		$data['content'] = I('post.content', '', false);
		if (empty($data['content'])) {
			header_json_message(406, '内容不能为空');
			return;
		}

		$data['modify_time'] = date('y-m-d H:i:s', time());
		$data['author_id'] = session('admin_id');

		if (I('post.istop') != '') {
			$data['istop'] = I('post.istop') == 'on' ? 1 : 0;
		}

		// 如果有上传文件
		if (!empty($_FILES['thumbnail']['tmp_name'])) {
			$upload_config = array(
				'maxSize'  => 1048576,								// 文件大小不超过1MB
				'rootPath' => './Uploads/',							// 文件上传根目录
				'savePath' => '',									// 上传子目录
				'mimes'    => array('image/jpeg', 'image/png'),		// 允许的MIME文件类型
				'exts'     => array('jpg', 'png'),					// 允许的后缀名
				'subName'  => false,								// 不适用子目录保存文件
			);
			$upload_ins = new \Think\Upload($upload_config);
			// 上传banner图
			$file_info = $upload_ins->upload();
			if (!$file_info) {
				header_message(406, $upload_ins->getError());
				return;
			}
			// 剪裁图片为414px * 176px
			$image = new \Think\Image();
			$image->open('./Uploads/'.$file_info['thumbnail']['savename']);
			$image->thumb(100, 70, \Think\Image::IMAGE_THUMB_FIXED)->save('./Uploads/'.$file_info['thumbnail']['savename']);
			$old_img = M('article')->where(array('id'=>$data['id']))->field('thumbnail')->select()[0];
			@unlink('./Uploads/55e53ec125f5b.jpg');
			$data['thumbnail'] = $file_info['thumbnail']['savename'];
		}

		// 添加数据
		if ($db_instance->update_article($data)) {
			header_message(200, 'success');
		} else {
			header_message(200, $db_instance->getError());
		}
	}

	public function article_del() {
		if (!IS_POST) {
			$this->error();
		}

		$db_instance = new \Home\Model\ArticleModel();
		$data = array();
		$data['id'] = I('post.article_id');
		if (empty($data['id'])) {
			header_json_message(406, 'article_id不能为空');
			return;
		}

		if ($db_instance->delete_article($data)) {
			header_json_message(200, 'success');
		} else {
			header_json_message(406, $db_instance->getError());
		}
	}
}