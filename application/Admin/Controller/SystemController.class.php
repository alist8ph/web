<?php

/**
 * 系统消息
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class SystemController extends AdminbaseController {
    function index(){

		$config=M("config_private")->where("id='1'")->find();
			
		$this->assign('config', $config);
			
    	
    	$this->display("edit");
    }
		
	function send(){
		$content=I("content");
		
		if(!$content){
			$data=array(
				"error"=>10001,
				"data"=>'',
				"msg"=>'内容不能为空'
			);
		}
		//$id=$_SESSION['ADMIN_ID'];
		//$user=M("users")->where("id={$id}")->find();	
		
		$data=array(
			"error"=>0,
			"data"=>'',
			"msg"=>''
		);
				
		echo json_encode($data);
		
	}		
		
}
