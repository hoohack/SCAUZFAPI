<?php

	function printToPage($result) {
		echo '<table border="1">';
		echo '<tr><td align="Center" width="2%"></td>';
		foreach ($result as $weekVal) {
			echo '<td align="Center" width="14%">'.$weekVal['dayofweeks'].'</td>';
		}
		echo '</tr>';

		echo '<tr>';
		echo '<td>1,2节</td>';
		foreach ($result as $val) {
			if(isset($val['1,2'])) {
				echo '<td align="Center">'.$val['1,2'] . '</td>';
			}
		}
		echo '</tr>';

		echo '<tr><td>3,4节</td>';
		foreach ($result as $tfval) {
			if(isset($val['3,4'])) {
				echo '<td align="Center">'.$tfval['3,4'] . '</td>';
			}
		}
		echo '</tr>';

		echo '<tr><td>7,8节</td>';
		foreach ($result as $val) {
			if(isset($val['7,8'])) {
				echo '<td align="Center">'.$val['7,8'] . '</td>';
			}
		}
		echo '</tr>';

		echo '<tr><td>9,10节</td>';
		foreach ($result as $val) {
			if(isset($val['9,10'])) {
				echo '<td align="Center">'.$val['9,10'] . '</td>';
			}
		}
		echo '</tr>';
		
		echo '<tr><td>11,12节</td>';
		foreach ($result as $val) {
			if(isset($val['11,12'])) {
				echo '<td align="Center">'.$val['11,12'] . '</td>';
			}else if(isset($val['11,12,13'])) {
				echo '<td align="Center">'.$val['11,12,13'] . '</td>';
			}
		}
		echo '</tr>';
		echo '</table>';
	}

	function StoreLesson($result) {
		$lesson_time = $lessonVal['dayofweeks'];
		$lesson_name = $lessonVal['lesson_name'];
		$teacher = $lessonVal['teacher'];
		$is_even = $lessonVal['is_even'];
		$room = $lessonVal['room'];
		$last_time = $lessonVal['1'];
		$student_id = $student;

		foreach ($result as $lessonVal) {
			$sql = "INSERT INTO `lesson` (l_id, lesson_time, lesson_name, teacher, is_even, room, last_time, student_id) 
						VALUES ('', '$lessonVal[dayofweeks]', '$lessonVal')";
		}
	}

	include './libs/autoload.php';
	if(isset($_POST['opbut'])) {
		switch ($_POST['operation']) {
			case 'lessonTable':
				$scauob = unserialize($_COOKIE['scauob']);
				$result = $scauob->init('lessonTable');
				echo "<pre>";
				print_r($result);
				echo "</pre>";
				printToPage($result);
				StoreLesson($result);
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