var result = {
    "trueList":[],     //总数随机
    "falseList":[]
}

var errorCounts = 0;

function showPoetry(somePoetry) {
    var right_rate = document.getElementById("perRight");
    var tops = document.getElementsByClassName('top');
    var answers = document.getElementsByClassName('answer');
    right_rate.innerText = somePoetry.percent*100 + "%";

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
            }, 1000); // 延迟 1 s。
        };
    })
}

function getPoetry() {
    if(errorCounts<3) {
        var request = new XMLHttpRequest();
        if(!request) return false;
        request.open('GET', "http://jcuan.xyz/poetry/content.php", true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.onreadystatechange = function() {
            if(request.readyState == 4) {
                if(request.status == 200 || request.status == 304) {
                    var obj = JSON.parse(request.responseText);
                    if(obj.errorCode == 0) {
                        initialButton();
                        showPoetry(obj);
                    }
                }
            }
        }
        request.send(null);
    } else {
        var hidden1 = document.getElementsByClassName("hidden1")[1];
        var hidden2 = document.getElementsByClassName("hidden2");
        hidden1.style.display = "none";
        [].forEach.call(hidden2, function(ele) {
            ele.style.visibility = "visible";
        })
        var xhr = new XMLHttpRequest();
        var formdata = new FormData();
        xhr.open("post","http://jcuan.xyz/poetry/result.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                if (xhr.status == 200 || xhr.status == 304) {
                    var data = JSON.parse(xhr.responseText);
                    if(data.errorCode == 0) {
                        document.getElementById("shareResult").innerText = result.trueList.length;
                        document.getElementById("sharePeople").innerText = data.percent*100+ "%";
                        document.getElementById("shareInfo").innerText = document.getElementById("shareTitle").innerText;
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
    imgs[0].style.display = "none";
    imgs[1].style.display = "inherit";
}

function initialButton() {
    var buttons = document.getElementsByClassName("answer");
    buttons[0].style.backgroundPosition = "0 -98px";
    buttons[1].style.backgroundPosition = "0 -244px";
    buttons[2].style.backgroundPosition = "0 -389px";
    [].forEach.call(buttons, function(ele) {
        ele.style.color = "#666";
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

imgs[0].addEventListener('click', begin);
var reload = document.getElementById("top3");
top3.addEventListener("click", function(){
    window.location.reload();
})
top4.addEventListener("click", function(){
    var share = document.getElementById("share");
    var share_mask = document.getElementById("share-mask");  // 阴影画布。

    // 分享的标题内容。
    var shareText = document.getElementById("shareTitle").innerText;
    document.title = shareText;
    if(isWeixin) {
        wx.onMenuShareAppMessage({
            title: shareText
        })
    }
    
    share_mask.style.display = "block";
    share.style.display = "block";
    share.style.background = "white";
    share_mask.onclick = function() {
        this.style.display = "none";
        share.style.display = "none";
    }
});
top5.addEventListener("click", function() {
    this.style.backgroundPosition = "-180px 0";
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "http://jcuan.xyz/poetry/praise.php", true);
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
});
getPoetry();