<?php
	if(isset($_POST['button'])) {
		include 'scauzf.php';
		$scau = new SCAUZF();
		$operation = $_POST['operation'];
		$result = $scau->init($_POST['username'], $_POST['password'], 1, $operation);
		echo $result;
	}