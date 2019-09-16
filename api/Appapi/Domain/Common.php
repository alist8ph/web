<?php

class Domain_Common {

	public function getConfigPub() {
		$rs = array();
		$model = new Model_Common();
		$rs = $model->getConfigPub();
		return $rs;
	}
	
	public function getConfigPri() {
		$rs = array();
		$model = new Model_Common();
		$rs = $model->getConfigPri();
		return $rs;
	}
	
	public function checkToken($uid,$token) {
		$rs = array();
		$model = new Model_Common();
		$rs = $model->checkToken($uid,$token);
		return $rs;
	}

	public function getUserInfo($uid) {
		$rs = array();
		$model = new Model_Common();
		$rs = $model->getUserInfo($uid);
		return $rs;
	}

	public function isBan($uid) {
		$rs = array();
		$model = new Model_Common();
		$rs = $model->isBan($uid);
		return $rs;
	}


	public function isBlackUser($uid){
		$rs = array();
		$model = new Model_Common();
		$rs = $model->isBlackUser($uid);
		return $rs;

	}

	/*检测手机号是否存在*/
	public function checkMoblieIsExist($mobile){
		$rs = array();
		$model = new Model_Common();
		$rs = $model->checkMoblieIsExist($mobile);
		return $rs;
	}

	/*检测手机号是否存在*/
	public function checkMoblieCanCode($mobile){
		$rs = array();
		$model = new Model_Common();
		$rs = $model->checkMoblieCanCode($mobile);
		return $rs;
	}
}
