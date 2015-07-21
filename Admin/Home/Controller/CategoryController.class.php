<?php
namespace Home\Controller;
use Common\Controller\BackendController;

class CategoryController extends BackendController {

	/**
	 * 默认跳转至栏目列表页面
	 */
	public function index() {
		redirect('Category/listing');
	}

	/**
	 * 栏目列表页面
	 */
	public function listing() {
		$this->display();
	}

	/**
	 * AJAX方式获取栏目列表
	 */
	public function get_category() {
		$category_info = M('category')->order('order_id')->select();
		header_json_message(200, $category_info);
	}

}