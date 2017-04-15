## errorCode使用情况

- 0 ： 正常

- 1 ： 请求或者用户输入参数有问题

- 2 ： 用户已经答完所有的题目

发生错误时候
```json
{
    "errorCode":1,
    "errorMsg":"缺少XX参数"
}
```

## http status使用

- 500 服务器错误
- 200 正常
- 404 not found

## api

### 获取题目
GET /poetry/content.php

返回数据
```json
{
    "errorCode":0,  //errorCode = 0 正常返回诗词。errorCode = 2，已答完所有的诗
    "id"：1,
    "first":"等闲识得东风面",
    "next":["二月春风似剪刀","万紫千红总是春","长郊草色绿无涯"],
    "answer":1,//正确答案
    "percent":0.32
}
```

非正常错误码
- 1或者2

### 上传最后的答题结果
POST /poetry/result.php

上传数据
```json
{
    "trueList":[0,1],     //总数随机
    "falseList":[2,3,4],  //总数为3,答错3次失败
}
```
返回数据
```json
{
    "errorCode":0,
  	"percent":0.99,	//击败了百分之多少的用户
    "praiseNum":999   //点赞数
}
```

### 增加点赞数目
GET /poetry/praise.php

//返回点赞数
```json
{
  
    "errorCode":0,
    "praiseNum":100，
}
```

### 查看首页的访问量
GET /poetry/star_pv.php

