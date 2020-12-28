<?php require_once "/var/www/html/config.php";
$Result = ShowImage("/data/images/honor/", $_POST["id"], 400, 600);
exit(json_encode(["img" => $Result]));