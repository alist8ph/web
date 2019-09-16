<?php

/**
 * 举报
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;


class ReportController extends AdminbaseController {

	function classify(){

		$this->display();
	}

	/*分类添加*/
	function classify_add(){

		$this->display();
	}


	/*分类添加提交*/
	function classify_add_post(){

		if(IS_POST){

				$this->success('添加成功','Admin/Report/classify',3);

		}

	}

	//分类排序
    function classify_listorders() { 
		

            $this->success("排序更新成功！");

    }

    /*分类删除*/
	function classify_del(){

				$this->success('删除成功');

	}

	/*分类编辑*/
	function classify_edit(){

		
		$this->display();
	}

	/*分类编辑提交*/
	function classify_edit_post(){

				  $this->success('修改成功');


	}

    function index(){

    	
    	$this->display();
    }
		
	function setstatus(){

							$this->success('标记成功');
							  		
	}		
		
	function del(){

								$this->success('删除成功');
						  
	}		

		
	function edit(){
					  
		$this->display();				
	}
		
	function edit_post(){

				  $this->success('修改成功',U('Report/index'));
		
	}

	function ban(){

    			$this->success("会员拉黑成功！");

    }

    function ban_video(){

				
    			$this->success("被举报用户所有视频下架成功！");

    }

    function ban_all(){

				
    		$this->success("操作成功！");

    }
    
}
