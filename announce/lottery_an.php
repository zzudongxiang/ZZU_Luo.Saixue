<?php require_once "/var/www/html/config.php";
$Title = GetSingleResult("SELECT Title FROM TIC_Competition WHERE ID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition');");

$Num = $_GET["num"];
if($Num > 30)
    $Num = 30;
else if($Num < 1)
    $Num = 1;

?>

<!doctype html>
<html lang="en">
<head>
    <?php include_once PATH."/judge/modules/body/head.php" ?>
    <meta charset="UTF-8"/>
    <title>随机抽签</title>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
        }

        #box {
            height: 850px;
            width: 1800px;
            position: relative;
            margin: 0 auto;
            overflow: hidden;
        }

        img, p {
            width: 70px;
            height: 57px;
            border-radius: 50%;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            behavior: url(PIE.htc);
            background-color: red;
            position: absolute;
            top: 0;
            left: 0;
            color: white;
            font-size: 50px;
            text-align: center;
            line-height: 40px;
            padding-top: 13px;
        }
    </style>
</head>
<body background="/images/background.gif">

<!-- 标题部分 -->
<div style="text-align: center; width: 1000px; margin: auto">
    <table style="width: 967px; height: 136px; background-image: url('/images/judge_title.png')">
        <tr>
            <td style="width: 138px" rowspan="3"></td>
        </tr>
        <tr style="height: 62px">
            <td style="color: #FFFF00; font-size: 35px;">
                <br>
                <b><?php echo $Title["Title"]; ?></b>
            </td>
        </tr>
        <tr style="height: 28px">
            <td style="text-align: left; font-size: 18px"><b>电脑随机抽签</b></td>
        </tr>
    </table>
</div>

<!-- 小球部分 -->
<div id="box"></div>
</body>
<script type="text/javascript">

    document.onkeydown = function (event) {
        var e = event || window.event || arguments.callee.caller.arguments[0];
        if (e && e.keyCode === 13 && t === 1) {
            window.location.href = '/announce/lottery_result.php?opt=shuffle';
            t = 0;
            w = !w;
        }
        if (e && e.keyCode === 13) {
            w = !w;
            t += 1;
        }
    };

    function randomNum(m, n) {
        return Math.floor(Math.random() * (n - m + 1) + m);
    }

    //获得BoxDiv
    var BoxDiv = document.getElementById("box");
    //定义数组存储所有的小球
    var Balls = [];

    var x1, y1, x2, y2;

    var w = 1, t = 0;

    //小球移动函数，判断小球的位置
    function moveBalls(ballObj) {
        setInterval(function () {
            if (w === 1) {
                ballObj.style.top = ballObj.y + "px";
                ballObj.style.left = ballObj.x + "px";
                //判断小球的标志量，对小球作出相应操作
                if (ballObj.yflag) {
                    //小球向下移动
                    ballObj.y += ballObj.speed + randomNum(1, 4);
                    if (ballObj.y >= 850 - ballObj.offsetHeight) {
                        ballObj.y = 850 - ballObj.offsetHeight + randomNum(1, 6);
                        ballObj.yflag = false;
                    }
                } else {
                    //小球向上移动
                    ballObj.y -= ballObj.speed;
                    if (ballObj.y <= 0) {
                        ballObj.y = 0;
                        ballObj.yflag = true;
                    }
                }
                if (ballObj.xflag) {
                    //小球向右移动
                    ballObj.x += ballObj.speed + randomNum(1, 10);
                    if (ballObj.x >= 1800 - ballObj.offsetWidth) {
                        ballObj.x = 1800 - ballObj.offsetWidth;
                        ballObj.xflag = false;
                    }
                } else {
                    //小球向左移动
                    ballObj.x -= ballObj.speed + randomNum(1, 20);
                    if (ballObj.x <= 0) {
                        ballObj.x = 0;
                        ballObj.xflag = true;
                    }
                }
                crash(ballObj);

            }
        }, 15);
    }

    //碰撞函数
    function crash(ballObj) {
        //通过传过来的小球对象来获取小球的X坐标和Y坐标
        x1 = ballObj.x;
        y1 = ballObj.y;
        for (var i = 0; i < Balls.length; i++) {
            //确保不和自己对比
            if (ballObj !== Balls[i]) {
                x2 = Balls[i].x;
                y2 = Balls[i].y;
                //判断位置的平方和小球的圆心坐标的关系
                if (Math.pow(x1 - x2, 2) + Math.pow(y1 - y2, 2) + 800 <= Math.pow(ballObj.offsetWidth + Balls[i].offsetWidth, 2)) {
                    //判断传过来的小球对象，相对于碰撞小球的哪个方位
                    if (ballObj.x < Balls[i].x) {
                        if (ballObj.y < Balls[i].y) {
                            //小球对象在被碰小球的左上角
                            ballObj.yflag = false;
                            ballObj.xflag = false;
                        } else if (ballObj.y > Balls[i].y) {
                            //小球对象在被碰小球的左下角
                            ballObj.xflag = false;
                            ballObj.yflag = true;
                        } else {
                            //小球对象在被撞小球的正左方
                            ballObj.xflag = false;
                        }
                    } else if (ballObj.x > Balls[i].x) {
                        if (ballObj.y < Balls[i].y) {
                            //小球对象在被碰撞小球的右上方
                            ballObj.yflag = false;
                            ballObj.xflag = true;
                        } else if (ballObj.y > Balls[i].y) {
                            //小球对象在被碰撞小球的右下方
                            ballObj.xflag = true;
                            ballObj.yflag = true;
                        } else {
                            //小球对象在被撞小球的正右方
                            ballObj.xflag = true;
                        }
                    } else if (ballObj.y > Balls[i].y) {
                        //小球对象在被撞小球的正下方
                        ballObj.yflag = true;
                    } else if (ballObj.y < Balls[i].y) {
                        //小球对象在被撞小球的正上方
                        ballObj.yflag = false;
                    }
                }
            }
        }
    }

    function randomColor() {
        var r = randomNum(0, 255);
        var g = randomNum(0, 255);
        var b = randomNum(0, 255);
        return "rgb(" + r + "," + g + "," + b + ")";
    }

    function createBalls() {
        for (var i = 0; i < <?php echo $Num; ?>; i++) {

            var ball = document.createElement("p");
            //随机小球起始的X坐标和小球的Y坐标
            ball.x = randomNum(0, 600);
            ball.y = randomNum(0, 800);
            //随机小球的移动速度
            ball.speed = 10;
            //随机小球移动的方向
            if (Math.random() - 0.5 > 0) {
                ball.xflag = true;
            } else {
                ball.xflag = false;
            }
            if (Math.random() - 0.5 > 0) {
                ball.yflag = true;
            } else {
                ball.yflag = false;
            }
            //随机小球的背景颜色
            ball.style.backgroundColor = randomColor();
            ball.src = "/images/ball/" + (i + 1) + ".gif";
            ball.innerHTML = i + 1;
            //将小球插入当wrapDiv中
            BoxDiv.appendChild(ball);
            //将所有的小球存储到数组中
            Balls.push(ball);
            moveBalls(ball);
        }
    }

    createBalls();

</script>
</html>

