<?php
	function getPostArgsFromWeb($result) {
		preg_match('/(<input [\s\S]*?type\="hidden"[\s\S]*?name\="__VIEWSTATE"[\s\S]*?value\=")(.*?)(")/i', $result, $arr);
		return $arr[2];
	}

	function getArrayScore($result) {
		// echo $result;
		$html = str_get_html($result);

		$trArr = array();
		$resultArr = array();

		$scoreTable = $html->find('table[class="datelist"]')[0];
		$scoreTable = str_get_html($scoreTable);
		$i = 0;
		foreach ($scoreTable->find('tr') as $rows) {
			if($i == 0) {
				++$i;
			}else {
				foreach ($rows->find('td') as $tdVal) {
					if($tdVal->innertext == "") {
						$tdVal->innertext = "&nbsp;";
					}
					array_push($trArr, $tdVal->innertext);
				}
				array_push($resultArr, $trArr);
				unset($trArr);
				$trArr = array();
			}
		}

		// echo "<pre>";
		// print_r($resultArr);
		// echo "</pre>";

		return $resultArr;
	}