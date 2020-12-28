<?php require_once "/var/www/html/config.php";
$CompetitionID = (int)GetSingleResult("SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition';")["ConfigValue"];
$Title = GetSingleResult("SELECT Title FROM TIC_Competition WHERE ID = $CompetitionID;");
$Num = GetSingleResult("SELECT COUNT(ID) FROM Comp_RegistrationInfo WHERE CompetitionID = $CompetitionID AND Status = 1 AND Shown = 1")["COUNT(ID)"];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/judge/modules/body/head.php" ?>
</head>
<body background="/images/background.gif">
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

<div style="margin: auto; width: 800px; padding: 50px 0">
    <img src="/images/lottery.png" width="800">
</div>
<div style="margin: auto; width: 200px; padding: 50px 0">
    <img src="/images/start.png" width="200" onclick="top.location='/announce/lottery_an.php?num=<?php echo $Num; ?>'">
</div>
<script>
    document.onkeydown = function (event) {
        var e = event || window.event || arguments.callee.caller.arguments[0];
        if (e && e.keyCode === 13) {
            window.location.href = '/announce/lottery_an.php?num=<?php echo $Num; ?>';
        }
    };
</script>
</body>
</html>
