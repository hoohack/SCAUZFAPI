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
