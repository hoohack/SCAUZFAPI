<?php
	/*
	*User class
	*use it as model to deal with some database operation with user
	*@author hhq
	*/
	
	class User {
		//db
		private $db;

		/*
		*construct function
		*load database
		*@author hhq
		*/
		public function __construct() {
			$this->db = &load_class('Database');
		}

		/*
		*getUserID function
		*@param studentID string (学生学号)
		*获取用户ID
		*@author hhq
		*/
		public function getUserID($studentID) {
			$db = &load_class('Database');
			
			$selectSQL = "SELECT id 
						FROM `student` 
						WHERE `s_id` = {$studentID} LIMIT 1";
			$selectRows = $this->db->fetch($selectSQL);
			$stu_id = $selectRows['id'];

			return $stu_id;
		}

		/*
		*析构函数
		*/
		public function __destruct() {
			// $this->db->close();
		}
	}

/*The end of this class*/