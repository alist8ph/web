<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="telephone=no" name="format-detection">
<meta name="baidu-site-verification" content="QpfdfPmoBr" />
<title><?php echo ($site_name); ?></title>
<meta name="keywords" content="云豹短视频"/>
<meta name="description" content="云豹短视频"/>

<link rel="stylesheet" type="text/css" href="/public/index/css/index.css">

</head>
<body>
<div class="content pr">
    <img src="/public/index/images/new_logo.png" class="c-1"/>
    <img src="<?php echo ($config['qr_url']); ?>" class="c-5"/>
    <a href="<?php echo ($config['app_ios']); ?>"><img src="/public/index/images/i_02.png" class="c-2 ios"></a>
    <a href="<?php echo ($config['app_android']); ?>"><img src="/public/index/images/button_Android.png" class="c-2"></a>
		<p class="footer-nav"></p>
    <p class="footer-copy"><?php echo ($config['copyright']); ?></p>
    <img src="/public/index/images/i_b.png" id='i-b' class="i-b pa"/>
    <img src="/public/index/images/i_s.png" id='i-s' class="i-s pa"/>
</div>

</body>
</html>