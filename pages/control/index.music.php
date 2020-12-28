<?php require_once "/var/www/html/config.php";
$Value = $_POST["RT_Music"];
if($Value == "on")
    $Value = "off";
else $Value = "on";
ExecuteNonQuery("UPDATE TIC_WebSite SET ConfigValue = '$Value' WHERE ConfigKey LIKE 'RT_Music';");