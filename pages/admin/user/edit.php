<?php require_once "/var/www/html/config.php";
LoginChecked();
if(!empty($_GET["id"]) && $_GET["id"] > 0)
{
    $QueryString = "SELECT * FROM TIC_UserInfo WHERE ID=:_ID";
    $Parameters  = [":_ID@d" => $_GET["id"]];
    $Result      = GetSingleResult($QueryString, $Parameters);
    $Method      = "update";
}
else
{
    $Result = ["ID" => "系统自动分配", "GUID" => "系统自动分配", "UserName" => "", "NikeName" => "", "OtherInfo" => "", "AdminRole" => "0"];
    $Method = "insert";
}
$AdminRole = ["0" => "", "1" => "", "999" => ""];
if($Result["AdminRole"] == "0")
    $AdminRole["0"] = "checked";
else if($Result["AdminRole"] == "1")
    $AdminRole["1"] = "checked";
else if($Result["AdminRole"] == "999")
    $AdminRole["999"] = "checked";
else $AdminRole["0"] = "checked";
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/modules/body/head.php"; ?>
</head>
<body style="width: 850px; overflow: hidden; margin: auto">

<form name="edit" class="layui-form" method="post" action="/pages/admin/user/edit._method.php">
    <!-- 给出一些隐藏对象, 用于提交表单时的数据提交 -->
    <div style="visibility: hidden; width: 0; height: 0;">
        <button id="btn-yes" name="btn-yes" type="button" onclick="process('<?php echo $Method ?>');">提交</button>
        <button id="btn-del" name="btn-del" type="button" onclick="process('del');">删除</button>
        <input name="method" id="method" value="">
        <input name="id" value="<?php echo $Result["ID"]; ?>">
    </div>
    <!-- 唯一标识 -->
    <div class="layui-form-item">
        <label class="layui-form-label">唯一标识</label>
        <div class="layui-form-mid layui-word-aux"><?php echo $Result["ID"]." / ".$Result["GUID"]; ?></div>
    </div>
    <!-- 姓名 -->
    <div class="layui-form-item">
        <label class="layui-form-label">姓名</label>
        <div class="layui-input-block">
            <label>
                <input type="text"
                       name="nikename"
                       required
                       lay-verify="required"
                       placeholder="请输入姓名"
                       autocomplete="off"
                       class="layui-input"
                       value="<?php echo $Result["NikeName"] ?>">
            </label>
        </div>
    </div>
    <!-- 学号 -->
    <div class="layui-form-item">
        <label class="layui-form-label">学号</label>
        <div class="layui-input-block">
            <label>
                <input type="text"
                       name="username"
                       required
                       lay-verify="required"
                       placeholder="请输入学号"
                       autocomplete="off"
                       class="layui-input"
                       value="<?php echo $Result["UserName"]; ?>">
            </label>
        </div>
    </div>
    <!-- 其他信息 -->
    <div class="layui-form-item">
        <label class="layui-form-label">其他信息</label>
        <div class="layui-input-block">
            <label>
                <input type="text"
                       name="otherinfo"
                       required
                       lay-verify="required"
                       placeholder="请输入年级/专业等信息"
                       autocomplete="off"
                       class="layui-input"
                       value="<?php echo $Result["OtherInfo"]; ?>">
            </label>
        </div>
    </div>
    <!-- 管理员 -->
    <div class="layui-form-item">
        <label class="layui-form-label">是否管理员</label>
        <div class="layui-input-block">
            <input type="radio"
                   name="adminrole"
                   title="普通学生"
                   lay-skin="primary"
                   value="0"
                <?php echo $AdminRole["0"] ?>>
            <input type="radio"
                   name="adminrole"
                   title="任课教师"
                   lay-skin="primary"
                   value="1"
                <?php echo $AdminRole["1"] ?>>
            <input type="radio"
                   name="adminrole"
                   title="网站管理"
                   lay-skin="primary"
                   value="999"
                <?php echo $AdminRole["999"] ?>>
        </div>
    </div>
    <!-- 重置密码 -->
    <div class="layui-form-item">
        <label class="layui-form-label">密码重置</label>

        <div class="layui-input-inline">
            <button class="layui-btn layui-btn-warm layui-btn-fluid layui-btn-radius" type="button"
                    onclick=process('reset')>
                重置
            </button>
        </div>
        <div class="layui-form-mid layui-word-aux">将密码重置为本人学号</div>
    </div>
</form>
<script>
    layui.use('form', function () {
        let form = layui.form;
    });
</script>
</body>
</html>
