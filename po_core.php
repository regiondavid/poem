<?php
define('POETRY_ROOT','root');

header('Access-Control-Allow-Origin:http://120.25.85.240');
header('Access-Control-Allow-Headers: X_Requested_With, Content-Type,XMLHttpRequest');
header('Access-Control-Allow-Credentials:true');
header('Access-Control-Allow-Methods : POST,OPTIONS,GET');

ini_set('display_errors','0');

//方便输出errorCode不为0的json
function  makeAndEchoWrongJson(int $errorCode, string $message)
{
    $info = ['errorCode'=>$errorCode,'errorMsg'=>$message];
    //header('Content-type: application/json;charset=utf-8');
    echo json_encode($info , JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

//输出json，可以自己选择验证码
function echoJson(array $info, $errorCode=false)
{
    //header('Content-type: application/json;charset=utf-8');
    if($errorCode!==false){
        $info['errorCode']=$errorCode;
    }
    echo json_encode($info , JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

//发生异常的时候发邮箱