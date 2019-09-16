<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<!-- Set render engine for 360 browser -->
	<meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->

	<link href="/public/simpleboot/themes/<?php echo C('SP_ADMIN_STYLE');?>/theme.min.css" rel="stylesheet">
    <link href="/public/simpleboot/css/simplebootadmin.css" rel="stylesheet">
    <link href="/public/js/artDialog/skins/default.css" rel="stylesheet" />
    <link href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
    <style>
		.length_3{width: 180px;}
		form .input-order{margin-bottom: 0px;padding:3px;width:40px;}
		.table-actions{margin-top: 5px; margin-bottom: 5px;padding:0px;}
		.table-list{margin-bottom: 0px;}
	</style>
	<!--[if IE 7]>
	<link rel="stylesheet" href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome-ie7.min.css">
	<![endif]-->
<script type="text/javascript">
//全局变量
var GV = {
    DIMAUB: "/",
    JS_ROOT: "public/js/",
    TOKEN: ""
};
</script>
<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/public/js/jquery.js"></script>
    <script src="/public/js/wind.js"></script>
    <script src="/public/simpleboot/bootstrap/js/bootstrap.min.js"></script>
<?php if(APP_DEBUG): ?><style>
		#think_page_trace_open{
			z-index:9999;
		}
	</style><?php endif; ?>
</head>
<body>
<style>
input{
  width:500px;
}
.form-horizontal textarea{
 width:500px;
}

.nav-tabs>.current>a{
    color: #95a5a6;
    cursor: default;
    background-color: #fff;
    border: 1px solid #ddd;
    border-bottom-color: transparent;
}
.nav li
{
	cursor:pointer
}
.nav li:hover
{
	cursor:pointer
}
.hide{
	display:none;
}
</style>


	<div class="wrap js-check-wrap">
		<!-- <ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo U('Configprivate/index');?>">设置</a></li>
			<li><a href="<?php echo U('Configprivate/lists');?>">管理</a></li>
			<li><a href="<?php echo U('Configprivate/add');?>">添加</a></li>
		</ul>
		<div class="form-actions">
			<span style="color:#ff0000">提示：新加设置请清空下缓存！</span>
		</div> -->
		<ul class="nav nav-tabs js-tabs-nav">
			<li><a>基本设置</a></li>
			<li><a>登录配置</a></li>
			<!-- <li><a>提现配置</a></li> -->
			<li><a>推送配置</a></li>
			<!-- <li><a>支付管理</a></li> -->
			<li><a>云存储设置</a></li>
			<li><a>视频参数设置</a></li>

		</ul>
		
		<form method="post" class="form-horizontal js-ajax-form" action="<?php echo U('Configprivate/set_post');?>">
		  <input type="hidden" name="post['id']" value="1">
			
			<div class="js-tabs-content">
				<!-- 基本配置 -->
				<div>
					<fieldset>
						<div class="control-group">
							<label class="control-label">缓存开关</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[cache_switch]" <?php if(($config['cache_switch']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[cache_switch]" <?php if(($config['cache_switch']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline"></label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">缓存时间</label>
							<div class="controls">				
								<input type="text" name="post[cache_time]" value="<?php echo ($config['cache_time']); ?>">网站数据的缓存时间（秒）
							</div>
						</div>
						
						
						<!-- <div class="control-group">
							<label class="control-label">注册奖励</label>
							<div class="controls">				
								<input type="text" name="post[reg_reward]" value="<?php echo ($config['reg_reward']); ?>"> 新用户注册奖励（整数）
							</div>
						</div> -->
						<div class="control-group">
							<label class="control-label">认证限制</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[auth_islimit]" <?php if(($config['auth_islimit']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[auth_islimit]" <?php if(($config['auth_islimit']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline">用户是否需要身份认证</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">未关注发送消息条数开关</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[private_letter_switch]" <?php if(($config['private_letter_switch']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[private_letter_switch]" <?php if(($config['private_letter_switch']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline"></label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">未关注可发送消息条数</label>
							<div class="controls">				
								<input type="text" name="post[private_letter_nums]" value="<?php echo ($config['private_letter_nums']); ?>"> 未关注用户可发送消息条数（整数）
							</div>
						</div>

						<div class="control-group">
							<label class="control-label">视频审核开关</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[video_audit_switch]" <?php if(($config['video_audit_switch']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[video_audit_switch]" <?php if(($config['video_audit_switch']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline"></label>
							</div>
						</div>
					</fieldset>
				</div>
				<!-- 登录配置 -->
				<div>
					<fieldset>
						<!-- <div class="control-group">
							<label class="control-label">登录奖励开关</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[bonus_switch]" <?php if(($config['bonus_switch']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[bonus_switch]" <?php if(($config['bonus_switch']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline"></label>
							</div>
						</div> -->
						
						<!-- <div class="control-group">
							<label class="control-label">微信公众平台Appid</label>
							<div class="controls">				
								<input type="text" name="post[login_wx_appid]" value="<?php echo ($config['login_wx_appid']); ?>"> 微信公众平台Appid	
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">微信公众平台AppSecret</label>
							<div class="controls">				
								<input type="text" name="post[login_wx_appsecret]" value="<?php echo ($config['login_wx_appsecret']); ?>"> 微信公众平台AppSecret	
							</div>
						</div> -->
						<div class="control-group">
							<label class="control-label">互亿无线开关</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[sendcode_switch]" <?php if(($config['sendcode_switch']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[sendcode_switch]" <?php if(($config['sendcode_switch']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline">互亿无线关闭，验证码默认123456</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">互亿无线APIID</label>
							<div class="controls">				
								<input type="text" name="post[ihuyi_account]" value="<?php echo ($config['ihuyi_account']); ?>"> 短信验证码   http://www.ihuyi.com/  互亿无线后台-》验证码、短信通知-》账号及签名->APIID
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">互亿无线key</label>
							<div class="controls">				
								<input type="text" name="post[ihuyi_ps]" value="<?php echo ($config['ihuyi_ps']); ?>"> 短信验证码 互亿无线后台-》验证码、短信通知-》账号及签名->APIKEY
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label">短信验证码IP限制开关</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[iplimit_switch]" <?php if(($config['iplimit_switch']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[iplimit_switch]" <?php if(($config['iplimit_switch']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline">短信验证码IP限制开关</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">短信验证码IP限制次数</label>
							<div class="controls">				
								<input type="text" name="post[iplimit_times]" value="<?php echo ($config['iplimit_times']); ?>"> 同一IP每天可以发送验证码的最大次数
							</div>
						</div>
					</fieldset>
				</div>
				
				<!-- 提现配置 -->
				<!-- <div>
					<fieldset>
						<div class="control-group">
							<label class="control-label">提现比例</label>
							<div class="controls">				
								<input type="text" name="post[cash_rate]" value="<?php echo ($config['cash_rate']); ?>"> 提现一元人名币需要的票数	
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">提现最低额度（元）</label>
							<div class="controls">				
								<input type="text" name="post[cash_min]" value="<?php echo ($config['cash_min']); ?>"> 可提现的最小额度，低于该额度无法体现
							</div>
						</div>
					</fieldset>
				</div> -->
				<!-- 三方配置 -->
				<div>
					<fieldset>
						<div class="control-group">
							<label class="control-label">极光推送模式</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[jpush_sandbox]" <?php if(($config['jpush_sandbox']) == "0"): ?>checked="checked"<?php endif; ?>>开发</label>
								<label class="radio inline"><input type="radio" value="1" name="post[jpush_sandbox]" <?php if(($config['jpush_sandbox']) == "1"): ?>checked="checked"<?php endif; ?>>生产</label>
								<label class="checkbox inline">极光推送模式</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">极光推送APP_KEY</label>
							<div class="controls">				
								<input type="text" name="post[jpush_key]" value="<?php echo ($config['jpush_key']); ?>">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">极光推送master_secret</label>
							<div class="controls">				
								<input type="text" name="post[jpush_secret]" value="<?php echo ($config['jpush_secret']); ?>">
							</div>
						</div>
					</fieldset>
				</div>
				<!-- 支付管理 -->
				<!-- <div>
					<fieldset>
						<div class="control-group">
							<label class="control-label">支付宝APP</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[aliapp_switch]" <?php if(($config['aliapp_switch']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[aliapp_switch]" <?php if(($config['aliapp_switch']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline">支付宝APP支付是否开启</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">合作者身份ID</label>
							<div class="controls">				
								<input type="text" name="post[aliapp_partner]" value="<?php echo ($config['aliapp_partner']); ?>">支付宝合作者身份ID
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">支付宝帐号</label>
							<div class="controls">				
								<input type="text" name="post[aliapp_seller_id]" value="<?php echo ($config['aliapp_seller_id']); ?>">支付宝登录账号
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">支付宝安卓密钥</label>
							<div class="controls">				
									<textarea name="post[aliapp_key_android]"><?php echo ($config['aliapp_key_android']); ?></textarea>支付宝安卓密钥
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">支付宝苹果密钥</label>
							<div class="controls">				
								<textarea name="post[aliapp_key_ios]"><?php echo ($config['aliapp_key_ios']); ?></textarea>支付宝苹果密钥
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">支付宝校验码</label>
							<div class="controls">				
								<input type="text" name="post[aliapp_check]" value="<?php echo ($config['aliapp_check']); ?>"> 支付宝校验码（扫码支付）
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">苹果支付模式</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[ios_sandbox]" <?php if(($config['ios_sandbox']) == "0"): ?>checked="checked"<?php endif; ?>>沙盒</label>
								<label class="radio inline"><input type="radio" value="1" name="post[ios_sandbox]" <?php if(($config['ios_sandbox']) == "1"): ?>checked="checked"<?php endif; ?>>生产</label>
								<label class="checkbox inline">苹果支付模式</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">支付宝PC</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[aliapp_pc]" <?php if(($config['aliapp_pc']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[aliapp_pc]" <?php if(($config['aliapp_pc']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline">支付宝PC扫码支付是否开启</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">微信支付PC</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[wx_switch_pc]" <?php if(($config['wx_switch_pc']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[wx_switch_pc]" <?php if(($config['wx_switch_pc']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline">微信支付PC 是否开启</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">微信支付</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[wx_switch]" <?php if(($config['wx_switch']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[wx_switch]" <?php if(($config['wx_switch']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline">微信支付开关</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">开放平台账号AppID</label>
							<div class="controls">				
								<input type="text" name="post[wx_appid]" value="<?php echo ($config['wx_appid']); ?>">微信开放平台账号AppID
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">微信应用appsecret</label>
							<div class="controls">				
								<input type="text" name="post[wx_appsecret]" value="<?php echo ($config['wx_appsecret']); ?>">微信应用appsecret
							</div>
						</div
						><div class="control-group">
							<label class="control-label">微信商户号mchid</label>
							<div class="controls">				
								<input type="text" name="post[wx_mchid]" value="<?php echo ($config['wx_mchid']); ?>">微信商户号mchid
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">微信密钥key</label>
							<div class="controls">				
								<input type="text" name="post[wx_key]" value="<?php echo ($config['wx_key']); ?>">微信密钥key
							</div>
						</div>
					</fieldset>
				</div> -->
				
				
				<!-- 腾讯云存储设置 -->
				<div>
					<fieldset>
					<div class="control-group">
							<label class="control-label">选择存储方式</label>
							<div class="controls">
										
								<label class="radio inline"><input type="radio" value="1" name="post[cloudtype]" <?php if(($config['cloudtype']) == "1"): ?>checked="checked"<?php endif; ?>>七牛云存储</label>
								<label class="radio inline"><input type="radio" value="2" name="post[cloudtype]" <?php if(($config['cloudtype']) == "2"): ?>checked="checked"<?php endif; ?>>腾讯云存储</label>
								
								<label class="checkbox inline"></label>
							</div>
					</div>

					


					<div class="control-group">
						<label class="control-label">七牛云存储accessKey</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[qiniu_accesskey]" value="<?php echo ($config['qiniu_accesskey']); ?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">七牛云存储secretKey</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[qiniu_secretkey]" value="<?php echo ($config['qiniu_secretkey']); ?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">七牛云存储bucket</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[qiniu_bucket]" value="<?php echo ($config['qiniu_bucket']); ?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">七牛云存储空间域名</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[qiniu_domain]" value="<?php echo ($config['qiniu_domain']); ?>">不带http://或https://，不要以/结尾；如qiniudemo.yunbaozhibo.com
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">七牛云存储空间地址</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[qiniu_domain_url]" value="<?php echo ($config['qiniu_domain_url']); ?>"> 以http://或https://开头，以/结尾；如http://qiniudemo.yunbaozhibo.com/
						</div>
					</div>


					<div class="control-group">
						<label class="control-label">腾讯云存储appid</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[txcloud_appid]" value="<?php echo ($config['txcloud_appid']); ?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">腾讯云存储secret_id</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[txcloud_secret_id]" value="<?php echo ($config['txcloud_secret_id']); ?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">腾讯云存储secret_key</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[txcloud_secret_key]" value="<?php echo ($config['txcloud_secret_key']); ?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">腾讯云存储region</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[txcloud_region]" value="<?php echo ($config['txcloud_region']); ?>"> 华北 tj 华东 sh 华南 gz
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">腾讯云存储bucket</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[txcloud_bucket]" value="<?php echo ($config['txcloud_bucket']); ?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">腾讯云存储图片存放目录</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[tximgfolder]" value="<?php echo ($config['tximgfolder']); ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">腾讯云存储视频存放目录</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[txvideofolder]" value="<?php echo ($config['txvideofolder']); ?>">
						</div>
					</div>
					<div class="control-group" style="display:none;">
						<label class="control-label">用户头像存放目录</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[txuserimgfolder]" value="<?php echo ($config['txuserimgfolder']); ?>">
						</div>
					</div>
					</fieldset>
				</div>

				<!-- 视频参数配置 -->
				<div>
					<fieldset>
						<div class="control-group">
							<label class="control-label">推荐视频显示方式</label>
							<div class="controls">
										
								<label class="radio inline"><input type="radio" value="0" name="post[video_showtype]" <?php if(($config['video_showtype']) == "0"): ?>checked="checked"<?php endif; ?>>随机</label>
								<label class="radio inline"><input type="radio" value="1" name="post[video_showtype]" <?php if(($config['video_showtype']) == "1"): ?>checked="checked"<?php endif; ?>>按曝光值</label>
								
								<label class="checkbox inline"></label>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label">评论权重值</label>
							<div class="controls">				
								<input type="text" name="post[comment_weight]" value="<?php echo ($config['comment_weight']); ?>"> 用于视频推荐	
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">点赞权重值</label>
							<div class="controls">				
								<input type="text" name="post[like_weight]" value="<?php echo ($config['like_weight']); ?>"> 用于视频推荐
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">分享权重值</label>
							<div class="controls">				
								<input type="text" name="post[share_weight]" value="<?php echo ($config['share_weight']); ?>"> 用于视频推荐
							</div>
						</div>

						<div class="control-group">
							<label class="control-label">初始曝光值</label>
							<div class="controls">				
								<input type="text" name="post[show_val]" value="<?php echo ($config['show_val']); ?>" onkeyup="this.value=this.value.replace(/[^0-9-]+/,'');"> 请填写整数，用于视频推荐
							</div>
						</div>

						<div class="control-group">
							<label class="control-label">每小时扣除曝光值</label>
							<div class="controls">				
								<input type="text" name="post[hour_minus_val]" value="<?php echo ($config['hour_minus_val']); ?>" onkeyup="this.value=this.value.replace(/[^0-9-]+/,'');"> 请填写整数，用于视频推荐
							</div>
						</div>
					</fieldset>
				</div>

			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary js-ajax-submit"><?php echo L('SAVE');?></button>
			</div>
		</form>
	</div>
	<script src="/public/js/common.js"></script>
</body>
</html>