<?php
/* 
   扩展配置
 */

namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class ConfigprivateController extends AdminbaseController{
	
	function index(){
		$config=M("config_private")->find(1);


		$this->assign('config',$config);

		$this->display();
	}

	
	function set_post(){
		if(IS_POST){
			
			 $config=I("post.post");
			foreach($config as $k=>$v){
				$config[$k]=html_entity_decode($v);
			}

				
				if (M("config_private")->where("id='1'")->save($config)!==false) {
					$this->success("保存成功！");
				} else {
					$this->error("保存失败！");
				}
		
		}
	}

}