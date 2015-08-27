<?php
namespace Home\Controller;
use Common\Controller\CommonController;
class VoteController extends CommonController {
    public function index(){
        echo 'abc';
    	$this->redirect('Vote/listing');
    }

    public function listing() {
    	$this->display();
    }

    public function detail() {
    	$this->display();
    }
}