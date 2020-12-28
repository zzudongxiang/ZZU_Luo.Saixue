<?php require_once "/var/www/html/config.php";
$Value = $_POST["RT_Web_URL"];
ExecuteNonQuery("UPDATE TIC_WebSite SET ConfigValue = '$Value' WHERE ConfigKey LIKE 'RT_Web_URL';");