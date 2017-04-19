<?php
//获得微信的配置文件
include 'wechat_config.php';
include 'po_core.php';
include 'connect.php';
//https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=ACCESS_TOKEN&type=jsapi
function combineParam($url,$pramList)
{
    foreach($pramList as $key => $value) {
        $url.='&'.$key.'='.$value;
    }
    return $url;
}

//失败返回false
function getAccessToken($dbh){

    $nowTime = time();
    $result = $dbh->query('SELECT * from po_wechat WHERE id=1')->fetch(PDO::FETCH_ASSOC);
    if(!$result || $nowTime>$result['timeDead']){    //超过有效时间
        $paramList=[
            'appid'=>APPID,
            'secret'=>APP_SECRET
        ];
        $url=combineParam(ACCESS_TOKEN_URL,$paramList);
        $result = file_get_contents($url);
        $data=json_decode($result,true);
        if(isset($data['access_token']) && isset($data['expires_in'])){
            $timeDead = $nowTime+$data['expires_in'];
            //token入数据库
            if($result) {
                $dbh->exec("UPDATE po_wechat SET data=$data[access_token], timeDead=$timeDead WHERE id=1");
            }else{
                $dbh->exec("INSERT INTO po_wechat (id,data,timeDead) VALUES (1, $data[access_token],$timeDead)");
            }
        }else{
            makeAndEchoWrongJson(1,'获得token出错');
            die;
        }
        return $data['access_token'];
    }else{  //token还有效
        return $result['data'];
    }
}


function getJsTicket($dbh){
    $nowTime = time();
    $result = $dbh->query('SELECT * from po_wechat WHERE id=2')->fetch(PDO::FETCH_ASSOC);
    if(!$result || $nowTime>$result['timeDead']){    //超过有效时间
        $token = getAccessToken($dbh);
        $url=JSAPI_URL.'access_token='.$token;
        $result = file_get_contents($url);
        $data=json_decode($result,true);
        if(isset($data['errcode']) && $data['errcode']==0){
            $timeDead = $nowTime+$data['expires_in'];
            //token入数据库
            if($result) {
                $dbh->exec("UPDATE po_wechat SET data=$data[ticket], timeDead=$timeDead WHERE id=2");
            }else{
                $dbh->exec("INSERT INTO po_wechat (id,data,timeDead) VALUES (2, $data[ticket],$timeDead)");
            }
            return $data['ticket'];
        }else{
            return false;
        }
    }else{  //还有效
        return $result['data'];
    }
}


function getConfig($dbh){
    $jsTicket= getJsTicket($dbh);
    if(!$jsTicket){
        makeAndEchoWrongJson(1,'获取jsapi_ticket出错');
        die;
    }
    //生成随机字符串
    $randomChars=function() {
        $pool='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $strLen=strlen($pool);
        $chars='';
        for($i=0;$i<16;$i++){
            $chars.=$pool[rand(0,$strLen)];
        }
        return $chars;
    };
    $noncestr=$randomChars();
    $timestamp = time();
    $url='http://nav.uestc.edu.cn/poem';

    $string1='jsapi_ticket='.$jsTicket.'&noncestr='.$noncestr.'&timestamp='.$timestamp.'&url='.$url;

    $sha1=sha1($string1);

    $config=[
        'appId'=>APPID,
        'timestamp'=>$timestamp,
        'noncestr'=>$noncestr,
        'signature'=>$sha1
    ];

    echoJson($config,0);
}

getConfig($dbh);




