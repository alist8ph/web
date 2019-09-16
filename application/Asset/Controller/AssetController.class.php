<?php

/**
 * 附件上传
 */
namespace Asset\Controller;
use Common\Controller\AdminbaseController;
use QCloud\Cos\Api;
use QCloud\Cos\Auth;
class AssetController extends AdminbaseController {


    function _initialize() {
    	$adminid=sp_get_current_admin_id();
    	$userid=sp_get_current_userid();
    	if(empty($adminid) && empty($userid)){
    		exit("非法上传！");
    	}
    }

    /**
     * swfupload 上传 
     */
    public function swfupload() {
        if (IS_POST) {
			$savepath=date('Ymd').'/';
            //上传处理类
            $config=array(
            		'rootPath' => './'.C("UPLOADPATH"),
            		'savePath' => $savepath,
            		'maxSize' => 100*1048576, //100M
            		'saveName'   =>    array('uniqid',''),
            		'exts'       =>    array('jpg', 'gif', 'png', 'jpeg',"txt",'zip'),
            		'autoSub'    =>    false,
            );
			$configpri=getConfigPri();
			  /* file_put_contents('./zhifu.txt',date('y-m-d h:i:s').'提交参数信息 :'.json_encode($_FILES)."\r\n",FILE_APPEND); */
			$a=$configpri['cloudtype'];

			if($a==1){


				$config_qiniu = array(
 
					'accessKey' => $configpri['qiniu_accesskey'], //这里填七牛AK
					'secretKey' => $configpri['qiniu_secretkey'], //这里填七牛SK
					'domain' => $configpri['qiniu_domain'],//这里是域名
					'bucket' => $configpri['qiniu_bucket']//这里是七牛中的“空间”
				);


	            $upload = new \Think\Upload($config,'Qiniu',$config_qiniu);


	            $info = $upload->upload();

	            if ($info) {
	                //上传成功
	                //写入附件数据库信息
	                $first=array_shift($info);
	                if(!empty($first['url'])){
	                	$url=$first['url'];
	                	
	                }else{
	                	$url=C("TMPL_PARSE_STRING.__UPLOAD__").$savepath.$first['savename'];
	                	
	                }


	                
					echo "1," . $url.",".'1,'.$first['name'];
					exit;


	            } else {
	                //上传失败，返回错误
	                exit("0," . $upload->getError());
	            }

			}else if($a==2){

				
				/* 腾讯云 */
				require(SITE_PATH.'api/public/txcloud/include.php');
				//bucketname
				$bucket = $configpri['txcloud_bucket'];
				//uploadlocalpath
				/* $src = $_FILES['file'];//'./hello.txt'; */
				$src = $_FILES["Filedata"]["tmp_name"];//'./hello.txt';
				
				
			
				//cosfolderpath
				$folder = '/'.$configpri['tximgfolder'];
				//cospath
				$dst = $folder.'/'.$_FILES["Filedata"]["name"];
				//config your information
				$config = array(
					'app_id' => $configpri['txcloud_appid'],
					'secret_id' => $configpri['txcloud_secret_id'],
					'secret_key' => $configpri['txcloud_secret_key'],
					'region' =>  $configpri['txcloud_region'],   // bucket所属地域：华北 'tj' 华东 'sh' 华南 'gz'
					'timeout' => 60
				);

				date_default_timezone_set('PRC');
				$cosApi = new 	Api($config);

				// Create folder in bucket.
		/* 		$ret = $cosApi->createFolder($bucket, $folder);
				var_dump($ret); */

				// Upload file into bucket.
				$ret = $cosApi->upload($bucket, $src, $dst);
				if($ret['code']!=0){
					//上传失败，返回错误
					exit("0," . $ret['message']);
				}
				$url = $ret['data']['source_url'];
				echo "1," . $url.",".'1,'.$_FILES["file"]["name"];
				exit;
			}
        } else {
            $this->display(':swfupload');
        }
    }

}
