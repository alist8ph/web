<?php

class Api_Video extends Api_Common {

	public function getRules() {
		return array(
            'getVideoList' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
            	'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
            'getVideo' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
            ),

            'getRecommendVideos'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
            	'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),

            'getNearby'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'lng' => array('name' => 'lng', 'type' => 'string', 'desc' => '经度值'),
                'lat' => array('name' => 'lat', 'type' => 'string','desc' => '纬度值'),
				'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            
		);
	}
	/**
     * 获取热门视频
     * @desc 用于获取热门视频
     * @return int code 操作码，0表示成功

     * @return string msg 提示信息
     */
	public function getVideoList() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $uid=$this->uid;
        $p=$this->p;

			$domain = new Domain_Video();
			$info= $domain->getVideoList($uid,$p);

			if($info==10010){
				$rs['code'] = 0;
				$rs['msg'] = "暂无视频列表";
				return $rs;
			}


        
		$rs['info'] =$info;
        return $rs;
    }	
	
	/**
     * 视频详情
     * @desc 用于获取视频详情
     * @return int code 操作码，0表示成功，1000表示视频不存在

     * @return string msg 提示信息
     */
	public function getVideo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Video();
        $result = $domain->getVideo($this->uid,$this->videoid);
		if($result==1000){
			$rs['code'] = 1000;
			$rs['msg'] = "视频已删除";
			return $rs;
			
		}
		$rs['info'][0]=$result;

        return $rs;
    }

    /**
     * 获取推荐视频
     * @desc 用户获取推荐视频
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function getRecommendVideos(){
    	$rs = array('code' => 0, 'msg' => '', 'info' => array());

    	$uid=$this->uid;
    	if($uid>0){ //非游客

    		$isBlackUser=$this->isBlackUser($this->uid);
			if($isBlackUser=='0'){
				$rs['code'] = 700;
				$rs['msg'] = '该账号已被禁用';
				return $rs;
			}
    	}
		

		$p=$this->p;


			$domain=new Domain_Video();
			$info=$domain->getRecommendVideos($uid,$p);

			if($info==1001){
				$rs['code']=1001;
				$rs['msg']="暂无视频列表";
				return $rs;
			}

		$rs['info']=$info;

		return $rs;
    }


	/**
	 * 获取附近的视频列表
	 * @desc 用于获取附近的视频列表
	 * @return int code 状态码，0表示成功
	 * @return string msg 提示信息
	 * @return array info 返回信息
	 */
	public function getNearby(){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$lng=$this->checkNull($this->lng);
		$lat=$this->checkNull($this->lat);
		$p=$this->checkNull($this->p);

		if($lng==''){
			return $rs;
		}
		
		if($lat==''){
			return $rs;
		}
		
		if(!$p){
			$p=1;
		}


			$domain = new Domain_Video();
			$info = $domain->getNearby($uid,$lng,$lat,$p);

			if($info==1001){
				return $rs;
			}


		$rs['info'] = $info;
        return $rs;
	}


}
