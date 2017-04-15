<?php
defined('POETRY_ROOT') or die('forbidden');

define("DSN","mysql:host=localhost;dbname=poetry");
define("DBUSER","root");
define("DBPASS","");

try{
    $dbh=new  PDO(DSN,DBUSER,DBPASS);
} catch (PDOException $exc){
    //设置状态码为500
    echo 'SERVER DATABASE ERROR';
    header('HTTP/1.1 500 Internal Server Error');
    die();
}

$dbh->exec("set names utf8");