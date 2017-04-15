<?php
	include './po_core.php';
	include "./connect.php";

	session_start();

	//因为文章不多，人数不多 所以存入session是可以接受的
	//第一次访问的时候，将文章id存入session
	if(!isset($_SESSION["numAll"])){	//第一次访问
		$numFiveSql ="SELECT id FROM po_main WHERE length=5";
		$numSevenSql="SELECT id FROM po_main WHERE length=7";
		$numFive =$dbh->query($numFiveSql)->fetchAll(PDO::FETCH_COLUMN);
		$numSeven=$dbh->query($numSevenSql)->fetchAll(PDO::FETCH_COLUMN);
		$numAll  =array_merge($numFive,$numSeven);	//这是待选择的诗句的id,合并顺序不能改变

		//将所有诗句id存在session中
		$_SESSION["numFive"] =$numFive;
		$_SESSION["numSeven"]=$numSeven;
		$_SESSION["numAll"]  =$numAll;
		$_SESSION['sum']=count($numAll);

	} elseif (count($_SESSION['numAll'])==0) {	//答完题目了
		echoJson(['info'=>'恭喜您答完了所有的题目'],2);
		$numAll  =array_merge($numFive,$numSeven);	//重新开始答题
		die;
	}

	$numAll  =$_SESSION["numAll"];
	$numFive =$_SESSION["numFive"];
	$numSeven=$_SESSION["numSeven"];

	$allCount = count($numAll);
	$numFiveCount = count($numFive);
	$key = rand(0,$allCount);  //选择的诗句的key
	if($key < $numFiveCount){	//说明是5言的
		$searchFive=true;
		$searchSeven=false;
		$start=0;	//错误答案id索引的开始和结束
		$end=$numFiveCount;
	}else{
		$searchSeven=true;
		$searchFive=false;
		$start=$numFiveCount;	//错误答案id索引的开始和结束
		$end=$allCount;
	}

	$return=[];	//返回的信息
	//选择题目
	$contentSql="SELECT title,first,next,victory,defeated FROM po_main WHERE id={$numAll[$key]}";
	$content   =$dbh->query($contentSql)->fetch(PDO::FETCH_ASSOC);
	unset($numAll[$key]);	//在all中删除

	//随机选择4句古诗
	$selectedIdList=[];
	$sqlInString = '';
	for($i=0;$i<3;$i++){
		$id=rand($start,$end);
		while(in_array($id,$selectedIdList))
		{
			$id=rand($start,$end);
		}
		$sqlInString.=' ,'.$id;
	}
	//去掉第一个多余的逗号和空格
	$sqlInString=substr($sqlInString,2);

	$choiceSql ="SELECT next FROM po_main WHERE id IN (".$sqlInString.')';
	$result=$dbh->query($choiceSql)->fetchALL(PDO::FETCH_ASSOC);
	$choice=[];	//返回的诗句
	foreach ($result as $info) {
		$choice[]=$info['next'];
	}
	$answer = rand(0,3);
	array_splice($choice,$answer,0,$content['next']);
	if($content['defeated']==0){
		$percent=1;
	}else{
		$percent=$content['victory']/$content['defeated'];
	}

	$return=[
		'id'=>$key,
		'first'=>$content['first'],
		'next'=>$choice,
		'answer'=>$answer,
		'percent'=>(float)sprintf('%.2f',$percent)
	];

	echoJson($return,0);
