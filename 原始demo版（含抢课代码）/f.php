<?php 


function getstate ($url,$cookiename,$big = 0,$host){
	if(!isset($_COOKIE['mycookie'])) echo "cookie读取错误";
	// echo $url;
	$ch = curl_init($url); //初始化
	$Refererurl = makeUrl(0,$host);
	$postarray[] = "Referer: $Refererurl";
	$postarray[] = "Connection: keep-alive";
	curl_setopt($ch, CURLOPT_HEADER, 1); //不返回$header部分
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //返回字符串，而非直接输出
	curl_setopt($ch,CURLOPT_COOKIE,$_COOKIE['mycookie']);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $postarray);
	$mainpagestr = curl_exec($ch);
	// echo "获取成功";
	 preg_match_all("/VIEWSTATE\"\svalue=\"(\S+)\"/isU" , $mainpagestr, $tea);
	$viewid = $tea[1][0];
	if(!$big)
	 	setcookie("$cookiename",$tea[1][0]);
	 // echo "<br>##".$viewid ;
	 return $viewid;
}

function makeUrl($option = 0,$host = "http://jwgl.gdut.edu.cn/"){
 $urlArrary = array( 
		"xs_main",//返回首页
		"xf_xsqxxxk",//全校性选修课
		"xstyk",//体育选修课
		"xskscx",//考试时间查询
		"xscj"//成绩查询
	);
	return $host.$urlArrary[$option].".aspx?xh=".$_COOKIE['schoolid'];
	
} 
function CheckKey(){
	if(isset($_COOKIE['mycookie'])&&isset($_COOKIE['schoolid'])&&isset($_COOKIE['host'])){echo "身份数据跟踪正常！<br>";}
	else{echo('请不要禁止cookie！<br>');}
	return Checkhost($_COOKIE['host']);
	
}
function Checkhost($host){
	 $hostArrary = array( 
		//检查对应目标是否为正确地址
 	"http://jwgl.gdut.edu.cn/",
 	"http://jwgldx.gdut.edu.cn/",
	"http://222.200.98.202/"
	);
	if (!in_array($host,$hostArrary)){echo "<script language=JavaScript>alert('请不要修改页面代码，如有需要添加新的学校，请联系管理员');</script>" ; return 0;} 
	else { return 1;}
}
function setDateArray($str,$globalsName){
	$sum =  count($str,1);
	$s_c = 	count($str,0);
	$GLOBALS["$globalsName"] = $sum / $s_c ;
}
function flash(){
	echo "<script language=JavaScript> location.replace(location.href);</script>";
}
 ?>