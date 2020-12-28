<?php require_once "/var/www/html/config.php";
$Title         = GetSingleResult("SELECT Title FROM TIC_Competition WHERE ID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition');");
$CompetitionID = (int)GetSingleResult("SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition';")["ConfigValue"];
$QueryString   = "
    SELECT TIC_UserInfo.NikeName, TIC_UserInfo.UserName, TIC_UserInfo.OtherInfo,  Comp_RegistrationInfo.Sort, Comp_RegistrationInfo.Score
    FROM TIC_UserInfo JOIN Comp_RegistrationInfo 
            ON TIC_UserInfo.ID = Comp_RegistrationInfo.UserID 
                   AND Comp_RegistrationInfo.CompetitionID = $CompetitionID 
                   AND Comp_RegistrationInfo.Status = 1 AND Comp_RegistrationInfo.Shown = 1 
    ORDER BY Comp_RegistrationInfo.Score DESC;
";
$Result        = GetDataTable($QueryString);
if(count($Result) > 0)
{
    $DataTable = "";
    $Index     = 0;
    foreach($Result as $Row)
    {
        $Index++;
        if($Row["Score"] > 0)
            $Score = $Row["Score"];
        else $Score = "暂无";
        $DataTable .= "
        <tr style=\"height: 50px;\">
                <td>
                    <div style=\"height: 37px; background-image: url('/images/judge_num.png'); \">
                        <b style='color: #ad1001; font-size: 30px;'>$Index</b>
                    </div>
                </td>
                <td style=\"background-image: url('/images/score_bar_1.png')\">
                    <b style='color: #c81e1b; font-size: 30px;'>".$Row["NikeName"]."</b>
                </td>
                <td style=\"background-image: url('/images/score_bar_2.png')\">
                    <b style='color: #c81e1b; font-size: 30px;'>".$Row["OtherInfo"]."</b>
                </td>
                <td style=\"background-image: url('/images/score_bar_3.png')\">
                    <b style='color: #c81e1b; font-size: 30px;'>$Score</b>
                </td>
            </tr>
        ";
    }
    $DataTable.="<tr><td colspan='4' style='height: 100px'></td></tr>";
}
else $DataTable = "<tr><td colspan='5'>暂无选手信息</td></tr>"
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php include_once PATH."/judge/modules/body/head.php" ?>
    <style>
        /*
        #box {
            width: 100%;
            height: 800px;
            overflow: hidden;
            border: 0 solid gray;
            position: relative;
            margin: 0 auto;
        }
        */
    </style>
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
            <td style="text-align: left; font-size: 18px"><b>现场成绩</b></td>
        </tr>
    </table>
</div>
<div id="box">
    <table lay-skin="nob" style="text-align: center; width: 900px; margin: auto;
       color: #f5f5f5;
       font-size: 24px" id="con1">
        <colgroup>
            <col width="60">
            <col width="150">
            <col>
            <col width="220">
        </colgroup>
        <?php echo $DataTable; ?>
    </table>
    <table lay-skin="nob" style="text-align: center; width: 900px; margin: auto;
       color: #f5f5f5;
       font-size: 24px" id="con2"></table>
</div>


<script type="text/javascript">
    let box = document.getElementById("box");
    let con1 = document.getElementById("con1");
    let con2 = document.getElementById("con2");
    con2.innerHTML = con1.innerHTML;
    setInterval(function () {
        if (box.scrollTop >= con1.scrollHeight) {
            box.scrollTop = 0;
        } else {
            box.scrollTop++;
        }
    }, 30);
</script>

</body>


</html>
