<?php
include './po_core.php';
include "./connect.php";

$result=$dbh->query('SELECT data FROM po_tongji WHERE id=2')->fetch(PDO::FETCH_ASSOC);
print_r($result);