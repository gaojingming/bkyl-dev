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

}