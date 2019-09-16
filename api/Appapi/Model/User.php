<?php

class Model_User extends Model_Common {
	/* 用户全部信息 */
	public function getBaseInfo($uid) {

		
		$info=DI()->notorm->users
				->select("id,user_nicename,avatar,avatar_thumb,sex,signature,coin,province,city,area,birthday,age,mobile")
				->where('id=?  and user_type="2"',$uid)
				->fetchOne();

		if($info['age']==-1){
			$info['age']="年龄未填写";
		}else{
			$info['age'].="岁";
		}

		if($info['city']==""){
			$info['city']="城市未填写";
			$info['hometown']="";
		}else{
			$info['hometown']=$info['province'].$info['city'].$info['area'];
		}	

		$info['avatar']=$this->get_upload_path($info['avatar']);
		$info['avatar_thumb']=$this->get_upload_path($info['avatar_thumb']);						
		$info['follows']=round(1,100000);
		$info['fans']=round(1,100000);
		$info['praise']=round(1,100000);
		$info['workVideos']=round(1,100000);
		$info['likeVideos']=round(1,100000);
		
					
		return $info;
	}

		/* 个人主页 */
	public function getUserHome($uid,$touid){
		$info=$this->getUserInfo($touid);				

		$info['follows']=$this->NumberFormat(round(1,100000));
		$info['fans']=$this->NumberFormat(round(1,100000));
		$info['isattention']=(string)round(0,1);

		return $info;
	}
}
