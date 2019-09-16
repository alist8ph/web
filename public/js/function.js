/**
语言包替换
key string 需要翻译的文本（语言包中的键值）
params object 需要替换的参数（动态变量的键对值）
**/
function LangT(key,params) {
	lang=typeof(lang)=='object'?lang:JSON.parse(lang);
	var rs = lang && lang[key] ? lang[key] : key;

	for (var k in params){
		var r = new RegExp('{'+k+'}', "ig");
		var re=params[k];
		rs=rs.replace(r, re);
	}
	return  rs;
}

/**
去除表情
key string 需要处理的字符串
**/
function fieldEmoji(str){
    var content=str;
    var ranges = [  
        '\ud83c[\udf00-\udfff]',  
        '\ud83d[\udc00-\ude4f]',  
        '\ud83d[\ude80-\udeff]'  
    ];  
    emojireg = content .replace(new RegExp(ranges.join('|'), 'g'), '');  
    
    return emojireg;
}
/**
检测是否有表情
key string 需要处理的字符串
**/
function hasEmoji(str){
    var ranges = [  
        '\ud83c[\udf00-\udfff]',  
        '\ud83d[\udc00-\ude4f]',  
        '\ud83d[\ude80-\udeff]'  
    ];  
    var part=new RegExp(ranges.join('|'), 'g');
   
    if(part.test(str)){
        return true;
    }else{
        return false;
    }
}