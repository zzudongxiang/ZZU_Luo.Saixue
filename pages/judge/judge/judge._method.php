<?php require_once "/var/www/html/config.php";
if(!empty($_GET["type"]))
{
    $Type = $_GET["type"];
    if($Type == "online")
        $Key = "OnlineStatus";
    else if($Type == "enabled")
        $Key = "Enabled";
    else if($Type == "judgerole")
        $Key = "JudgeRole";
    else $Key = "Weight";
    $Value = $_POST["value"];
    $ID    = $_POST["id"];
    ExecuteNonQuery("UPDATE TIC_Judge SET $Key = $Value WHERE ID = $ID");
}
exit(json_encode(["code" => 0]));