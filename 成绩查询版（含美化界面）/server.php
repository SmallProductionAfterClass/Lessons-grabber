<?php 
header("Content-Type:text/html;charset=utf-8");
$do = $_GET['do'];
$inAjax = $_GET['inAjax'];
$host = $_GET['host'];
$captcha = $_POST['captcha'];
$userName = $_POST['userName'];
$passWord = $_POST['passWord'];
if(!$inAjax) return false;
$hostarr = array("","http://jwgl.gdut.edu.cn/","http://jwgldx.gdut.edu.cn/");
include_once "function.php";
$do = $do?$do:"default";
session_start();
switch($do){
	case "getCaptcha":
		$viewid = initck($hostarr[$host]);
		getImg($hostarr[$host]);
	break;
	case "login":
		login($userName,$passWord,$hostarr[$_POST['host']],$_POST['captcha']);
		setcookie('host',$_POST['host']);
		break;
	case "checkAllScore":
		getstate(makeUrl(4,$hostarr[$_COOKIE['host']]),"cxcjId",0,$hostarr[$_COOKIE['host']]);
	    checkScore(0,$hostarr[$_COOKIE['host']]);
	    break;
	case "checkScoreByGrade":
		getstate(makeUrl(4,$hostarr[$_COOKIE['host']]),"cxcjId",0,$hostarr[$_COOKIE['host']]);
		
	break;
	case "checkScoreByTerm":
		getstate(makeUrl(4,$hostarr[$_COOKIE['host']]),"cxcjId",0,$hostarr[$_COOKIE['host']]);
		checkScore(2,$hostarr[$_COOKIE['host']],$_GET['grade'],$_GET['term']);
	break;
	case "checkCETScore":
		checkCETScore($hostarr[$_COOKIE['host']]);
	break;
	default:echo("接收到请求:".$do);
	break;
}
 ?>