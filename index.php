<?php
//访问量+1
include('./po_core.php');
include('./connect.php');
$pvSql = 'UPDATE po_tongji SET data = data+1 WHERE id = 2';
$dbh->exec($pvSql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>poetry</title>
    <meta charset="utf-8" />
    <meta name="viewport" content=" width=device-width, height=device-height, initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0, minimal-ui,user-scalable=no">
    <link rel="stylesheet" href="layout/style.css" >
</head>
<body>
    <div id="shareTitle">别来春半，晓看才子赏诗词。我在这里（XX猜诗词）答对了<span id="shareResult">0</span>句诗词，击败了全球<span id="sharePeople"></span>的人，一起来玩玩吧！</div>
    <div class="container">
        <div class="main">
            <img src="image/begin.jpg">
            <img src="image/answer.jpg">
            <div id="box">
                <div id="topbox">
                    <div id="right-rate">此题正确率：<span id="perRight">0</span></div>
                    <div id="top1" class="top">云想衣裳花想容</div>
                    <div id="lead" class="top hidden1"></div>
                </div>
                <div id="botbox" class="hidden1">
                    <div id="ans1" class="answer" value="1">五湖烟景有谁争</div>
                    <div id="ans2" class="answer" value="2">五湖烟景有谁争</div>
                    <div id="ans3" class="answer" value="3">五湖烟景有谁争</div>
                </div>
                <div id="top3" class="top hidden2">再来一次</div>
                <div id="top4" class="top hidden2">分享朋友圈</div>
                <p id="shareInfo"></p>
                <div id="top5" class="top hidden2">
                    <p class="like"></p>
                </div> 
            </div>
            <div id="share-mask">
                <img src="./image/share.png">
            </div>
        </div>
    </div>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="js/basic.js"></script>
</body>
</html>