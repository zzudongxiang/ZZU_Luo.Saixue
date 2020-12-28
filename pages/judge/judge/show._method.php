<?php require_once "/var/www/html/config.php";
$Result = ShowImage("/data/images/judge/", $_POST["id"], 180, 280, ["style" => "border-radius: 50px"]);
exit(json_encode(["img" => $Result]));