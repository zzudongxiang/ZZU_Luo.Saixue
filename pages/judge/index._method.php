<?php require_once "/var/www/html/config.php";
$ID = $_POST["id"];
ExecuteNonQuery("UPDATE TIC_WebSite SET ConfigValue = $ID WHERE ConfigKey LIKE 'JudgeCompetition';");
ExecuteNonQuery("UPDATE TIC_WebSite SET ConfigValue = '0' WHERE ConfigKey LIKE 'SpeakerID';");
ExecuteNonQuery("UPDATE TIC_Judge SET OnlineStatus = 0, Enabled = 0;");
//ExecuteNonQuery("UPDATE Comp_RegistrationInfo SET Sort = 0 WHERE CompetitionID = $ID;");
exit(json_encode(["code" => 0]));
