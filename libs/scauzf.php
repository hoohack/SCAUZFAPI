<?php
	/*
	*抓取华农教务处信息的API
	*用户根据API登陆之后再选择相应的操作
	*@author hhq
	*@date 2014/4/10
	*/
	
	//引入第三方文件，用于操作html
	include('./libs/simple_html_dom.php');
	
	class SCAUZF {
		//需要访问的url
		private $accessUrl;

		//跳转前url，防止302重定向
		private $beforeUrl;

		//POST发送的内容
		private $postContents;

		//登陆后的操作
		private $operation;

		//用户登陆的学号
		private $studentID;

		//密码
		private $password;

		//正方系统对于不同学生入口的隐藏参数
		private $zfParam;

		//cookie文件
		private $cookiefile;

		//用户姓名
		private $userName;

		//错误标记，判断用户是否登陆成功
		private $isError;

		//运行结果
		private $returnResult;

		/*构造函数
			@param $studentID(string), $password(string)
			@author hhq
			功能:设置学生学号，密码，学生入口，cookiefile命名
		*/
		public function __construct() {
			$this->cookiefile = 'temp.txt';
			$this->isError = false;
		}

		/*
			@author hhq
			功能:根据不同的入口设置需要访问的url和隐藏参数
		*/
		protected function setAccessUrl() {
			$this->accessUrl = 'http://202.116.160.170/';
			$this->zfParam = 'dDwtMTg3MTM5OTI5MTs7PgIWopWooBNLG0IJUQbwNWElYxSD';
		}

		/*
		*	@param $studentID(string), $accessUrl(string)
		*	@author hhq
		*	功能：根据学号设置beforeurl
		*/
		protected function setBeforeUrl($studentID, $accessUrl) {
			$this->beforeUrl = $accessUrl . 'xskbcx.aspx?xh=' . $studentID;
		}

		/*
		*	get 请求函数
		*	@param accessUrl(string), beforeUrl(string)
		*	@author hhq
		*	功能:返回请求结果
		*/
		protected function getRequest($accessUrl, $beforeUrl) {
			//初始化一个CURL会话
			$ch = curl_init($accessUrl);

			//设置将头文件的信息作为数据流输出
			curl_setopt($ch, CURLOPT_HEADER, 0);

			//设置跳转前的url，在HTTP请求头中"Referer: "的内容
			curl_setopt($ch, CURLOPT_REFERER, $beforeUrl);

			//在HTTP请求中包含一个"User-Agent: "头的字符串
			// curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

			//连接结束后保存cookie信息的文件
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiefile);

			//包含cookie数据的文件名，读取cookie内容
	        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiefile);

	        //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	        //将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量
	        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	        
	        //执行一个CURL会话
	        $result = curl_exec($ch);
	        
	        //关闭CURL会话
	        curl_close($ch);
	        return $result;
		}

		/*
			post 请求函数
			@param accessUrl(string),postContents(string), beforeUrl(string)
			@author hhq
			功能：发送一个post请求并返回请求结果
		*/
		protected function postRequest($accessUrl, $postContents, $beforeUrl) {
			$ch=curl_init();

			//终止从服务端进行验证
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			//同上
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

			//需要获取的URL地址
			curl_setopt($ch, CURLOPT_URL, $accessUrl);
			if(!($beforeUrl==="")) {
				curl_setopt($ch, CURLOPT_REFERER, $beforeUrl);
			}
			curl_setopt($ch, CURLOPT_HEADER, 0);
	  		// curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiefile );  
			curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiefile );
	  		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

			//说明会发送一个常规的POST请求
			curl_setopt($ch, CURLOPT_POST, 1);

			//设置使用HTTP协议中的"POST"操作来发送postContents
	   		curl_setopt($ch, CURLOPT_POSTFIELDS, $postContents);
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
		}

		/*
			@author hhq
			功能:获取含有验证码的url
		*/
		protected function getCodeUrl() {
			return $this->accessUrl . "default2.aspx";
		}

		/*
			@author hhq
			功能:获得post所需要的数据,包括所有参数和隐含参数
		*/
		protected function getPostContents() {
			$content = "TextBox1=" . $this->studentID . "&TextBox2=" . $this->password;
			$content = $content . "&RadioButtonList1=学生&TextBox3=&Button1=&lbLanguage=&__VIEWSTATE=" . $this->zfParam . "&__VIEWSTATEGENERATOR=92719903";
			return $content;
		}

		/*
			@author hhq
			功能:从登陆后的返回结果中获得用户名,并将用户名存储到数据库中
		*/
		protected function getUserName($postResult) {
			$message;
			$htmlContents = str_get_html($postResult);
			foreach($htmlContents->find('span[id="xhxm"]') as $row){
				$message=$row;
			}

			if(isset($message)) {
				$msgArr = explode(' ',$message);
			}

			
			if(isset($msgArr[1])){
				$this->isError = false;
			}else{
				$this->isError = true;
				unlink($this->cookiefile);
				trigger_error("login failed", E_USER_ERROR);
			}

			$this->userName = $msgArr['3'];
			$db = &load_class('Database');

			$selectSQL = "SELECT count(s_id) as id_count FROM `student` WHERE `s_id` = {$this->studentID}";

			//如果用户不存在于数据库则插入到数据库中
			if($db->fetch($selectSQL) !== false) {
				$result = $db->fetch($selectSQL);
				if($result['id_count'] == 0) {
					$param = array(":id" => "",
							":s_id" => $this->studentID,
							":s_name" => $this->userName);
			
					$insertSQL = "INSERT INTO `student` (id, s_id, s_name) VALUES (:id, :s_id, :s_name)";

					if(!$db->execute($insertSQL, $param)) {
						die($db->errorMessage);
					}
				}
			}

			$db->close();
		}

		/*
			@author hhq
			功能:获取课表
		*/
		protected function getLessonTable() {
			$lesson = &load_class('lesson');
			/*
				如果数据库中有课表就从数据库中获取课表
				否则就从页面中抓取课表并保存到数据库中
			*/
			if($lesson->existInDB($this->studentID)) {
				$result = $lesson->getTableFromDB($this->studentID);
				$this->returnResult = $result;
			}else {
				require_once("./libs/classDealer.php");
				$result = $this->getRequest($this->accessUrl . "xskbcx.aspx?xh=" . $this->studentID . "&xm=" . $this->userName . "&gnmkdm=N121603", $this->beforeUrl);
				$re = classDealer_init($result, 1);
				$lessonArrs = ReverseArray($re);
				$lesson->storeIntoDB($lessonArrs, $this->studentID);
				$this->returnResult = $lessonArrs;
			}
		}

		/*
			@author hhq
			功能:获取考试信息
		*/
		protected function getTest() {
			$testModel = &load_class('Test');
			
			if($testModel->existInDB($this->studentID)) {
				$result = $testModel->getTestFromDB($this->studentID);
			}else {
				$result = $this->getRequest($this->accessUrl . "xskscx.aspx?xh=" . $this->studentID . "&xm=" . $this->userName . "&gnmkdm=N121604", $this->beforeUrl);
				include 'testDealer.php';
				$result = getArrayTest($result);
				$testModel->storeIntoDB($result, $this->studentID);
			}

			$this->returnResult = $result;
		}

		/*
			@author hhq
			功能：获取考试成绩
		*/
		protected function getAllScore() {
			$score = &load_class('Score');

			if($score->existInDB($this->studentID)) {
				$resultArr = $score->getScoreFromDB($this->studentID);

				$this->returnResult = $resultArr;
			}else {
				$result = $this->getRequest($this->accessUrl . "xscjcx.aspx?xh=" . $this->studentID . "&xm=" . $this->userName . "&gnmkdm=N121605", $this->beforeUrl);
				require_once("./libs/scoreDealer.php");
				$arg=getPostArgsFromWeb($result);
				$arg=urlencode($arg);

				//获取历史成绩
				$scorePostData="__EVENTTARGET=&__EVENTARGUMENT=&__VIEWSTATE=" . $arg . "&__VIEWSTATEGENERATOR=9727EB43&hidLanguage=&ddlXN=&ddlXQ=&ddl_kcxz=&btn_zcj=历史成绩";
				
				$result = $this->postRequest($this->accessUrl . "xscjcx.aspx?xh=" . $this->studentID . "&xm=" . $this->userName . "&gnmkdm=N121605" , $scorePostData, $this->beforeUrl);
				$resultArr = getArrayScore($result);
				$score->storeIntoDB($resultArr, $this->studentID);
				$this->returnResult = $resultArr;
			}
		}

		/*
		*	@author hhq
		*	功能:获取某一学年信息
		*/
		protected function getYearScore($year = "") {
			$score = &load_class('score');

			//如果存在在数据库则从数据库中获取，否则从原页面中获取
			if($score->yearScoreExistInDB($this->studentID, $year)) {
				$result = $score->getYearScoreFromDB($this->studentID, $year);

				$this->returnResult = $result;
			}else {
				$result = $this->getRequest($this->accessUrl . "xscjcx.aspx?xh=" . $this->studentID . "&xm=" . $this->userName . "&gnmkdm=N121605", $this->beforeUrl);
				require_once("./libs/scoreDealer.php");
				$arg=getPostArgsFromWeb($result);
				$arg=urlencode($arg);
				
				//获取学年成绩
				$scorePostData="__EVENTTARGET=&__EVENTARGUMENT=&__VIEWSTATE=" . $arg . "&__VIEWSTATEGENERATOR=9727EB43&hidLanguage=&ddlXN=" . $year . "&ddlXQ=&ddl_kcxz=&btn_xn=学年成绩";
				
				$result = $this->postRequest($this->accessUrl . "xscjcx.aspx?xh=" . $this->studentID . "&xm=" . $this->userName . "&gnmkdm=N121605" , $scorePostData, $this->beforeUrl);
				$resultArr = getArrayScore($result);
				$this->returnResult = $resultArr;
			}
			
		}

		/*
		*	@author hhq
		*	功能：获取个人信息
		*/
		protected function getPersonalMsg() {
			$result = $this->getRequest($this->accessUrl . "xsgrxx.aspx?xh=" . $this->studentID . "&xm=" . $this->userName . "&gnmkdm=N121501", $this->beforeUrl);
			$this->returnResult = $result;
		}

		/*
			用户登陆后的操作的调度器
			@author hhq
			@param action(string)
			功能:根据action的值调用不同的操作
		*/
		protected function dispatcher($action, $year = "") {
			switch($action){
				case "lessonTable":{
					$this->getLessonTable();//获取课表
					break;
				}
				case "checkTest":{
					$this->getTest();//获取考试信息
					break;
				}
				case "allScore":{
					$this->getAllScore();//获取历年成绩
					break;
				}
				case "yearScore" : {
					$this->getYearScore($year);//获取学年成绩
					break;
				}
				case "personalMsg": {
					$this->getPersonalMsg();//获取个人信息
					break;
				}
				case "updateLessonTable": {
					$this->updateLessonTable();
					break;
				}
				default:{
					$this->isError = true;
					break;
				}
			}
		}

		/*
		*	对外接口
		*	@param studentID(string), password(string),action(string)
		*	@author hhq
		*	功能：用户提供学号，密码，学生入口和所需要进行的操作，返回运行结果
		*/
		public function init($action, $year = "") {
			if($this->isError) {
				unlink($this->cookiefile);
				trigger_error("login fialed", E_USER_ERROR);
			}else {
				$this->dispatcher($action, $year);
				return $this->returnResult;
			}
		}

		/*
		*	对外接口
		*	@author hhq
		*	@param studentID(string), password(string)
		*	功能:模拟用户登陆
		*/
		public function login($studentID, $password) {
			$this->studentID = $studentID;
			$this->password = $password;

			//设置要访问的url
			$this->setAccessUrl();

			//根据用户ID和url设置跳转前的url
			$this->setBeforeUrl($this->studentID, $this->accessUrl);

			//发送一个get请求
			$this->getRequest($this->accessUrl, "");

			//获得含有验证码的url
			$codeUrl = $this->getCodeUrl();

			//获取post的内容
			$this->postContents = $this->getPostContents();

			//发送一个post请求
			$postResult = $this->postRequest($this->accessUrl, $this->postContents, "");
			
			//获取用户名
			$this->getUserName($postResult);
		}

		/*
		*	@author hhq
		*	功能:更新课表
		*/
		protected function updateLessonTable() {
			$db = &load_class('Database');

			//删除原有课表
			$stu_id = $this->getUserID();

			$param = array(":stu_id" => $stu_id);
			$sql = "DELETE FROM `lesson`
					WHERE `stu_id` = :stu_id";
			if($db->execute($sql, $param)) {
				//重新获取课表
				$this->getLessonTable();
			}

			$db->close();
		}

		/*
		*	@author hhq
		*	@param propertyName(string)
		*	魔法函数,返回类成员变量
		*/
		public function __get($propertyName) {
			if($propertyName == 'isError') {
				return $this->isError;
			}
		}

		/*
		*	@author hhq
		*	@param propertyName(string)
		*	魔法函数，isset()函数判断私有变量是不是被定义时，自动调用__isset()
		*/
		public function __isset($propertyName) {
			return isset($this->$propertyName);
		}

		/*
		*	@author hhq
		*	析构函数
		*	功能:删除cookie文件
		*/
		public function __destruct() {
			if(file_exists($this->cookiefile)) {
				chmod($this->cookiefile, 0777);
				unlink($this->cookiefile);
			}
			if(file_exists('temp.txt')) {
				chmod('temp.txt', 0777);
				unlink('temp.txt');
			}
		}
	}
/*
	The end of the class
*/
