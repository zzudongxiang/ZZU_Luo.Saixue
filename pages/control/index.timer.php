<?php require_once "/var/www/html/config.php";
$Value = $_POST["RT_Timer"];
if($Value == "on")
    $Value = "off";
else $Value = "on";
ExecuteNonQuery("UPDATE TIC_WebSite SET ConfigValue = '$Value' WHERE ConfigKey LIKE 'RT_Timer';");
if($Value == "off")
{
    ExecuteNonQuery("UPDATE TIC_WebSite SET ConfigValue = 'off' WHERE ConfigKey LIKE 'RT_Speaker';");
    ExecuteNonQuery("UPDATE TIC_WebSite SET ConfigValue = '请评委、现场同学同选手交流互动，然后进入打分环节' WHERE ConfigKey LIKE 'RT_Speaker_Msg';");
}