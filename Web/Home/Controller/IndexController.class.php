<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	echo 'index: <br>';
    	echo U('Index/index');
    }
}