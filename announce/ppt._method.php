<?php require_once "/var/www/html/config.php";
$PPT_ID = GetSingleResult("SELECT ConfigValue FROM TIC_WebSite WHERE ConfigKey LIKE 'RT_PPT';")["ConfigValue"];
$Img    = ShowImage("/data/images/ppt/", $PPT_ID, 1880, 1080);
exit(json_encode(["html" => $Img]));



