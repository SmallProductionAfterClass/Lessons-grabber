<?php 

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
        if (!$url) 
        { 
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
function flash(){
	echo "<script language=JavaScript> location.replace(location.href);</script>";
}
function msg($num){
    $temp = array('msg'=>$num);
    echo json_encode($temp);
}
function makeUrl($option = 0,$host = "http://jwgl.gdut.edu.cn/"){
 $urlArrary = array( 
		"xs_main",//返回首页
		"xf_xsqxxxk",//全校性选修课
		"xstyk",//体育选修课
		"xskscx",//考试时间查询
		"xscj",//成绩查询
		"xsdjkscx"//等级考试查询
	);
	return $host.$urlArrary[$option].".aspx?xh=".$_COOKIE['schoolid'];
	
}
function setDateArray($str,$globalsName){
	$sum =  count($str,1);
	$s_c = 	count($str,0);
	$GLOBALS["$globalsName"] = $sum / $s_c ;
}
//--------------------------------------

function getImg($host){
        $url = $host."CheckCode.aspx"; 
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch,CURLOPT_COOKIE,$GLOBALS['cookie']);// 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
        curl_setopt($ch, CURLOPT_TIMEOUT, "10");     
        $img =   curl_exec($ch); 
        curl_close($ch); 
        $img = substr($img,0,strlen($img)-735); 
        $fp = fopen("verifyCode.jpg","w");
		fwrite($fp,$img);
}
function initck($host = "http://jwgl.gdut.edu.cn/")
{	
	// $needcookie = 1;
	// if(isset($_COOKIE['mycookie'])&&isset($_COOKIE['viewid'])) 
	// { 

	// 	//如果登录已经获取，那么提交时就不必获取viewid来浪费资源
	// 	$GLOBALS['cookie'] = $_COOKIE['mycookie']; 
	// 	$viewid = $_COOKIE['viewid'];
	// }
	// if($needcookie != 0 ||$_GET['action']=='newkey'||$_GET['action']=='changehost') 
	// { 
		// echo "<br>现在抓取网站:".$host;
	//先获取cookies并保存
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
	// }
	return $viewid;
}
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
	 preg_match_all("/VIEWSTATE\"\svalue=\"(\S+)\"/isU" , $mainpagestr, $tea);
	$viewid = $tea[1][0];
	if(!$big)	
	 	{
	 		$GLOBALS[$cookiename]=$tea[1][0];
	 	}
}
function checkCETScore ($host){
	if(!isset($_COOKIE['mycookie'])) echo "cookie读取错误";
	$inner = curl_init(makeUrl(5,$host)); //初始化
	$Refererurl = makeUrl(0,$host);
	$postarray[] = "Referer: $Refererurl";
	$postarray[] = "Connection: keep-alive";
	curl_setopt($inner, CURLOPT_HEADER, 1); //不返回$header部分
	curl_setopt($inner, CURLOPT_RETURNTRANSFER, true); //返回字符串，而非直接输出
	curl_setopt($inner,CURLOPT_COOKIE,$_COOKIE['mycookie']);
	curl_setopt($inner, CURLOPT_HTTPHEADER, $postarray);
	$inner = curl_exec($inner);
	// echo "获取成功";
	$inner =  iconv("GBK", "UTF-8//IGNORE", $inner);
	preg_match_all("/<td>([^<>]{9})<\/td><td>([^<>]*?)<\/td><td>([^<>]*?)<\/td><td>([^<>]*?)<\/td><td>([^<>]*?)<\/td><td>([^<>]*?)<\/td><td>([^<>]*?)<\/td><td>([^<>]*?)<\/td><td>([^<>]*?)<\/td><td>([^<>]*?)<\/td>/isU" , $inner,$GLOBALS['list']);
	setDateArray($GLOBALS['list'],'s_r');
	echo json_encode($GLOBALS['list']);
}
function checkScore($status,$host,$grade='',$term=''){
	if(!isset($GLOBALS['cxcjId'])){echo "未获取到页面密钥，请刷新重试！"; return;}
	if(!is_null($grade)){
		$firstgrade=substr($_COOKIE['schoolid'], 2, 2);
		$grade = '20'.((int)$firstgrade+$grade).'-20'.((int)$firstgrade+($grade+1));
	}

	$va_url = makeUrl(4,$host);
	$dataarray['__VIEWSTATE'] =  $GLOBALS['cxcjId'];
	$dataarray['ddlXN'] =  $grade;
	$dataarray['ddlXQ'] = $term;
	$dataarray['txtQSCJ'] = 0 ;
	$dataarray['txtZZCJ'] = 100 ;
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
	setDateArray($GLOBALS['list'],'s_r');
	echo json_encode($GLOBALS['list']);
}

function login($userName,$passWord,$host="http://jwgl.gdut.edu.cn/",$captcha)
{
	$va_url = $host.'default2.aspx';
	$dataarray['__VIEWSTATE'] =  $_COOKIE["viewid"];
	$dataarray['txtUserName'] =  $userName;
	$dataarray['TextBox2'] = $passWord;
	$dataarray['txtSecretCode'] = $captcha ;
	$dataarray2['Button1'] = '' ;
	$dataarray2['lbLanguage'] = '' ;
	$dataarray2['hidPdrs'] = '' ;
	$dataarray2['hidsc'] = '' ;
	$str = "&RadioButtonList1=%D1%A7%C9%FA&";
	setcookie('schoolid',$userName);
	$data = http_build_query($dataarray).$str.http_build_query($dataarray2);
	$curl = curl_init(); //初始化一个cURL会话，必有  
	curl_setopt($curl, CURLOPT_URL, $va_url);      //设置验证登陆的 $url 链接  
	curl_setopt($curl, CURLOPT_NOBODY, 1); 
	curl_setopt($curl, CURLOPT_POST, 1);           //模拟post提交  
	curl_setopt($curl, CURLOPT_TIMEOUT,5);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data ); 
	curl_setopt($curl,CURLOPT_COOKIE,$_COOKIE['mycookie']);
	curl_redir_exec($curl);
	if(curl_getinfo($curl,CURLINFO_HTTP_CODE)=='0') {
		msg(1);
	}
	else{ 
	// 登录失败
		msg(0);
		$_COOKIE['viewid']="";
	exit;
	}
	curl_close($curl); 
}
 ?>
