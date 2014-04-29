<?php
	
	function getArrayTest($data) {
		$html = str_get_html($data);
		$tdArr = array();
		$resultArr = array();
		$i = 0;
		foreach ($html->find('tr') as $trVal) {
			if($i == 0) {
				++$i;
			}else {
				foreach ($trVal->find('td') as $tdVal) {
					array_push($tdArr, $tdVal->innertext);
				}
				array_push($resultArr, $tdArr);
			}
		}

		return $resultArr;
	}