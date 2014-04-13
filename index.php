<?php
	include 'scauzf.php';
	include 'autoload.php';

	if(isset($_POST['button'])) {
		$scau = &load_class('SCAUZF');
		$scau->login($_POST['username'], $_POST['password'], 1);
		if(isset($scau->isError) && $scau->isError == true) {
			trigger_error("login failed", E_USER_ERROR);
		}else if(isset($scau->isError)){
			$scauob = serialize($scau);
			setcookie("scauob", $scauob, time()+3600*24);
			redirect('./operation.html');
		}
	}
?>