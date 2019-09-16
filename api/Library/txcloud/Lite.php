<?php
/**
 * 腾讯云存储接口调用
 * 1、图片文件上传
 *
 * 参考：https://cloud.tencent.com/document/product/436/6274
 *
 * @author: dogstar 2015-03-17
 */

require_once dirname(__FILE__) . '/txcloud/include.php');
 
class TxCloud_Lite {

    protected $config;

    /**
     * @param string $config['accessKey']  统一的key
     * @param string $config['secretKey']
     * @param string $config['space_bucket']  自定义配置的空间
     * @param string $config['space_host']  
     */
    public function __construct($config = NULL) {
        $this->config = $config;

        if ($this->config === NULL) {
            $this->config = DI()->config->get('app.TxCloud');
        }
    }

    /**
     * 文件上传
     * @param string $dst 待上传文件的绝对路径
     * @return string 上传成功后的URL，失败时返回空
     */
    public function uploadFile($file)
    {
        $fileUrl = '';
		//cosfolderpath
		$folder = '/test2';
		//cospath
		$dst = '/test2/'.$file["name"];
		//bucketname
		$bucket = 'aosika';
		//uploadlocalpath
		/* $src = $_FILES['file'];//'./hello.txt'; */
		$src = $file["tmp_name"];//'./hello.txt';
        if (!file_exists($dst)) {
            return $fileUrl;
        }

        $config = $this->config;
		
		//config your information
		/* $config = array(
			'app_id' => '1255500835',
			'secret_id' => 'AKIDbBcrfKT7EE3gBUQqjPxKWWJvPxPk3thI',
			'secret_key' => 'XvCLJ7j8NSN6f7QcfXZR7g2C9tRCm5pQ',
			'region' => 'sh',   // bucket所属地域：华北 'tj' 华东 'sh' 华南 'gz'
			'timeout' => 60
		); */

		date_default_timezone_set('PRC');
		$cosApi = new 	\QCloud\Cos\Api($config['config']);

		// Create folder in bucket.
		$ret = $cosApi->createFolder($bucket, $folder);
		/* var_dump($ret); */

		// Upload file into bucket.
		$ret = $cosApi->upload($bucket, $src, $dst);
		/* var_dump($ret); */

        return $fileUrl;
    }
	
	/**
     * 多次有效签名:图片
     * @return string 
     */
    public function createReusableSignatureImg($filepath)
    { 
		$bucket = 'aosika';
        $config = $this->config;
		$filepath = '/test2/1.jpg';
		$expiration = time() + 3600; 
		$auth = new \QCloud\Cos\Auth($config['app_id'], $config['secret_id'], $config['secret_key']);
		$signature = $auth->createReusableSignature($expiration, $bucket, $filepath);
        return $signature;	
    }
	/**
     * 多次有效签名:视频
     * @return string 
     */
    public function createReusableSignatureMP4($filepath)
    { 
		$bucket = 'aosika';
        $config = $this->config;
		$filepath = '/test2/2.mp4';
		$expiration = time() + 3600; 
		$auth = new \QCloud\Cos\Auth($config['app_id'], $config['secret_id'], $config['secret_key']);
		$signature = $auth->createReusableSignature($expiration, $bucket, $filepath);
        return $signature;	
    }
	/**
     * 单次有效签名
     * @return string 
     */
    public function createNonreusableSignature($filepath)
    { 
		$bucket = 'aosika';
        $config = $this->config;
		
		$auth = new \QCloud\Cos\Auth($config['app_id'], $config['secret_id'], $config['secret_key']);
		$signature = $auth->createNonreusableSignature($bucket, $filepath);
        return $signature;	
    }
	
}
