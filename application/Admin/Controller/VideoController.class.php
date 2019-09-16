<?php

/**
 * 短视频
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
use QCloud\Cos\Api;
use QCloud\Cos\Auth;



class VideoController extends AdminbaseController {

	/*待审核视频列表*/
    function index(){
		
		if($_REQUEST['ordertype']!=''){
			$ordertype=$_REQUEST['ordertype'];
			$_GET['ordertype']=$_REQUEST['ordertype'];
		}
		 $map['isdel']=0;
		 $map['status']=0; 
		
		if($_REQUEST['keyword']!=''){
			$map['uid|id']=array("eq",$_REQUEST['keyword']); 
			$_GET['keyword']=$_REQUEST['keyword'];
		}		
		if($_REQUEST['keyword1']!=''){
			$map['title']=array("like","%".$_REQUEST['keyword1']."%");  
			$_GET['keyword1']=$_REQUEST['keyword1'];
		}
		//用户名称
		if($_REQUEST['keyword2']!=''){
			/* $map['title']=array("like","%".$_REQUEST['keyword2']."%");   */
			$_GET['keyword2']=$_REQUEST['keyword2'];
			$username=$_REQUEST['keyword2'];
			$userlist =M("users")->field("id")->where("user_nicename like '%".$username."%'")->select();
			$strids="";
			foreach($userlist as $ku=>$vu){
				if($strids==""){
					$strids=$vu['id'];
				}else{
					$strids.=",".$vu['id'];
				}
			}
			$map['uid']=array("in",$strids);
		}
		
		$p=I("p");
		if(!$p){
			$p=1;
		}

    	$video_model=M("users_video");
    	$count=$video_model->where($map)->count();
    	$page = $this->page($count, 20);
		$orderstr="";
		if($ordertype==1){//评论数排序
			$orderstr="comments DESC";
		}else if($ordertype==2){//票房数量排序（点赞）
			$orderstr="likes DESC";
		}else if($ordertype==3){//分享数量排序
			$orderstr="shares DESC";
		}else{
			$orderstr="addtime DESC";
		}
		
    	$lists = $video_model
			->where($map)
			->order($orderstr)
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
		foreach($lists as $k=>$v){
			if($v['uid']==0){
				$userinfo=array(
					'user_nicename'=>'系统管理员'
				);
			}else{
				$userinfo=getUserInfo($v['uid']);
				if(!$userinfo){
					$userinfo=array(
						'user_nicename'=>'已删除'
					);
				}
				
			}

			
			$lists[$k]['userinfo']=$userinfo;
			
			$hasurgemoney=($v['big_urgenums']-$v['urge_nums'])*$v['urge_money'];
			$lists[$k]['hasurgemoney']=$hasurgemoney;
		}
    	$this->assign('lists', $lists);
		$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	$this->assign("p",$p);
    	$this->display();
    }


     /*未通过视频列表*/
	
    function nopassindex(){
		
		if($_REQUEST['ordertype']!=''){
			$ordertype=$_REQUEST['ordertype'];
			$_GET['ordertype']=$_REQUEST['ordertype'];
		}
		 $map['isdel']=0; 
		 $map['status']=2; 
		
		if($_REQUEST['keyword']!=''){
			$map['uid|id']=array("eq",$_REQUEST['keyword']); 
			$_GET['keyword']=$_REQUEST['keyword'];
		}		
		if($_REQUEST['keyword1']!=''){
			$map['title']=array("like","%".$_REQUEST['keyword1']."%");  
			$_GET['keyword1']=$_REQUEST['keyword1'];
		}
		//用户名称
		if($_REQUEST['keyword2']!=''){
			/* $map['title']=array("like","%".$_REQUEST['keyword2']."%");   */
			$_GET['keyword2']=$_REQUEST['keyword2'];
			$username=$_REQUEST['keyword2'];
			$userlist =M("users")->field("id")->where("user_nicename like '%".$username."%'")->select();
			$strids="";
			foreach($userlist as $ku=>$vu){
				if($strids==""){
					$strids=$vu['id'];
				}else{
					$strids.=",".$vu['id'];
				}
			}
			$map['uid']=array("in",$strids);
		}
		
		$p=I("p");
		if(!$p){
			$p=1;
		}

    	$video_model=M("users_video");
    	$count=$video_model->where($map)->count();
    	$page = $this->page($count, 20);
		$orderstr="";
		if($ordertype==1){//评论数排序
			$orderstr="comments DESC";
		}else if($ordertype==2){//票房数量排序（点赞）
			$orderstr="likes DESC";
		}else if($ordertype==3){//分享数量排序
			$orderstr="shares DESC";
		}else{
			$orderstr="addtime DESC";
		}
		
    	$lists = $video_model
			->where($map)
			->order($orderstr)
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
		foreach($lists as $k=>$v){
			if($v['uid']==0){
				$userinfo=array(
					'user_nicename'=>'系统管理员'
				);
			}else{
				$userinfo=getUserInfo($v['uid']);
				if(!$userinfo){
					$userinfo=array(
						'user_nicename'=>'已删除'
					);
				}
				
			}

			
			$lists[$k]['userinfo']=$userinfo;
			
			$hasurgemoney=($v['big_urgenums']-$v['urge_nums'])*$v['urge_money'];
			$lists[$k]['hasurgemoney']=$hasurgemoney;
		}
    	$this->assign('lists', $lists);
		$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	$this->assign("p",$p);
    	$this->display();
    }


    /*审核通过视频列表*/
	
    function passindex(){
		
		if($_REQUEST['ordertype']!=''){
			$ordertype=$_REQUEST['ordertype'];
			$_GET['ordertype']=$_REQUEST['ordertype'];
		}
		 $map['isdel']=0; 
		 $map['status']=1; 
		
		if($_REQUEST['keyword']!=''){
			$map['uid|id']=array("eq",$_REQUEST['keyword']); 
			$_GET['keyword']=$_REQUEST['keyword'];
		}		
		if($_REQUEST['keyword1']!=''){
			$map['title']=array("like","%".$_REQUEST['keyword1']."%");  
			$_GET['keyword1']=$_REQUEST['keyword1'];
		}
		//用户名称
		if($_REQUEST['keyword2']!=''){
			/* $map['title']=array("like","%".$_REQUEST['keyword2']."%");   */
			$_GET['keyword2']=$_REQUEST['keyword2'];
			$username=$_REQUEST['keyword2'];
			$userlist =M("users")->field("id")->where("user_nicename like '%".$username."%'")->select();
			$strids="";
			foreach($userlist as $ku=>$vu){
				if($strids==""){
					$strids=$vu['id'];
				}else{
					$strids.=",".$vu['id'];
				}
			}
			$map['uid']=array("in",$strids);
		}
		
		$p=I("p");
		if(!$p){
			$p=1;
		}

    	$video_model=M("users_video");
    	$count=$video_model->where($map)->count();
    	$page = $this->page($count, 20);
		$orderstr="";
		if($ordertype==1){//评论数排序
			$orderstr="comments DESC";
		}else if($ordertype==2){//票房数量排序（点赞）
			$orderstr="likes DESC";
		}else if($ordertype==3){//分享数量排序
			$orderstr="shares DESC";
		}else{
			$orderstr="addtime DESC";
		}
		
    	$lists = $video_model
			->where($map)
			->order($orderstr)
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
		foreach($lists as $k=>$v){
			if($v['uid']==0){
				$userinfo=array(
					'user_nicename'=>'系统管理员'
				);
			}else{
				$userinfo=getUserInfo($v['uid']);
				if(!$userinfo){
					$userinfo=array(
						'user_nicename'=>'已删除'
					);
				}
				
			}

			
			$lists[$k]['userinfo']=$userinfo;
			
			$hasurgemoney=($v['big_urgenums']-$v['urge_nums'])*$v['urge_money'];
			$lists[$k]['hasurgemoney']=$hasurgemoney;
		}
    	$this->assign('lists', $lists);
		$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	$this->assign("p",$p);
    	$this->display();
    }

		
	function del(){

			$res['code']=1002;
			$res['msg']='视频删除失败';
			echo json_encode($res);
			exit;		
										  			
	}		
    //排序
    public function listorders() { 

            $this->success("排序更新成功！");

    }	
    

	function add(){

		$this->display();				
	}

	function add_post(){

		$this->success('添加成功','Admin/Video/passindex',3);
		
	}

	function edit(){					  
		$this->display();				
	}
	
	function edit_post(){

				$this->success('修改成功');		
	}
	
    function reportlist(){

    	$this->display();
    }
		
	function setstatus(){
				$this->success('标记成功');
							  		
	}		
	//删除用户举报列表
	function report_del(){
				$this->success('删除成功');
								  
	}	
	//举报内容设置**************start******************
	
	//举报类型列表
	
	function reportset(){
		$this->display();
	}
	//添加举报理由
	function add_report(){
		
		$this->display();
	}
	function add_reportpost(){

				$this->success('添加成功');
	}
	//编辑举报类型名称
	function edit_report(){
							  
		$this->display();				
	}
	
	function edit_reportpost(){

				  $this->success('修改成功');
		
	}
	//删除举报类型名称
	function del_report(){

				$this->success('删除成功');
						  
		$this->display();		
	}
	  //举报内容排序
    public function listordersset() { 

            $this->success("排序更新成功！");
    }	
//举报内容设置**************end******************	
//
    //设置下架
    public function setXiajia(){

    		$res['code']=1002;
    		$res['msg']="下架失败";
    		echo json_encode($res);
    		exit;
    	
    }

    /*下架视频列表*/
    public  function lowervideo(){

    	if($_REQUEST['ordertype']!=''){
			$ordertype=$_REQUEST['ordertype'];
			$_GET['ordertype']=$_REQUEST['ordertype'];
		}
		 $map['isdel']=1; 
		
		if($_REQUEST['keyword']!=''){
			$map['uid|id']=array("eq",$_REQUEST['keyword']); 
			$_GET['keyword']=$_REQUEST['keyword'];
		}		
		if($_REQUEST['keyword1']!=''){
			$map['title']=array("like","%".$_REQUEST['keyword1']."%");  
			$_GET['keyword1']=$_REQUEST['keyword1'];
		}
		//用户名称
		if($_REQUEST['keyword2']!=''){
			/* $map['title']=array("like","%".$_REQUEST['keyword2']."%");   */
			$_GET['keyword2']=$_REQUEST['keyword2'];
			$username=$_REQUEST['keyword2'];
			$userlist =M("users")->field("id")->where("user_nicename like '%".$username."%'")->select();
			$strids="";
			foreach($userlist as $ku=>$vu){
				if($strids==""){
					$strids=$vu['id'];
				}else{
					$strids.=",".$vu['id'];
				}
			}
			$map['uid']=array("in",$strids);
		}

		$p=I("p");
		if(!$p){
			$p=1;
		}
		
		
    	$video_model=M("users_video");
    	$count=$video_model->where($map)->count();
    	$page = $this->page($count, 20);
		$orderstr="";
		if($ordertype==1){//评论数排序
			$orderstr="comments DESC";
		}else if($ordertype==2){//点赞数量排序
			$orderstr="likes DESC";
		}else if($ordertype==3){//分享数量排序
			$orderstr="shares DESC";
		}else{
			$orderstr="addtime DESC";
		}
		
    	$lists = $video_model
			->where($map)
			->order($orderstr)
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
		foreach($lists as $k=>$v){
			if($v['uid']==0){
				$userinfo=array(
					'user_nicename'=>'系统管理员'
				);
			}else{
				$userinfo=getUserInfo($v['uid']);
				if(!$userinfo){
					$userinfo=array(
						'user_nicename'=>'已删除'
					);
				}
				
			}

			
			$lists[$k]['userinfo']=$userinfo;
			
			$hasurgemoney=($v['big_urgenums']-$v['urge_nums'])*$v['urge_money'];
			$lists[$k]['hasurgemoney']=$hasurgemoney;
		}
    	$this->assign('lists', $lists);
		$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	$this->assign("p",$p);
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
}
