<?php require_once "/var/www/html/config.php";
$Value = $_POST["RT_Volume"];
if(!preg_match("/^\d+$/",$Value)) exit();
ExecuteNonQuery("UPDATE TIC_WebSite SET ConfigValue = '$Value' WHERE ConfigKey LIKE 'RT_Volume';");