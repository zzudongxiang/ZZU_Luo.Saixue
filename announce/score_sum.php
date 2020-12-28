<?php require_once "/var/www/html/config.php";
$Title         = GetSingleResult("SELECT Title FROM TIC_Competition WHERE ID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition');");
$UserID        = GetSingleResult("SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'SpeakerID';")["ConfigValue"];
$CompetitionID = GetSingleResult("SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition';")["ConfigValue"];
$UserInfo      = GetSingleResult("SELECT * FROM TIC_UserInfo WHERE ID = $UserID;");
$UserText      = $UserInfo["NikeName"]."(".$UserInfo["UserName"].") - ".$UserInfo["OtherInfo"];
$Score         = GetSingleResult("SELECT IF(Score<0, '暂无', Score) AS Score, Sort FROM Comp_RegistrationInfo WHERE UserID = $UserID AND CompetitionID = $CompetitionID;");
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/judge/modules/body/head.php" ?>
</head>
<body style="background-image: url('/images/background.gif')">
<div style="text-align: center; width: 1000px; margin: auto">
    <table style="width: 967px; height: 136px; background-image: url('/images/judge_title.png')">
        <tr>
            <td style="width: 138px" rowspan="3"></td>
        </tr>
        <tr style="height: 62px">
            <td style="color: #FFFF00; font-size: 35px; ">
                <br>
                <b><?php echo $Title["Title"]; ?></b>
            </td>
        </tr>
        <tr style="height: 28px">
            <td style="text-align: left; font-size: 18px"><b>选手得分</b></td>
        </tr>
    </table>
</div>
<h1 style="margin: 20px 0; text-align: center; color: #FFFFFF"><b><?php echo $UserText; ?><b></h1>
<br>
<div style="width: 652px; margin: auto; height: 450px; background-image: url('/images/judge_sum.png'); padding-top:100px">
    <div style="width: 380px; text-align: center; float: left; font-size: 60px; color: #FF0000;"><?php echo $Score["Sort"]; ?>号</div>
    <div style="clear: both; height: 20px">&nbsp;</div>
    <div style="width: 462px; padding-left: 190px; padding-top: 60px">
        <div style="text-align: center; font-size: 170px; color: #FF0000;">
            <b><?php echo $Score["Score"]; ?><b>
        </div>
    </div>
</div>

</body>
</html>
