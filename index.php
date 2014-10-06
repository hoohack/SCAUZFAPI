<?php
	/*
	*index page
	*login to do other things
	*/
	include './libs/autoload.php';

	if(isset($_POST['button'])) {
		$scau = &load_class('SCAUZF');
		$scau->login($_POST['username'], $_POST['password']);

		if(isset($scau->isError)) {
			if($scau->isError == true) {
				trigger_error("login failed", E_USER_ERROR);
			}else if(($scau->isError == false)){
				//将对象序列化后保存到cookie中,为了实现所有页面都能访问
				$scauob = serialize($scau);
				setcookie("scauob", $scauob, time()+3600*24);
				redirect('./operation.html');
			}else {
				echo "error";
			}
		}
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="stylesheets/login.css" />
	</head>
	<body>
		<title>获取正方信息</title>
		<div class="login-area">
			<div class="login-title">登录正方系统</div>
		   	<div class="login-form">
			    <form id="loginform" name="loginform" method="post" action="index.php">
				    <label name="usernamelabel" for="username">学 号 </label>
				    <input type="text" name="username"  /><br>
				    <label name="passwordlabel" for="password">密 码 </label>
					<input type="password" name="password" /><br>
				    <input type="submit" name="button" id="button" value="登录" />
			    </form>
	    	</div>
    	</div>
    </body>
</html>