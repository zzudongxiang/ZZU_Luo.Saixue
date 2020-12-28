<?php require_once "/var/www/html/config.php";
$Value = $_POST["RT_PPT"];
ExecuteNonQuery("UPDATE TIC_WebSite SET ConfigValue = '$Value' WHERE ConfigKey LIKE 'RT_PPT';");
$RT_Speaker_Msg = GetSingleResult("SELECT Text FROM TIC_PPT WHERE ID = $Value")["Text"];
ExecuteNonQuery("UPDATE TIC_WebSite SET ConfigValue = '$RT_Speaker_Msg' WHERE ConfigKey LIKE 'RT_Speaker_Msg';");