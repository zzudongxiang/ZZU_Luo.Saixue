<?php require_once "/var/www/html/config.php";

# 如果当前账户已经登陆, 则跳转到个人中心页面
if(LoginChecked(False))
    RunJS(array("top.jump" => "/pages/frame/user/view.php"));
# 获取登陆界面的信息
$NoticeData = GetSingleResult("SELECT * FROM TIC_WebSite WHERE ConfigKey LIKE 'Login_Notice';")["ConfigValue"];
# 如果通知消息不是空白的, 则按照指定的样式显示到页面上
if(!empty(trim($NoticeData)))
{
    $Notice = "<div class='layui-elem-quote' style='width: 500px; margin: auto;'>";
    $Notice .= "    <i class='layui-icon layui-icon-tips' style='font-size: 20px; color: #f5bd00; float: left'>&nbsp;&nbsp;</i>";
    $Notice .= "    <h2 style='font-size: 20px; color: #f5bd00'> 提示:</h2>";
    $Notice .= "    <hr class='layui-bg-orange'>$NoticeData";
    $Notice .= "</div>";
}
else $Notice = "";
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/modules/body/head.php"; ?>
</head>
<body class="sys-body-style">
<?php include_once PATH."/modules/navigation/menu.php"; ?>
<div class="layui-form" style="width: 200px; margin: auto; text-align: center;">
    <h1 style="text-align: center">登&nbsp;&nbsp;&nbsp;录</h1>
    <hr>
    <form action="login._method.php?login=true" method="post">
        <div class="layui-form-item">
            <label>
                <input type="text" name="username" placeholder="请输入学号" autocomplete="off" class="layui-input">
            </label>
        </div>
        <div class="layui-form-item">
            <label>
                <input type="password" name="userpwd" placeholder="请输入密码" autocomplete="off" class="layui-input">
            </label>
        </div>
        <hr>
        <div class="layui-form-item">
            <button type="submit" class="layui-btn layui-btn-radius layui-btn-fluid">登&nbsp;&nbsp;录</button>
        </div>
    </form>
</div>
<br>
<?php echo $Notice; ?>
<br>
<?php include_once PATH."/modules/body/footer.php"; ?>
</body>
</html>
