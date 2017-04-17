<?php
include './po_core.php';
include "./connect.php";


$praiseSql="UPDATE po_tongji SET data=data+1 WHERE id =1";
$dbh->exec($praiseSql);

$sql = 'SELECT data FROM po_tongji WHERE id = 1';
$result = $dbh->query($sql)->fetch(PDO::FETCH_ASSOC);
echoJson(['praiseNum'=>$result['data'] ],0);