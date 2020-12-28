<?php require_once "/var/www/html/config.php";
$Name = $_POST["name"];
$GUID = GetGUID();
ExecuteNonQuery("INSERT INTO TIC_Judge (GUID, JudgeName) VALUES ('$GUID', '$Name') ON DUPLICATE KEY UPDATE JudgeName = '$Name';");
exit(json_encode(["code" => 0]));