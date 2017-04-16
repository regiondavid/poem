<?php
include './po_core.php';
include "./connect.php";
session_start();

//提交的信息
if(!isset($_POST['trueList']) || !isset($_POST['falseList'])){
	makeAndEchoWrongJson(1,'参数缺少');
	die;
}
$trueList = json_decode($_POST['trueList']);
$falseList = json_decode($_POST['falseList']); 

$trueCount = count($trueList);
$falseCount = count($falseList);
//参数不对,答对的题目不可能超过300
if(!is_array($trueList) || !is_array($falseList) || count($falseList) >3 || $trueCount > 300){
	makeAndEchoWrongJson(1,'传入参数测试不对');
	die;
}


if(!isset($_SESSION['numAll']) || $_SESSION['sum']-count($_SESSION['numAll'])<3){
	makeAndEchoWrongJson(1,'有毒，不排除你是一个坏孩子');
	die;
}


//更新每道题目的正确错误率

$victorySql='';
foreach($trueList as $id) {
	if(is_int($id)){
		$victorySql.=' ,'.$id;
	}
}
//去掉第一个多余的逗号和空格
$victorySql=substr($victorySql,2);

$defeatedSql='';
foreach($falseList as $id) {
	if(is_int($id)){
		$defeatedSql.=' ,'.$id;
	}
}
//去掉第一个多余的逗号和空格
$defeatedSql=substr($defeatedSql,2);
//更新数据库中的信息
$sql1='UPDATE po_main SET victory = victory+1 WHERE id in ('.$victorySql.')';
$dbh->exec($sql1);
$sql2='UPDATE po_main SET defeated = defeated+1 WHERE id in ('.$defeatedSql.')';
$dbh->exec($sql2);


//更新用户排行表
$rankCount = $dbh->query('SELECT count(*) AS count FROM po_user')->fetch(PDO::FETCH_ASSOC)['count'];
if($rankCount >= 10000){	//只存储一万条
	$dbh->exec('DELETE FROM po_user ORDER BY `TIME` LIMIT 100');	//删除100条
	$rankCount-=100;
}
$dbh->exec('INSERT INTO po_user (victory,`time`) VALUES ('.$trueCount.', '.time().')');


//计算超过的人百分比
$abovePercent = 'SELECT count(*) AS count FROM po_user WHERE victory < '.$trueCount;
$result = $dbh->query($abovePercent)->fetch(PDO::FETCH_ASSOC)['count'];
if($rankCount==0){
	$percent=0;
}else{
	$percent = $result/$rankCount;
}

//获得点赞数
$praiseNum = $dbh->query('SELECT data FROM po_tongji WHERE id=1')->fetch(PDO::FETCH_ASSOC)['data'];


$return=[
	'percent'=>(float)sprintf('%.2f',$percent),
	'praiseNum'=>$praiseNum
];

echoJson($return,0);


