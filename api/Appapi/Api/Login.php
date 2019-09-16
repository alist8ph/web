<?php
session_start();
class Api_Login extends Api_Common { 
	public function getRules() {
        return array(
			'userLogin' => array(
                'user_login' => array('name' => 'user_login', 'type' => 'string', 'desc' => '账号'),
				'code' => array('name' => 'code', 'type' => 'string', 'require' => true,   'desc' => '验证码'),
            ),
			
			'userFindPass' => array(
                'user_login' => array('name' => 'user_login', 'type' => 'string', 'min' => 1, 'require' => true,  'min' => '6',  'max'=>'30', 'desc' => '账号'),
				'user_pass' => array('name' => 'user_pass', 'type' => 'string', 'min' => 1, 'require' => true,  'min' => '1',  'max'=>'30', 'desc' => '密码'),
				'user_pass2' => array('name' => 'user_pass2', 'type' => 'string', 'min' => 1, 'require' => true,  'min' => '1',  'max'=>'30', 'desc' => '确认密码'),
                'code' => array('name' => 'code', 'type' => 'string', 'min' => 1, 'require' => true,   'desc' => '验证码'),
            ),	
			'userLoginByThird' => array(
                'openid' => array('name' => 'openid', 'type' => 'string', 'min' => 1, 'require' => true,   'desc' => '第三方openid'),
                'type' => array('name' => 'type', 'type' => 'string', 'min' => 1, 'require' => true,   'desc' => '第三方标识'),
                'nicename' => array('name' => 'nicename', 'type' => 'string',   'default'=>'',  'desc' => '第三方昵称'),
                'avatar' => array('name' => 'avatar', 'type' => 'string',  'default'=>'', 'desc' => '第三方头像'),
            ),
			
		
			'getForgetCode' => array(
				'mobile' => array('name' => 'mobile', 'type' => 'string', 'min' => 1, 'require' => true,  'desc' => '手机号'),
			),

			'getLoginCode' => array(
				'mobile' => array('name' => 'mobile', 'type' => 'string', 'min' => 1, 'require' => true,  'desc' => '手机号'),
			),
        );
	}
	
    /**
     * 会员登陆 需要密码
     * @desc 用于用户登陆信息
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
    public function userLogin() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		$user_login=$this->checkNull($this->user_login);
		$code=$this->checkNull($this->code);

		if($code==''){
			$rs['code'] = 1001;
            $rs['msg'] = '请填写验证码';
             return $rs;
		}

		if($user_login!=$_SESSION['login_mobile']){
			$rs['code'] = 1001;
            $rs['msg'] = '手机号码错误';
            return $rs;
		}
		if($code!=$_SESSION['login_mobile_code']){
			$rs['code'] = 1001;
            $rs['msg'] = '验证码错误';
            return $rs;
		}

        $domain = new Domain_Login();
        $info = $domain->userLogin($user_login);

		if($info==1002){
			$rs['code'] = 1002;
            $rs['msg'] = '该账号已被禁用';
            return $rs;	
		}
	
        $rs['info'][0] = $info;
				
		
        return $rs;
    }		
   	
	/**
     * 会员找回密码
     * @desc 用于会员找回密码
     * @return string msg 提示信息
     */
    public function userFindPass() {
		
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$user_login=$this->checkNull($this->user_login);
		$user_pass=$this->checkNull($this->user_pass);
		$user_pass2=$this->checkNull($this->user_pass2);
		$code=$this->checkNull($this->code);
		
		
	 	if($user_login!=$_SESSION['forget_mobile']){
            $rs['code'] = 1001;
            $rs['msg'] = '手机号码不一致';
            return $rs;					
		}

		if($code!=$_SESSION['forget_mobile_code']){
            $rs['code'] = 1002;
            $rs['msg'] = '验证码错误';
            return $rs;					
		}	
		

		if($user_pass!=$user_pass2){
            $rs['code'] = 1003;
            $rs['msg'] = '两次输入的密码不一致';
            return $rs;					
		}	

		$check = $this->passcheck($user_pass);
		if($check== 0 ){
            $rs['code'] = 1004;
            $rs['msg'] = '密码6-12位数字与字母';
            return $rs;										
        }else if($check== 2){
            $rs['code'] = 1005;
            $rs['msg'] = '密码不能纯数字或纯字母';
            return $rs;										
        }		

		$domain = new Domain_Login();
        $info = $domain->userFindPass($user_login,$user_pass);	
		
		if($info==1006){
			$rs['code'] = 1006;
            $rs['msg'] = '该帐号不存在';
            return $rs;	
		}else if($info===false){
			$rs['code'] = 1007;
            $rs['msg'] = '重置失败，请重试';
            return $rs;	
		}
		
		$_SESSION['forget_mobile'] = '';
		$_SESSION['forget_mobile_code'] = '';
		$_SESSION['forget_mobile_expiretime'] = '';

        return $rs;
    }
	
    /**
     * 第三方登录
     * @desc 用于用户登陆信息
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
    public function userLoginByThird() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		$openid=$this->checkNull($this->openid);
		$type=$this->checkNull($this->type);
		$nicename=$this->checkNull($this->nicename);
		$avatar=$this->checkNull($this->avatar);
		
        $domain = new Domain_Login();
        $info = $domain->userLoginByThird($openid,$type,$nicename,$avatar);
		
        if($info==1002){
            $rs['code'] = 1002;
            $rs['msg'] = '该账号已被禁用';
            return $rs;					
		}

        $rs['info'][0] = $info;

        return $rs;
    }
	
			

	/**
	 * 获取找回密码短信验证码
	 * @desc 用于找回密码获取短信验证码
	 * @return int code 操作码，0表示成功,2发送失败
	 * @return string msg 提示信息
	 */
	 
	public function getForgetCode() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$mobile = $this->mobile;
		
		$ismobile=$this->checkMobile($mobile);
		if(!$ismobile){
			$rs['code']=1001;
			$rs['msg']='请输入正确的手机号';
			return $rs;	
		}

		$isExist=$this->checkMoblieIsExist($mobile);

		if($isExist==0){
			$rs['code']=1001;
			$rs['msg']='该手机号不存在';
			return $rs;
		}

		if($_SESSION['forget_mobile']==$mobile && $_SESSION['forget_mobile_expiretime']> time() ){
			$rs['code']=1002;
			$rs['msg']='验证码1分钟有效，请勿多次发送';
			return $rs;
		}

		$mobile_code = $this->random(6,1);
		
		/* 发送验证码 */
 		$result=$this->sendCode($mobile,$mobile_code);

		if($result['code']===0){

			$_SESSION['forget_mobile'] = $mobile;
			$_SESSION['forget_mobile_code'] = $mobile_code;
			$_SESSION['forget_mobile_expiretime'] = time() +60*5;


		}else if($result['code']==667){

			$_SESSION['forget_mobile'] = $mobile;
			$_SESSION['forget_mobile_code'] = $result['msg'];
			$_SESSION['forget_mobile_expiretime'] = time() +60*5;	
			
			$rs['code']=0;
			$rs['msg']=$result['msg'];

			return $rs;

		}else{

			$rs['code']=1002;
			$rs['msg']=$result['msg'];

			return $rs;
		}
		
		$rs['msg']="发送成功";
		return $rs;
	}


	/**
	 * 获取登录短信验证码
	 * @desc 用于登录获取短信验证码
	 * @return int code 操作码，0表示成功,2发送失败
	 * @return array info 
	 * @return string msg 提示信息
	 */
	 
	public function getLoginCode() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$mobile = $this->mobile;
		
		$ismobile=$this->checkMobile($mobile);
		if(!$ismobile){
			$rs['code']=1001;
			$rs['msg']='请输入正确的手机号';
			return $rs;	
		}

		//验证手机号是否被禁用
		$status=$this->checkMoblieCanCode($mobile);

		if($status==0){
			$rs['code']=1001;
			$rs['msg']='该账号已被禁用';
			return $rs;	
		}

		if($_SESSION['login_mobile']==$mobile && $_SESSION['login_mobile_expiretime']> time() ){
			$rs['code']=1002;
			$rs['msg']='验证码1分钟有效，请勿多次发送';
			return $rs;
		}
		
		$mobile_code = $this->random(6,1);
		
		/* 发送验证码 */
 		$result=$this->sendCode($mobile,$mobile_code);
		if($result['code']===0){
			$_SESSION['login_mobile'] = $mobile;
			$_SESSION['login_mobile_code'] = $mobile_code;
			$_SESSION['login_mobile_expiretime'] = time() +60*5;

		}else if($result['code']==667){
			$_SESSION['login_mobile'] = $mobile;
            $_SESSION['login_mobile_code'] = $result['msg'];
            $_SESSION['login_mobile_expiretime'] = time() +60*5;
            
            $rs['code']=$result['code'];
			$rs['msg']='验证码为：'.$result['msg'];

			return $rs;
		}else{
			$rs['code']=1002;
			$rs['msg']=$result['msg'];

			return $rs;
		}
		
		$rs['msg']="发送成功";
		return $rs;
	}		

}
