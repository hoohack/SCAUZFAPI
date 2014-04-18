<?php

	function printToPage($result) {
		
		foreach ($result as $val) {
			echo '<tr><td colspan="2" align="Center" width="14%">'.$val['dayofweeks'].'</td></tr><br>';
			if(isset($val['1,2'])) {
				echo '<tr><td align="Center" rowspan="2">'.$val['1,2'] . '</td></tr><br>';
			}
			if(isset($val['3,4'])) {
				echo '<td align="Center" rowspan="2">'.$val['3,4'] . '</td><br>';
			}
			if(isset($val['7,8'])) {
				echo '<td align="Center" rowspan="2">'.$val['7,8'] . '</td><br>';
			}
			if(isset($val['9,10'])) {
				echo '<td align="Center" rowspan="2">'.$val['9,10'] . '</td><br>';
			}
			if(isset($val['11,12'])) {
				echo '<td align="Center" rowspan="2">'.$val['11,12'] . '</td><br>';
			}
			if(isset($val['11,12,13'])) {
				echo '<td align="Center" rowspan="2">'.$val['11,12,13'] . '</td><br>';
			}
			echo "<br>";
		}
	}

	include './libs/autoload.php';
	if(isset($_POST['opbut'])) {
		switch ($_POST['operation']) {
			case 'lessonTable':
				$scauob = unserialize($_COOKIE['scauob']);
				$result = $scauob->init('lessonTable');
				// echo "<pre>";
				// print_r($result);
				// echo "</pre>";
				printToPage($result);
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