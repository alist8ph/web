<?php

class Model_Common extends PhalApi_Model_NotORM {

	/* 密码检查 */
	public function passcheck($user_pass) {
		$num = preg_match("/^[a-zA-Z]+$/",$user_pass);
		$word = preg_match("/^[0-9]+$/",$user_pass);
		$check = preg_match("/^[a-zA-Z0-9]{6,12}$/",$user_pass);
		if($num || $word ){
			return 2;
		}else if(!$check){
			return 0;
		}		
		return 1;
	}
	
	/* 密码加密 */
	public function setPass($pass){
		$authcode='rCt52pF2cnnKNB3Hkp';
		$pass="###".md5(md5($authcode.$pass));
		return $pass;
	}	
	
	/* 公共配置 */
	public function getConfigPub() {
			$config= DI()->notorm->config
					->select('*')
					->where(" id ='1'")
					->fetchOne();
				
			if($config['login_type']){
				$config['login_type']=preg_split('/,|，/',$config['login_type']);
			}else{
				$config['login_type']=array();
			}
			
			if($config['share_type']){
				$config['share_type']=preg_split('/,|，/',$config['share_type']);
			}else{
				$config['share_type']=array();
			}

		return 	$config;
	}		
	
	/* 私密配置 */
	public function getConfigPri() {

			$config= DI()->notorm->config_private
					->select('*')
					->where(" id ='1'")
					->fetchOne();

		return 	$config;
	}		
	
	/**
	 * 返回带协议的域名
	 */
	public function get_host(){

		$config=$this->getConfigPub();
		return $config['site'];
	}	
	
	/**
	 * 转化数据库保存的文件路径，为可以访问的url
	 */
	public function get_upload_path($file){
		if(strpos($file,"http")===0){
			return html_entity_decode($file);
		}else if(strpos($file,"/")===0){
			$filepath= $this->get_host().$file;
			return html_entity_decode($filepath);
		}else{
			$configpri=$this->getConfigPri();
			if($configpri['cloudtype']==1){ //七牛存储
				$space_host=$configpri['qiniu_domain_url'];
			}else{
				$space_host="http://";
			}
			
			$filepath=$space_host.$file;
			return html_entity_decode($filepath);
		}
	}
	
	/* 判断token */
	public function checkToken($uid,$token) {

			$userinfo=DI()->notorm->users
						->select('token,expiretime')
						->where('id = ? and user_type="2"', $uid)
						->fetchOne();	

		if($userinfo['token']!=$token || $userinfo['expiretime']<time()){
			return 700;				
		}else{
			return 	0;				
		} 		
	}	
	
	/* 用户基本信息 */
	public function getUserInfo($uid) {



		if($uid==0){



			if($uid==='dsp_admin_1'){



				$info['user_nicename']="云豹官方";	
				$info['avatar']=$this->get_upload_path('/officeMsg.png');
				$info['avatar_thumb']=$this->get_upload_path('/officeMsg.png');
				$info['id']="dsp_admin_1";

			}else if($uid==='dsp_admin_2'){

				$info['user_nicename']="系统通知";	
				$info['avatar']=$this->get_upload_path('/systemMsg.png');
				$info['avatar_thumb']=$this->get_upload_path('/systemMsg.png');
				$info['id']="dsp_admin_2";
			}else{

				$info['user_nicename']="系统管理员";	
				$info['avatar']=$this->get_upload_path('/default.jpg');
				$info['avatar_thumb']=$this->get_upload_path('/default_thumb.jpg');
				$info['id']="0";
			}



			$info['coin']="0";
				$info['sex']="1";
				$info['signature']='';
				$info['province']='';
				$info['city']='城市未填写';
				$info['birthday']='';
				$info['praise']='0';
				$info['fans']='0';
				$info['follows']='0';
				$info['workVideos']='0'; //作品数
				$info['likeVideos']='0'; //喜欢别人的视频数
				$info['age']="年龄未填写";

			

		}else{
				$info=DI()->notorm->users
						->select('id,user_nicename,avatar,coin,avatar_thumb,sex,signature,province,city,birthday,age')
						->where('id=? and user_type="2"',$uid)
						->fetchOne();	
				if($info){

					if($info['age']<0){
						$info['age']="年龄未填写";
					}else{
						$info['age'].="岁";
					}

					if($info['city']==""){
						$info['city']="城市未填写";
					}

					$info['avatar']=$this->get_upload_path($info['avatar']);
					$info['avatar_thumb']=$this->get_upload_path($info['avatar_thumb']);

					$info['praise']=round(1,100000);
					$info['fans']=round(1,100000);
					$info['follows']=round(1,100000);
					$info['workVideos']=round(1,100000);
					$info['likeVideos']=round(1,100000);
				}
		}
		return 	$info;		
	}

	/**
	*  @desc 根据两点间的经纬度计算距离
	*  @param float $lat 纬度值
	*  @param float $lng 经度值
	*/
	public function getDistance($lat1, $lng1, $lat2, $lng2){
		$earthRadius = 6371000; //近似地球半径 单位 米
		 /*
		   Convert these degrees to radians
		   to work with the formula
		 */

		$lat1 = ($lat1 * pi() ) / 180;
		$lng1 = ($lng1 * pi() ) / 180;

		$lat2 = ($lat2 * pi() ) / 180;
		$lng2 = ($lng2 * pi() ) / 180;


		$calcLongitude = $lng2 - $lng1;
		$calcLatitude = $lat2 - $lat1;
		$stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);  $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
		$calculatedDistance = $earthRadius * $stepTwo;
		
		$distance=$calculatedDistance/1000;
		if($distance<10){
			$rs=round($distance,2);
		}else if($distance > 1000){
			$rs='>1000';
		}else{
			$rs=round($distance);
		}
		return $rs.'km';
	}
	/* 判断账号是否禁用 */
	public function isBan($uid){
		$status=DI()->notorm->users
					->select("user_status")
					->where('id=?',$uid)
					->fetchOne();
		if(!$status || $status['user_status']==0){
			return 0;
		}
		return 1;
	}
	/* 时间差计算 */
	public function datetime($time){
		$cha=time()-$time;
		$iz=floor($cha/60);
		$hz=floor($iz/60);
		$dz=floor($hz/24);
		/* 秒 */
		$s=$cha%60;
		/* 分 */
		$i=floor($iz%60);
		/* 时 */
		$h=floor($hz/24);
		/* 天 */
		
		if($cha<60){
			return $cha.'秒前';
		}else if($iz<60){
			return $iz.'分钟前';
		}else if($hz<24){
			return $hz.'小时前';
		}else if($dz<30){
			return $dz.'天前';
		}else{
			return date("Y-m-d",$time);
		}
	}		
	/* 时长格式化 */
	public function getSeconds($cha){		 
		$iz=floor($cha/60);
		$hz=floor($iz/60);
		$dz=floor($hz/24);
		/* 秒 */
		$s=$cha%60;
		/* 分 */
		$i=floor($iz%60);
		/* 时 */
		$h=floor($hz/24);
		/* 天 */
		
		if($cha<60){
			return $cha.'秒';
		}else if($iz<60){
			return $iz.'分'.$s.'秒';
		}else if($hz<24){
			return $hz.'小时'.$i.'分'.$s.'秒';
		}else if($dz<30){
			return $dz.'天'.$h.'小时'.$i.'分'.$s.'秒';
		}
	}	
	
	/* 数字格式化 */
	public function NumberFormat($num){
		if($num<10000){

		}else if($num<1000000){
			$num=round($num/10000,2).'万';
		}else if($num<100000000){
			$num=round($num/10000,1).'万';
		}else if($num<10000000000){
			$num=round($num/100000000,2).'亿';
		}else{
			$num=round($num/100000000,1).'亿';
		}
		return $num;
	}

	//账号是否禁用
	public function isBlackUser($uid){

		
		$userinfo=DI()->notorm->users->where("id=".$uid." and user_status=0")->fetchOne();
		

		if($userinfo){
			return 0;//禁用
		}
		return 1;//启用


	}

	/*检测手机号是否存在*/
	public function checkMoblieIsExist($mobile){
		$res=DI()->notorm->users->select("id,user_nicename,user_type")->where("mobile='{$mobile}'")->fetchOne();


		if($res){
			//判断账号是否被禁用
			if($res['user_status']==0){
				return 0;
			}else{
				return 1;
			}
		}else{
			return 0;
		}
		
	}


	/*检测手机号是否可以发送验证码*/
	public function checkMoblieCanCode($mobile){
		$res=DI()->notorm->users->select("id,user_nicename,user_type,user_status")->where("mobile='{$mobile}'")->fetchOne();


		if($res){
			//判断账号是否被禁用
			if($res['user_status']==0){
				return 0;
			}else{
				return 1;
			}
		}else{
			return 1;
		}
		
	}

	/*距离格式化*/
	public function distanceFormat($distance){
		if($distance<1000){
			return $distance.'米';
		}else{

			if(floor($distance/10)<10){
				return number_format($distance/10,1);  //保留一位小数，会四舍五入
			}else{
				return ">10千米";
			}
		}
	}



}
