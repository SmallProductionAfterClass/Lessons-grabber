
<?php  
header("Content-type: text/html; charset=UTF-8"); 
include "f.php" ;
function Sinfo($ch) {
	echo "<br>---------------输出信息开始---------------<br>";
	foreach (curl_getinfo($ch) as $key=>$value)
	{
	echo $key.'=>'.$value;
	echo "<br>";
	}
	echo "<br>头信息：<br>".curl_getinfo($ch,CURLINFO_HEADER_OUT);
	echo "<br>---------------输出结束---------------<br>";
}

function curl_redir_exec($ch,$debug="") 
{ 
    static $curl_loops = 0; 
    static $curl_max_loops = 20; 
    if ($curl_loops++ >= $curl_max_loops) 
    { 
        $curl_loops = 0; 
        return FALSE; 
    } 
    curl_setopt($ch, CURLOPT_HEADER, true); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $data = curl_exec($ch); 
    $debbbb = $data; 
    list($header, $data) = explode("\n\n", $data, 2); 
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 

    if ($http_code == 301 || $http_code == 302) { 
        $matches = array(); 
        preg_match('/Location:(.*?)\n/', $header, $matches); 
        $url = @parse_url(trim(array_pop($matches))); 
        //print_r($url); 
        if (!$url) 
        { 
            //couldn't process the url to redirect to 
            $curl_loops = 0; 
            return $data; 
        } 
        $last_url = parse_url(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL)); 
        $new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . ($url['query']?'?'.$url['query']:''); 
        curl_setopt($ch, CURLOPT_URL, $new_url); 

        return curl_redir_exec($ch); 
    } else { 
        $curl_loops=0; 
        return $debbbb; 
    } 
} 

function initck($host = "http://jwgl.gdut.edu.cn/")
{
	
	if(isset($_COOKIE['mycookie'])&&isset($_COOKIE['viewid'])) 
	{ 
		//如果登录已经获取，那么提交时就不必获取viewid来浪费资源
		$GLOBALS['cookie'] = $_COOKIE['mycookie']; 
		$viewid = $_COOKIE['viewid'];
		$needcookie = 0;
	}
	if($needcookie != 0 ||$_GET['action']=='newkey'||$_GET['action']=='changehost') { 
		echo "<br>现在抓取网站:".$host;
	//先获取cookies并保存
		// echo "mei".$needcookie.$_GET['action'];
	$url = $host."default2.aspx";
	$ch = curl_init($url); //初始化
	curl_setopt($ch, CURLOPT_HEADER, 1); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //返回字符串，而非直接输出
	$mainpagestr = curl_exec($ch);	
	curl_setopt($ch,CURLOPT_COOKIE,$GLOBALS['cookie']);
	preg_match('/Set-Cookie:((.*)=(.*));/iU',$mainpagestr,$str); //正则匹配
	$GLOBALS['cookie'] = $str[1];
	// echo "<br>ini".$GLOBALS['cookie'];
	setcookie('mycookie',$str[1]);
	 preg_match_all("/VIEWSTATE\"\svalue=\"(\S+==)\"/isU" , $mainpagestr, $tea);
	$viewid = $tea[1][0];
	 setcookie('viewid',$tea[1][0]);
	 // if($_GET['action']=='changehost') getImg($host);
	 }
	return $viewid;
}

 function getImg($host = "http://jwgl.gdut.edu.cn/") 
    { 
    	echo "<br>触发getimg（）图片抓取地址:".$host;
        $url = $host."CheckCode.aspx"; 
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        // curl_setopt($ch, CURLOPT_HEADER, 0);  
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $postarray);
        // curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file); 
        curl_setopt($ch,CURLOPT_COOKIE,$GLOBALS['cookie']);// 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
        curl_setopt($ch, CURLOPT_TIMEOUT, "10"); 
        // header("Content-type:image/gif");
         //这个视不同图片格式不一样，请注意 
        $img =   curl_exec($ch); 
        // Sinfo($ch);
        curl_close($ch); 
        $fp = fopen("verifyCode.jpg","w");
		fwrite($fp,$img);
		
    }
 function login ($viewid,$host="http://jwgl.gdut.edu.cn/")
{
	$va_url = $host.'default2.aspx';
	$dataarray['__VIEWSTATE'] =  $viewid;
	$dataarray['txtUserName'] =  $_POST['txtUserName']; 
	$dataarray['TextBox2'] = $_POST['TextBox2'];
	$dataarray['txtSecretCode'] = $_POST['txtSecretCode'] ;
	$dataarray2['Button1'] = '' ;
	$dataarray2['lbLanguage'] = '' ;
	$dataarray2['hidPdrs'] = '' ;
	$dataarray2['hidsc'] = '' ;
	$str = "&RadioButtonList1=%D1%A7%C9%FA&";
	setcookie('schoolid',$_POST['txtUserName']);
	$data = http_build_query($dataarray).$str.http_build_query($dataarray2);

	 // echo "<br>login".$GLOBALS['cookie'];
	$curl = curl_init(); //初始化一个cURL会话，必有  
	curl_setopt($curl, CURLOPT_URL, $va_url);      //设置验证登陆的 $url 链接  
	// curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //设置结果保存在变量中，还是输出，默认为0（输出） 
	// curl_setopt($curl, CURLOPT_HEADER, 1);//返回response头部信息  
	curl_setopt($curl, CURLOPT_NOBODY, 1); 
	curl_setopt($curl, CURLOPT_POST, 1);           //模拟post提交  


	curl_setopt($curl, CURLOPT_TIMEOUT,5);
	// curl_setopt($curl, CURLOPT_HTTPHEADER, $postarray);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data ); 
	// curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file);
	curl_setopt($curl,CURLOPT_COOKIE,$GLOBALS['cookie']);
	// echo "aaaa!aaa<br>";
	// echo $GLOBALS['cookie'] ;
	curl_redir_exec($curl);

	// curl_exec($curl);
	echo  curl_getinfo($curl,CURLINFO_HTTP_CODE); sleep(3);
	if(curl_getinfo($curl,CURLINFO_HTTP_CODE)=='0') {
		echo "<br>登录成功<br>";
		echo "<script language=JavaScript> location.replace(location.href.replace('1.php','home.php'));</script>";
	}
	else{ 
	// 登录失败
		$_COOKIE['viewid']="";
	flash();
	exit;

	}
	curl_close($curl); 
	
}
// 判断是不是post-1.是不是正确的地址-
switch($_SERVER['REQUEST_METHOD']){
	case 'GET':
	if(!isset( $_COOKIE['host'])){setcookie('host',"http://jwgl.gdut.edu.cn/");flash();}
	else {if(Checkhost($_COOKIE['host']))
		{$host = $_COOKIE['host'];}
		else{echo "<script language=JavaScript>alert('!!请不要修改页面代码，如有需要添加新的学校，请联系管理员');</script>" ;$host = "#" ;}
		}
	break;
	case 'POST':
	if(isset( $_POST['host'])&&!Checkhost($_POST['host'])){
		echo "<script language=JavaScript>alert('请不要修改页面代码，如有需要添加新的学校，请联系管理员');</script>" ; 
	 }
 	else if(isset( $_POST['host'])){ $host = $_POST['host']; setcookie('host',$host);}
	 	else{echo "<script language=JavaScript>alert('请不要修改页面代码！');</script>" ;$host = "#" ; }
	break;

}
echo $host."<_host";

// $host = "http://jwgl.gdut.edu.cn/";
$viewid = initck($host); 
 if($_POST['Button1']=='登录')
{		
	// if($_POST['txtSecretCode']=""){flash();return;}
	login ($viewid,$host);
}
else{
	getImg($host);
}
if($_GET['action']=='flash'){getImg($host);}


?>

<!doctype html>
<html>
<head>
	<title>后台代理登录</title>
	<link rel="stylesheet" href="home.css">
</head>
<body >
	<form name="form1" method="post" action="1.php" id="form1" >
	<span>学号：</span><input name="txtUserName" type="text" id="txtUserName" tabindex="1" onblur="getCurrentName()" >
	<br><span>密码：</span><input name="TextBox2" type="password" id="TextBox2" tabindex="2" >
	<br><span>验证：</span><input name="txtSecretCode" type="text" id="txtSecretCode" /><a href="?action=flash"><img src="verifyCode.jpg" alt=""></a>
	<br><span>学校：</span><select name="host" id="host" onchange="changehost()">
		<option value="http://jwgl.gdut.edu.cn/" selected = "selected" >广东工业大学</option>
		<option value="http://jwgldx.gdut.edu.cn/"  >广东工业大学<b>电信服</b></option>
		
	</select>
	<input name="verifyCodeTime" type="hidden" id="verifyCodeTime" value='<?php echo $cookie_file ; ?>'/>
	<br><a href="?action=newkey"title="每把钥匙有10分钟有效期，10分钟内不换钥匙可以加快登录！" >更换身份密钥</a><input id="auto_login" type="checkbox" name="auto_login" checked="checked">
	<label for="auto_login">自动登录</label><a href="#"title="勾上后，输入完验证码会自动登录哦！" >?</a>
	<br><input type="submit" name="Button1" value="登录" id="Button1" class="btn_dl" />
	
	</form>
	<script type="text/javascript" src="jquery-1.8.3.min.js"></script> 
	<script>
	if(location.href.indexOf('action=newkey')!=-1)
		// location.replace(location.href.replace('?action=newkey',""));
		history.pushState({}, "页面标题", location.href.replace('?action=newkey',""));
	var user = {
		'username': '',
    	'password': '',
    	'is_autologin':'',
    	'need_setup': '',
    	'lastname':'',
    	'lasthost':''
    };
	// 导入数据
	function LoadSettings(){
		user.username = localStorage.username;
    	user.password = localStorage.password;
    	user.lasthost = localStorage.lasthost;
    	localStorage.lastname = localStorage.username
    	user.is_autologin = parseInt(localStorage.is_autologin, 10) || 0;
    	if (user.username && user.password && user.username != "undefined" && user.password != "undefined") {
    	    user.need_setup = false;
    	    console.log(user.need_setup);
    	    document.getElementById("txtUserName").value = user.username;
    	    document.getElementById("TextBox2").value = user.password;

    	} else {
    	    user.need_setup = true;
    	}
	}
	//检查是否需要重新获取页面密钥
	function checkid(){
		if(document.getElementById("txtUserName").value !=localStorage.lastname )  
			 document.getElementById("TextBox2").value = "";
		else document.getElementById("TextBox2").value = localStorage.password;
	}
	function getCurrentName()
	{
		localStorage.username = document.getElementById("txtUserName").value;
		checkid();
	}
	function changehost(){
		 user.lasthost = $('#host').val();
		 alert(user.lasthost);
		 document.cookie='host='+user.lasthost;
		_save_user_settings();
		location.replace("?action=changehost");

	}
	//显示配置信息
	function ShowSettings() {

	        if (user.is_autologin) {
	            $('input[name="auto_login"]').attr({checked: 'checked'});
	        }
	        if (user.lasthost) {
	            $('option[value="'+user.lasthost+'"]').attr({selected: 'selected'});
	        }

	        $('input#Button1').click(function() {
	            user.username = $('#txtUserName').val();
	            user.password = $('#TextBox2').val();
	            user.lasthost = $('#host').val();
	            user.lastname = user.username;
	            if ($('input[name="auto_login"]').is(':checked')) {
	                user.is_autologin = 1;
	            } else {
	                user.is_autologin = 0;;
	            }
	            _save_user_settings();
	        });
	} 
	function _save_user_settings() {
		
    var prop;
    for (prop in user) {
        localStorage.setItem(prop, user[prop]);
    }
	}

	//检测验证码是否输完
	function Captchaok(){
	    console.log(document.getElementById("txtSecretCode").value.length);
	    if(document.getElementById("txtSecretCode").value.length==4)
	        {return true;}
	    return false;

	}
	// 检查是否可以按下登录了
	function autologin(){
	    // console.log(user.is_autologin&&Captchaok());
	     // console.log(user.is_autologin);
	    if (user.is_autologin&&Captchaok()) {
	                document.getElementById("Button1").click();
	            }
	}
	$(document).ready(function(){   
	    $("#txtSecretCode").focus();   
	});
	var captcha = 0;
	LoadSettings();
    ShowSettings();
	setInterval(function(){
	    // 有改变才进行比较
	        if(captcha!=document.getElementById("txtSecretCode").value)
	        {
	            
	            autologin();
	            captcha=document.getElementById("txtSecretCode").value;
	        }
	    
	    

	    },200);

	</script>
</body>
</html>