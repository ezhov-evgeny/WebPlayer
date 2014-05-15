<?php
	function pr($arr){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}

	function parsePL($mvalues) {
		for ($i=0; $i < count($mvalues); $i++)
			$tr[$mvalues[$i]["tag"]] = $mvalues[$i]["value"];
		return $tr;
	}
	
	function addRating($filename, $trID, $rating){
		$arr = jsonRead($filename);
		if(!empty($arr[$trID])){
			if(!isset($arr[$trID]['ip'][$_SERVER['REMOTE_ADDR']])){
				$arr[$trID]['sumrating'] += $rating;
				$arr[$trID]['votes']++;
				$arr[$trID]['rating'] = round($arr[$trID]['sumrating']/$arr[$trID]['votes'], 3);
				$arr[$trID]['ip'][$_SERVER['REMOTE_ADDR']] = $_SERVER['REMOTE_ADDR'];
			}else{
				return false;
			}
		}else{
			$arr[$trID]['sumrating'] = $rating;
			$arr[$trID]['votes'] = 1;
			$arr[$trID]['rating'] = $rating;
			$arr[$trID]['ip'][$_SERVER['REMOTE_ADDR']] = $_SERVER['REMOTE_ADDR'];
		}
		return jsonWrite($filename, $arr);
	}
	
	function jsonRead($filename){
		$fp = fopen($filename, 'r');
		$jsonArray = json_decode(fread($fp, filesize($filename)),true);
		fclose($fp);
		return $jsonArray;
	}

	function jsonWrite($filename, $jsonArray){
		$fp = fopen($filename, 'w');
		fwrite($fp, json_encode($jsonArray));
		fclose($fp);
		return true;
	}
	
	function readPlaylist($playlist/*, $ratinglist*/) {
		// читать xml
		
		$data = implode("",file($playlist));
		$parser = xml_parser_create();
		xml_parser_set_option($parser,XML_OPTION_CASE_FOLDING,0);
		xml_parser_set_option($parser,XML_OPTION_SKIP_WHITE,1);
		xml_parse_into_struct($parser,$data,$values,$tags);
		xml_parser_free($parser);

		// цикл по этим структурам
		foreach ($tags as $key=>$val) {
			if ($key == "track") {
				$trackranges = $val;
				// каждая пара вхождений массива это нижняя и верхняя
				// границы диапазона для определения каждой молекулы
				for ($i=0; $i < count($trackranges); $i+=2) {
					$offset = $trackranges[$i] + 1;
					$len = $trackranges[$i + 1] - $offset;
					$tdb[] = parsePL(array_slice($values, $offset, $len));
				}
			} else {
				continue;
			}
		}
		return $tdb;
	}
?>