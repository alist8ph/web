<?php

/**
 * 会员
 */
namespace User\Controller;
use Common\Controller\AdminbaseController;
class IndexadminController extends AdminbaseController {
	
	protected $users_model;
	
	function _initialize() {
		parent::_initialize();
		$this->users_model = D("Common/Users");
	}

	
    function index(){
			
			$map=array();
			$map['user_type']=2;
			
			if($_REQUEST['iszombie']!=''){
				$map['iszombie']=$_REQUEST['iszombie'];
				$_GET['iszombie']=$_REQUEST['iszombie'];
			 }
			 
			 if($_REQUEST['isban']!=''){
				$map['user_status']=$_REQUEST['isban'];
				$_GET['isban']=$_REQUEST['isban'];
			 }
			 
			 if($_REQUEST['issuper']!=''){

				$map['issuper']=$_REQUEST['issuper'];
				$_GET['issuper']=$_REQUEST['issuper'];
			 }
			 

			 if($_REQUEST['ishot']!=''){
				$map['ishot']=$_REQUEST['ishot'];
				$_GET['ishot']=$_REQUEST['ishot'];
			 }
			 
			 if($_REQUEST['iszombiep']!=''){
				$map['iszombiep']=$_REQUEST['iszombiep'];
				$_GET['iszombiep']=$_REQUEST['iszombiep'];
			 }
					 
			 if($_REQUEST['start_time']!=''){
				$map['create_time']=array("gt",$_REQUEST['start_time']);
				$_GET['start_time']=$_REQUEST['start_time'];
			 }
			 
			 if($_REQUEST['end_time']!=''){
				$map['create_time']=array("lt",$_REQUEST['end_time']);
				$_GET['end_time']=$_REQUEST['end_time'];
			 }
			 if($_REQUEST['start_time']!='' && $_REQUEST['end_time']!='' ){
				$map['create_time']=array("between",array($_REQUEST['start_time'],$_REQUEST['end_time']));
				$_GET['start_time']=$_REQUEST['start_time'];
				$_GET['end_time']=$_REQUEST['end_time'];
			 }

			 if($_REQUEST['keyword']!=''){
				$where['id|user_login|user_nicename']	=array("like","%".$_REQUEST['keyword']."%");
				$where['_logic']	="or";
				$map['_complex']=$where;
				
				$_GET['keyword']=$_REQUEST['keyword'];
			 }
			

    	$users_model=$this->users_model;
    	$count=$users_model->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $users_model
    	->where($map)
    	->order("id DESC")
    	->limit($page->firstRow . ',' . $page->listRows)
    	->select();

			
    	$this->assign('lists', $lists);
    	$this->assign('formget', $_GET);
    	$this->assign("page", $page->show("Admin"));
    	
    	$this->display(":index");
    }
     function del(){
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->delete();
    		if ($rst!==false) {
					
					
					// 删除关注记录
					M("users_attention")->where("uid='{$id}' or touid='{$id}'")->delete();
					//删除关注信息
					M("users_attention_messages")->where("uid='{$id}' or touid='{$id}'")->delete();
					//删除用户认证
					M("users_auth")->where("uid={$id}")->delete();
					// 删除黑名单
					M("users_black")->where("uid='{$id}' or touid='{$id}'")->delete();
					//删除音乐
					M("users_music")->where("uploader='{$id}'")->delete();
					//删除音乐收藏
					M("users_music_collection")->where("uid={$id}")->delete();
					//删除举报记录记录
					M("users_report")->where("uid='{$id}' or touid='{$id}'")->delete();
					//删除反馈记录
					M("feedback")->where("uid='{$id}'")->delete();

                    /* 删除视频 */
                    $videolist=M('users_video')->field("id")->where("uid={$id}")->select();
                    //删除视频举报
                    M('users_video_report')->where("uid='{$id}' or touid='{$id}'")->delete();

                    foreach($videolist as $k=>$v){
                       
                        //删除评论喜欢
                        M('users_video_comments_like')->where("videoid={$v['id']}")->delete();
                        //删除评论
                        M('users_video_comments')->where("videoid={$v['id']}")->delete();
                        //删除评论信息
                        M("users_video_comments_messages")->where("videoid={$v['id']}")->delete();
                        //删除评论@信息
                        M("users_video_comments_at_messages")->where("videoid={$v['id']}")->delete();
                        //删除视频喜欢
                        M('users_video_like')->where("videoid={$v['id']}")->delete();
                        //删除视频举报
                        M('users_video_report')->where("videoid={$v['id']}")->delete();
                        //删除赞列表
                        M("praise_messages")->where("videoid={$v['id']}")->delete();

                    }

                    //删除视频
                    M('users_video')->where("uid={$id}")->delete();
					
					//删除系统通知
					M("system_push")->where("uid={$id}")->delete();

					//删除赞列表
					M("praise_messages")->where("uid='{$id}' or touid='{$id}'")->delete();
                    
			
    			$this->success("会员删除成功！");
    		} else {
    			$this->error('会员删除失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }   
    function ban(){
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->setField('user_status','0');
    		if ($rst!==false) {
				/*$nowtime=time();
				$redis = connectionRedis();
				$time=$nowtime + 60*60*1;
				$live=M("users_live")->field("uid")->where("islive='1'")->select();
				foreach($live as $k=>$v){
					$redis -> hSet($v['uid'] . 'shutup',$id,$time);
				}
				$redis -> close();	*/
    			$this->success("会员拉黑成功！");
    		} else {
    			$this->error('会员拉黑失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
    
    function cancelban(){ 
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->setField('user_status','1');
    		if ($rst!==false) {
    			$this->success("会员启用成功！");
    		} else {
    			$this->error('会员启用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }   

	function cancelsuper(){
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->setField('issuper','0');
			$rst = M("users_super")->where("uid='{$id}'")->delete();
    		if ($rst!==false) {
				$redis = connectionRedis();
				$redis  -> hDel('super',$id);
				$redis -> close();
    			$this->success("会员取超管成功！");
    		} else {
    			$this->error('会员取消超管失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
    
    function super(){ 
    	$id=intval($_GET['id']);
    	if ($id) {
			$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->setField('issuper','1');
    		$rst = M("users_super")->add(array('uid'=>$id,'addtime'=>time()));
    		if ($rst!==false) {
				$redis = connectionRedis();
				$redis  -> hset('super',$id,'1');
				$redis -> close();
    			$this->success("会员设置超管成功！");
    		} else {
    			$this->error('会员设置超管失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }

	function cancelhot(){
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->setField('ishot','0');
    		if ($rst!==false) {
    			$this->success("会员取消热门成功！");
    		} else {
    			$this->error('会员取消热门失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
    
    function hot(){ 
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->setField('ishot','1');
    		if ($rst!==false) {
    			$this->success("会员设置热门成功！");
    		} else {
    			$this->error('会员设置热门失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }	
	
	function cancelrecommend(){
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->setField('isrecommend','0');
    		if ($rst!==false) {
    			$this->success("会员取消推荐成功！");
    		} else {
    			$this->error('会员取消推荐失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
    
    function recommend(){ 
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->setField('isrecommend','1');
    		if ($rst!==false) {
    			$this->success("会员推荐成功！");
    		} else {
    			$this->error('会员推荐失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
		
		function cancelzombie(){
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->setField('iszombie','0');
    		if ($rst!==false) {
    			$this->success("关闭成功！");
    		} else {
    			$this->error('关闭失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
    
    function zombie(){ 
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->setField('iszombie','1');
    		if ($rst!==false) {
    			$this->success("开启成功！");
    		} else {
    			$this->error('开启失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }	
    function zombieall(){ 
    	$iszombie=intval($_GET['iszombie']);

    		$rst = M("Users")->where("user_type='2'")->setField('iszombie',$iszombie);
    		if ($rst!==false) {
    			$this->success("操作成功！");
    		} else {
    			$this->error('操作失败！');
    		}

    }				
		
		function cancelzombiep(){
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->setField('iszombiep','0');
    		if ($rst!==false) {
				M("users_zombie")->where("uid='{$id}'")->delete();
    			$this->success("关闭成功！");
    		} else {
    			$this->error('关闭失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
    
    function zombiep(){ 
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->setField('iszombiep','1');
    		if ($rst!==false) {
				$users_zombie=M("users_zombie");
				$isexist=$users_zombie->where("uid={$id}")->find();
				if(!$isexist){
					$users_zombie->add(array("uid"=>$id));	
				}
    			$this->success("开启成功！");
    		} else {
    			$this->error('开启失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }			
		
    //批量设置僵尸粉
    public function zombiepbatch() { 
		$iszombiep=intval($_GET['iszombiep']);
		$ids = $_POST['ids'];
		$tids=join(",",$_POST['ids']);
		$users_zombie=M("users_zombie");
		$rst = M("Users")->where("id in ({$tids}) and user_type='2'")->setField('iszombiep',$iszombiep);
		if ($rst!==false) {
			if($iszombiep==1){
				foreach($ids as $k=>$v){
					$isexist=$users_zombie->where("uid={$v}")->find();
					if(!$isexist){
						$users_zombie->add(array("uid"=>$v));	
					}
					
				}
				
			}else{
				$users_zombie->where("uid in ({$tids})")->delete();
			}
			$this->success("设置成功！");
		} else {
			$this->error('设置失败！');
		}
    }		
		
		function cancelrecord(){
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->setField('isrecord','0');
    		if ($rst!==false) {
    			$this->success("关闭成功！");
    		} else {
    			$this->error('关闭失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
    
    function record(){ 
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->setField('isrecord','1');
    		if ($rst!==false) {
    			$this->success("开启成功！");
    		} else {
    			$this->error('开启失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }	
    function recordall(){ 
    	$isrecord=intval($_GET['isrecord']);

    		$rst = M("Users")->where("user_type='2'")->setField('isrecord',$isrecord);
    		if ($rst!==false) {
    			$this->success("操作成功！");
    		} else {
    			$this->error('操作失败！');
    		}

    }				
	function add(){
			  
				$this->display(":add");				
	}
	
	function add_post(){
		if(IS_POST){			
			$user=$this->users_model;
			 
			if( $user->create()){
				$user->user_type=2;
				$user->user_pass=sp_password($_POST['user_pass']);
				$user->code=createCode();
				$avatar=$_POST['avatar'];
				
				if($avatar==''){
					$user->avatar= '/default.jpg'; 
					$user->avatar_thumb= '/default_thumb.jpg'; 
				}else if(strpos($avatar,'http')===0){
					/* 绝对路径 */
					$user->avatar=  $avatar; 
					$user->avatar_thumb=  $avatar;
				}else if(strpos($avatar,'/')===0){
					/* 本地图片 */
					$user->avatar=  $avatar;
					$user->avatar_thumb=  $avatar; 
				}else{
					/* 七牛 */
					//$user->avatar=  $avatar.'?imageView2/2/w/600/h/600'; //600 X 600
					//$user->avatar_thumb=  $avatar.'?imageView2/2/w/200/h/200'; // 200 X 200
				}

				$user->create_time=date('Y-m-d H:i:s',time());
				if(trim($_POST['signature'])==""){
					$user->signature='这家伙很懒，什么都没留下';
				}
				
				$result=$user->add();
				if($result!==false){
					$this->success('添加成功');
				}else{
					$this->error('添加失败');
				}					 
				 
			}else{
				$this->error($this->users_model->getError());
			}
			

		}			
	}		
	function edit(){
		$id=intval($_GET['id']);
		if($id){
			$userinfo=M("users")->find($id);
			$this->assign('userinfo', $userinfo);						
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display(":edit");				
	}
	
	function edit_post(){
		if(IS_POST){			
			$user=M("users");
			$user->create();
			$avatar=$_POST['avatar'];
			
			$code=$_POST['code'];
			$id=$_POST['id'];
			if($code!=''){
				$isexist=M("users")->field("id")->where("code='{$code}' and id!={$id}")->find();
				if($isexist){
					$this->error('邀请码已存在');
				}
			}
				
			if($avatar==''){
				$user->avatar= '/default.jpg'; 
				$user->avatar_thumb= '/default_thumb.jpg'; 
			}else if(strpos($avatar,'http')===0){
				/* 绝对路径 */
				$user->avatar=  $avatar; 
				$user->avatar_thumb=  $avatar;
			}else if(strpos($avatar,'/')===0){
				/* 本地图片 */
				$user->avatar=  $avatar; 
				$user->avatar_thumb=  $avatar; 
			}else{
				/* 七牛 */
				//$user->avatar=  $avatar.'?imageView2/2/w/600/h/600'; //600 X 600
				//$user->avatar_thumb=  $avatar.'?imageView2/2/w/200/h/200'; // 200 X 200
			}
			 $result=$user->save(); 
			 if($result!==false){
				  $this->success('修改成功');
			 }else{
				  $this->error('修改失败');
			 }
		}			
	}
	/* 生成邀请码 */
	function createCode(){
		$code=createCode();
		$rs=array('info'=>$code);
		echo json_encode($rs);
		exit;
	}
	//重置密码
	function resetpassword(){
		$id=intval($_GET['id']);
		if($id){
			$userinfo=M("users")->find($id);
			$this->assign('userinfo', $userinfo);						
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display(":resetpassword");				
	}
	function edit_resetpwdpost(){
		if(IS_POST){			
			$user=M("users");
			$user->create();
			$user->user_pass=sp_password($_POST['user_pass']);
			 $result=$user->save(); 
			 if($result!==false){
				  $this->success('修改成功');
			 }else{
				  $this->error('修改失败');
			 }
		}
	}
		
}
