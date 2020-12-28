<?php require_once "/var/www/html/config.php";
$UserData = LoginChecked();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/modules/body/head.php"; ?>
</head>
<body class="sys-body-style">
<?php include_once PATH."/modules/navigation/menu.php"; ?>
<div class="sys-console-body">
    <div class="sys-nav">
        <?php include_once PATH."/modules/navigation/nav.php"; ?>
    </div>
    <div class="sys-panel">
        <h1 class="site-h1">修改登陆密码</h1>
        <hr class="layui-bg-blue">
        <br>
        <form action="pwd._method.php" method="post" style="width: 300px; margin: auto">
            <h1 style="text-align: center">修改密码</h1>
            <br>
            <div class="layui-form-item">
                <input type="password" name="userpwd" placeholder="请输入密码" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-item">
                <input type="password" name="re-userpwd" placeholder="请再次输入密码" autocomplete="off" class="layui-input">
            </div>
            <br>
            <div class="layui-form-item">
                <button type="submit" class="layui-btn layui-btn-radius layui-btn-fluid">提&nbsp;&nbsp;&nbsp;&nbsp;交
                </button>
            </div>
        </form>
    </div>
</div>
<?php include_once PATH."/modules/body/footer.php"; ?>
</body>
</html>