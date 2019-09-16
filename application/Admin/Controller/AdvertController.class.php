<?php

/**
 * 广告位
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
use QCloud\Cos\Api;
use QCloud\Cos\Auth;



class AdvertController extends AdminbaseController {

	/*广告列表*/
    function index(){
		
    	$this->display();
    }



		
	function del(){



			$res['msg']='广告删除成功';
			echo json_encode($res);
			exit;		
										  			
	}		
   
    

	function add(){

		$this->display();				
	}

	function add_post(){

				$this->success('添加成功','Admin/Advert/index',3);
		
	}

	function edit(){

		$this->display();				
	}
	
	function edit_post(){

				$this->success('修改成功');
		
	}
	

    //设置下架
    public function setXiajia(){
    	$res=array("code"=>0,"msg"=>"下架成功","info"=>array());

    		$res['code']=1002;
    		$res['msg']="下架失败";
    		echo json_encode($res);
    		exit;

    	
    }

    /*下架视频列表*/
    public  function lowervideo(){

    	$this->display();
    }


    public function  video_listen(){

    	$this->display();
    }


    /*视频上架*/
    public function set_shangjia(){


    		$this->success("上架成功");

    	$this->display();
    }

    public function commentlists(){
    	

    	$this->display();

    }

    //排序
    public function listorders() { 

            $this->success("权重更新成功！");

    }
}
