<?php
	if(isset($_POST['button'])) {
		include 'scauzf.php';
		$scau = new SCAUZF();
		$operation = $_POST['operation'];
		$scau->login($_POST['username'], $_POST['password'], 1);
		redirect('operation.php')
		$result = $scau->init($operation);
		echo $result;
	}