<?php
	include 'autoload.php';
	if(isset($_POST['opbut'])) {
		echo $_POST['operation'];
		switch ($_POST['operation']) {
			case 'lessonTable':
				$scauob = unserialize($_COOKIE['scauob']);
				$result = $scauob->init('lessonTable');
				echo $result;
				break;
			case "personalMsg":
				$scauob = unserialize($_COOKIE['scauob']);
				$result = $scauob->init('personalMsg');
				echo $result;
				break;
			case "checkScore":
				if($_POST['scoreop'] == 'allScore') {
					$scauob = unserialize($_COOKIE['scauob']);
					$result = $scauob->init('allScore');
					echo $result;
				}
				break;
			default:
				break;
		}
	}
?>