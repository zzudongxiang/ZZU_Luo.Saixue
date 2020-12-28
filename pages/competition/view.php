<?php require_once "/var/www/html/config.php";
$UserData                 = LoginChecked();
$CompetitonID             = empty($_GET["id"]) ? "" : $_GET["id"];
$QueryString              = "SELECT * FROM TIC_Competition WHERE ID = :_CompetitionID;";
$Parameters               = [":_CompetitionID@d" => $CompetitonID];
$Result                   = GetSingleResult($QueryString, $Parameters);
$QueryString              = "SELECT ID, Status FROM Comp_RegistrationInfo WHERE CompetitionID = :_CompetitionID AND UserID = :_UserID;";
$Parameters[":_UserID@d"] = $UserData["ID"];
$Status                   = GetSingleResult($QueryString, $Parameters);
if(empty($Status))
{
    $Method = "insert";
    $Status = ["ButtonValue" => "　竞赛报名　", "UploadButton" => "hidden"];
}
else if(!empty($Status["Status"]) || $Status["Status"] == "0")
{
    $Method = "update";
    if($Status["Status"] == "0")
        $Status = ["ButtonValue" => "　竞赛报名　", "UploadButton" => "hidden"];
    else if($Status["Status"] == "1")
        $Status = ["ButtonValue" => "　取消报名　", "UploadButton" => ""];
    else die("报名状态错误");
}
else
{
    die("Unknow Error: Competition/View Line:26");
}

if(strtotime(date("Y-m-d H:i:s")) > strtotime($Result["EndTime"]))
{
    $ButtonStyle           = ["class" => "layui-btn-disabled", "js" => "return;"];
    $Status["ButtonValue"] = "　无法报名　";
}
else $ButtonStyle = ["class" => "", "js" => ""];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/modules/body/head.php"; ?>
</head>
<body style="width: 850px; overflow: hidden; margin: auto">
<form name="edit" class="layui-form layui-field-box layui-form-pane"
      action="/pages/competition/view._method.php"
      method="post">
    <input name="id" value="<?php echo $CompetitonID; ?>" style="visibility: hidden; width: 0; height: 0">
    <input name="method" value="<?php echo $Method; ?>" style="visibility: hidden; width: 0; height: 0">
    <!-- 标题 -->
    <div class="layui-form-item">
        <label class="layui-form-label">竞赛名称</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input" value="<?php echo $Result["Title"]; ?>" disabled>
        </div>
    </div>
    <!-- 提交作品时间 -->
    <div class="layui-form-item">
        <label class="layui-form-label">提交时间</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" value="<?php echo $Result["UploadTime"]; ?>" disabled>
        </div>
        <div class="layui-form-mid layui-word-aux">
            <span style="margin-left: 10px">在此时间之前进行报名并在此时间之后上传作品</span>
        </div>
    </div>
    <!-- 截至时间 -->
    <div class="layui-form-item">
        <label class="layui-form-label">截至时间</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" value="<?php echo $Result["EndTime"]; ?>" disabled>
        </div>
        <div class="layui-form-mid layui-word-aux">
            <span style="margin-left: 10px">在此时间之前可以进行报名/取消报名操作或上传作品</span>
        </div>
    </div>
    <!-- 比赛说明 -->
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">更多信息</label>
        <div class="layui-input-block">
            <textarea class="layui-textarea" disabled style="height: 250px"><?php echo $Result["Text"]; ?></textarea>
        </div>
    </div>
    <!-- 完成按钮 -->
    <div style="text-align: center">
        <div style="text-align: center">
            <button class="layui-btn layui-btn-radius <?php echo $ButtonStyle["class"] ?>"
                    type="button"
                    onclick="SubmitRegistrationInfo();">
                <?php echo $Status["ButtonValue"]; ?>
            </button>
            <button class="layui-btn layui-btn-radius layui-btn-warm"
                    style="visibility: <?php echo $Status["UploadButton"]; ?>"
                    type="button"
                    onclick="Jump();">提交/查看作品
            </button>
        </div>
    </div>
</form>
<script>
    function SubmitRegistrationInfo() {
        <?php echo $ButtonStyle["js"]; ?>
        layui.use('layer', function () {
            layui.layer.load();
            document.edit.submit();
        });
    }

    function Jump() {
        layui.use('layer', function () {
            layui.layer.load(1);
            top.location.href = '/pages/competition/submit.php?id=<?php echo $CompetitonID; ?>';
        });
    }
</script>
</body>
</html>