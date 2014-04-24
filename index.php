<?php
	/*
	*index page
	*login to do other things
	*/
	include './libs/autoload.php';

	if(isset($_POST['button'])) {
		$scau = &load_class('SCAUZF');
		$entrance = $_POST['entrance'];
		$scau->login($_POST['username'], $_POST['password'], $entrance);

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