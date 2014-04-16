<?php
	/*
	*抓取华农教务处信息的API
	*用户根据API登陆之后再选择相应的操作
	@author hhq
	@date 2014/4/10
	*/
	
	//引入第三方文件，用于操作html
	include('./libs/simple_html_dom.php');
	
	class SCAUZF {
		//需要访问的url
		var $accessUrl;

		//跳转前url，防止302重定向
		var $beforeUrl;

		//POST发送的内容
		var $postContents;

		//学生入口，对应正方系统的1,2,3,4
		var $entrance;

		//登陆后的操作
		var $operation;

		//用户登陆的学号
		var $studentID;

		//密码
		var $password;

		//正方系统不同学生入口的隐藏参数
		var $zfParam;

		//cookie文件
		var $cookiefile;

		//用户姓名
		var $userName;

		//错误标记，判断用户是否登陆成功
		var $isError;

		//运行结果
		var $returnResult;

		private static $_instance = NULL;

		/*构造函数
			@param $studentID(string), $password(string), $entrance(int)
			设置学生学号，密码，学生入口，cookiefile命名
		*/
		public function __construct($entrance = 1) {
			$this->entrance = $entrance;
			$this->cookiefile = 'temp.txt';
			$this->isError = false;
		}

		/*
			根据不同的入口设置访问的url，并根据不同的入口设置隐藏参数
		*/
		protected function setAccessUrl() {
			switch ($this->entrance) {
				case '1':
					$this->accessUrl = 'http://202.116.160.166/';
					$this->zfParam = 'dDwtMTg3MTM5OTI5MTs7Pm3EYMABeWjEprmuXse/oURhr5WV';
					break;
				case '2':
					$this->accessUrl = 'http://202.116.160.174/';
					$this->zfParam = 'dDwtMTg3MTM5OTI5MTs7PtWSKRNBLWWjCjYZdgnYOO7NxHv4';
					break;
				case '3':
					$this->accessUrl = 'http://202.116.160.173/';
					$this->zfParam = 'dDwtMTg3MTM5OTI5MTs7PpThNct/WCRJmqE0Bbet1xB2o04M';
					break;
				case '4':
					$this->accessUrl = 'http://202.116.160.167/';
					$this->zfParam = 'dDwtMTg3MTM5OTI5MTs7PiXqg0GwJxzn4SLMWMrOOoJJHvHk';
					break;
				default:
					break;
			}
		}

		/*
			根据学号设置beforeurl
			@param $studentID(string), $accessUrl(string)
		*/
		protected function setBeforeUrl($studentID, $accessUrl) {
			$this->beforeUrl = $accessUrl . 'xskbcx.aspx?xh=' . $studentID;
		}

		/*
			get 请求
			@param accessUrl(string), beforeUrl(string)
			返回请求结果
		*/
		protected function getRequest($accessUrl, $beforeUrl) {
			$ch = curl_init($accessUrl);	//初始化一个cURL会话
			curl_setopt($ch, CURLOPT_HEADER, 0);//设置将头文件的信息作为数据流输出
			curl_setopt($ch, CURLOPT_REFERER, $beforeUrl);//设置跳转前的url，在HTTP请求头中"Referer: "的内容
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);//在HTTP请求中包含一个"User-Agent: "头的字符串
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiefile);//连接结束后保存cookie信息的文件
	        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiefile);//包含cookie数据的文件名，读取cookie内容
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	//将curl_exec()获取的信息以文件流的形式返回，而不是直接输出
	        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);	//将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量
	        $result = curl_exec($ch);	//执行一个cURL会话
	        curl_close($ch);			//关闭一个cURL会话
	        return $result;
		}

		/*
			post 请求
			@param accessUrl(string),postContents(string), beforeUrl(string)
			返回请求结果
		*/
		protected function postRequest($accessUrl, $postContents, $beforeUrl) {
			$ch=curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);//终止从服务端进行验证
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);//同上
			curl_setopt($ch, CURLOPT_URL, $accessUrl);//需要获取的URL地址
			if(!($beforeUrl==="")) {
				curl_setopt($ch, CURLOPT_REFERER, $beforeUrl);
			}
			curl_setopt($ch, CURLOPT_HEADER, 0);
	  		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiefile );  
			curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiefile );
	  		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_POST, 1);	//说明会发送一个常规的POST请求
	   		curl_setopt($ch, CURLOPT_POSTFIELDS, $postContents);//使用HTTP协议中的"POST"操作来发送postContents
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
		}

		/*
			获取含有验证码的url
		*/
		protected function getCodeUrl() {
			return $this->accessUrl . "default2.aspx";
		}

		/*
			获得post所需要的数据,包括所有参数和隐含参数
		*/
		protected function getPostContents() {
			$content="TextBox1=" . $this->studentID . "&TextBox2=" . $this->password;
			$content=$content . "&RadioButtonList1=学生&TextBox3=&Button1=&lbLanguage=&__VIEWSTATE=" . $this->zfParam . "&__VIEWSTATEGENERATOR=92719903";
			return $content;
		}

		/*
			从登陆后的返回结果中获得用户名
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

			$this->userName = $msgArr[1];
		}

		/*
			获取课表
		*/
		protected function getLessonTable() {
			require_once("./libs/classDealer.php");
			$result = $this->getRequest($this->accessUrl . "xskbcx.aspx?xh=" . $this->studentID . "&xm=" . $this->userName . "&gnmkdm=N121603", $this->beforeUrl);
			
			$re = classDealer_init($result, 1);
			$result = classDealer_array($re);
			// $result = $re;
			$this->returnResult = $result;
		}

		/*
			获取考试信息
		*/
		protected function getTest() {
			$result = $this->getRequest($this->accessUrl . "xskscx.aspx?xh=" . $this->studentID . "&xm=" . $this->userName . "&gnmkdm=N121604", $this->beforeUrl);
			$this->returnResult = $result;
		}

		/*
			获取考试成绩
		*/
		protected function getAllScore() {
			$result = $this->getRequest($this->accessUrl . "xscjcx.aspx?xh=" . $this->studentID . "&xm=" . $this->userName . "&gnmkdm=N121605", $this->beforeUrl);
			require_once("./libs/scoreDealer.php");
			$arg=getPostArgsFromWeb($result);
			$arg=urlencode($arg);
			//获取历史成绩
			$scorePostData="__EVENTTARGET=&__EVENTARGUMENT=&__VIEWSTATE=" . $arg . "&__VIEWSTATEGENERATOR=9727EB43&hidLanguage=&ddlXN=&ddlXQ=&ddl_kcxz=&btn_zcj=历史成绩";
			
			$result = $this->postRequest($this->accessUrl . "xscjcx.aspx?xh=" . $this->studentID . "&xm=" . $this->userName . "&gnmkdm=N121605" , $scorePostData, $this->beforeUrl);
			$this->returnResult = $result;
		}

		protected function getYearScore($year = "") {
			$result = $this->getRequest($this->accessUrl . "xscjcx.aspx?xh=" . $this->studentID . "&xm=" . $this->userName . "&gnmkdm=N121605", $this->beforeUrl);
			require_once("./libs/scoreDealer.php");
			$arg=getPostArgsFromWeb($result);
			$arg=urlencode($arg);
			//获取历史成绩
			$scorePostData="__EVENTTARGET=&__EVENTARGUMENT=&__VIEWSTATE=" . $arg . "&__VIEWSTATEGENERATOR=9727EB43&hidLanguage=&ddlXN=" . $year . "&ddlXQ=&ddl_kcxz=&btn_xn=学年成绩";
			// var_dump($scorePostData);
			$result = $this->postRequest($this->accessUrl . "xscjcx.aspx?xh=" . $this->studentID . "&xm=" . $this->userName . "&gnmkdm=N121605" , $scorePostData, $this->beforeUrl);
			$this->returnResult = $result;
		}

		protected function getPersonalMsg() {
			$result = $this->getRequest($this->accessUrl . "xsgrxx.aspx?xh=" . $this->studentID . "&xm=" . $this->userName . "&gnmkdm=N121501", $this->beforeUrl);
			$this->returnResult = $result;
		}

		/*
			用户登陆后的操作的调度器,根据action的值调用不同的操作
			@param action(string)
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
					$this->getAllScore();//获取考试成绩
					break;
				}
				case "yearScore" : {
					$this->getYearScore($year);
					break;
				}
				case "personalMsg": {
					$this->getPersonalMsg();
					break;
				}
				default:{
					$this->isError = true;
					break;
				}
			}
		}

		/*
			对外接口
			用户提供学号，密码，学生入口和所需要进行的操作，返回运行结果
			@param studentID(string), password(string), entrance(int), action(string)
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
			对外接口
			模拟登陆
			@param studentID(string), password(string), entrance(int)
		*/
		public function login($studentID, $password, $entrance) {
			$this->studentID = $studentID;
			$this->password = $password;
			$this->entrance = $entrance;

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

		public function __get($propertyName) {
			if($propertyName == 'isError') {
				return $this->isError;
			}
		}

		//析构函数,删除cookie文件
		public function __destruct() {
			if(file_exists($this->cookiefile)) {
				unlink($this->cookiefile);
			}
			if(file_exists('temp.txt')) {
				unlink('temp.txt');
			}
		}
	}
/*
	end of the class
*/
