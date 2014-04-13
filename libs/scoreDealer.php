<?php
	function getPostArgsFromWeb($result) {
		preg_match('/(<input [\s\S]*?type\="hidden"[\s\S]*?name\="__VIEWSTATE"[\s\S]*?value\=")(.*?)(")/i', $result, $arr);
		return $arr[2];
	}