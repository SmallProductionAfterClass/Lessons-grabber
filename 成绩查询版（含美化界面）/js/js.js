
var AjaxStr=function(doname,getStr){
        url = "server.php?inAjax=1&do=";
        url += doname + getStr; 
        return url;
    };  

var getCaptcha = function(){
	var url = AjaxStr("getCaptcha","&host="+$("#host").val());
    var data = "";
    var result = "";
    $.ajax({
        url: url,
        async: false,//改为同步方式
        type: "GET",
        data: data,
        success: function (res) {
            $("#verifyCode").attr("src",'verifyCode.jpg?t='+Math.random());
        }
    });
}

var authenticate = function(userName,passWord,captcha){
	var url = AjaxStr("login","")
    var data = {userName:userName,passWord:passWord,host:$("#host").val(),captcha:captcha};
    var result = "";
    $.ajax({
        url: url,
        async: false,//改为同步方式
        type: "POST",
        data: data,
        success: function (res) {
            result = res;
        }
    });
    return (JSON.parse(result)).msg;
}
var login = function(){
	
	var $userName = $("#loginUserName");
	var $passWord = $("#loginPassWord");
	var $captcha =  $("#captcha");
	if(authenticate($userName.val(),$passWord.val(),$captcha.val())==1){
		$passWord.parent().removeClass("am-form-error");
		$userName.parent().removeClass("am-form-error");
		window.location.href = "main.html";
		// 需要修改
	}	
	else {
		$passWord.parent().addClass("am-form-error");
		$userName.parent().addClass("am-form-error");
		getCaptcha();
		$("#captcha").val();
	}
}
var fillCETTable = function(list){
	var $table = $("#CETscorebox")
	$table.html("");
	$table.html("<table  class='tablesorter am-u-sm-center'><thead> <th>学年</th><th>学期</th><th>等级考试名称</th><th>准考证号</th><th>成绩</th><th>听力成绩</th><th>阅读成绩</th><th>写作成绩</th> </thead> <tbody> </tbody></table>");
	$tableContent = $("#CETscorebox table tbody");
	for (i=0; i <list[1].length-1 ; i++) 
		{
		var str = "<tr><td>"+list[1][i]+"</td><td>"+list[2][i]+"</td><td>"+list[3][i]+"</td><td>"+list[4][i]+"</td><td>"+list[6][i]+"</td><td>"+list[7][i]+"</td><td>"+list[8][i]+"</td><td>"+list[9][i]+"</td></tr>";

		$tableContent.append(str);
		}
	 $('table').tablesorter({

      theme: 'blackice',

      onRenderHeader: function (index) {
          // the span wrapper is added by default
          $(this).find('div.tablesorter-header-inner').addClass('roundedCorners');
      },

  	});
}
var fillTable = function(list){
	var $table = $("#scorebox")
	$table.html("");
	$table.html("<table  class='tablesorter am-u-sm-center'><thead> <th>课程名称</th><th>成绩</th><th>学分</th><th>补考成绩</th><th>重修成绩</th> </thead> <tbody> </tbody></table>");
	$tableContent = $("#scorebox table tbody");
	for (i=0; i <list[1].length-1 ; i++) 
		{
		var str = "<tr><td>"+list[2][i]+"</td><td>"+list[4][i]+"</td><td>"+list[8][i]+"</td><td>"+list[6][i]+"</td><td>"+list[7][i]+"</td></tr>";
		$tableContent.append(str);
		}
	 $('table').tablesorter({

      theme: 'blackice',

      onRenderHeader: function (index) {
          // the span wrapper is added by default
          $(this).find('div.tablesorter-header-inner').addClass('roundedCorners');
      },

  	});
}
var checkAllScore = function(){	
	var url = AjaxStr("checkAllScore","");
	fillTable(checkscore(url));		 
	}

var checkScoreByGrade =function(){
	var url = AjaxStr("checkScoreByGrade","&grade="+$("select.grade").val());
	fillTable(checkscore(url));	
}
var checkScoreByTerm = function(){
	var url = AjaxStr("checkScoreByTerm","&grade="+$("select.grade").val()+"&term="+$("select.term").val());
	fillTable(checkscore(url));	
}

var checkCETScore = function(){
	$(".checkScore").addClass("hidden");
	$(".checkCETScore").removeClass("hidden");
	$(".about").addClass("hidden");
	var url = AjaxStr("checkCETScore","");
	fillCETTable(checkscore(url));	
}
var about = function(){
	$(".checkScore").addClass("hidden");
	$(".checkCETScore").addClass("hidden");
	$(".about").removeClass("hidden");
}
var checkScore = function(){
	$(".checkScore").removeClass("hidden");
	$(".checkCETScore").addClass("hidden");
	$(".about").addClass("hidden");
}
var checkscore =function(url){
	    var result = "";
	    var data = {};
    $.ajax({
        url: url,
        async: false,//改为同步方式
        type: "GET",
        data: data,
        success: function (res) {
            result = res;
        }
    });
    return (JSON.parse(result));
}

//初始化
var isIndex;
var captchaValue=0;
var user = {
		'username': '',
    	'password': '',
    	'is_autologin':'',
    	'need_setup': '',
    	'lastname':'',
    	'lasthost':''
    };
var load = function(){
	if($("#loginTitle").length)isIndex = 1;
	else isIndex = 0;
	// loadDate();
	if(isIndex){
				LoadSettings();
			    ShowSettings();
			    setTimeout(getCaptcha,100);
				

		}
	else{
			$("#userName span").html(" "+document.cookie.match(new RegExp("(^| )schoolid=([^;]*)(;|$)"))[2]);

			}
			
}()

//登录页
$("#loginBtn").click(login);
$("#captcha").keydown(function(e){
  if (e.keyCode == 13) login();
})
$("#loginPassWord").keydown(function(e){
  if (e.keyCode == 13) $("#captcha").focus();
  
})
$("#loginUserName").keydown(function(e){
  if (e.keyCode == 13) $("#loginPassWord").focus();
})
$("#verifyCode").click(getCaptcha);

// 导入数据
	function LoadSettings(){
		user.username = localStorage.username;
    	user.password = localStorage.password;
    	user.lasthost = localStorage.lasthost;
    	localStorage.lastname = localStorage.username
    	user.is_autologin = parseInt(localStorage.is_autologin, 10) || 0;
    	if (user.is_autologin&&user.username && user.password && user.username != "undefined" && user.password != "undefined") {
    	    user.need_setup = false;
    	    $("#loginUserName").val(user.username);
    	    $("#loginPassWord").val(user.password);
    	    $("#captcha").focus();
    	    setInterval(function(){
				    // 有改变才进行比较
				        if(captcha!=$("#captcha").val())
				        {           
				            autologin();
				            captcha=$("#captcha").val();
				        }
				    
				    },200);
    	} else {
    	    user.need_setup = true;	  
    	}
    	getCaptcha();
	}
	//检查是否需要重新获取页面密钥
	function checkid(){
		if($("#loginUserName").val()!=localStorage.lastname )  
			 $("#loginPassWord").val("");
		else $("#loginPassWord").val(user.password);
	}
	function getCurrentName()
	{
		localStorage.username = $("#loginUserName").val();
		checkid();
	}
	function changehost(){
		 user.lasthost = $('#host').val();
		_save_user_settings();
		getCaptcha();
		setTimeout(getCaptcha,100);
	}
	//显示配置信息
	function ShowSettings() {

	        if (user.is_autologin) {
	            $('#auto').attr({checked: 'checked'});
	        }
	        if (user.lasthost) {
	            $('#host option[value="'+user.lasthost+'"]').attr({selected: 'selected'});
	        }

	        $('#loginBtn').click(function() {
	            user.username = $('#loginUserName').val();
	            user.password = $('#loginPassWord').val();
	            user.lasthost = $('#host').val();
	            user.lastname = user.username;
	            if ($('#auto').is(':checked')) {
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
	    console.log($("#captcha").val().length);
	    if($("#captcha").val().length==4)
	        {return true;}
	    return false;

	}
	// 检查是否可以按下登录了
	function autologin(){
	    if (user.is_autologin&&Captchaok()) {
	                $('#loginBtn').click();
	            }
	}
	function changeAuto(){
		if ($('#auto').is(':checked')) {
	                user.is_autologin = 1;
	            } else {
	                user.is_autologin = 0;;
	                user.username = "";
	            	user.password = "";
	            }
	    setInterval(function(){
				    // 有改变才进行比较
				        if(captcha!=$("#captcha").val())
				        {           
				            autologin();
				            captcha=$("#captcha").val();
				        }
				    
				    },200);
	            _save_user_settings();
	}
	



//主页面
$("#checkScoreByGradeBtn").click(checkScoreByGrade);
$("#checkScoreByTermBtn").click(checkScoreByTerm);
$("#checkAllScoreBtn").click(checkAllScore);
$("#exit>button").click(function(){location.replace(location.href.replace('main.html','index.html'));});
$("#checkCETScoreBtn").click(checkCETScore);
$("#checkScoreBtn").click(checkScore);
$("#aboutBtn").click(about);