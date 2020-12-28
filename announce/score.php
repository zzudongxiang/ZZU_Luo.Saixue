<?php require_once "/var/www/html/config.php";
$Title       = GetSingleResult("SELECT Title FROM TIC_Competition WHERE ID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition');");
$QueryString = "
    SELECT TIC_Judge.ID, JudgeName, IFNULL(Judge_ScoreDetail.Score, '暂无') AS Score  
    FROM (SELECT * FROM TIC_Judge WHERE TIC_Judge.Enabled = 1) AS TIC_Judge LEFT JOIN Judge_ScoreDetail 
    ON  Judge_ScoreDetail.CompetitionID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition')
    AND Judge_ScoreDetail.UserID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'SpeakerID')
    AND Judge_ScoreDetail.JudgeID = TIC_Judge.ID ;
";
$Result      = GetDataTable($QueryString);
if(count($Result) > 0)
{
    $DataTable = "";
    $ItemTable = "";
    $Index     = 0;
    $DivCount  = 0;
    foreach($Result as $Row)
    {
        $ID = $Row["ID"];
        if(($Index++) % 5 == 0)
        {
            $DataTable = "
            <div style=\"width: ".($DivCount * 204)."px; text-align: center; margin: auto\">
            $ItemTable
            </div><div style='clear: both'></div>
            ".$DataTable;
            $ItemTable = "";
            $DivCount  = 0;
        }
        $ItemTable .= "
        <div style='width: 180px; padding: 10px; float: left'>
            <div style='display:flex; align-items:center; justify-content:center; height: 230px'>
                ".ShowImage("/data/images/judge/", $Row["ID"], 180, 230, ["style" => "border-radius: 50px"])."
                <br>
            </div>
            <div style='color: #FFFFFF; font-size: 30px; margin: 5px; height: 45px'><b>".$Row["JudgeName"]."</b><br></div>
            <div style=\"width: 170px; height: 75px; margin: auto; background-image: url('/images/judge_score.png')\">
                <div style='color: #FFFF00; font-size: 50px; padding-top: 1px'><b class='score' id='score_$ID'>".$Row["Score"]."</b>
                </div>
            </div>
        </div>
        ";
        $DivCount++;
    }
    if((++$Index) % 5 != 0)
    {
        $DataTable = "
            <div style=\"width: ".($DivCount * 200)."px; text-align: center; margin: auto\">
            $ItemTable
            </div><div style='clear: both'></div>
            ".$DataTable;
    }
}
$UserInfo = GetSingleResult("SELECT * FROM TIC_UserInfo WHERE ID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'SpeakerID');");
$UserText = $UserInfo["NikeName"]."(".$UserInfo["UserName"].") - ".$UserInfo["OtherInfo"];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/modules/sys/sys.css">
    <link rel="stylesheet" href="/css/layui.css">
    <script src="/lay/jquery-1.3.2.min.js"></script>
</head>
<body style="background-image: url('/images/background.gif')" onload="getScore()">
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
            <td style="text-align: left; font-size: 18px"><b>评委打分</b></td>
        </tr>
    </table>
</div>
<h1 style="margin: 20px 0; text-align: center; color: #FFFFFF"><b><?php echo $UserText; ?><b></h1>
<br>
<div class="layui-form">
    <?php echo $DataTable; ?>
</div>
<br>
<script>
    function getScore() {
        window.setInterval(function () {
            $.ajax({
                url: "/announce/score._method.php",
                dataType: 'json',
                type: 'post',
                success: function (data) {
                    for (var key in data) {
                        $("#" + key).text(data[key]);
                    }
                    data = null;
                }
            });
        }, 1000);
    }

    var show = true;
    window.setInterval(function () {
        var scores = $(".score");
        var s = [];
        for (var i = 0; i < scores.length; i++) {
            scores[i].style.display = "";
            if (scores[i].innerHTML.match(/^\d+(\.\d+)?$/) !== null)
                s[i] = parseFloat(scores[i].innerHTML);
            else s[i] = -1;
        }
        s.sort(function (a, b) {
            return a - b;
        });
        var min = 0;
        for (var i = 0; i < s.length; i++) {
            if (s[i] > 0) {
                min = s[i];
                break;
            }
        }
        var max = s[s.length - 1];
        for (var i = 0; i < scores.length; i++) {
            if (max - parseFloat(scores[i].innerHTML) < 0.01) {
                scores[i].style.display = show ? "" : "none";
                break;
            }
        }
        for (var i = 0; i < scores.length; i++) {
            if (parseFloat(scores[i].innerHTML) - min < 0.01) {
                scores[i].style.display = show ? "" : "none";
                break;
            }
        }
        show = !show;
    }, 1000);

</script>
</body>
</html>