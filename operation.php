<?php
	include './libs/autoload.php';
	if(isset($_POST['opbut'])) {
		switch ($_POST['operation']) {
			case 'lessonTable':
				$scauob = unserialize($_COOKIE['scauob']);
				$result = $scauob->init('lessonTable');
				$arrayResult = json_decode($result, true);
				echo "<pre>";
				print_r($arrayResult);
				echo "</pre>";
				break;
			case "personalMsg":
				$scauob = unserialize($_COOKIE['scauob']);
				$result = $scauob->init('personalMsg');
				echo $result;
				break;
			case "checkScore":
				if(isset($_POST['scoreop']) && $_POST['scoreop'] == 'allScore') {
					$scauob = unserialize($_COOKIE['scauob']);
					$result = $scauob->init('allScore');
					echo $result;
				}else if(isset($_POST['scoreop']) && ($_POST['scoreop'] == 'yearScore')
					&& isset($_POST['yearop']) && $_POST['yearop'] != ""){
					$scauob = unserialize($_COOKIE['scauob']);
					$result = $scauob->init('yearScore', $_POST['yearop']);
					echo $result;
				}else if(isset($_POST['scoreop']) && ($_POST['scoreop'] == 'yearScore') 
					&& isset($_POST['yearop']) && $_POST['yearop'] == "") {
					echo '<script type="text/javascript">alert("学年不能为空");window.history.back()</script>';
				}
				break;
			case "checkTest" : 
				$scauob = unserialize($_COOKIE['scauob']);
				$result = $scauob->init('checkTest');
				echo $result;
				break;
			default:
				break;
		}
	}
?>