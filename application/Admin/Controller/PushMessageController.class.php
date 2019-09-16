<?php

/**
 * 极光推送
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
use JMessage\JMessage;
use JMessage\IM\Admin;
use JMessage\IM\Message;
use JMessage\IM\User;

class PushMessageController extends AdminbaseController {

	/*推送发送*/
	function add(){

		$this->display();
	}

	function add_post(){
		$rs=array("code"=>0,"msg"=>"","info"=>array());


			$rs['code']=1002;
			$rs['msg']="推送失败";
			echo json_encode($rs);



	}

	/*推送记录*/
	public function index(){

    	$this->display();
	}

	/*将原来的信息重新获取一份新加入数据库*/
	public function push_add(){

		$res=array("code"=>0,"msg"=>"","info"=>array());


		echo json_encode($res);

	}


	public function push(){
		
		$res=array("code"=>0,"msg"=>"","info"=>array());


			$res['code']=1001;
			$res['msg']="消息推送失败";
			echo json_encode($res);
			exit;

		
	}

	public function del(){
		$id=I("id");

			$this->success("删除成功");

	}

	

}
