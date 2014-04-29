<?php
	/*
	*User class
	*use it as model to deal with some database operation with user
	*@author hhq
	*/
	
	class User {
		private $db;

		public function __construct() {
			$this->db = &load_class('Database');
		}

		public function getUserID($studentID) {
			$db = &load_class('Database');
			
			$selectSQL = "SELECT id 
						FROM `student` 
						WHERE `s_id` = {$studentID} LIMIT 1";
			$selectRows = $this->db->fetch($selectSQL);
			$stu_id = $selectRows['id'];

			return $stu_id;
		}

		public function __destruct() {
			// $this->db->close();
		}
	}

/*The end of this class*/