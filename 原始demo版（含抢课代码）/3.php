<?php 
// 体育选课代码
header("Content-type: text/html; charset=UTF-8"); 
session_start();
include "f.php" ;
if(CheckKey())
	$host = $_COOKIE['host'];

if(!isset($_SESSION['tyxkId'])||$_GET["newid"]==1)
	$_SESSION['tyxkId'] = getstate(makeUrl(2,$host),"tyxkId",1,$host);
else echo "再次读取数据成功";
// echo "获取完成".$_SESSION['tyxkId'];
function select_PE ($status = 0,$host)
{
	//$status 
	// =0 第一次打开页面，得到所有课程 
	// =1 得到该课程老师 
	// =2 发送选课请求
	$va_url = makeUrl(2,$host);
	$event = $dataarray['__EVENTTARGET'] = $status<1?"":($status<2?"ListBox1":"") ;
	$dataarray['__EVENTARGUMENT'] = "" ;
 	$dataarray['__VIEWSTATE'] = $_SESSION['tyxkId'];
	$dataarray["$event"] =  $_GET["$event"];
	if($status==2){
		preg_match_all('/\w\(\w{4}-\w{4}-\w\)-([^-]*?)-/isU' , $_GET["ListBox2"],$str); 
		$dataarray['ListBox1'] = $str[1][0]; 
		$dataarray["ListBox2"] =  $_GET["ListBox2"];
		$str2 = "&button3=%D1%A1%B6%A8%BF%CE%B3%CC";
	}
	// echo "<br><br>xian zai  status shi ".$status."event shi ".$event."=".$_GET["$event"];
	$str = "&DropDownList1=%CF%EE%C4%BF";
	$Refererurl = makeUrl(2,$host);
	$postarray[] = "Referer: $Refererurl";
	$postarray[] = "Connection: keep-alive";	
	$data = http_build_query($dataarray).$str.$str2;
	$curl = curl_init(); //初始化一个cURL会话，必有  
	curl_setopt($curl, CURLOPT_URL, $va_url);      //设置验证登陆的 $url 链接  
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //设置结果保存在变量中，还是输出，默认为0（输出） 
	curl_setopt($curl, CURLOPT_POST, 1);           //模拟post提交  
	curl_setopt($curl, CURLOPT_TIMEOUT,5);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $postarray);
	curl_setopt($curl,CURLOPT_COOKIE,$_COOKIE['mycookie']);
	if($status==0){
		curl_setopt($curl, CURLOPT_POST,0); 
		$inner = curl_exec($curl);
	}
	else{
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data ); 		
		$inner = curl_exec($curl);

	}
	
	$inner =  iconv("GBK", "UTF-8//IGNORE", $inner);
	if(curl_getinfo($curl,CURLINFO_HTTP_CODE)=='200')
		{$GLOBALS['meg']="获取课程成功，请选择！";}
	else  {$GLOBALS['meg']="获取课程失败，请联系管理员或<a href='3.php?newid=1'>刷新时间密钥</a>";}	
	preg_match_all('/>(ty\w{6})∥([^<>]*?)<\/option>/isU' , $inner,$GLOBALS['PElist']);
	preg_match_all('/<option\svalue=\"(\S{68,70})\">(\S+)∥(\S+)∥(\S+)∥(\S+)∥(\S+)∥(\S+)∥(\S+)<\/option>/isU' , $inner,$GLOBALS['Teacherlist']);
	preg_match_all('/<option\svalue=\"(\S{33,35})\">(\S+)∥(\S+)∥(\S+)∥(\S+)∥(\S+)∥(\S+)∥(\S+)<\/option>/isU' , $inner,$GLOBALS['PE_got_list']);
	setDateArray($GLOBALS['PElist'],'PE_r');
	setDateArray($GLOBALS['Teacherlist'],'TE_r');
	if($status==2){
		preg_match_all('/(?!\s)<script.*?>alert\S{2}(\S*?)\S{3}<\/script/isU' , $inner,$str);
		 $GLOBALS['meg']= $str[1][0];
	}
	
}
if (isset($_GET["status"]))
	select_PE($_GET["status"],$host);
	else select_PE(0,$host);
// echo "<br>status->".$_GET["status"];
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>体育抢课</title>
<link rel="stylesheet" href="home.css">
</head>
<body>
	<form name="PEForm" id="PEForm" action="3.php"  >
	<input name="status" type="hidden" id="status" value='0'/>
	<select name="ListBox1" size="10"  id="subject" onchange = "submitSelect(1)">
	<?php
	if(isset($GLOBALS['PElist']))
			for ($i=0; $i <$GLOBALS['PE_r']-1 ; $i++) 
				echo '<option value="'.$GLOBALS['PElist'][1][$i].'">'.$GLOBALS['PElist'][2][$i].'</option>';
			else echo "数据丢失";
	 ?>
	</select>
	<select name="ListBox2" size="10" width="10" id="teacher" >
		<?php
		if(isset($GLOBALS['Teacherlist']))
				for ($i=0; $i <$GLOBALS['TE_r']-1 ; $i++) 
					echo '<option value="'.$GLOBALS['Teacherlist'][1][$i].'">'.$GLOBALS['Teacherlist'][2][$i].'-'.$GLOBALS['Teacherlist'][4][$i].'</option>';
				else echo "数据丢失";
		 ?>
	</select>
	<input type="submit" name="Go" value="选课" id="Button" class="button" onclick = "submitSelect(2)"/>
	</form>
<div id="mesbox">消息提示：<span><?php echo $GLOBALS['meg'] ;?></span></div>
<div id= "gotlist">已选课程：<span>
	<?php if(isset($GLOBALS['PE_got_list']))
		echo $GLOBALS['PE_got_list'][2][0]."-".$GLOBALS['PE_got_list'][4][0]."-".$GLOBALS['PE_got_list'][7][0] ;
		else echo "还未选到体育课！";
	?></span></div>
	<div><a href="home.php">返回功能选择</a></div>
<script type="text/javascript" src="jquery-1.8.3.min.js"></script> 
<script>
	function submitSelect(formnum){
		$("#status").val(formnum);
		str = formnum==2?"正在尝试选课...":"正在获取对应课程的老师,请稍后！";
		$("#mesbox span").html(str);
		$("#PEForm").submit();
	}

</script>
</body>
</html>