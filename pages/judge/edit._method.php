<?php require_once "/var/www/html/config.php";
$ID            = $_GET["id"];
$CompetitionID = GetSingleResult("SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition';")["ConfigValue"];
if(empty(($_GET["mth"])) || $_GET["mth"] != "user")
{
    $ScoreInfo     = GetSingleResult("SELECT * FROM Judge_ScoreDetail WHERE ID = $ID");
    $UserID        = (int)$ScoreInfo["UserID"];
    $CompetitionID = (int)$ScoreInfo["CompetitionID"];
    ExecuteNonQuery("UPDATE Comp_RegistrationInfo SET Score = -1 WHERE UserID = $UserID AND CompetitionID = $CompetitionID;");
    ExecuteNonQuery("DELETE FROM Judge_ScoreDetail WHERE ID = $ID;");
}
else
{
    ExecuteNonQuery("DELETE FROM Judge_ScoreDetail WHERE UserID = $ID AND CompetitionID = $CompetitionID;");
    ExecuteNonQuery("UPDATE Comp_RegistrationInfo SET Score = -1 WHERE UserID = $ID AND CompetitionID = $CompetitionID;");
}
RunJS(["back" => ""]);