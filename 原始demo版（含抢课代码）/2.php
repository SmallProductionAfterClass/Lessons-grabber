<?php 
include "f.php" ;
// 成绩查询代码
if(CheckKey())
	$host = $_COOKIE['host'];

getstate(makeUrl(4,$host),"cxcjId",0,$host);
function getscore ($status,$host)

{
	if(!isset($_COOKIE['cxcjId'])){echo "未获取到页面密钥，请刷新重试！"; return;}
	$va_url = makeUrl(4,$host);
	$dataarray['__VIEWSTATE'] =  $_COOKIE['cxcjId'];
	$dataarray['ddlXN'] =  $_GET['year'];
	$dataarray['ddlXQ'] = $_GET['session'];
	$dataarray['txtQSCJ'] = 0 ;
	$dataarray['txtZZCJ'] = 100 ;
	// $dataarray['RadioButtonList1'] = '%D1%A7%C9%FA' ;
	// $dataarray2['Button1'] = '' ;
	// $dataarray2['lbLanguage'] = '' ;
	// $dataarray2['hidPdrs'] = '' ;
	// $dataarray2['hidsc'] = '' ;
	switch($status){
		case 0:
		$str = "&Button2=%D4%DA%D0%A3%D1%A7%CF%B0%B3%C9%BC%A8%B2%E9%D1%AF";
		break;
		case 2:
		$str = "&Button1=%B0%B4%D1%A7%C6%DA%B2%E9%D1%AF";
		break;
		case 1:
		$str = "&Button5=%B0%B4%D1%A7%C4%EA%B2%E9%D1%AF";
		break;
		default:
		echo "数据出现错误，请不要修改页面代码！";
	}
	
	$Refererurl = makeUrl(4,$host);
	$postarray[] = "Referer: $Refererurl";
	$postarray[] = "Connection: keep-alive";
	$data = http_build_query($dataarray).$str;
	// $data = http_build_query($dataarray).$str.http_build_query($dataarray2);

	 // echo "<br>login".$GLOBALS['cookie'];
	$curl = curl_init(); //初始化一个cURL会话，必有  
	curl_setopt($curl, CURLOPT_URL, $va_url);      //设置验证登陆的 $url 链接  
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //设置结果保存在变量中，还是输出，默认为0（输出） 
	curl_setopt($curl, CURLOPT_POST, 1);           //模拟post提交  
	curl_setopt($curl, CURLOPT_TIMEOUT,5);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $postarray);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data ); 	
	curl_setopt($curl,CURLOPT_COOKIE,$_COOKIE['mycookie']);
	$inner = curl_exec($curl);
	$inner =  iconv("GBK", "UTF-8//IGNORE", $inner);
	preg_match_all("/<td>([^<>]{8})<\/td><td>([^<>]*?)<\/td><td>([^<>]*?)<\/td><td>([^<>]*?)<\/td><td>([^<>]*?)<\/td><td>([^<>]*?)<\/td><td>([^<>]*?)<\/td><td>([^<>]*?)<\/td>/isU" , $inner,$GLOBALS['list']);
	// if(curl_getinfo($curl,CURLINFO_HTTP_CODE)=='0')
	// 	{echo "<br>获取成绩成功<br>";}
	setDateArray($GLOBALS['list'],'s_r');
	

}

if($_GET['All']!=NULL){
	// echo "查询所有成绩";
		getscore(0,$host);
	}
else if($_GET['bySession']!=NULL&&$_GET['byYear']==NULL){
        // echo "按学期查询";
        getscore(2,$host);
		}
 	 else if($_GET['byYear']!=NULL){
  		// echo "按学年查询";
  		getscore(1,$host);
  
}







 ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>成绩查询</title>
	<link rel="stylesheet" href="home.css">
		<style>
	 .roundedCorners {
	    border: #777 2px solid;
	    padding: 0 10px;
	    border-radius: 1em;
	    -moz-border-radius: 1em;
	    -webkit-border-radius: 1em;
	}
	</style>
	<link rel="stylesheet" href="theme.black-ice.css">
</head>
<body>
	<form action="" class="checkscore" method = 'GET'>
		<span>学年</span>
		<select name="year" id="year">
			<!-- option[value="200$-200$@2"]{200$-200$@2}*14 -->
			<option selected="selected" value=""></option>
			<option value="2001-2002">2001-2002</option>
			<option value="2002-2003">2002-2003</option>
			<option value="2003-2004">2003-2004</option>
			<option value="2004-2005">2004-2005</option>
			<option value="2005-2006">2005-2006</option>
			<option value="2006-2007">2006-2007</option>
			<option value="2007-2008">2007-2008</option>
			<option value="2008-2009">2008-2009</option>
			<option value="2009-2010">2009-2010</option>
			<option value="2010-2011">2010-2011</option>
			<option value="2011-2012">2011-2012</option>
			<option value="2012-2013">2012-2013</option>
			<option value="2013-2014">2013-2014</option>
			<option value="2014-2015">2014-2015</option>
		</select>
		<span>学期</span>
		<select name="session" id="session">
			<option selected="selected" value=""></option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
		</select>
		<br><input type="submit" name="bySession" value="按学期查询" id="Button" class="button" />
		<input type="submit" name="byYear" value="按学年查询" id="Button2" class="button" />
		<input type="submit" name="All" value="所有成绩查询" id="Button3" class="button" />
	</form>
	<table  class="tablesorter">
		<thead>
		<!-- 辅修直接用样式表示 -->
		<th>课程名称</th><th>成绩</th><th>学分</th><th>补考成绩</th><th>重修成绩</th>	
		</thead>
		 <tbody>
		
		
		<?php 
			if(isset($GLOBALS['list']))
			for ($i=0; $i <$GLOBALS['s_r']-1 ; $i++) 
				echo "<tr><td>".$GLOBALS['list'][2][$i]."</td><td>".$GLOBALS['list'][4][$i]."</td><td>".$GLOBALS['list'][8][$i]."</td><td>".$GLOBALS['list'][6][$i]."</td><td>".$GLOBALS['list'][7][$i]."</td></tr>";
			else echo "数据丢失";			
		 ?>
		</tbody>
	</table>
	<div><a href="home.php">返回功能选择</a></div>
	<script type="text/javascript" src="jquery-1.8.3.min.js"></script> 
<script type="text/javascript" src="jquery.tablesorter.js"></script> 
<script>
	$('table').tablesorter({

	    theme: 'blackice',

	    onRenderHeader: function (index) {
	        // the span wrapper is added by default
	        $(this).find('div.tablesorter-header-inner').addClass('roundedCorners');
	    },

	});
</script>

</body>
</html>