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
<style>
.table img{
	max-width:100px;
	max-height:100px;
}
</style>
</head>
<body>
	<div class="wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a >通知记录</a></li>
			<li><a href="<?php echo U('PushMessage/add');?>">通知添加</a></li>
		</ul>
		
		<form class="well form-search" method="post" action="<?php echo U('PushMessage/index');?>">
			
			关键字： 
			<input type="text" name="keyword" style="width: 200px;" value="<?php echo ($formget["keyword"]); ?>" placeholder="请输入标题内容">
			<input type="submit" class="btn btn-primary" value="搜索">
		</form>		
		
		<form method="post" class="js-ajax-form" >
		
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<th align="center">ID</th>
						<th>标题</th>
						<th>简介</th>
						<th>类型</th>
						<th>链接</th>
						<th>推送者</th>
						<th>推送IP</th>
						<th>推送时间</th>
						
						<th align="center"><?php echo L('ACTIONS');?></th>
					</tr>
				</thead>
				<tbody>
					<?php $url_type=array("1"=>"普通内容","2"=>"外部链接"); ?>
					<?php if(is_array($lists)): foreach($lists as $key=>$vo): ?><tr>
						<td align="center"><?php echo ($vo["id"]); ?></td>
						<td style="max-width: 200px;"><?php echo ($vo['title']); ?></td>
						<td style="max-width: 200px;"><?php echo ($vo['synopsis']); ?></td>
						<td><?php echo ($url_type[$vo['type']]); ?></td>
						<td>
							<?php if($vo['type'] == '1'): ?><a href="/index.php?g=Appapi&m=Message&a=msginfo&id=<?php echo ($vo['id']); ?>" target="_blank">/index.php?g=Appapi&m=Message&a=msginfo&id=<?php echo ($vo['id']); ?></a>
							<?php else: ?>
								<a href="<?php echo ($vo['url']); ?>" target="_blank"><?php echo ($vo['url']); ?></a><?php endif; ?>
						</td>
						<td><?php echo ($vo['admin']); ?></td>
						<td><?php echo ($vo['ip']); ?></td>
						<td><?php echo (date("Y-m-d H:i:s",$vo["addtime"])); ?></td>
						
						<td align="center">
							<a href="javascript:void(0);" onclick="push(<?php echo ($vo['id']); ?>)" >推送</a>							
							 |
							 <a href="<?php echo U('PushMessage/del',array('id'=>$vo['id']));?>" class="js-ajax-dialog-btn" data-msg="您确定要删除吗？">删除</a>
							</if>
						</td>
					</tr><?php endforeach; endif; ?>
				</tbody>
			</table>
			<div class="pagination"><?php echo ($page); ?></div>
		</form>

	</div>
	<script src="/public/js/common.js"></script>
	<script src="/public/layer/layer.js"></script>


	<script type="text/javascript">

		var count=<?php echo ($count); ?>;
		var lastid=0;
		var res=0;


		function push(id){

			layer.confirm('是否确定推送该信息？', {
			  btn: ['确定','取消'] //按钮
			}, function(){
			  
				if(id==""){
					layer.msg("推送消息获取失败");
					return;
				}

				layer.closeAll('dialog');

				$.ajax({
					url: '/index.php?g=Admin&m=PushMessage&a=push_add',
					type: 'POST',
					dataType: 'json',
					data: {id: id},
					async:true,

					beforeSend:function(){

						layer.open({
						  title:"提示",
						  type: 1,
						  skin: 'layui-layer-demo', //样式类名
						  closeBtn: 0, //不显示关闭按钮
						  anim: 2,
						  area: ['300px', '150px'],
						  shadeClose: true, //开启遮罩关闭
						  content: "<div style='width:90%;padding:5%;'>推送中…… 时间较久，请耐心等待</div>"
						});

					},
					success:function(data){

						if(data.code!=0){
							layer.msg(data.msg);
							return;
						}

						//var count=data.info;

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
						console.log(e);
						layer.msg("消息添加失败",{time:1000},function(){
							layer.closeAll('dialog');
							location.reload();
						});
				}
				});
				
				
				
			}, function(){
			  layer.closeAll();
			});
		}

	/*发送信息*/
	function sendMsg(lastid,num,msgid,count){

		$.ajax({

			url: '/index.php?g=Admin&m=PushMessage&a=push',
			type: 'POST',
			dataType: 'json',
			data: {lastid:lastid,num:num,msgid:msgid},
			async:false,
			
			success:function(data){

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