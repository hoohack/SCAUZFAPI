<?php
	if(isset($_POST['button'])) {
		include 'scauzf.php';
		$scau = new SCAUZF();
		$result = $scau->init($_POST['username'], $_POST['password'], 1, 'lessonTable');
		echo $result;
	}