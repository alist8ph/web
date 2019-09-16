<?php

class Domain_Login {

    public function userLogin($user_login) {
        $rs = array();

        $model = new Model_Login();
        $rs = $model->userLogin($user_login);

        return $rs;
    }

   
	
    public function userFindPass($user_login,$user_pass) {
        $rs = array();
        $model = new Model_Login();
        $rs = $model->userFindPass($user_login,$user_pass);

        return $rs;
    }	

    public function userLoginByThird($openid,$type,$nickname,$avatar) {
        $rs = array();

        $model = new Model_Login();
        $rs = $model->userLoginByThird($openid,$type,$nickname,$avatar);

        return $rs;
    }			

}
