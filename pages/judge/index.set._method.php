<?php require_once "/var/www/html/config.php";
$ID = $_POST["id"];
ExecuteNonQuery("UPDATE TIC_WebSite SET ConfigValue = $ID WHERE ConfigKey LIKE 'SpeakerID';");
exit(json_encode(["code" => 0]));
