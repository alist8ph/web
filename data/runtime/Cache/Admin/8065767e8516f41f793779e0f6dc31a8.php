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
<style type="text/css">
	#linkUrl{
		display: none;
	}
</style>
</head>
<body>
	<div class="wrap">
		<ul class="nav nav-tabs">
			<li ><a href="<?php echo U('PushMessage/index');?>">通知记录</a></li>
			<li class="active"><a >通知添加</a></li>
		</ul>
		<form method="post" class="form-horizontal js-ajax-form" >
			<fieldset>

				
				<div class="control-group">
					<label class="control-label">消息标题</label>
					<div class="controls">
						<input type="text" id="title" name="title">
						<span class="form-required">*</span>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">消息简介</label>
					<div class="controls">
						<textarea name="synopsis" id="synopsis" style="width: 300px;height: 150px;"></textarea>
						<span class="form-required">*</span>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">消息类型</label>
					
						<div class="controls">
							<label class="radio inline" ><input type="radio" name="msg_type" value="1" checked  />文本类型</label>
							<label class="radio inline" ><input type="radio" name="msg_type" value="2" >外部链接</label>
						</div>
					
				</div>

				<div class="control-group" id="msg_con">
					<label class="control-label">消息内容</label>
					<div class="controls">
						<script type="text/plain" id="content" style="width: 70%;" name="content"></script>
						
					</div>
				</div>

				<div class="control-group" id="linkUrl">
					<label class="control-label">外部链接</label>
					<div class="controls">
						<input type="text" name="url" id="url">
						
					</div>
				</div>


			</fieldset>
			<div class="form-actions">
				<button id="submit" type="button" class="btn btn-primary">推送</button>
				<a class="btn" href="<?php echo U('PushMessage/index');?>"><?php echo L('BACK');?></a>
			</div>
		</form>
	</div>
	<script src="/public/js/common.js"></script>
	<script type="text/javascript" src="/public/js/content_addtop.js"></script>
	<script type="text/javascript" src="/public/layer/layer.js"></script>
	<script type="text/javascript">
		//编辑器路径定义
		var editorURL = GV.DIMAUB;
	</script>
	<script type="text/javascript" src="/public/js/ueditor/ueditor.config.js"></script>
	<script type="text/javascript" src="/public/js/ueditor/ueditor.all.min.js"></script>

	<!-- 极光IM -->
	<script type="text/javascript" src='/public/js/jmessage/jmessage-sdk-web.2.6.0.min.js'></script>
	<script type="text/javascript" src='/public/js/jmessage/jmessage.js'></script>
	<!-- 极光IM -->
	<script type="text/javascript">

		var isPush=0;
		var lastid=0;
		var res=0;

		$(function(){
			//编辑器
			editorcontent = new baidu.editor.ui.Editor();
			editorcontent.render('content');
			try {
				editorcontent.sync();
			} catch (err) {
			}

			//切换消息类型
			$("input[name='msg_type']").click(function(){
				var val=$(this).val();
				if(val==1){
					$("#linkUrl").hide();
					$("#msg_con").show();
				}else{
					$("#msg_con").hide();
					$("#linkUrl").show();
				}
			});

			$("#submit").click(function(){

				if(isPush==1){
					return;
				}

				var title=$("#title").val();
				var synopsis=$("#synopsis").val();
				var msg_type=$('input[name="msg_type"]:checked').val();
				var url=$('#url').val();

				var ue = UE.getEditor('content');

				var content=ue.getContent();

				if(title==""){
					layer.msg("请填写标题");
					return;
				}

				if(synopsis==""){
					layer.msg("请填写简介");
					return;
				}

				if(msg_type==2){
					if(url==""){
						layer.msg("请填写链接地址");
						return;
					}
				}

				isPush=1;
				$("#submit").attr("disabled",true).text("推送中……");

				$.ajax({
					url: '/index.php?g=Admin&m=PushMessage&a=add_post',
					type: 'POST',
					dataType: 'json',
					data: {title:title,synopsis:synopsis,msg_type:msg_type,content:content,url:url},

					beforeSend:function(){

						layer.open({
						  title:"提示",
						  type: 1,
						  skin: 'layui-layer-demo', //样式类名
						  closeBtn: 0, //不显示关闭按钮
						  anim: 2,
						  area: ['300px', '150px'],
						  shadeClose: true, //开启遮罩关闭
						  content: "<div style='width:90%;padding:5%;'>信息添加并推送中…… 请耐心等待</div>"
						});

					},
					success:function(data){
						var code=data.code;
						if(code!=0){
							layer.msg(data.msg);
							return;
						}
						var id=data.info.id;
						var count=data.info.count;

						
						var num=30; //每次查询推送人数

						sendMsg(lastid,num,id,count);
						
						if(res==-1){
							layer.msg("消息推送失败",{time:1000},function(){
								layer.closeAll('dialog');
								location.reload();
							});

							return;
						}else{
							layer.msg("推送成功",{time:1000},function(){
								layer.closeAll('dialog');
								location.reload();
							});
						}
						

					},
					error:function(e){
						isPush=0;
						$("#submit").attr("disabled",false).text("推送");
						//layer.msg(e);
						layer.msg("消息添加失败",{time:1000},function(){
							layer.closeAll('dialog');
							location.reload();
						});
					}
				});
				
				
			});

	});

	/*发送信息*/
	function sendMsg(lastid,num,msgid,count){

		$.ajax({

			url: '/index.php?g=Admin&m=PushMessage&a=push',
			type: 'POST',
			dataType: 'json',
			data: {lastid:lastid,num:num,msgid:msgid},
			async:false,
			
			success:function(data){

				//console.log(data);
				if(data.code!=0){
					res=-1;
					return;
				}

				count=count-num;
				lastid=data.info;

				if(count>0){
					sendMsg(lastid,num,msgid,count);
				}
				
			},
			error:function(e){
				console.log(e);
				res=-1;
				return;
			}
		});
	}
	</script>
</body>
</html>