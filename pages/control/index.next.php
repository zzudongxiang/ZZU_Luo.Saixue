<?php require_once "/var/www/html/config.php";
$ID = $_POST["id"];
if($ID > 0)
{
    ExecuteNonQuery("UPDATE TIC_WebSite SET ConfigValue = '$ID' WHERE ConfigKey LIKE 'SpeakerID';");
    ExecuteNonQuery("UPDATE TIC_WebSite SET ConfigValue = 'off' WHERE ConfigKey LIKE 'RT_Speaker';");
    $UserName = GetSingleResult("SELECT NikeName FROM TIC_UserInfo WHERE ID = $ID;")["NikeName"];
    ExecuteNonQuery("UPDATE TIC_WebSite SET ConfigValue = '请选手: $UserName 上台展示, 下一位选手在\"备赛区\"做好准备' WHERE ConfigKey LIKE 'RT_Speaker_Msg';");
}
