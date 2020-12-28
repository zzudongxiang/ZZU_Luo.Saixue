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
        <h1 class="site-h1">个人信息查看</h1>
        <hr class="layui-bg-blue">
        <div style="width: 600px; margin: auto">
<!--
            <fieldset class="layui-elem-field">
                <legend>注册信息</legend>
                <div class="layui-field-box layui-form layui-form-pane">
                    <div class="layui-form-item">
                        <label class="layui-form-label">注册编号</label>
                        <div class="layui-input-block">
                            <input disabled type="text" autocomplete="off" class="layui-input"
                                   value="<?php echo $UserData["ID"] ?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">用户标识</label>
                        <div class="layui-input-block">
                            <input disabled type="text" autocomplete="off" class="layui-input"
                                   value="<?php echo $UserData["GUID"] ?>">
                        </div>
                    </div>
                </div>
            </fieldset>
-->
            <fieldset class="layui-elem-field">
                <legend>学生信息</legend>
                <div class="layui-field-box layui-form layui-form-pane">
                    <div class="layui-form-item">
                        <label class="layui-form-label">姓　　名</label>
                        <div class="layui-input-block">
                            <input disabled type="text" autocomplete="off" class="layui-input"
                                   value="<?php echo $UserData["NikeName"] ?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">学　　号</label>
                        <div class="layui-input-block">
                            <input disabled type="text" autocomplete="off" class="layui-input"
                                   value="<?php echo $UserData["UserName"] ?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">年级专业</label>
                        <div class="layui-input-block">
                            <input disabled type="text" autocomplete="off" class="layui-input"
                                   value="<?php echo $UserData["OtherInfo"] ?>">
                        </div>
                    </div>
                </div>
            </fieldset>
            <br>
            <div class='layui-elem-quote' style='width: 500px; margin: auto; background-color: #FFFFFF'>
                <i class='layui-icon layui-icon-tips'
                   style='font-size: 20px; color: #f5bd00; float: left'>&nbsp;&nbsp;</i>
                <h2 style='font-size: 20px; color: #f5bd00'> 提示:</h2>
                <hr class='layui-bg-orange'>
                学生信息如有误, 请联系管理员进行修改。
            </div>
        </div>
    </div>
</div>
<?php include_once PATH."/modules/body/footer.php"; ?>
</body>
</html>