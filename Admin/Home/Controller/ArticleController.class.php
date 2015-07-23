<?php
namespace Home\Controller;
use Common\Controller\BackendController;

class ArticleController extends BackendController {
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

	public function index() {
		$this->redirect('Article/listing');
	}

	public function listing() {
		$this->display();
	}

	public function add() {
		$this->display();
	}

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