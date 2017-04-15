<?php
include './po_core.php';
include "./connect.php";

	session_start();

	if(!isset($_SESSION['praise'])){
		$praiseSql="UPDATE po_tongji SET data=data+1 WHERE id =1";
		$dbh->exec($praiseSql);
		$_SESSION['praise']=1;
	}

	$sql = 'SELECT data FROM po_tongji WHERE id = 1';
	$result = $dbh->query($sql)->fetch(PDO::FETCH_ASSOC);
	echoJson(['praiseNum'=>$result['data'] ],0);