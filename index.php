<?php
/*	if(!@$_SESSION['index']){
		$dom=new DOMDocument('0.1','utf-8');
		$filePath="./page-view.xml";
		$dom->load($filePath);
		$get=$dom->childNodes->item(0);
		$get->nodeValue=$get->nodeValue+1;
		$dom->save($filePath);
	}*/
//访问量+1
include('./po_core.php');
include('./connect.php');
$pvSql = 'UPDATE po_tongji SET data = data+1 WHERE id = 2';
$dbh->exec($pvSql);
?>

这是放首页静态资源的地方
