
<?php  
header('Content-type:text/html;Charset=utf-8');  
//初始化cookie
$cookie_file = dirname(__FILE__).'\cookie.txt';
//$cookie_file = tempnam("tmp","cookie"); 
//先获取cookies并保存
$url = "http://jwgl.gdut.edu.cn/default2.aspx";
$ch = curl_init($url); //初始化
curl_setopt($ch, CURLOPT_HEADER, 0); //不返回header部分
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //返回字符串，而非直接输出
curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookie_file); //存储cookies
curl_exec($ch);
curl_close($ch);

$user = $_POST['txtUserName'];       //登陆用户名  
$pass = $_POST['TextBox2']; //登陆密码
$verification = $_POST['txtSecretCode'] ;   
$va_url = 'http://jwgl.gdut.edu.cn/default2.aspx';

$post_fields = "Accept:text/html,application/xhtml+xml,application/xml;$q=0.9,image/webp,*/*;$q=0.8&Accept-Encoding:gzip,deflate,sdch&Accept-Language:zh-CN,zh;$q=0.8&Connection:keep-alive&Content-Length:226&Content-Type:application/x-www-form-urlencoded&__VIEWSTATE:dDwyMDczNjQ0MDAyOzs+ZAbA6O/1EbwHu7DtD+$Qn47Hn2mA=" ;           //验证的 $url 链接地址  

 //post提交信息串  
$curl = curl_init(); //初始化一个cURL会话，必有  
curl_setopt($curl, CURLOPT_URL, $va_url);      //设置验证登陆的 $url 链接  
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0); //设置结果保存在变量中，还是输出，默认为0（输出）  
curl_setopt($curl, CURLOPT_POST, 1);           //模拟post提交  
curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields); //设置post串
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);   
$data = curl_exec($curl);  //执行此cURL会话，必有  
//检查是否有错误  
if(curl_errno($curl)) {  
    exit('Curl error: ' . curl_error($curl));  
}  
curl_close($curl); 
echo $data;  
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>广工教务管理系统</title>
	<link rel="stylesheet" href="main.css">
	<script language="javascript">
			<!--
	var count = 0;
	// window.onload = function() {
	// 	// var url = window.location.href;
	// 	var url = "http://jwgl.gdut.edu.cn/"
	// 	var parent = window.parent;
	// 	var length = parent.frames.length;
	// 	var win = parent.frames["zhuti"];
	// 	if (length > 0 && win != undefined) {
	// 		parent.location.href = url;
	// 	}

	document.getElementById("txtUserName").focus();
	// }

	function show(me) {
		if (count == 0) {
			me.value = "";
		}
	}

	function reloadcode() {
		var verify = document.getElementById('icode');
		verify.src = verify.src + '?';
	}

	function keydown(me) {
		
		if (me.value.length == 40000) {
			document.getElementById("Button1").click();
		}
	}

	
	 //-->
	</script>
</head>
<body class="login_bg">
<form name="form1" method="post" action="1.php" id="form1">
	<input type="hidden" name="__VIEWSTATE" value="dDwyMDczNjQ0MDAyOzs+T+OLFxnillaN248tCpcms1ri8qs=" />
	
	<div class="login_main">
		<div class="login_right">
			<dl>
				<dt class="uesr">
				<label id="lbYhm">用户名：</label>
				</dt>
				<dd>
				<input name="txtUserName" type="text" id="txtUserName" tabindex="1" class="text_nor" />
				</dd>
			</dl>
			<div style="CLEAR:both"></div>
			<dl>
				<dt class="passw">
				<label id="lbMm">密　码：</label>
				</dt>
				<dd>
				<input name="TextBox2" type="password" id="TextBox2" tabindex="2" class="text_nor" />
				</dd>
			</dl>
			<div ></div>
			<dl >
				<dt class="yzm">
				<label id="lbYzm">验证码：</label>
				</dt>
				<dd>
				<input name="txtSecretCode" type="text" id="txtSecretCode" tabindex="3" class="text_nor" alt="看不清，换一张" title="看不清，换一张"  onkeydown="keydown(this);"  />
				<img id="icode" src="http://jwgl.gdut.edu.cn/CheckCode.aspx" onclick="reloadcode();" alt="看不清，换一张" title="看不清，换一张" alt="" border="1" />
				<a id="icodems" onclick="reloadcode();">看不清换一张</a>
				</dd>
				<dt></dt>
			</dl>
			<dl>
				<dd>
				<table id="RadioButtonList1" border="0" style="display:none;">
					<tr>
<td>
	<input id="RadioButtonList1_0" type="radio" name="RadioButtonList1" value="部门" tabindex="4" />
	<label for="RadioButtonList1_0">部门</label>
</td>
<td>
	<input id="RadioButtonList1_1" type="radio" name="RadioButtonList1" value="教师" tabindex="4" />
	<label for="RadioButtonList1_1">教师</label>
</td>
<td>
<input id="RadioButtonList1_2" type="radio" name="RadioButtonList1" value="学生" checked="checked" tabindex="4" />
<label for="RadioButtonList1_2">学生</label>
</td>
					</tr>
				</table></dd>
				
			</dl>
			<dl>
				<dd>
				<input type="submit" name="Button1" value="登录" id="Button1" class="btn_dl" />
				<input type="submit" name="Button2" value="关闭" id="Button2" class="btn_cz" onclick="window.close();return false;" />
				<input name="lbLanguage" type="text" id="lbLanguage" style="DISPLAY: none" />
				<p><br>
				<A style="DISPLAY: none" href="xsxjxtdl.aspx" target="_blank">
				<span id="lbSelect">学生学籍信息查询系统</span></A></p>
				</dd>
			</dl>
		</div>
	</div>
</form>
	
</body>
</html>