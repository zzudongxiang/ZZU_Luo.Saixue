<?php require_once "/var/www/html/config.php";
$GUID      = $_COOKIE["JudgeID"];
$JudgeName = GetSingleResult("SELECT JudgeName FROM TIC_Judge WHERE GUID LIKE '$GUID' AND OnlineStatus = 1;");
if(empty($JudgeName) || empty($JudgeName["JudgeName"]))
{
    setcookie("JudgeID", "", time(), "/");
    RunJS(["top.jump" => "/judge/index.php"]);
}
else $JudgeName = $JudgeName["JudgeName"];
$Title = GetSingleResult("SELECT Title FROM TIC_Competition WHERE ID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition');");
if(empty($Title) || empty($Title["Title"]))
    $Title = "当前未开放竞赛";
else $Title = $Title["Title"];
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/judge/modules/body/head.php" ?>
</head>
<body class="judge-body" onload="checked('wait')">
<?php include_once PATH."/judge/modules/navigation/menu.php"; ?>
<h1 style="text-align: center; margin-top: 50px"><?php echo $Title; ?></h1>
<hr>
<h2 style="text-align: center; margin-top: 50px">评委<b style="color: #323639"> <?php echo $JudgeName; ?> </b>您好，请等待选手上场...</h2>
<div style="margin: auto; padding: 50px 0; width: 200px">
    <?php echo ShowImage("/images/", "waiting", 200, 200); ?>
</div>
<div style="text-align: center; margin-bottom: 150px">
    <a class="layui-btn layui-btn-radius" href="/" >　返　回　首　页　</a>
</div>
<?php include_once PATH."/modules/body/footer.php"; ?>
<script>
    layui.use('form', function () {
        let form = layui.form;
    });
    $(function () {
        $('#login').click(function () {
            $.ajax({
                type: "post",
                url: "/judge/login._method.php",
                data: {
                    guid: $('#judgeid').val()
                },
                dataType: 'json',
                success: function (data) {
                    if (data.code === 0) {
                        alert("成功");
                    } else
                        alert(data.info);
                }
            });
        })
    })
</script>
</body>
</html>
