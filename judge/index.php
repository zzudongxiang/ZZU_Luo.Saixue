<?php require_once "/var/www/html/config.php";
if(!empty($_COOKIE["JudgeID"]))
    RunJS(["top.jump" => "/judge/wait.php"]);
$Title = GetSingleResult("SELECT Title FROM TIC_Competition WHERE ID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition');");
if(empty($Title) || empty($Title["Title"]))
    $Title = "当前未开放竞赛";
else $Title = $Title["Title"];
$JudgeInfo = GetDataTable("SELECT * FROM TIC_Judge WHERE Enabled = 1;");
$Selected  = "";
if(!empty($JudgeInfo))
{
    foreach($JudgeInfo as $Judge)
    {
        $GUID     = $Judge["GUID"];
        $Name     = $Judge["JudgeName"];
        $Selected .= "<option value='$GUID'>$Name</option>";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/judge/modules/body/head.php" ?>
</head>
<body class="judge-body">
<?php include_once PATH."/judge/modules/navigation/menu.php"; ?>
<h1 style="text-align: center; margin-top: 50px"><?php echo $Title; ?></h1>
<hr>
<div style="padding-top: 100px; width: 400px; margin: auto" class="layui-form">
    <select id="judgeid" lay-verify="">
        <option value="">请选择评委</option>
        <?php echo $Selected; ?>
    </select>
</div>
<div style="padding-top: 100px; width: 100px; margin: auto">
    <button type="button" id="login" class="layui-btn layui-btn-radius layui-btn-fluid">
        登　　陆
    </button>
</div>
<div style="height: 200px"></div>
<?php include_once PATH."/modules/body/footer.php"; ?>
<script>
    layui.use('form', function () {
        let form = layui.form;
    });
    $(function () {
        $('#login').click(function () {
            $.ajax({
                type: "post",
                url: "/judge/method/login.php",
                data: {
                    guid: $('#judgeid').val()
                },
                dataType: 'json',
                success: function (data) {
                    if (data.code === 0)
                        top.location.href = "/judge/wait.php";
                    else
                        alert(data.info);
                }
            });
        })
    })
</script>
</body>
</html>
