<?php require_once "/var/www/html/config.php";
$Value = $_POST["RT_Speaker_Msg"];
ExecuteNonQuery("UPDATE TIC_WebSite SET ConfigValue = '$Value' WHERE ConfigKey LIKE 'RT_Speaker_Msg';");