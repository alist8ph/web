<?php

class Api_Common extends PhalApi_Api {
	
	/* 公共配置 */
	protected function getConfigPub() {

			$domain = new Domain_Common();
			$config = $domain->getConfigPub();

        
		return 	$config;

	}	
	/* 私密配置 */
	protected function getConfigPri() {
		$key='getConfigPri';

			$domain = new Domain_Common();
			$config = $domain->getConfigPri();

		return 	$config;
	}	

	/* 判断token */
	protected function checkToken($uid,$token) {
		

			$domain = new Domain_Common();
			$rs = $domain->checkToken($uid,$token);
			return $rs;							

		
	}
	/* 用户基本信息 */
	protected function getUserInfo($uid) {

			$domain = new Domain_Common();
			$info = $domain->getUserInfo($uid);				

		return $info;
	}

	/* 数字格式化 */
	protected function NumberFormat($num){
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
	/**
	 * 返回带协议的域名
	 */
	protected function get_host(){
		//$host=$_SERVER["HTTP_HOST"];
	//	$protocol=$this->is_ssl()?"https://":"http://";
		//return $protocol.$host;
		$config=$this->getConfigPub();
		return $config['site'];
	}	
	
	/**
	 * 转化数据库保存的文件路径，为可以访问的url
	 */
	protected function get_upload_path($file){
		if(strpos($file,"http")===0){
			return html_entity_decode($file);
		}else if(strpos($file,"/")===0){
			$filepath= $this->get_host().$file;
			return html_entity_decode($filepath);
		}else{
			$space_host= DI()->config->get('app.Qiniu.space_host');
			$filepath=$space_host."/".$file;
			return html_entity_decode($filepath);
		}
	}	
	/* 去除NULL 判断空处理 主要针对字符串类型*/
	protected function checkNull($checkstr){
		$checkstr=urldecode($checkstr);
		$checkstr=htmlspecialchars($checkstr);
		$checkstr=trim($checkstr);
		if( strstr($checkstr,'null') || (!$checkstr && $checkstr!=0 ) ){
			$str='';
		}else{
			$str=$checkstr;
		}
		return $str;	
	}

	/* 密码检查 */
	protected function passcheck($user_pass) {
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
	/* 判断账号是否禁用 */
	protected function isBan($uid){
		$domain = new Domain_Common();
		$rs = $domain->isBan($uid);
		return $rs;
		
	}

	/* 检验手机号 */
	protected function checkMobile($mobile){

		$ismobile = preg_match("/^1[3|4|5|7|8]\d{9}$/",$mobile);
		if($ismobile){
			return 1;
		}else{
			return 0;
		}
	}
	/* 发送验证码 */
	protected function sendCode($mobile,$code){
		$rs=array();

            $rs['code']=667;
			$rs['msg']='123456';
            return $rs;


		return $rs;
	}
	/* 发送验证码 */
	
	/* 账号是否被禁 */
	protected function isBlackUser($uid) {

		$domain = new Domain_Common();
		$rs = $domain->isBlackUser($uid);
		
		return $rs;
	}

	/*判断手机号是否存在*/
	protected function checkMoblieIsExist($mobile){
		$domain = new Domain_Common();
		$rs = $domain->checkMoblieIsExist($mobile);
		return $rs;
	}

	/*判断手机号是否存在*/
	protected function checkMoblieCanCode($mobile){
		$domain = new Domain_Common();
		$rs = $domain->checkMoblieCanCode($mobile);
		return $rs;
	}
} 
