<?php 
header("Content-type: text/html; charset=UTF-8"); 
include "f.php" ;
// 仪表盘
CheckKey(); 
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>个人中心</title>
	<link rel="stylesheet" href="home.css">
</head>
<body>
	<div class="function" >
		<div class="scoreInquiry"><a href="2.php">成绩查询</a></div>
		<br><div class="CurriculumInquiry"><a href="#">课程表查询</a></div>
		<br><div class="aboutUs"><a href="#">关于我们</a></div>
		<br><div class="PESnatch"><a href="3.php">体育抢课</a></div>

	</div>
</body>
</html>