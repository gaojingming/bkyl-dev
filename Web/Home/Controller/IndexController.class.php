<?php
namespace Home\Controller;
use Common\Controller\CommonController;
class IndexController extends CommonController {
    public function index(){
    	$art_db = M('article');

        $news = $art_db->where(array('category_id'=>'1'))->order('modify_time DESC')->limit(4)->select();
    	$traditional_tech = $art_db->where(array('category_id'=>'3'))->order('modify_time DESC')->limit(5)->select();
    	$clinic_study = $art_db->where(array('category_id'=>'5'))->order('modify_time DESC')->limit(5)->select();
    	$pro_lesson = $art_db->where(array('category_id'=>'4'))->order('modify_time DESC')->limit(5)->select();
    	$knowledge = $art_db->where(array('category_id'=>'7'))->order('modify_time DESC')->limit(5)->select();

        $this->assign('news', $news);
    	$this->assign('traditional_tech', $traditional_tech);
    	$this->assign('clinic_study', $clinic_study);
    	$this->assign('pro_lesson', $pro_lesson);
    	$this->assign('knowledge', $knowledge);
    	$this->display();
    }
}