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
	<div class="wrap">
		<ul class="nav nav-tabs">
			<li ><a href="<?php echo U('Music/index');?>">音乐列表</a></li>
			<li class="active"><a >音乐添加</a></li>
		</ul>
		<form method="post" class="form-horizontal js-ajax-form" action="<?php echo U('Music/music_add_post');?>">
			<fieldset>

				<div class="control-group">
					<label class="control-label">音乐分类</label>
					<div class="controls">
						<select name="classify_id">
						    <option value="0">默认分类</option>
						   <?php if(is_array($classify)): $i = 0; $__LIST__ = $classify;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo['id']); ?>"><?php echo ($vo['title']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>			
							 
						</select>
						<span class="form-required">*</span>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">音乐名称</label>
					<div class="controls">
						<input type="text" name="title">
						<span class="form-required">*</span>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">演唱者</label>
					<div class="controls">
						<input type="text" name="author">
						<span class="form-required">*</span>
					</div>
				</div>


				
				<div class="control-group">
					<label class="control-label">封面</label>
					<div class="controls">
								<div >
									<input type="hidden" name="img_url" id="thumb2" value="">
									<a href="javascript:void(0);" onclick="flashupload('thumb_images', '附件上传','thumb2',thumb_images,'1,jpg|jpeg|gif|png|bmp,1,,,1','','','');return false;">
										    <img src="/admin/themes/simplebootx/Public/assets/images/default-thumbnail.png" id="thumb2_preview" width="135" style="cursor: hand" />
									</a>
									<input type="button" class="btn btn-small" onclick="$('#thumb2_preview').attr('src','/admin/themes/simplebootx/Public/assets/images/default-thumbnail.png');$('#thumb2').val('');return false;" value="取消图片">
								</div>
						<span class="form-required"></span>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">音乐长度</label>
					<div class="controls">
						<input type="text" id="length" name="length" readonly="readonly">
						<span class="form-required">*(系统自动获取)</span>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">被使用次数</label>
					<div class="controls">
						<input type="text" name="use_nums">
						<span class="form-required">*填写正整数</span>
					</div>
				</div>

				<div class="control-group upload_video_area">
					<label class="control-label">上传音乐</label>
					<div class="controls">
						<input type="file" name="file" id="upfile" />
						<span class="form-required">*MP3格式</span>
					</div>
				</div>

				<div class="control-group upload_video_area">
					<label class="control-label"></label>
					<div class="controls">
						<audio id="audio" controls="" style="display: none;"></audio>
					</div>
					
				</div>

			</fieldset>
			<div class="form-actions">
				<button id="submit" type="submit" class="btn btn-primary js-ajax-submit"><?php echo L('ADD');?></button>
				<a class="btn" href="<?php echo U('Music/index');?>"><?php echo L('BACK');?></a>
			</div>
		</form>
	</div>
	<script src="/public/js/common.js"></script>
	<script type="text/javascript" src="/public/js/content_addtop.js"></script>
	<script type="text/javascript" src="/public/layer/layer.js"></script>
	<script type="text/javascript">
		
		 $(function () {  
            $("#upfile").change(function () {

            	$('#submit').removeAttr("disabled"); 

            	//获取文件类型
            	var a=$("#upfile").val();
            	var arr=a.split('.');
            	var type=arr[arr.length-1];
            	if(type.toLowerCase()!="mp3"){
            		layer.msg("请上传MP3格式文件");
            		$("#submit").attr("disabled","true");
            		return;
            	}


                var objUrl = getObjectURL(this.files[0]);  
                $("#audio").attr("src", objUrl);  
                $("#audio")[0].play();  
                $("#audio").show();  
                getTime();  
            });  
        }); 


        //获取mp3文件的时间 兼容浏览器  
        function getTime() {  
            setTimeout(function () {  
                var duration = $("#audio")[0].duration;  
                if(isNaN(duration)){  
                    getTime();  
                }  
                else{

                	//console.log($("#audio")[0]); 
                   // console.info("该歌曲的总时间为："+$("#audio")[0].duration+"秒");
                    var length=Math.floor($("#audio")[0].duration); //获取音乐长度

                    if(length<15){ //长度小于15秒
                    	layer.msg('音乐长度不能低于15秒');
                    	$("#submit").attr("disabled","true");	
                    }

                    var len_str="00:00";
                    if(length>60){
                    	var minute=Math.floor(length/60);
                    	var second=length%60;
                    	if(minute<10){
                    		minute="0"+minute;
                    	}
                    	if(second<10){
                    		second="0"+second;
                    	}
                    	len_str=minute+":"+second;

                    }else{
                    	len_str="00:"+length;
                    }

                    //console.log(len_str);
                    $("#length").val(len_str);

                }  
            }, 10);  
        }  
        //把文件转换成可读URL 
        function getObjectURL(file) {

            var url = null;  
            if (window.createObjectURL != undefined) { // basic  
                url = window.createObjectURL(file);  
            } else if (window.URL != undefined) { // mozilla(firefox)  
                url = window.URL.createObjectURL(file);  
            } else if (window.webkitURL != undefined) { // webkit or chrome  
                url = window.webkitURL.createObjectURL(file);  
            }  
            return url;  
        }


	</script>
</body>
</html>