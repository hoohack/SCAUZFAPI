<?php
	/*
	 *传进参数为课表html代码
	 *返回数据课与课之间以$分割
	 *返回数据课中的内容以#分割
	*/

	//对外接口，传进参数为课表html
	// include 'simple_html_dom.php';
	function classDealer_init($data,$type){
		if($type === 2){
			return getClassText($data);
		}else{
			return getClassHtml($data);
		}
		
	}

	function classDealer_array($data) {
		$html = str_get_html($data);
		$lessonArr = array();
		foreach ($html->find('td[rowspan="2"]') as $val) {
			preg_match('/(<td [\s\S]*?align\="Center"[\s\S]*?rowspan\="2"[\s\S]*?(width\="7%")?>)(.*?)(<\/td>)/i', $val, $arr);
			$arrMsg = explode("<br>", $arr[3]);
			$lessonMsg = array();
			$lessonMsg['lessonName'] = $arrMsg[0];
			$lessonMsg['lessonIndex'] = $arrMsg[1];
			$lessonMsg['teacherName'] = $arrMsg[2];
			$lessonMsg['classRoom'] = $arrMsg[3];
			$lessonMsg['weekDays'] = substr($arrMsg[1], 0, 6);
			array_push($lessonArr, $lessonMsg);
		}
		return json_encode($lessonArr);
	}

	function ReverseArray($data) {
		$html = str_get_html($data);
		$lessonArr = array();
		$tdArr = array();
		$trArr = array();
		$count = 0;
		foreach ($html->find('tr') as $trval) {
			foreach ($trval->find('td') as $tdVal) {
				array_push($tdArr, $tdVal->innertext);
			}
			array_push($trArr, $tdArr);
			unset($tdArr);
			$count = 0;
			$tdArr = array();
			array_push($lessonArr, $trval->innertext);
		}
		$index = 0;
		foreach ($trArr as $trval) {
			if(count($trArr[$index]) <= 7) {
				array_splice($trArr, $index, 1);
				--$index;
			}
			else if(count($trArr[$index]) == 8) {
				array_splice($trArr[$index], 0, 1);
			}else if(count($trArr[$index]) == 9) {
				array_splice($trArr[$index], 0, 2);
			}
			++$index;
		}
		array_splice($trArr, 1, 1);
		array_splice($trArr, 3, 2);
		$reverseArr = array();
		$weekdays = array('星期一', '星期二', '星期三', '星期四', '星期五');
		for($i = 0; $i < count($trArr); ++$i) {
			for($j = 0; $j < count($trArr[$i]); ++$j) {
				$reverseArr[$j][$i] = $trArr[$i][$j];
			}
		}

		array_splice($reverseArr, 5, 2);
		$result = array();
		for($i = 0; $i < 5; ++$i) {
			$reverseArr[$i]['dayofweeks'] = $reverseArr[$i][0];
			$reverseArr[$i]['1,2'] = $reverseArr[$i][1];
			$reverseArr[$i]['3,4'] = $reverseArr[$i][2];
			$reverseArr[$i]['7,8'] = $reverseArr[$i][3];
			$reverseArr[$i]['9,10'] = $reverseArr[$i][4];
			if($reverseArr[$i][5] != '&nbsp;') {
				$arr = explode("<br>", $reverseArr[$i][5]);
				$tempVal = substr($arr[1], 4, 1);
				if(is_numeric($tempVal)) {
					if(substr($arr[1], 12, 1) == '2') {
						$reverseArr[$i]['11, 12'] = $reverseArr[$i][5];
					}else {
						$reverseArr[$i]['11, 12, 13'] = $reverseArr[$i][5];
					}
				}else {
					if(substr($arr[1], 11, 1) - substr($arr[1], 9, 1) == 2) {
						$reverseArr[$i]['11, 12'] = $reverseArr[$i][5];
					}else {
						$reverseArr[$i]['11, 12, 13'] = $reverseArr[$i][5];
					}
				}
			}else {
				$reverseArr[$i]['11, 12'] = $reverseArr[$i][5];
			}
			
			array_splice($reverseArr[$i], 0, 7);
		}

		return $reverseArr;
	}

	//获取html课表
	function getClassHtml($data){
		//html课表css内容
		$cssText="<style>.blacktab{border-collapse: collapse;width: 100%;margin: 2px auto;}table,tr,td{border:1px solid black;}</style>";
		$html=str_get_html($data);
		foreach($html->find('table[id="Table1"]') as $row1){
			$m = $row1;
		}
		$data=$m;
		return "<!DOCTYPE html><html><head>" . $cssText . "</head><body>" . $data ."</body></html>";
	}

	//获取纯文本课表
	function getClassText($data){
		$html=str_get_html($data);
		foreach($html->find('table[id="Table1"]') as $row1){
			$m=$row1;
		}
		$re = classDealer_getTr($m->outertext);
		
		return $re;
	}

	//以tr标签进行分割
	function classDealer_getTr($data){
		$r="";
		$html =str_get_html($data);
		foreach($html->find('td[rowspan="2"]') as $row1){
			$re= classDealer_getAllLeason($row1->innertext);
			
			$r = $r . $re . "$";
		}
		foreach($html->find('td[rowspan="3"]') as $row1){
			$re= classDealer_getAllLeason($row1->innertext);
			$r = $r . $re . "$";
		}
		return $r;
		
	}

	//把课表的每节课分割
	function classDealer_getAllLeason($data){
		$r="";
		$s=explode("<br>",$data);
		$count=0;
		foreach($s as $row1){
			$r=$r . $row1 ;
			if($count!=3&&$count!=8)
			$r=$r . "#";
			$count++;
		}
		return $r;
	}
	
?>
