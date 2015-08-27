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

		$data = M('article')->where(array('category_id'=>$category_id))->field('id, title, modify_time')->order('modify_time desc')->select();

		switch ($format) {
			case 'datatables':
				$table_data = $this->datatables_ajax_encode($data, $order_arr);
				echo json_encode($table_data);
				break;

			default:
				echo json_encode($data);
		}
	}

	public function article_add() {
		if (!IS_POST)
			$this->error();

		$db_instance = new \Home\Model\ArticleModel();
		$data['category_id'] = I('post.category_id');
		if (empty($data['category_id'])) {
			header_json_message(406, 'category_id不能为空');
			return;
		}

		$data['title'] = I('post.title');
		if (empty($data['title'])) {
			header_json_message(406, 'title不能为空');
			return;
		}

		$data['content'] = I('post.content', '', false);
		if (empty($data['content'])) {
			header_json_message(406, 'content不能为空');
			return;
		}

		$data['modify_time'] = date('y-m-d H:i:s', time());
		$data['author_id'] = session('admin_id');

		if (I('post.istop') != '') {
			$data['istop'] = I('post.istop') == 'true' ? 1 : 0;
		}

		// 添加数据
		if ($db_instance->addNewArticle($data)) {
			header_json_message(200, 'success');
		} else {
			header_json_message(200, $db_instance->getError());
		}
	}

	public function article_update() {
		if (!IS_POST) {
			$this->error();
		}

		$db_instance = new \Home\Model\ArticleModel();
		$data['id'] = I('post.id');
		if (empty($data['id'])) {
			header_json_message(406, 'id不能为空');
			return;
		}

		$data['category_id'] = I('post.category_id');
		if (empty($data['category_id'])) {
			header_json_message(406, 'category_id不能为空');
			return;
		}

		$data['title'] = I('post.title');
		if (empty($data['title'])) {
			header_json_message(406, 'title不能为空');
			return;
		}

		$data['content'] = I('post.content', '', false);
		if (empty($data['content'])) {
			header_json_message(406, 'content不能为空');
			return;
		}

		$data['modify_time'] = date('y-m-d H:i:s', time());
		$data['author_id'] = session('admin_id');

		if (I('post.istop') != '') {
			$data['istop'] = I('post.istop') == 'true' ? 1 : 0;
		}

		// 添加数据
		if ($db_instance->update_article($data)) {
			header_json_message(200, 'success');
		} else {
			header_json_message(200, $db_instance->getError());
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