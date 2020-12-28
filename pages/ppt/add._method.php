<?php require_once "/var/www/html/config.php";
$Name = $_POST["name"];
ExecuteNonQuery("INSERT INTO TIC_PPT (Title) VALUES ('$Name') ON DUPLICATE KEY UPDATE Title = '$Name';");
exit(json_encode(["code" => 0]));