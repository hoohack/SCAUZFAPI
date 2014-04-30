<?php
	/*
	*Lesson class
	*use it as model to deal with some database operation with lesson
	*@author hhq
	*/
	class Lesson {
		//db
		private $db;

		/*
		*construct function
		*load Database
		*@author hhq
		*/
		public function __construct() {
			$this->db = &load_class('Database');
		}

		/*
		*getUserID function
		*@param studentID string(用户ID)
		*获取用户ID
		*@author hhq
		*/
		protected function getUserID($studentID) {
			$selectSQL = "SELECT id 
						FROM `student` 
						WHERE `s_id` = {$studentID} LIMIT 1";
			$selectRows = $this->db->fetch($selectSQL);
			$stu_id = $selectRows['id'];

			return $stu_id;
		}

		/*
		*existInDB function
		*@param studentID string(学生学号)
		*判断数据库是否含有课表
		*@author hhq
		*/
		public function existInDB($studentID) {
			//获得用户ID
			$stu_id = $this->getUserID($studentID);

			$selectSQL = "SELECT `id`
						FROM `lesson`
						WHERE stu_id = {$stu_id}";

			$selectArrs = $this->db->fetch($selectSQL);

			if($selectArrs !== false) {
				return true;
			}else {
				return false;
			}
		}

		/*
		*storeInDB function
		*@param lessonArr array 存储课表的数组
		*@param studentID string 学生学号
		*将存储课表的数组存储到数据库中
		*@author hhq
		*/
		public function storeIntoDB($lessonArrs, $studentID) {
			$stu_id = $this->getUserID($studentID);

			$i = 0;
			foreach ($lessonArrs as $lessonVal) {
				foreach ($lessonVal as $val) {
					if($i == 0) {
						$dayofweeks = $val;
						++$i;
					}else {
						$lesson_time = array_keys($lessonVal)[$i];
						$lesson_msg = $val;
						$param = array(
									":id" => "",
									":dayofweeks" => $dayofweeks,
									":lesson_time" => $lesson_time,
									":lesson_msg" => $lesson_msg,
									":stu_id" => $stu_id);
						$insertSQL = "INSERT INTO `lesson`
										(id, dayofweeks, lesson_time, lesson_msg, stu_id)
										VALUES (:id, :dayofweeks, :lesson_time, :lesson_msg, :stu_id)";
						if(!$this->db->execute($insertSQL, $param)) {
							die($this->db->errorMessage);
						}else {
							++$i;
						}
					}
				}
				$i = 0;
			}
		}

		/*
		*getTableFromDB function
		*@param studentID string (学生学号)
		*从数据库中获取课表
		*@author hhq
		*/
		public function getTableFromDB($studentID) {
			$weekdays = array('星期一', '星期二', '星期三', '星期四', '星期五');
			$times = array('1,2', '3,4', '7,8', '9,10', '11,12', '11,12,13');
			$stu_id = $this->getUserID($studentID);

			$tableArr = array();
			$lessonArr = array();

			foreach ($weekdays as $weekVal) {
				foreach ($times as $timeVal) {
					$lessonArr['dayofweeks'] = $weekVal;
					$selectSQL = "SELECT dayofweeks, lesson_time, lesson_msg 
								FROM `lesson`
								WHERE `stu_id` = {$stu_id} AND `lesson_time` = '{$timeVal}' AND `dayofweeks` = '{$weekVal}'
								LIMIT 1";
					$selectArrs = $this->db->fetch($selectSQL);

					if($selectArrs !== false) {
						if(count($selectArrs) != 0) {
							$lessonArr[$timeVal] = $selectArrs['lesson_msg'];
						}
					}
				}
				//push each lesson into tableArr
				array_push($tableArr, $lessonArr);
				unset($lessonArr);
				$lessonArr = array();
			}
			
			return $tableArr;
		}

		/*
		*destruct function
		*close db connection
		*@author hhq
		*/
		public function __destruct() {
			$this->db->close();
		}
	}

/*The end of the class*/