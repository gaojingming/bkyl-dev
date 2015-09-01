<?php
namespace Home\Controller;
use Common\Controller\CommonController;

class ArticleController extends CommonController {
    public function index(){
    	redirect(U('Home/Article/article_list'));
    }

    /**
     * 文章列表页面
     */
    public function article_list() {
    	$category_id = I('get.category_id');
        $category = M('category')->where(array('id'=>$category_id))->select()[0]['name'];
    	$db_article = M('article');

        $count = $db_article->where(array('category_id'=>$category_id))->count();
        $page = new \Think\Page($count, 25);
        $show = $page->show();
        $list = $db_article->where(array('category_id'=>$category_id))->field('id, title, modify_time')->order('istop DESC, modify_time DESC')->limit($page->firstRow.', '.$page->listRows)->select();

        $rank = $db_article->order('view_num DESC')->limit(10)->select();

        $this->assign('article_list', $list);
        $this->assign('rank', $rank);
    	$this->assign('page', $show);
        $this->assign('category', $category);
    	$this->display();
    }

    /**
     * 文章详细内容页面
     */
    public function article() {
    	$article_id = I('get.id');
        $db = M('article');
        $rs = $db->where(array('id'=>$article_id))->select()[0];
        $data['id'] = $article_id;
        $data['view_num'] = $rs['view_num'] + 1;
        $db->save($data);

        $rank = $db->order('view_num DESC')->limit(10)->select();

        $this->assign('rank', $rank);
    	$this->assign('article_data', $rs);
    	$this->display();
    }
}