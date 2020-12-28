<?php require_once "/var/www/html/config.php";
$Result = ShowImage("/data/images/ppt/", $_POST["id"], 600, 400);
exit(json_encode(["img" => $Result]));