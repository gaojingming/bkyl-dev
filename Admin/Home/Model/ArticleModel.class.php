<?php
namespace Home\Model;
use Think\Model;

class ArticleModel extends Model {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('category_id', 'check_category_id', '文章分类错误', self::VALUE_VALIDATE, 'callback'),
	);

	/**
	 * category_id字段验证函数
	 * @return true or false
	 */
	protected function check_category_id($cate_id) {
		if (preg_match('/^[0-9]+$/', $cate_id) == 0)
			return false;
		$select_rs = M('category')->where(array('id'=>$cate_id))->select();
		if (empty($select_rs))
			return false;
		return true;
	}

	/**
	 * 查询当前文章内容
	 * @param  $id 文章id编号
	 * @return array 文章相关信息
	 */
	public function get_article($id) {
		$data = $this->where(array('id'=>$id))->select();
		return $data;
	}

	/**
	 * 添加新文章
	 * @return  true or false
	 */
	public function addNewArticle($data) {
		if (!$this->create($data))
			return false;
		$this->add($data);
		return true;
	}

	public function update_article($data) {
		if (!$this->create($data))
			return false;
		$this->save($data);
		return true;
	}

	public function delete_article($data) {
		$this->where(array('id'=>$data['id']))->delete();
		return true;
	}
}