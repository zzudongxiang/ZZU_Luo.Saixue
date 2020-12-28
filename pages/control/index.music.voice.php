<?php require_once "/var/www/html/config.php";
$Value = $_POST["RT_Music_Voice"];
ExecuteNonQuery("UPDATE TIC_WebSite SET ConfigValue = '$Value' WHERE ConfigKey LIKE 'RT_Music_Voice';");