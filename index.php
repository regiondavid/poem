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
    <title>人间四月天猜诗词</title>
    <meta charset="utf-8" />
    <meta name="viewport" content=" width=device-width, height=device-height, initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0, minimal-ui,user-scalable=no">
    <link href="http://lib.baomitu.com/animate.css/3.5.2/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="layout/style.css" >
</head>
<body>
    <audio src="music/music.mp3" autoplay="autoplay" loop="loop"></audio>
    <img src="image/begin.jpg" id="shareImg">
    <div id="shareTitle">我在最美人间四月天中战胜了全球<span id="sharePeople"></span>的人，快来一起玩玩吧！</div>
    <div class="container">
        <div class="main">
            <img src="image/begin.jpg">
            <div id="mask"></div>
            <img src="image/answer.jpg">
            <div id="box">
                <div id="topbox">
                    <div id="right-rate">此题正确率：<span id="perRight">0</span></div>
                    <div id="top1" class="animated top">云想衣裳花想容</div>
                </div>
                <div id="lead" class="top hidden1"></div>
                <div id="botbox" class="hidden1">
                    <div id="ans1" class="answer animated" value="1">五湖烟景有谁争</div>
                    <div id="ans2" class="answer animated" value="2">五湖烟景有谁争</div>
                    <div id="ans3" class="answer animated" value="3">五湖烟景有谁争</div>
                </div>
                <div class="share-buttons">
                    <button id="top3" class="top hidden2">再来一次</button>
                    <button id="top4" class="top hidden2">分享朋友圈</button>
                </div>
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
<script>
    wx.onMenuShareAppMessage({
        title: '人间四月天猜诗词，下一个诗人就是你',
        link: "哈哈哈哈",
        imgUrl: "https://v2ex.assets.uxengine.net/avatar/d835/6068/167592_large.png?m=1460471532"
    })
</script>
<!--<script src="js/local.js"></script>-->
</body>
</html>