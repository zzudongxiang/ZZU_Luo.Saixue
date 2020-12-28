<?php require_once "/var/www/html/config.php";
$Result = GetSingleResult("SELECT * FROM Comp_RegistrationInfo WHERE CompetitionID = (SELECT ConfigValue FROM TIC_Competition.TIC_WebSite WHERE ConfigKey LIKE 'JudgeCompetition') AND UserID = (SELECT ConfigValue FROM TIC_Competition.TIC_WebSite WHERE ConfigKey LIKE 'SpeakerID');");
if(empty($Result["UserID"]))
    $ID = "";
else $ID = $Result["UserID"];
if(empty($Result["Score"]))
    $Score = 0;
else $Score = $Result["Score"];
exit(json_encode(["id" => $ID, "score" => $Score]));