<?php

class Api_Home extends Api_Common {  

	public function getRules() {
		return array(
			'search' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'key' => array('name' => 'key', 'type' => 'string', 'default'=>'' ,'desc' => '用户ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
			),
            'videoSearch' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
                'key' => array('name' => 'key', 'type' => 'string', 'default'=>'' ,'desc' => '关键词'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),
		);
	}
	
    /**
     * 配置信息
     * @desc 用于获取配置信息
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return array info[0] 配置信息

     * @return string msg 提示信息
     */
    public function getConfig() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		$configpri = $this->getConfigPri();
        $info = $this->getConfigPub();
		$info['tximgfolder']=$configpri['tximgfolder'];//腾讯云图片存储目录
        $info['txvideofolder']=$configpri['txvideofolder'];//腾讯云视频存储目录
        $info['cloudtype']=$configpri['cloudtype'];//视频云存储类型
		$info['qiniu_domain']=$configpri['qiniu_domain_url'];//七牛云存储空间地址（后台配置）
        $info['private_letter_switch']=$configpri['private_letter_switch']; //未关注时可发送私信开关
        $info['private_letter_nums']=$configpri['private_letter_nums']; //未关注时可发送私信条数
        $info['video_audit_switch']=$configpri['video_audit_switch']; //视频审核是否开启
        $rs['info'][0] = $info;

        return $rs;
    }	

    /**
     * 登录方式开关信息
     * @desc 用于获取登录方式开关信息
     * @return int code 操作码，0表示成功
     * @return array info 

     * @return string msg 提示信息
     */
    public function getLogin() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $info = $this->getConfigPub();
        $rs['info'][0]['login_type'] = $info['login_type'];

        return $rs;
    }		
	
	
} 
