<?php require_once "/var/www/html/config.php";
$JudgeID       = $_COOKIE["JudgeID"];
$JudgeID       = GetSingleResult("SELECT ID FROM TIC_Judge WHERE GUID LIKE '$JudgeID';");
$UserID        = (int)GetSingleResult("SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'SpeakerID';")["ConfigValue"];
$CompetitionID = (int)GetSingleResult("SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition';")["ConfigValue"];
if(!empty($JudgeID) && !empty($JudgeID["ID"]))
    $JudgeID = $JudgeID["ID"];
else exit(json_encode(["code" => 1, "msg" => "未检索到评委身份"]));
$QueryString = "
INSERT INTO Judge_ScoreDetail 
    (JudgeID, UserID, CompetitionID, Score) 
VALUES 
    ($JudgeID, $UserID, $CompetitionID, ".$_POST["score"].")
ON DUPLICATE KEY UPDATE Score = ".$_POST["score"].";";
$Result      = ExecuteNonQuery($QueryString);
if($Result)
{
    $JudgeCount = GetSingleResult("SELECT COUNT(*) FROM TIC_Judge WHERE Enabled = 1;")["COUNT(*)"];
    $ScoreCount = GetSingleResult("SELECT COUNT(*) FROM Judge_ScoreDetail WHERE UserID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'SpeakerID') AND CompetitionID = (SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition');")["COUNT(*)"];
    if($JudgeCount <= $ScoreCount)
    {
        include PATH."/judge/method/getscore.php";
        $MIN      = GetSingleResult("SELECT MIN(Score) FROM Judge_ScoreDetail WHERE UserID = $UserID AND CompetitionID = $CompetitionID")["MIN(Score)"];
        $MAX      = GetSingleResult("SELECT MAX(Score) FROM Judge_ScoreDetail WHERE UserID = $UserID AND CompetitionID = $CompetitionID")["MAX(Score)"];
        $UserInfo = GetSingleResult("SELECT Sort, Score, NikeName FROM TIC_UserInfo JOIN Comp_RegistrationInfo WHERE TIC_UserInfo.ID = $UserID AND TIC_UserInfo.ID = Comp_RegistrationInfo.UserID AND CompetitionID = $CompetitionID");
        $Msg      = "修正一个最高分: $MAX 分, 修正一个最低分: $MIN 分, ".$UserInfo["Sort"]."号选手——".$UserInfo["NikeName"]."的最终得分为: ".$UserInfo["Score"]." 分";
        ExecuteNonQuery("UPDATE TIC_WebSite SET ConfigValue = '$Msg' WHERE ConfigKey LIKE 'RT_Speaker_Msg';");
    }
    exit(json_encode(["code" => 0]));
}
else exit(json_encode(["code" => 1, "msg" => "写入数据失败"]));
