<?php
	/*
	*Score class
	*use it as model to deal with some database operation with score
	*@author hhq
	*/
	class Score {
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

		public function storeIntoDB($resultArr, $studentID) {
			$stu_id = $this->getUserID($studentID);

			foreach ($resultArr as $rows) {
				$s_year = $rows[0];
				$term = $rows[1];
				$course_code = $rows[2];
				$course_name = $rows[3];
				$course_property = $rows[4];
				$course_belong = $rows[5];
				$credit = $rows[6];
				$grade_point = $rows[7];
				$usually_score = $rows[8];
				$midterm_score = $rows[9];
				$final_score = $rows[10];
				$experiment_score = $rows[11];
				$score = $rows[12];
				$minor_flag = $rows[13];
				$makeup_score = $rows[14];
				$rebuilt_score = $rows[15];
				$course_college = $rows[16];
				$remark = $rows[17];
				$rebuilt_flag = $rows[18];

				$param = array(":id" => "",
								":s_year" => $s_year,
								":term" => $term,
								":course_code" => $course_code,
								":course_name" => $course_name,
								":course_property" => $course_property,
								":course_belong" => $course_belong,
								":credit" => $credit,
								":grade_point" => $grade_point,
								":usually_score" => $usually_score,
								":midterm_score" => $midterm_score,
								":final_score" => $final_score,
								":experiment_score" => $experiment_score,
								":score" => $score,
								":minor_flag" => $minor_flag,
								":rebuilt_score" => $rebuilt_score,
								":course_college" => $course_college,
								":remark" => $remark,
								":rebuilt_flag" => $rebuilt_flag,
								":stu_id" => $stu_id);

				$insertSQL = "INSERT INTO `score`
								(id, s_year, term, course_code, course_name, course_property, course_belong, credit,
									grade_point, usually_score, midterm_score, final_score, experiment_score,
									score, minor_flag, rebuilt_score, course_college, remark, rebuilt_flag, stu_id)
								VALUES(:id, :s_year, :term, :course_code, :course_name, :course_property, :course_belong, :credit,
									:grade_point, :usually_score, :midterm_score, :final_score, :experiment_score,
									:score, :minor_flag, :rebuilt_score, :course_college, :remark, :rebuilt_flag, :stu_id)";
				
				if($this->db->execute($insertSQL, $param) === false) {
					die($this->db->errorMessage);
				}
			}
		}

		protected function pushInArray($resultArrs) {
			$result = array();
			foreach ($resultArrs as $rows) {
				$arr = array();
				array_push($arr, $rows[1]);
				array_push($arr, $rows[2]);
				array_push($arr, $rows[3]);
				array_push($arr, $rows[4]);
				array_push($arr, $rows[5]);
				array_push($arr, $rows[6]);
				array_push($arr, $rows[7]);
				array_push($arr, $rows[8]);
				array_push($arr, $rows[9]);
				array_push($arr, $rows[10]);
				array_push($arr, $rows[11]);
				array_push($arr, $rows[12]);
				array_push($arr, $rows[13]);
				array_push($arr, $rows[14]);
				array_push($arr, $rows[15]);
				array_push($arr, $rows[16]);
				array_push($arr, $rows[17]);
				array_push($arr, $rows[18]);

				array_push($result, $arr);
				unset($arr);
			}

			return $result;
		}

		public function getScoreFromDB($studentID) {
			$stu_id = $this->getUserID($studentID);

			$selectSQL = "SELECT id, s_year, term, course_code, course_name, course_property, course_belong, credit,
									grade_point, usually_score, midterm_score, final_score, experiment_score,
									score, minor_flag, rebuilt_score, course_college, remark, rebuilt_flag, stu_id
						FROM `score`
						WHERE stu_id = {$stu_id}";
		
			$resultArrs = $this->db->fetchAll($selectSQL);

			$result = $this->pushInArray($resultArrs);		

			return $result;
		}

		public function getYearScoreFromDB($studentID, $year) {
			$stu_id = $this->getUserID($studentID);

			$selectSQL = "SELECT id, s_year, term, course_code, course_name, course_property, course_belong, credit,
									grade_point, usually_score, midterm_score, final_score, experiment_score,
									score, minor_flag, rebuilt_score, course_college, remark, rebuilt_flag, stu_id
						FROM `score`
						WHERE stu_id = {$stu_id} AND s_year = '{$year}'";
			
			$resultArrs = $this->db->fetchAll($selectSQL);

			$result = $this->pushInArray($resultArrs);

			return $result;
		}

		public function existInDB($studentID) {
			$stu_id = $this->getUserID($studentID);

			$selectSQL = "SELECT `id`
						FROM `score`
						WHERE stu_id = {$stu_id}";

			$selectArrs = $this->db->fetch($selectSQL);

			if($selectArrs !== false) {
				return true;
			}else {
				return false;
			}
		}

		public function yearScoreExistInDB($studentID, $year) {
			$stu_id = $this->getUserID($studentID);

			$selectSQL = "SELECT `id`
						FROM `score`
						WHERE stu_id = {$stu_id} AND s_year = '{$year}'";
			
			$selectRows = $this->db->fetch($selectSQL);
			if($selectRows !== false) {
				return true;
			}else {
				return false;
			}
		}

		public function __destruct() {
			//
		}
	}