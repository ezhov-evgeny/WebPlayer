<?php
if(isset($_REQUEST['id'])){
	$ip = $_SERVER['REMOTE_ADDR'];
	require 'xmlparser.php';
	if($_REQUEST['tab'] == 'tab1'){
		$filename = 'rating1.json';
	}else if($_REQUEST['tab'] == 'tab2'){
		$filename = 'rating2.json';
	}else if($_REQUEST['tab'] == 'tab3'){
		$filename = 'rating3.json';
	}
	
	if(addRating($filename, $_REQUEST['id'], $_REQUEST['rating']))
		echo 'ok';
}