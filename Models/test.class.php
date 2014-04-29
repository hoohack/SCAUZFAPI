<?php
	/*
	*Test class
	*use it as model to deal with some database operation with test
	*@author hhq
	*/

	class Test {
		private $db;

		public function __construct() {
			$this->db = &load_class('Database');
		}

		public function getUserID($studentID) {
			$selectSQL = "SELECT id 
						FROM `student` 
						WHERE `s_id` = {$studentID} LIMIT 1";
			$selectRows = $this->db->fetch($selectSQL);
			$stu_id = $selectRows['id'];

			return $stu_id;
		}

		public function getUserName($s_id) {
			$selectSQL = "SELECT `s_name`
							FROM `student`
							WHERE `id` = {$s_id}
							LIMIT 1";
			$selectRows = $this->db->fetch($selectSQL);
			$stu_name = $selectRows['s_name'];

			return $stu_name;
		}

		public function storeIntoDB($data, $studentID) {
			$s_id = $this->getUserID($studentID);
			$s_name = $this->getUserName($s_id);

			foreach ($data as $rows) {
				$course_code = $rows[0];
				$course_name = $rows[1];
				$test_time = $rows[3];
				$test_address = $rows[4];
				$test_form = $rows[5];
				$seat_number = $rows[6];
				$campus = $rows[7];

				$param = array(":id" => "",
								":course_code" => $course_code,
								":course_name" => $course_name,
								":s_name" => $s_name,
								":test_time" => $test_time,
								":test_address" => $test_address,
								":seat_number" => $seat_number,
								":campus" => $campus,
								":stu_id" => $s_id);
				$insertSQL = "INSERT INTO `test_msg`
								(id, course_code, course_name, s_name, test_time, test_form, test_address,
									seat_number, campus, stu_id)
								VALUES (:id, :course_code, :course_name, :s_name, :test_time, :test_address,
									:seat_number, :campus, :stu_id)";

				if(!$this->db->execute($insertSQL, $param)) {
					die($db->errorMessage);
				}
			}
		}

		public function getTestFromDB($studentID) {
			$stu_id = $this->getUserID($studentID);
			$testArrs = array();
			$selectSQL = "SELECT course_code, course_name, s_name, test_time, test_address, test_form,
							seat_number, campus
							FROM `test_msg`
							WHERE `stu_id` = {$stu_id}";

			$selectArrs = $this->db->fetchAll($selectSQL);
			if($selectArrs !== false) {
				$singleRow = array();
				foreach ($selectArrs as $rows) {
					$singleRow = array('course_code' => $rows['course_code'],
						'course_name' => $rows['course_name'],
						's_name' => $rows['s_name'],
						'test_time' => $rows['test_time'],
						'test_address' => $rows['test_address'],
						'test_form' => $rows['test_form'],
						'seat_number' => $rows['seat_number'],
						'campus' => $rows['campus']);
					array_push($testArrs, $singleRow);
				}
			}

			return $testArrs;
		}

		public function existInDB($studentID) {
			$stu_id = $this->getUserID($studentID);

			$selectSQL = "SELECT `id`
						FROM `test_msg`
						WHERE stu_id = {$stu_id}";

			$selectArrs = $this->db->fetch($selectSQL);

			if($selectArrs !== false) {
				return true;
			}else {
				return false;
			}
		}

		public function __destruct() {
			//
		}
	}

/*The end of the class*/