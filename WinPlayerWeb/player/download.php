<?php
if(isset($_REQUEST['id'])){
	$playlist1 = 'playlist1.xml';
	$playlist2 = 'playlist2.xml';
	$playlist3 = 'playlist3.xml';

	
	// ���������� ������
	require 'xmlparser.php';
	
	// ��������� ���������
	$arTrackLists[] = readPlaylist($playlist1);
	$arTrackLists[] = readPlaylist($playlist2);
	$arTrackLists[] = readPlaylist($playlist3);

	
	foreach($arTrackLists as $arTrackList)
		foreach($arTrackList as $arTrack)
			if($arTrack['id'] == $_REQUEST['id'])
				$path = $arTrack['location'];
				
	if(isset($path)){

		$parts = explode("/", $path);
	    $path_true =  $_SERVER['DOCUMENT_ROOT'];
	    for ($t=3;$parts[$t];$t++){
		    $path_true .= '/'.$parts[$t];
	    }
	
		if(!($handle = fopen($path, "r"))){
			echo "File failed to open";
		}else{
			header("Content-disposition: attachment; filename=".$parts[count($parts) - 1]);
			header("Content-Type: audio/mpeg");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: " . (string)(filesize($path_true)));
			header("Pragma: no-cache");
			header("Expires: 0");

			while (!feof($handle)) {
			  echo fread($handle, 8192);
                            echo fread($handle, 8192);
			}

			fclose($handle);
		}

	}else{
		echo 'Error';
	}
}

