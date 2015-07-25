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
}