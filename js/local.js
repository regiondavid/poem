var result = {
    "trueList":[],     //总数随机
    "falseList":[]
}

var errorCounts = 0;
var likeindex = 0;
var shareResult = 0;

function showPoetry(somePoetry) {
    var right_rate = document.getElementById("perRight");
    var tops = document.getElementsByClassName('top');
    var answers = document.getElementsByClassName('answer');
    right_rate.innerText = parseInt(somePoetry.percent*100) + "%";

    var firstTen = document.createTextNode(somePoetry.first);
    tops[0].removeChild(tops[0].firstChild);
    tops[0].appendChild(firstTen);
    [].forEach.call(answers, function(ele, index) {
        var answerText = document.createTextNode(somePoetry.next[index]);
        answers[index].removeChild(answers[index].firstChild);
        answers[index].appendChild(answerText);
        answers[index].onclick = function() {
            var j = parseInt(this.getAttribute("value"));
            var k = parseInt(somePoetry.answer);
            var point = 0;
            this.style.color = "#fff";
            if(j-1 == k) {
                result.trueList.push(somePoetry.id);
                point = 1;
                this.style.backgroundPosition = changeBg(index, point);
            } else {
                errorCounts += 1;
                result.falseList.push(somePoetry.id);
                this.style.backgroundPosition = changeBg(index, point);
            }
            setTimeout(function() {
                getPoetry(); // 更新之后再次请求。
            }, 200); // 延迟 1 s。
        };
    })
}

function getPoetry() {
    if(errorCounts<3) {
        var answer = document.getElementsByClassName("answer");
        [].forEach.call(answer, function(ele) {
            ele.className = "answer animated fadeOutLeft";
        });
        document.getElementById("questionTitle").className = "fadeOutUp animated top";
        var request = new XMLHttpRequest();
        if(!request) return false;
        request.open('GET', "http://jcuan.xyz/poetry/content.php", true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.withCredentials = true;
        request.onreadystatechange = function() {
            if(request.readyState == 4) {
                if(request.status == 200 || request.status == 304) {
                    var obj = JSON.parse(request.responseText);
                    if(obj.errorCode == 0) {
                        setTimeout(function(){
                            initialButton();
                            showPoetry(obj);
                        }, 1000);
                    }
                }
            }
        }
        request.send(null);
    } else {
        var fhidden = document.getElementsByClassName("fhidden")[1];
        var shidden = document.getElementById("likeBt");
        var shareBt = document.getElementsByClassName("share-buttons")[0];
        shareBt.style.display = "block";
        fhidden.style.display = "none";
        likeBt.style.display = "block";
        var xhr = new XMLHttpRequest();
        var formdata = new FormData();
        xhr.open("post","http://jcuan.xyz/poetry/result.php", true);
        xhr.withCredentials = true;
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                if (xhr.status == 200 || xhr.status == 304) {
                    var data = JSON.parse(xhr.responseText);
                    if(data.errorCode == 0) {
                        shareResult = result.trueList.length;
                        document.getElementById("sharePeople").innerText = parseInt(data.percent*100)+ "%";
                        document.getElementsByClassName("like")[0].innerText = data.praiseNum;
                        document.getElementById("shareInfo").innerText = "恭喜您！您答对了" + shareResult + "首诗词，击败了全球" + document.getElementById("sharePeople").innerText + "的人！喜欢我就点个赞吧！";
                    } else alert(data.errorMsg)
                }
            }
        }
        formdata.append("falseList", JSON.stringify(result.falseList));
        formdata.append("trueList", JSON.stringify(result.trueList));
        xhr.send(formdata)
    }
}

var imgs = document.getElementsByTagName("img");

function begin() {
    imgs[1].style.display = "none";
    document.getElementById("mask").style.display = "none";
    imgs[2].style.display = "inherit";
}

function initialButton() {
    var buttons = document.getElementsByClassName("answer");
    document.getElementById("questionTitle").className = "fadeInUp animated top";
    buttons[0].style.backgroundPosition = "0 -98px";
    buttons[1].style.backgroundPosition = "0 -244px";
    buttons[2].style.backgroundPosition = "0 -389px";
    [].forEach.call(buttons, function(ele) {
        ele.style.color = "#666";
        ele.className = "answer animated fadeInRight";
    })
}
function changeBg(index, state) {
    if(index == 0) {
        if(state) {
            return "0 -2px"
        } else return "0 -50px"
    } else if (index == 1) {
        if (state) {
            return "0 -147px"
        } else return "0 -195px"
    } else {
        if(state) {
            return "0 -290px"
        } else return "0 -340px"
    }
}

imgs[1].addEventListener('click', begin);
var reload = document.getElementById("againBt");
againBt.addEventListener("click", function(){
    window.location.hash = Date.parse(new Date());
    window.location.reload();
})
shareBt.addEventListener("click", function(){
    var share_mask = document.getElementById("share-mask");  // 阴影画布。

    // 分享的标题内容。
    var shareText = document.getElementById("shareTitle").innerText;
    document.title = shareText;
    // "我在最美人间四月天中战胜了全球人，快来一起玩玩吧！"
    
    share_mask.style.display = "block";
    share_mask.onclick = function() {
        this.style.display = "none";
    }
});
likeBt.addEventListener("click", function() {
    likeindex ++;
    if (likeindex == 1) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "http://jcuan.xyz/poetry/praise.php", true);
        xhr.withCredentials = true;
        xhr.onreadystatechange = function() {
            if(xhr.readyState == 4) {
                if (xhr.status == 200 || xhr.status == 304) {
                    var text = JSON.parse(xhr.responseText);
                    if(text.errorCode == 0) {
                        document.getElementsByClassName("like")[0].innerText = text.praiseNum;
                    }
                }
            }
        }
        xhr.send(null);
    }
});
getPoetry();